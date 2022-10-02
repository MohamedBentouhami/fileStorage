<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Contact;
use Illuminate\Support\Str;
use App\Models\FileEncryptManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;




class MyFileController extends Controller
{
    /**
     * you may to be authenticated for using this class
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Return page of file that resume all files of the user authenticated
     */

    public function index()
    {
        $id = Auth::id();
        $data = File::allFiles($id);
        $contacts = Contact::getContact($id);
        return view('file', ['result' => $data, 'contacts' => $contacts,]);
    }

    /**
     * Allows to store file givent by user authenicate
     */
    public function store(Request $request)
    {
        $id = Auth::id();
        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            File::store($request, $id);
        }

        return redirect('/myFiles');
    }

    /**
     * Allows to download file with the idFileGiven
     */
    public function download($idFile)
    {

        $userId = Auth::id();

        $cipherFileName = File::getFileName($idFile,  $userId);

        if (!empty($cipherFileName) && Storage::disk('local')->exists($cipherFileName[0]->name)) {
            $cipherFileName = $cipherFileName[0]->name;
            $file = Storage::disk('local')->get($cipherFileName);
            $keyEncrypted = base64_decode(File::getKeyFile($idFile, $userId));
            $key = FileEncryptManager::decryptKey($keyEncrypted);
            $fileName = FileEncryptManager::decryptNameFile($cipherFileName, $key);

            $file = FileEncryptManager::decryptFile($file, $key);


            return response()->streamDownload(function () use ($file) {
                echo $file;
            }, $fileName);
        }

        return redirect('/404');
    }

    public  function delete($idFile)
    {
        $userId = Auth::id();

        $cipherFileName = File::getFileName($idFile,  $userId);

        if (!empty($cipherFileName) && Storage::disk('local')->exists($cipherFileName[0]->name)) {

            Storage::delete($cipherFileName[0]->name);
            File::deleteFile($idFile);
            return redirect('/myFiles');
        }

        return redirect('/404');
    }


    public  function edit(Request $request)
    {

        $id = Auth::id();
        if ($request->hasFile('fileEdit') && $request->file('fileEdit')->isValid()) {
            $idFile = $request->input('idFileEdit');

            if (File::edit($request, $id, $idFile) == 0) {

                return back()->with(['warning' => "The new version of the file does not have the same extension as the previous one"]);
            }


            return back()->with(['success' => "File updated"]);
        }


        return redirect('/myFiles');
    }
}
