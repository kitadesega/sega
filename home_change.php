<?php
header("Content-Type:text/html; charset=UTF-8");
session_start(  );
include( "parts/function.php" );
if ( !isset( $_SESSION['user'] ) ) {
    header( "Location: index.php" );
}
//変更ボタンを押した時のプロフィール変更処理
if( isset( $_POST['change'] ) ) {
$savedir = "./img/";    //  master 
$upfname = $_FILES["upfname"]["name"];
//テキストボックス入力値 
$newfilename = $_POST["filename"];
$hitokoto = $_POST["hitokoto"];
$privacy = $_POST["privacy"];
//元のファイルの拡張子を抜き出す 
$fileext = substr( $upfname, strrpos( $upfname, "." ) );
if ( $fileext != "" ) {
    $savefilename = $newfilename . $fileext;
    move_uploaded_file( $_FILES["upfname"]["tmp_name"], $savedir . $savefilename );
    NGO( "update users set
    user_img = '" . $savefilename . "' where user_id = '" . $_SESSION['user'] . "'" );
}
NGO( "update users set
hitokoto= '" . $hitokoto . "' , privacy = '$privacy' where user_id = '" . $_SESSION['user'] . "'" );
header( 'location:home.php' );
    
}
// ユーザーIDからユーザー名を取り出す
$SqlRes = NGO( "SELECT * FROM users WHERE user_id=" . $_SESSION['user'] . "" );
// ユーザー情報の取り出し
while ( $row = $SqlRes->fetch( PDO::FETCH_ASSOC ) ) {
    $username = $row['username'];
    $email = $row['email'];
    $imgurl = $row['user_img'];
    $hitokoto = $row['hitokoto'];
    $_SESSION['userN'] = $username;
    $_SESSION['userNimg'] = $imgurl;
}
?>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
<?php include( "parts/css.php" ); ?>
<?php include( "parts/header.php" ); ?>
<style>
html{
overflow-x : hidden;
overflow-y : auto;
}

body{
overflow-x : hidden;
overflow-y : auto;
}
</style>
<body>
    <div class="container">  
        <div class="row">
            <!-- 残り8列はコンテンツ表示部分として使う -->
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">名前：
                            <?php echo $username; ?>
                        </div>
                    </div>
                    <div class="panel-body"><a class="disabled">プロフィール画像：<img src="img/<?php print $imgurl; ?>" class="img-circle" width="100" /></a>
                        <?php
                        //  処理部 
                        //戻るボタンで戻ってきた 
                        if ( isset( $_POST["fpath"] ) ) {
                            unlink( $_POST["fpath"] );
                        }
                        ?>
                        <form action="home_change.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="filename" value="<?php echo $username; ?>" />
                             <input type="hidden" name="change" value="b" />
                             <br />
                            <input type="file" name="upfname" /><br />



                            <br/><a class="disabled">メールアドレス：
                                <?php echo $email; ?></a><br /><a class="disabled">一言コメント：</a><br />
                            <textarea name="hitokoto" class="form-control" rows="3" cols="20" wrap="soft"><?php echo $hitokoto; ?></textarea>
                            <form action="radio1.php" method="post">
                                フォロワーとか公開する？<br>
                                <input type="radio" name="privacy" value="ON" checked>する<br>
                                <input type="radio" name="privacy" value="OFF">しない<br>
                                <br/>
                                <input type="submit" value="変更" />
                            </form>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">sv-02</div>
                    </div>
                    <div class="panel-body"></div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">sv-03</div>
                    </div>
                    <div class="panel-body">サーバー３のステータスOK</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
