<?php
$dsn = "mysql:host=localhost;charset=utf8;dbname=db14";
$pdo = new PDO($dsn, 'root', '');

if (!empty($_FILES['img']['tmp_name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], "../images/" . $_FILES['img']['name']);
    $sql = "insert into `title`(`img`,`text`) values('{$_FILES['img']['name']}','{$_POST['text']}')";
    $pdo->exec($sql);
    header("location:../admin.php?do=title");
}
