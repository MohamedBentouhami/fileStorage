<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\File;
use App\Models\FileEncryptManager;
use App\Models\SharedFile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;




class SharedFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $id = Auth::id();
        $sharedFile =  new SharedFile();
        $data = $sharedFile->allSharedFiles($id);

        return view('sharedFile', ['result' => $data]);
    }
    public function sendFile(Request $request)
    {
        $userId = Auth::id();

        $idReceiver = $request->input('contactName');

        $idFile = $request->input('idFile');

        $cipherFileName = File::getFileName($idFile,  $userId);

        if (!empty($cipherFileName) && Storage::disk('local')->exists($cipherFileName[0]->name)) {

            $keyEncrypted = base64_decode(File::getKeyFile($idFile, $userId));
            $key = FileEncryptManager::decryptKey($keyEncrypted);

            $publicKeyReceiver = User::getPublicKey($idReceiver)[0]->public_key;
            openssl_public_encrypt($key, $keyEncryptedFileReceiver, base64_decode($publicKeyReceiver));

            SharedFile::insertSharingFile(
                $cipherFileName[0]->name,
                $idFile,
                $idReceiver,
                $keyEncryptedFileReceiver
            );
            return back()->with(['success' => "The file has been send"]);
        }


        return redirect('/404');
    }

    public function download($idFile)
    {

        $userId = Auth::id();

        $cipherFileName = SharedFile::getFileName($idFile,  $userId);
        $privateKeyEncrypted = User::select('encrypted_private_key')
            ->where('id', Auth::id())->get()[0]->encrypted_private_key;
        $key = FileEncryptManager::decryptNameFile($privateKeyEncrypted, Session::get('masterKey')); //decrypt the private key with master key

        if (!empty($cipherFileName) && Storage::disk('local')->exists($cipherFileName[0]->name)) {
            $cipherFileName = $cipherFileName[0]->name;
            $file = Storage::disk('local')->get($cipherFileName);
            $keyEncrypted = base64_decode(SharedFile::getKeyFile($idFile, $userId));

            openssl_private_decrypt($keyEncrypted, $decrypted, $key);

            $fileName = FileEncryptManager::decryptNameFile($cipherFileName, $decrypted);

            $file = FileEncryptManager::decryptFile($file, $decrypted);


            return response()->streamDownload(function () use ($file) {
                echo $file;
            }, $fileName);
        }

        return redirect('/404');
    }
}
