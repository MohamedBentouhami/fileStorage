<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\FileEncryptManager;



class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'crypt_key', 'user_id'];


    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function contacts()
    {

        return $this->belongsToMany(Contact::class, 'shared_files');
    }

    /**
     * Gives all files name by decipher the name from data base
     */
    public static function allFiles($id)
    {

        $result = DB::select("select id,name,crypt_key from files where ? = user_id", [$id]);
        for ($i = 0; $i < count($result); ++$i) {
            $keyFile = base64_decode($result[$i]->crypt_key);
            $key = FileEncryptManager::decryptKey($keyFile);
            $result[$i]->name = FileEncryptManager::decryptNameFile($result[$i]->name, $key);
        }
        return $result;
    }

    /**
     * Allows to store the file that it was upload
     */
    public static function store($request, $userId)
    {

        $fullName = $request->file('file')->getClientOriginalName();

        $randomKey = FileEncryptManager::generateRandomKey();
        $encryptedName = FileEncryptManager::encryptFileName($fullName, $randomKey);
        $encryptedName = base64_encode($encryptedName);


        $file = $request->file('file');
        $fileContent = $file->get();
        $encrypted = FileEncryptManager::encryptFile($fileContent, $randomKey); //fichier encypted


        Storage::disk('local')->put($encryptedName, $encrypted);
        $keyEncypted = FileEncryptManager::encryptRandomKey($randomKey);
        $key = base64_encode($keyEncypted);

        DB::insert(
            "INSERT INTO files (name,user_id,crypt_key) values (?,?,?)",
            [
                $encryptedName, $userId, $key
            ]
        );
    }

    public static function edit($request, $userId, $idFile)
    {
        $newExtension = $request->file('fileEdit')->extension();
        $result = DB::select("select id,name,crypt_key from files where ? = user_id and id = ? ", [$userId, $idFile]);

        $keyFile = base64_decode($result[0]->crypt_key);
        $key = FileEncryptManager::decryptKey($keyFile);
        $oldNameCipher = $result[0]->name;
        $oldFileNameDecipher = FileEncryptManager::decryptNameFile($oldNameCipher, $key);
        if ($newExtension !== pathinfo($oldFileNameDecipher, PATHINFO_EXTENSION)) {

            return false;
        }

        if (Storage::disk('local')->exists($oldNameCipher)) {

            Storage::delete($oldNameCipher);
            $file = $request->file('fileEdit');
            $fileContent = $file->get();
            $encrypted = FileEncryptManager::encryptFile($fileContent, $key); //fichier encypted
            Storage::disk('local')->put($oldNameCipher, $encrypted);
        }

        return true;
    }

    /**
     * select the cipher name from data base thanks the given id file.
     */
    public static function getFileName($id, $userId)
    {
        $data = DB::select("select name from files where user_id= ? and id = ? ", [$userId, $id]);

        return $data;
    }

    /**
     * Delete by the given id, the file in the data base
     */
    public static function deleteFile($idFile)
    {
        DB::select("DELETE FROM files
        WHERE id = ?", [$idFile]);
    }

    public static function getKeyFile($idFile, $userId)
    {
        $result = DB::select("select crypt_key from files where id = ? and user_id = ?", [$idFile, $userId]);

        return $result[0]->crypt_key;
    }
}
