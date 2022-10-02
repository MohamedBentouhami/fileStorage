<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;


class FileEncryptManager extends Model
{
    use HasFactory;


    /**
     * Generate a random key
     *
     */
    public static function generateRandomKey()
    {

        return  openssl_random_pseudo_bytes(128);
    }

    /**
     * cipher the given random key
     */
    public static function encryptRandomKey($randomKey)
    {
        $cipher = "AES-128-CBC";
        $masterKey = Session::get('masterKey');
        $options = 0;
        $iv = FileEncryptManager::getRandomNumbers();

        $encryptedRandomKey = openssl_encrypt($randomKey, $cipher, $masterKey, $options, $iv);

        return $iv . $encryptedRandomKey;
    }
    /**
     * cipher the given fileName
     */
    public static function decryptKey($encryptedKey)
    {
        $cipher = "AES-128-CBC";
        $masterKey = Session::get('masterKey');
        $options = 0;
        $iv = substr($encryptedKey, 0, 16);

        return openssl_decrypt(substr($encryptedKey, 16), $cipher, $masterKey, $options, $iv);
    }
    /**
     * cipher the given fileName
     */
    public static function encryptFileName($fileName, $key)
    {
        $cipher = "AES-128-CBC";

        $options = 0;
        $iv = FileEncryptManager::getRandomNumbers();

        $encryptedFileName = openssl_encrypt($fileName, $cipher, $key, $options, $iv);

        return $iv . $encryptedFileName;
    }
    /**
     * cipher the given fileName
     */
    public static function decryptNameFile($encryptFileName, $key)
    {
        $encryptFileName = base64_decode($encryptFileName);
        $cipher = "AES-128-CBC";
        $options = 0;
        $iv = substr($encryptFileName, 0, 16);

        return openssl_decrypt(substr($encryptFileName, 16), $cipher, $key, $options, $iv);
    }

    public static function encryptFile($file, $key)
    {
        $cipher = "AES-128-CBC";

        $options = 0;
        $iv = FileEncryptManager::getRandomNumbers();
        $encryptedFile = openssl_encrypt($file, $cipher, $key, $options, $iv);

        return $iv . $encryptedFile;
    }
    public static function decryptFile($encryptFile, $key)
    {

        $cipher = "AES-128-CBC";

        $options = 0;
        $iv = substr($encryptFile, 0, 16);
        $file = openssl_decrypt(substr($encryptFile, 16), $cipher, $key, $options, $iv);

        return $file;
    }

    /**
     * Generate 16 randomely caracterers
     */
    public static function getRandomNumbers()
    {
        $data = openssl_random_pseudo_bytes(16);
        return substr(base64_encode($data), 0, 16);
    }
}
