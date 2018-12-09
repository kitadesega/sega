<?php
//データベース操作関数、SQL文を受け取る
function NGO($process) {
    include("mysqlenv.php");

    try {
        $dbh = new PDO($dsn, $USER, $PASS);
    } catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
    }
    $sql = $process;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt;
}

//タグの名前抜き出す時に使う関数ぢゃ
function NGOpro($process2) {
    include("mysqlenv.php");
    try {
        $dbh = new PDO($dsn, $USER, $PASS);
    } catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
    }
    $sql = ("select * from tag where id = '$process2'");
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $Row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $Row;
}

function iset($REQ) {
    if (isset($_REQUEST[$REQ]))
        $_SESSION[$REQ] = $_REQUEST[$REQ];
    return $REQ;
}

//XSS対策だよ！
function XSS($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//エスケープの奴だよ！対策だよ！
function sethtmlspecialchars($data) {
    if (is_array($data)) {//データが配列の場合
        return array_map("sethtmlspecialchars", $data);
    } else {//データが配列ではない場合
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

//getの場合の処理関数～
$_GET = sethtmlspecialchars($_GET);

//postの場合の処理関数～えすけーぷ！
$_POST = sethtmlspecialchars($_POST);



//表示する時のエスケープ処理
function es ($data){
  //配列の時は値を１づつ呼び出して再帰呼び出し:自身を呼び出すこと
  if (is_array($data)){
    return array_map(__METHOD__,$data);
    //__METHOD__は実行中のメソッド:es()を呼び出すマジック定数
  }else{
    return htmlspecialchars($data,ENT_QUOTES,'UTF-8');
  }
}

//ランダム文字列作成関数～
function makeRandStr($length) {
    $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
}

//消す奴
function delete($table, $user) {
    NGO("DELETE FROM `$table` WHERE user_id = '$user'");
}

//スマホ判定
function ua_smt() {
//ユーザーエージェントを取得
    $ua = $_SERVER['HTTP_USER_AGENT'];
//スマホと判定する文字リスト
    $ua_list = array('iPhone', 'iPad', 'iPod', 'Android');
    foreach ($ua_list as $ua_smt) {
//ユーザーエージェントに文字リストの単語を含む場合はTRUE、それ以外はFALSE
        if (strpos($ua, $ua_smt) !== false) {
            return true;
        }
    } return false;
}
?>


