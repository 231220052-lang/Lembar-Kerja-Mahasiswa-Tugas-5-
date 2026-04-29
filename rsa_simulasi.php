<?php
// Paksa OpenSSL pakai config (WAJIB di Windows Laragon)
putenv("OPENSSL_CONF=C:\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\extras\\ssl\\openssl.cnf");

echo "<h2>Simulasi Kirim Surat RSA</h2>";


// 1. Setup Alice (Penerima)
$config = [
    "config" => "C:\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\extras\\ssl\\openssl.cnf",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

// Generate key
$res = openssl_pkey_new($config);

if (!$res) {
    die("❌ Gagal generate key");
}

// Ambil private key (string)
openssl_pkey_export($res, $privateKey);

// Ambil public key
$publicKeyDetails = openssl_pkey_get_details($res);
$publicKey = $publicKeyDetails['key'];

echo "<h3>1. Public Key Alice:</h3>";
echo "<pre>$publicKey</pre>";


$pesan = "Halo Alice, ini pesan rahasia dari Bob!";
echo "<h3>2. Pesan Asli:</h3>$pesan<br><br>";

// Enkripsi pakai public key
openssl_public_encrypt($pesan, $ciphertext, $publicKey);

// Encode base64
$cipher = base64_encode($ciphertext);

echo "<h3>Cipher (Base64):</h3>$cipher<br><br>";



// 3. Alice Membaca Pesan

$privateKeyObj = $res;

// Dekripsi
if (!openssl_private_decrypt(base64_decode($cipher), $hasil, $privateKeyObj)) {
    die("❌ Gagal dekripsi");
}

echo "<h3>3. Hasil Dekripsi:</h3>$hasil";
?>