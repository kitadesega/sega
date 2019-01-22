<?php
session_start( );
if(  isset( $_SESSION['user']) != "") {
	// ログイン済みの場合はリダイレクト
	header( "Location: profile.php");
}

// DBとの接続
include( "parts/function.php");
?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>なまえなににしよ</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet">
    <script src="js/jquery-3.3.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/head.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
</head>
<body>

<?php
// signupがPOSTされたときに下記を実行
if( isset( $_POST['signup'])) {

	$username		= $_POST['username'];
	$email			= $_POST['email'];
	$password		= $_POST['password'];
	$description	= $_POST['description'];
	$URL			= $_POST['URL'];

	$savedir        = "./img/";
	$tmpname        = $_FILES["file"]["name"];
	$ext = ".".pathinfo($tmpname , PATHINFO_EXTENSION);
	$file_name		= $username.$ext;
	move_uploaded_file( $_FILES["file"]["tmp_name"], $savedir . $file_name );

	// POSTされた情報をDBに格納する
	$query = "INSERT INTO users( user_img,username,email,password,hitokoto,URL) VALUES( '$file_name','$username','$email','$password','$description','$URL')";

	if( NGO( $query)) {  ?>
	<?php header( "Location: index.php"); ?>
		<div class="alert alert-success" role="alert">登録しました</div>
		<?php } else { ?>
		<div class="alert alert-danger" role="alert">エラーが発生しました。</div>
		<?php
	}
} ?>
<style>
	a{
		color:black;
		padding-left:10px;
	}
</style>
<?php if (ua_smt() == true) { ?>
<div class="col-xs-12">
<?php }else{ ?>
<div class="col-xs-4 col-xs-offset-4">
<?php } ?>
	<form method="post"enctype="multipart/form-data">
		<h1 style="text-align:center">会員登録フォーム</h1>
		<div class = "profile-edit-image">
			<a>プロフィール画像</a>
			<div class="imagePreview">
			<span id = "parent">
		
			</span>
			</div>
			<div class="input-group"style="margin-top:5px;">
				<label class="input-group-btn">
					<span class="btn btn-primary">
						画像ファイルを選択<input type="file" style="display:none" class="uploadFile"name="file">
					</span>
				</label>
				<input type="text" class="form-control" readonly="">
			</div>
		</div>
		<div class = "profile-edit-name">
			<a>ユーザネーム</a><br/>
			<input type="text" value="<?php echo $username; ?>" placeholder="" name="username" id="reg-username"  />
		</div>

		<div class = "profile-edit-name">
			<a>メールアドレス</a><br/>
			<input type="text" value="<?php echo $username; ?>" placeholder="" name="email" id="reg-username" />
		</div>

		<div class = "profile-edit-name">
			<a>パスワード</a><br/>
			<input type="password" value="<?php echo $username; ?>" placeholder="" name="password" id="reg-username" />
		</div>

		<div class = "profile-edit-form">
			<a>紹介文</a><br/>
			<textarea id="reg-Introduction" name = "description" cols="30" rows="7"><?php echo $hitokoto; ?></textarea>
		</div>
		
		<div class = "profile-edit-name">
			<a><img src="svg/svg2/mark-github.svg" width="25" height="25" style="margin-top:-5px;">  Github URL</a><br/>
			<input type="text" value="<?php echo $url ?>" placeholder="" name="URL" id="reg-username" />
		</div>
				
		<div class = "reg_button">
			<button type="submit" class="btn btn-success" name="signup">会員登録する</button>
		</div>
		<a href="index.php" style="color:DeepSkyBlue">ログインはこちら</a>
	</form>
</div>

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