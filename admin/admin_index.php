<?php
ob_start();
header("Content-Type:text/html; charset=UTF-8");
// ここから、register.phpと同様
session_start();
if (isset($_SESSION['adminuser'])) {
    header("Location: index.php");
}
include("../parts/function.php");
?>

<?php
$emali = "";
$password = "";

if(isset($_POST['login'])) {

	$user =$_POST['user'];
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
	// クエリの実行
	$result = NGO("SELECT * FROM admin WHERE user='$user'");

	// パスワードとユーザーIDの取り出し
	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$db_hashed_pwd = $row['pass'];
		$user_id = $row['user'];
	}

	if (password_verify($_POST["password"], $db_hashed_pwd)) {
		$_SESSION['adminuser'] = $user_id;
		header("Location: index.php");
		exit;
	} else { ?>
<div class="alert alert-danger" role="alert"><?php echo $password ?><br/><?php echo $db_hashed_pwd ?>ユーザーの名前とパスワードが一致しません。</div>
	<?php }
} ?>

<!DOCTYPE HTML>
<?php include("css/css.php"); ?>
<body>
<div class="col-xs-6 col-xs-offset-3">

<form method="post">
	<h1>神のドア</h1>
	
	<div class="form-group">
		<input type="user"  class="form-control" name="user" placeholder="ユーザー" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" required />
	</div>
	<button type="submit" class="btn btn-default" name="login">ログインする</button>
	
</form>

</div>
</body>
</html>