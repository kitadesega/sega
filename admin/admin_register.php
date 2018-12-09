<?php
session_start();


// DBとの接続
include("../parts/function.php");
?>
<!DOCTYPE HTML>

<?php include("css/css.php"); ?>
</head>
<body>
<div class="col-xs-6 col-xs-offset-3">

<?php
// signupがPOSTされたときに下記を実行
if(isset($_POST['signup'])) {

	$username = $_POST['username'];
	$password = password_hash($_POST['username'], PASSWORD_DEFAULT);

	// POSTされた情報をDBに格納する
	$query = "INSERT INTO admin(user,pass) VALUES('$username','$password')";

	if(NGO($query)) {  ?>
		<div class="alert alert-success" role="alert">登録しました<?php echo $username ?><?php echo $password ?></div>
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
		<input type="password" class="form-control" name="password" placeholder="パスワード" required />
	</div>
	
	<a href="admin_index.php">ログインはこちら</a>

    <input type="submit" name = "signup" value="aaa">
</form>

</div>
</body>
</html>