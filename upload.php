<?php
$conn = new mysqli("localhost", "root", "", "absensi_qr");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['image'])) {
    $img = $_POST['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $filename = uniqid() . '.png';
    $path = 'uploads/' . $filename;

    // Simpan ke folder
    file_put_contents($path, $data);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO absensi (foto) VALUES (?)");
    $stmt->bind_param("s", $filename);
    $stmt->execute();

    echo "<h2>Foto berhasil disimpan!</h2>";
    echo "<p>Berikut foto yang kamu simpan:</p>";
    echo "<img src='$path' width='300' style='border: 1px solid #333; padding: 10px;'>";

    echo "<br><br><a href='index.php'>⬅️ Kembali ke Halaman Utama</a>";
} else {
    echo "Data gambar tidak ditemukan.";
}
?>
