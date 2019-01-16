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
<?php if (ua_smt() == true) { ?>
    <style>
body {
	background-color:#fff;
	color: #333333;
}
</style>

<div class = "profile-edit">
    <div class = "profile-edit-container">
        <div class = "profile-edit-image">
            <a>プロフィール画像</a>

            <div class="imagePreview">
                <span id = "parent">
            <?PHP if (isset($imgurl)) {?>
            <img src="img/<?php print $imgurl; ?>" />
                  <?PHP } else { ?>
                <input type="file" id="photo_1" name="photo_1" class="file" data-preview-file-type="image" data-language="ja" data-show-upload="false" data-show-caption="false" data-show-remove="false" data-default-preview-content='<img src="/images/noimage.png" alt="Photo1" style="width:160px">'>
            <?PHP } ?></span>
            </div>
            <div class="input-group"style="margin-top:5px;">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        画像ファイルを選択<input type="file" style="display:none" class="uploadFile">
                    </span>
                </label>
                <input type="text" class="form-control" readonly="">
            </div>

        </div>
        <div class = "profile-edit-name">
            <a>ユーザネーム</a><br/>
            <input type="text" value="<?php echo $username; ?>" placeholder="" name="username" id="username" />
        </div>
        <div class = "profile-edit-name">
            <a>性別</a><br/>
            <input type="radio" id="bar_1" value="男性" name="sex" <?php if($sex == "男性"){ ?>checked<?php }?>/><label for="bar_1">男性</label>
            <input type="radio" id="bar_2" value="女性" name="sex" <?php if($sex == "女性"){ ?>checked<?php }?>/><label for="bar_2">女性</label>
        </div>
       
        <div class = "profile-edit-form">
            <a>紹介文</a><br/>
            <textarea id="Introduction" name = "description" cols="30" rows="7"><?php echo $hitokoto; ?></textarea>
        </div>
        <div class = "profile-edit-name">
            <a><img src="svg/svg2/mark-github.svg" width="25" height="25" style="margin-top:-5px;">  Github URL</a><br/>
            <input type="text" value="<?php echo $url ?>" placeholder="" name="url" id="username" />
        </div>
       
        <div class = "update_button">
            <button type="submit" style = "width:150px"class="btn btn-info btn-lg btn-round">変 更</button>
        </div>

    </div>
</div>

                <?php }else{ ?>

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
                    <?php } ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <script>
        $(document).on('change', ':file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.parent().parent().next(':text').val(label);
            $("#parent").remove();
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                reader.onloadend = function(){ // set image data as background of div
                    input.parent().parent().parent().prev('.imagePreview').css("background-image", "url("+this.result+")");
                }
            }
        });
        </script>


</body>
</html>
