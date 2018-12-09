<?php
session_start( );
if(  isset( $_SESSION['user']) != "") {
	// ログイン済みの場合はリダイレクト
	header( "Location: home.php");
}

// DBとの接続
include( "parts/function.php");
?>
<?php include( "parts/css.php"); ?>
</head>
<body>
<div class="col-xs-6 col-xs-offset-3">

<?php
// signupがPOSTされたときに下記を実行
if( isset( $_POST['signup'])) {

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// POSTされた情報をDBに格納する
	$query = "INSERT INTO users( username,email,password) VALUES( '$username','$email','$password')";

	if( NGO( $query)) {  ?>
		<div class="alert alert-success" role="alert">登録しました<?php echo $username ?><?php echo $email ?><?php echo $password ?></div>
		<?php } else { ?>
		<div class="alert alert-danger" role="alert">エラーが発生しました。</div>
		<?php
	}
} ?>

<form method="post">
	<h1>会員登録フォーム</h1>
	<div class="form-group">
		<input type="text" class="form-control" name="username" placeholder="ユーザー名" required />
	</div>
	<div class="form-group">
		<input type="email"  class="form-control" name="email" placeholder="メールアドレス" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" required />
	</div>
	<button type="submit" class="btn btn-default" name="signup">会員登録する</button>
	<a href="index.php">ログインはこちら</a>
</form>

</div>
</body>
</html>