<?php

header("Content-Type:text/html; charset=UTF-8");
session_start();
include("parts/function.php");

if ( $_POST["follow"] == 0 ) {

    $my = $_SESSION['user'];
    $user = $_POST["userID"];

    NGO( "INSERT INTO follows ( user_id,follow_id ) VALUES ( '$my','$user' ) ;" );
    NGO( "INSERT INTO notice ( user_id,target_id ) VALUES ( '$my','$user' ) ;" );
}

//フォロー解除処理
if ( $_POST["follow"] == 1 ) {
    $my = $_SESSION['user'];
    $user = $_POST["userID"];

    NGO( "DELETE FROM `follows` WHERE user_id = '$my' AND follow_id = '$user';" );

}

$arr = array('result' => "success");
            //add the header here
            header('Content-Type: application/json');
            echo json_encode( $arr );