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
<?php if (ua_smt() == true) {
    ?>
<nav class="navbar navbar-expand navbar-fixed-bottom" style="width:80px;">
    <div class="container">
        <div class="dropdown rightmenu">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                <span class="haguruma"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a href="profile.php">プロフィール</a></li>
                <li><a href="home_change.php">プロフィール変更</a></li>
                <li><a href="tagdisp.php">タグ設定</a></li>
                <li><a href="Contact.php">お問い合わせ</a></li>
                <li><a href="home.php?logout">ログアウト</a></li>
            </ul>
        </div>
    </div><!-- /.container -->
</nav>

<div id="tweet">
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top" style="padding-bottom:0px;background-color:;">
        <div class="container" style="padding:transparent">

     <ul class="nav navbar-nav nav-tabs panele paddin">
        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/profile.php') {echo ' active';} ?>">
            <a href="profile.php"><img src="svg/home.svg" width="24" height="24"><br />Home</a>
        </li>
        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/home.php') {echo ' active';} ?> ">
            <a href="home.php" ><img src="svg/diary.svg" width="24" height="24"><br />diary</a>
        </li>
        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/chat_list.php') { echo ' active';} ?> ">
            <a href="chat_list.php"><img src="svg/chat.svg" width="24" height="24"><br />チャット </a>
        </li>
        <li class="header-head <?php if ($_SERVER['SCRIPT_NAME'] == '/sega/user_search.php') {echo ' active';} ?>">
            <a href="user_search.php"><img src="svg/search.svg"><br />検索</a>
        </li>
     </ul>

        </div><!-- /.container -->
    </nav>
</div>
<?php
} else {
        ?>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
           
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/profile.php') {
            echo ' active';
        } ?>"><a href="profile.php" style = "font-size:22px;"><i class="fas fa-home"></i> ホーム</a>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/user_search.php') {
            echo ' active';
        } ?>"><a href="user_search.php" style = "font-size:22px;"><i class="fas fa-search"></i> ユーザー検索</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/chat_list.php') {
            echo ' active';
        } ?>"><a href="chat_list.php" style = "font-size:22px;"><i class="fas fa-comments"></i> チャット</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style = "font-size:22px;">
                        <i class="fas fa-cogs"></i> 設定 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/home_change.php') {
            echo ' active';
        } ?>"><a href="home_change.php">プロフィール変更</a></li>
                        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/tagdisp.php') {
            echo ' active';
        } ?>"><a href="tagdisp.php">タグ設定</a></li>
                        <li><a href="#">ダミー</a></li>
                        <li role="separator" class="divider"></li>
                        
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class=""><a href="admin/index.php"><i class="fas fa-ad"></i> 管理者ページ</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/sega/Contact.php') {
            echo ' active';
        } ?>"><a href="Contact.php"><i class="far fa-envelope"></i> お問い合わせ</a></li>
                <li><a href="home.php?logout"><i class="fas fa-user"></i> ログアウト</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>
<?php
    } ?>
<script>
    /*
     * 確認ダイアログの返り値によりフォーム送信
     */
    function submitChk() {
        /* 確認ダイアログ表示 */
        var flag = confirm("送信してもよろしいですか？\n\n送信したくない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>

<style>

</style>
