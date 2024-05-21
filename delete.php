<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM kriteria WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: dashboard.php');
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
