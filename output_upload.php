<?php

header("Content-Type:text/html; charset=UTF-8");
session_start();
include("parts/function.php");
//ポストで来たかどうか判断//複数ファイル配列送信対策
if (isset($_POST["output"])) {
    $title = $_POST["title"];
    $tag = $_POST["tag"];
    $text = $_POST["text"];
    $media = $_POST["title"];

$tempfile = $_FILES['fname']['tmp_name'];
$filename = './img/' . $_FILES['fname']['name'];
$name = $_FILES['fname']['name'];
$fileext = substr($name, strrpos($name, "."));

//正規表現で画像以外を弾く。
if (!preg_match('/\.gif$|\.png$|\.jpg$|\.jpeg$|\.bmp$/i', $filename)) {
    $elmsg = '不正な拡張子';
    
}
$checkname = $filename;

//既に同じ名前のファイルがあった場合、ランダムな文字列にして一致しなくなるまで繰り返すんご～
while (file_exists($checkname)) {
    $name = makeRandStr(5) . $fileext;
    $checkname = './img/' . $name;
}

//重複しなければそのままの名前を、重複したら違う名前を。でもこれ二度手間？？？？？
$filename = $checkname;
if (is_uploaded_file($tempfile)) {
    if (move_uploaded_file($tempfile, $filename)) {
        $user = $_SESSION['user'];
        NGO("INSERT INTO `output`(`id`, `user_id`, `tag_id`, `title`, `media`, `text`)"
                . " VALUES ('','$user','$tag','$title','$name','$text')");

        
    } else {
        $elmsg = "ファイルをアップロードできません。";
    }
} else {
    $elmsg = "ファイルが選択されていません。";
}

header("Location: home.php");
   
}

?>