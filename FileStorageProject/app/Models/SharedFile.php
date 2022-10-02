<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\DB;
use App\Models\FileEncryptManager;

class SharedFile extends Model
{
    use HasFactory;

    protected $fillable = ['file_id', 'contact_id', 'name', 'id_owner'];




    public static function allSharedFiles($id)
    {

        $result = DB::select("select id,name,id_owner,crypt_key,numerical_signature
         FROM shared_files WHERE ? = contact_id", [$id]);
        $privateKeyEncrypted = User::select('encrypted_private_key')
            ->where('id', Auth::id())->get()[0]->encrypted_private_key;
        $key = FileEncryptManager::decryptKey(base64_decode($privateKeyEncrypted));

        for ($i = 0; $i < count($result); ++$i) {

            //Verify numerical signature
            $pubKey = User::getPublicKey($result[$i]->id_owner)[0]->public_key;
            $signature = base64_decode($result[$i]->numerical_signature);
            $ok =  openssl_verify($result[$i]->crypt_key, $signature, base64_decode($pubKey), "sha256WithRSAEncryption");
            if (!$ok) {
                self::where('id', $result[$i]->id)->delete();
                unset($result[$i]);
            } else {
                $keyFile = base64_decode($result[$i]->crypt_key);
                openssl_private_decrypt($keyFile, $decrypted, $key);
                $result[$i]->name = FileEncryptManager::decryptNameFile($result[$i]->name, $decrypted);
                $result[$i]->contactName = User::select('name')->where('id',  $result[$i]->id_owner)
                    ->get()[0]->name;
            }
        }

        return $result;
    }

    /**
     * Allows to insert into shared_files table with the given id file, encrypted name file by the assmetric key file,
     *  the id of who will benefit of that file, and the key file crypted by public key of the receiver
     */
    public static function insertSharingFile($fileNameEncrypted, $fileId, $idContact, $keyEncrypted)
    {

        $signature  = base64_encode(self::signKeyFile(base64_encode($keyEncrypted)));

        DB::insert(
            "INSERT INTO shared_files (file_id,contact_id,name,id_owner,crypt_key,numerical_signature) values (?,?,?,?,?,?)",
            [
                $fileId, $idContact, $fileNameEncrypted, Auth::id(), base64_encode($keyEncrypted),
                $signature
            ]
        );
    }

    public static function getFileName($id, $userId)
    {
        $cipherFileName = DB::select("select name from shared_files where ? = contact_id
         and ? =id ", [$userId, $id]);

        return $cipherFileName;
    }

    public static function getKeyFile($idFile, $userId)
    {
        $result = DB::select("select crypt_key from shared_files where id = ? and contact_id = ?", [$idFile, $userId]);

        return $result[0]->crypt_key;
    }

    /**
     * Allows to sign the assymetric key to prove that the sender is the realy sender
     */
    private static function signKeyFile($keyFile)
    {
        $privateKeyEncrypted = User::select('encrypted_private_key')
            ->where('id', Auth::id())->get()[0]->encrypted_private_key;
        $privKey = FileEncryptManager::decryptNameFile($privateKeyEncrypted, Session::get('masterKey'));
        openssl_sign($keyFile, $signature, $privKey, OPENSSL_ALGO_SHA256);




        return $signature;
    }
}
