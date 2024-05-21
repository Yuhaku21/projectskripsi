<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM bobot_kriteria WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil dihapus!";
    header('Location: dashboard.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
