<?php

namespace AuthSlim\Utilities;

class EncryptDecrypt
{
    public static function encrypt($str, $key)
    {
        $key = md5($key);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv);

        $ciphertext = $iv . $ciphertext;

        # encode the resulting cipher text so it can be represented by a string
        $cipher = base64_encode($ciphertext);

        return trim($cipher);
    }

    public static function decrypt($str, $key)
    {
        $key = md5($key);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

        $ciphertext_dec = base64_decode($str);

        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        # may remove 00h valued characters from end of plain text
        $decipher = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        return trim($decipher);
    }
}