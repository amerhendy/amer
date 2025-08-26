<?php
namespace Amerhendy\Amer\App\Helpers\AmerHelper;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait EncryptionHelper
{
    protected static function getEncryptionKey(): string
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(Str::after($key, 'base64:'), true);
        }

        if (!$key || strlen($key) !== 32) {
            throw new RuntimeException('Invalid encryption key. It must be 32 bytes for AES-256-CBC.');
        }

        return $key;
    }

    public static function encryptData(string $data): string
    {
        $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $key = self::getEncryptionKey();
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed.');
        }

        // Combine IV + encrypted data then base64 encode
        return base64_encode($iv . $encrypted);
    }

    public static function decryptData(string $data): string
    {
        $key = self::getEncryptionKey();
        $raw = base64_decode($data, true);

        if ($raw === false) {
            throw new RuntimeException('Invalid base64 data.');
        }

        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($raw, 0, $ivLength);
        $cipherText = substr($raw, $ivLength);

        $decrypted = openssl_decrypt($cipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new RuntimeException('Decryption failed.');
        }

        return $decrypted;
    }
    public static function decryptRncryptQuery($encrypted)
    {
        // جلب مفتاح التشفير
        $key = config('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        // لازم الطول يكون 32 بايت
        if (strlen($key) !== 32) {
            throw new \Exception('Invalid encryption key length');
        }

        // تحويل البيانات من base64
        $ciphertext = base64_decode($encrypted);

        // AES-256-CBC بيستخدم IV طوله 16 بايت في البداية
        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($ciphertext, 0, $ivLength);
        $realCiphertext = substr($ciphertext, $ivLength);

        // فك التشفير
        $decrypted = openssl_decrypt($realCiphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new \Exception('Decryption failed');
        }

        return $decrypted;
    }

}
