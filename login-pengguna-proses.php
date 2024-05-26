<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wisata";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$user = $_POST['username'];
$pass = $_POST['password'];

// Ambil data user dari database
$sql = "SELECT * FROM users WHERE username='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verifikasi password
    if (password_verify($pass, $row['password'])) {
        // Set session dan redirect ke index.php
        $_SESSION['username'] = $user;
        header("Location: home.html");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found.";
}

$conn->close();
?>
