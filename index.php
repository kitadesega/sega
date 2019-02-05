<?php
ob_start();
header('Content-Type:text/html; charset=UTF-8');
// ここから、register.phpと同様
session_start();
if (isset($_SESSION['user']) != '') {
    header('Location:profile.php');
}
include 'parts/function.php';

?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>たぐったー</title>
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

<?php
$emali = '';
$password = '';
$db_hashed_pwd = '';
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $email = addslashes($email);
    $password = $_POST['password'];
    
    $result = NGO("SELECT * FROM users WHERE email='$email'");

    // パスワードとユーザーIDの取り出し
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $db_hashed_pwd = $row['password'];
        $user_id = $row['user_id'];
    }

    if ($password == $db_hashed_pwd) {
        $_SESSION['user'] = $user_id;
        header('Location: profile.php');
        exit;
    } else {
        ?>
		<div class="alert alert-danger" role="alert">メールアドレスとパスワードが一致しません。</div>
	<?php
    }
} ?>

<!DOCTYPE HTML>

<body>
<?php if (ua_smt() == true) { ?>
<div class="col-xs-8 col-xs-offset-2">
<form method="post">
	<h1 style="text-align:center">ログイン</h1>
	
	<div class="form-group">
		<input type="email"  class="form-control" name="email" placeholder="メールアドレス" id="reg-username"required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" id="reg-username" required />
	</div>
	<button type="submit" class="btn btn-success" name="login">ログイン</button>
	<br/><a href="register.php">会員登録はこちら</a>
</form>
</div>
<?php }else{ ?>
    <div class="col-xs-3 col-xs-offset-4"style="margin-top:100px;">
<form method="post">
	<h1 style="text-align:center">ログイン</h1>
	
	<div class="form-group">
		<input type="email"  class="form-control" name="email" placeholder="メールアドレス" id="reg-username"required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="パスワード" id="reg-username" required />
    </div>
    <div style="float:right;padding-right:30%;">
	<button type="submit" class="btn btn-success" name="login">ログイン</button>
	<a href="register.php"><button type="submit" class="btn btn-info" name="login">会員登録はこちら</button></a></div>
</form>
</div>

<?php } ?>
</body>
</html>