<?php

header("Content-Type:text/html; charset=UTF-8");
session_start();
include("parts/function.php");

if ( $_POST["bookmark"] == 0 ) {

    $my = $_SESSION['user'];
    $tweet_id = $_POST["tweet_id"];
    $user_id = $_POST["user_id"];

    NGO( "INSERT INTO favorite ( user_id,favorite_id ) VALUES ( '$my','$tweet_id' ) ;" );
    NGO( "INSERT INTO notice ( user_id,target_id,relation_id ) VALUES ( '$my','$user_id','$tweet_id' ) ;" );
}

//フォロー解除処理
if ( $_POST["bookmark"] == 1 ) {
    $my = $_SESSION['user'];
    $tweet_id = $_POST["tweet_id"];
    NGO( "DELETE FROM `favorite` WHERE user_id = '$my' AND favorite_id = '$tweet_id';" );

}
