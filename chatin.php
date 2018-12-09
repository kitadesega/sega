
<?php
if ( !isset( $_SESSION)) {
    session_start( );
}
if ( !isset( $_SESSION['user'])) {
    header( "Location:index.php");
}
$chatid = "";
if ( isset( $_POST["chatid"])) {
    $chatid = $_POST["chatid"];
}
include( "parts/function.php");

$ume = $_SESSION['userN'];

$SqlRes = NGO( "select count( *) from user_tbl where handle = '$ume'");

$unko = $SqlRes->fetch( PDO::FETCH_ASSOC);

$SqlRes = NGO( "select count( roomid) from user_tbl where roomid = '$chatid';");

$RowC = $SqlRes->fetch( PDO::FETCH_ASSOC);

$SqlRes = NGO( "select * from room_tbl where roomid = '$chatid';");

$RowL = $SqlRes->fetch( PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>チャットフレーム版</title>
    </head>
    <body>
        <?php if ( $RowC["count( roomid)"] < $RowL["limit"] || $unko["count( *)"] != 0) { ?>
            <form name="form1" method="post" action="chat.php">
                ハンドル名：<?php echo $_SESSION['userN']; ?><input type="hidden"name="fHandle" value="<?php echo $_SESSION['userN']; ?>">
                <input name="fIn" type="hidden" value="logIn"> <!-- 最初のログインの情報を送信している。 -->
                <input type="hidden" name="chatid" value="<?php print $chatid; ?>">
                <input type="hidden" name="chatname" value="<?php print $RowL["roomname"]; ?>">
            </form>
            <script>
                document.forms[0].submit( ); //自動でサブミットさせるスクリプトだよん
            </script><?php
        } else {

            header( 'location: chat_list.php');
        }
        ?>
    </body>
</html>