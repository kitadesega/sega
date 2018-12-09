<?php   
// ini_set("display_errors", On);  
// error_reporting(E_ALL);  

//  HTTPヘッダーで文字コードを指定
// header("Content-Type:text/html; charset=UTF-8");

//  処理部
//半角カナは文字化けを防ぐために全角カナに変換する。
//$Cont = "aaa";
//$NameTo = "aaa";
//$AddTo = "segarian8@gmail.com";
//$NameFrom = "aaaa";
//$AddFrom = "wasi";
//$Title = "aaaaaaa";
//$Cont = mb_convert_kana($Cont,"KV","UTF-8");
//
////カレント言語の設定(日本語 UTF-8)
//mb_language("uni");
//
////メールタイトル、送信先名、送信元名、メール本文は『UTF-8』から『ISO-2022-JP』に変換しておく
//// $Title = mb_convert_encoding($Title,"IS-2022-JP","UTF-8");
//
////月化情報(ヘッダー)の設定
//$headers = "To:$NameTo \ <$AddTo>\r\n";
//$headers .= "From:$NameFrom \ <$AddFrom>\r\n";
//$headers .= "Content-Type:text/plan; charset=ISO-2022-JP\r\n";
//$headers .= "Content-Transfer-Encoding: 7bit\r\n";
//
//echo $headers;
//////メール送信
//if(mb_send_mail($AddTo,$Title,$Cont,$headers)){
//   print("MAIL OK!");
//}else{
//   print("MAIL NG!");
//
//}
?>

<?php
// ini_set("SMTP", "smtp://localhost");
// ini_set("smtp_port", "1025");
// ini_set("sendmail_path", "/usr/bin/env catchmail -f some@from.address");  

mb_language("Japanese");
mb_internal_encoding("UTF-8");
$to = 'keisuke226214@gmail.com';
$title = 'www';
$content = '草';
if(mb_send_mail($to, $title, $content)){
echo "メールを送信しました";
} else {
echo "メールの送信に失敗しました";
}
?>