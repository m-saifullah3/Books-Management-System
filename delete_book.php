<?php require_once './database/connection.php'; ?>

<?php

if (isset ($_GET['id']) && !empty ($_GET['id'])) {
    $book_id = htmlspecialchars($_GET['id']);
} else {
    header('Location: ./admin_sign_in.php');
}

$sql = "SELECT `book_picture` FROM `books` WHERE `book_id` = $book_id";
$result = $conn->query($sql);
$picture = $result->fetch_assoc();
$pathtodir = getcwd();
$picture = implode($picture);
$dir = $pathtodir . '/books_pictures/' . $picture;
unlink($dir);

$sql = "DELETE FROM `books` WHERE `book_id` = $book_id";

if ($conn->query($sql)) {
    header('Location: ./admin_index.php');
} else {
    echo 'Book has failed to delete';
}