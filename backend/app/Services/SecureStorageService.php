<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SecureStorageService
{
    private string $disk;
    private string $key;

    public function __construct(string $disk = 'local')
    {
        $this->disk = $disk;
        $this->key = base64_decode(config('app.storage_encryption_key_base64', ''));
    }

    public function store(string $contents, ?string $extension = null): string
    {
        $this->assertKey();
        $iv = random_bytes(12); // GCM recommended 96-bit IV
        $tag = '';
        $cipher = 'aes-256-gcm';
        $ciphertext = openssl_encrypt($contents, $cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);
        $payload = json_encode([
            'v' => 1,
            'alg' => $cipher,
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
            'ct' => base64_encode($ciphertext),
        ]);
        $path = 'secure/'.Str::uuid().($extension ? ".{$extension}" : '');
        Storage::disk($this->disk)->put($path, $payload);
        return $path;
    }

    public function retrieve(string $path): string
    {
        $this->assertKey();
        $payload = Storage::disk($this->disk)->get($path);
        $data = json_decode($payload, true);
        if (!is_array($data) || empty($data['ct']) || empty($data['iv']) || empty($data['tag'])) {
            throw new \RuntimeException('Invalid encrypted payload');
        }
        $ciphertext = base64_decode($data['ct']);
        $iv = base64_decode($data['iv']);
        $tag = base64_decode($data['tag']);
        $plain = openssl_decrypt($ciphertext, $data['alg'] ?? 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);
        if ($plain === false) {
            throw new \RuntimeException('Decryption failed');
        }
        return $plain;
    }

    private function assertKey(): void
    {
        if (!$this->key || strlen($this->key) !== 32) {
            throw new \RuntimeException('Invalid STORAGE_ENCRYPTION_KEY (base64-encoded 32 bytes)');
        }
    }
}
