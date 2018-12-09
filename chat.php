<!doctype html>
<?php
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
session_start();
include( "parts/function.php" );
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
?>
<?php include( "parts/css.php" ); ?>
<link href="css/top.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<script src="js/ajax.js"></script>
<style>

    a:link{
        text-decoration: none;
        pointer-events: auto;
    }

</style>
<?php
$sIn = "";
iset('chatname');
iset('chatid');
if (isset($_REQUEST['fIn']))
    $sIn = $_REQUEST['fIn']; //入室画面から入室したことを表すフラグの受信

if (!isset($_SESSION['chatid'])) {
    $_SESSION['chatid'] = $_REQUEST['chatid'];
}
$chatid = $_SESSION['chatid'];
if (!isset($_SESSION['chatname'])) {
    $_SESSION['chatname'] = $_REQUEST['chatname'];
}

//msgInの処理
if (isset($_REQUEST['chatid']))
    $chatid = $_REQUEST['chatid'];
else
    $chatid = "";
if (isset($_REQUEST['fHandle']))
    $sHandle = $_REQUEST['fHandle'];
else
    $sHandle = ""; //ハンドル名の受信

if (isset($_REQUEST['fMsg']))
    $sMsg = $_REQUEST['fMsg'];
else
    $sMsg = ""; //メッセージの受信

if (isset($_REQUEST['fSub2']))
    $sSub2 = $_REQUEST['fSub2'];
else
    $sSub2 = ""; //退室ボタンの値の受信

$chatname = $_SESSION['chatname'];
if ($sIn == "logIn") {//入室画面から入室した時の処理
    $sMsg = "{$_SESSION['userN']}さんが入室されました。";
    $SqlRes = NGO("select * from user_tbl where handle = '" . $_SESSION['userN'] . "' AND roomid = '" . $chatid . "' AND user_id = '" . $_SESSION['user'] . "'");
    $Row3 = $SqlRes->fetch(PDO::FETCH_ASSOC);
    if ($_SESSION['user'] != $Row3['user_id']) {
        NGO("insert into user_tbl values('{$_SESSION['userN']}', '{$chatid}','{$_SESSION['user']}')");
        NGO("insert into chat_tbl values( null,'{$_SESSION['user']}','{$_SESSION['userN']}','{$_SESSION['userNimg']}','{$sMsg}',null,'{$chatid}' );");
    }
}
?>

<body>
    <div class="container">
        <?php include( "parts/header.php" ); ?>
        <div class="row">

            <div class="col-xs-12 col-md-7">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                        </div>
                    </div>
                    <div class="panel-body">
                        <a style="font-size:16px;">チャットルーム名：<span style="color:red;font-size:16px;">
                                <?= $chatname ?></span>
                            ハンドル名：<span style="color:red;">
                                <?= $sHandle ?></a><img src="img/<?php print $_SESSION['userNimg']; ?>" style="margin-left:30px;" class="img-circle" width="60" /></span><br />
                        <?php
                        include( "userlist.php" );
                        ?>
                        <br />
                        <div style="display:inline-flex">
                            <div id="bms_send">
                                <form name="form1" method="post" action="chat.php">
                                    <input name="fMsg" autofocus id="bms_send_message" type="textarea" size="100%"><!--　メッセージの入力　-->
                                    <input name="fHandle" type="hidden" value="<?= $sHandle ?>">
                                    <input name="chatid" type="hidden" value="<?= $chatid ?>">
                                    <input name="chatname" type="hidden" value="<?= $chatname ?>">
                                    <input type="submit" name="fSub1" id="bms_send_btn" value="発言">
                                </form>
                                <form name="form1" method="post" action="chat_edit.php">
                                    <input name="fHandle" type="hidden" value="<?= $sHandle ?>">
                                    <input name="chatidD" type="hidden" value="<?= $chatid ?>">
                                    <input name="chatname" type="hidden" value="<?= $chatname ?>">
                                    <input type="submit" name="fSub2" id="bms_send_btn2" value="退室">
                                </form>
                            </div>
                        </div>
                        <script type="text/javascript">
                            document.getElementById('fMsg').focus();

                        </script>
                        <?php
                        if ($sMsg != "" && $sIn != "logIn") {//メッセージが入力されている場合。
                            //$sMsg = real_escape_string( $sMsg );
                            $sMsg = addslashes($sMsg);
                            NGO("insert into chat_tbl values( null,'{$_SESSION['user']}','{$_SESSION['userN']}','{$_SESSION['userNimg']}','{$sMsg}',null,'{$chatid}' );");
                        }
                        //ここからチャットログ表示の処理
                        ?>
                        <div><span style="color:blue;"></span>

                        </div>
                        <hr>
                        <table class="col-xs-12  col-md-12">
                            <td style="background:#bcbcbc">
                                <div class="line__container">
                                    <div class="line__contents scroll">

                                        <div id="ajaxreload">
                                            <?php
                                            include( "ajax.php" );
                                            ?>
                            </td>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>
