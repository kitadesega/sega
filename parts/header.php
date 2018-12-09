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
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/profile.php') {
        echo ' active';
    } ?>"><a href="profile.php"><img src="svg/home.svg" width="24" height="24"><br />Home</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/user_search.php') {
        echo ' active';
    } ?>"><a href="user_search.php"><img src="svg/search.svg" width="24" height="24"><br />検索</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/chat_list.php') {
        echo ' active';
    } ?> "><a href="chat_list.php"><img src="svg/chat.svg" width="24" height="24"><br />チャット </a></li>
                <li><a href="#tabtest3" class="iphoneSE" data-toggle="tab"><img src="svg/file.svg" width="24" height="24"><br />ライブラリ</a></li>

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

                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/profile.php') {
            echo ' active';
        } ?>"><a href="profile.php" style = "font-size:22px;"><i class="fas fa-home"></i> ホーム</a>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/user_search.php') {
            echo ' active';
        } ?>"><a href="user_search.php" style = "font-size:22px;"><i class="fas fa-search"></i> ユーザー検索</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/chat_list.php') {
            echo ' active';
        } ?>"><a href="chat_list.php" style = "font-size:22px;"><i class="fas fa-comments"></i> チャット</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style = "font-size:22px;">
                        <i class="fas fa-cogs"></i> 設定 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/home_change.php') {
            echo ' active';
        } ?>"><a href="home_change.php">プロフィール変更</a></li>
                        <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/tagdisp.php') {
            echo ' active';
        } ?>"><a href="tagdisp.php">タグ設定</a></li>
                        <li><a href="#">ダミー</a></li>
                        <li role="separator" class="divider"></li>
                        
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class=""><a href="admin/index.php"><i class="fas fa-ad"></i> 管理者ページ</a></li>
                <li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/chat21/Contact.php') {
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
    html {

        overflow-y: scroll;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    .haguruma {
        width: 20px;
        height: 20px;
        padding: 15px;
        width: 20%;
        background: url("svg/haguruma.svg") left no-repeat;
        color: #8a9aa4;
        float: left;

    }

    .none {
        width: auto;
        padding: 0;
        margin: 0;
        background: none;
        border: 0;
        font-size: 0;
        line-height: 0;
        overflow: visible;
        cursor: pointer;
        outline: none;

    }

    .panele {
        margin-bottom: 10px;
        background-color: #fff;
        padding-bottom: 0px;
        border-radius: 2px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-noborder {
        border-top: 0px !important;
        border-bottom: 0px !important;
    }

    @media screen and (max-width: 680px)and (min-width: 330px) {
        body {
            padding-top: 70px;
            padding-bottom: 80px;
        }

        * {
            font-size: 16px;
        }

        /* ナビゲーション（下）の長さとか*/
        .navbar-nav>li>a {
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 15px;
        }

        /* 上に固定してあるメニュー*/
        .navbar-nav {
            margin: 0px -15px;
        }

        .com_foot {
            font-size: 10px;
        }

        .chkbox input[type=checkbox] {
            width: 24px;
            height: 24px;
            -moz-transform: scale(1.4);
            -webkit-transform: scale(1.4);
            transform: scale(1.4);
        }

        /*歯車の位置*/
        .btn-default {
            margin-top: 10px;
            background-color: transparent;
            border-color: transparent;
            margin-left: -10px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            -webkit-transform: scale(1.5);
            transform: scale(1.5);

        }

        h1,
        h2 {
            font-size: 1.5em;
        }

        h3,
        h4 {
            font-size: 1.3em;
        }

        p,
        div,
        .panel-title,
        .form-control {
            font-size: 1em;
        }

        p.story {
            font-size: 1.0em;
        }

        .copyright {
            font-size: 0.7em;
        }

        li {}

        #bottom {}

        #tweet li {
            width: 25%;
        }

        #tweet li a {
            text-align: center;
            font-size: 10px;
        }

        #bottom li {
            width: 33%;
        }

        #bottom li a {
            text-align: center;
            font-size: 10px;
        }

        .a {
            font-size: 10px;
        }

        /* header */
        .rightmenu {
            width: ;
            float: left;

        }

        /* スマホの時に上のナブバーを透明にする */
        .navbar-default {
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-collapse,
        .navbar-default .navbar-form {
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-toggle {
            border-color: transparent;
        }

        .navbar-default .navbar-nav .open .dropdown-menu>.active>a,
        .navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,
        .navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover {
            color: transparent;
            background-color: transparent;
        }


        .navbar-default .navbar-toggle:focus,
        .navbar-default .navbar-toggle:hover {
            border-color: transparent;
        }

        .navbar-default .navbar-nav>.open>a,
        .navbar-default .navbar-nav>.open>a:focus,
        .navbar-default .navbar-nav>.open>a:hover {
            color: transparent;
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-toggle:focus,
        .navbar-default .navbar-toggle:hover {
            background-color: transparent;
            border-color: transparent;
        }

    }

    @media screen and (max-width: 330px) {
        body {
            padding-top: 50px;
        }

        * {
            font-size: 14px;
        }

        body {
            padding-top: 70px;
            padding-bottom: 80px;
        }

        * {
            font-size: 16px;
        }

        /* ナビゲーション（下）の長さとか*/
        .navbar-nav>li>a {
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 15px;
        }

        /* 上に固定してあるメニュー*/
        .navbar-nav {
            margin: 0px -15px;
        }

        /*歯車の位置*/
        .btn-default {
            margin-top: 10px;
            background-color: transparent;
            border-color: transparent;
            margin-left: -10px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            -webkit-transform: scale(1.5);
            transform: scale(1.5);

        }

        h1,
        h2 {
            font-size: 1.5em;
        }

        h3,
        h4 {
            font-size: 1.3em;
        }

        p,
        div,
        .panel-title,
        .form-control {
            font-size: 1em;
        }

        p.story {
            font-size: 1.0em;
        }

        .copyright {
            font-size: 0.7em;
        }

        li {}

        #bottom {}

        #tweet li {
            width: 25%;
        }

        #tweet li a {
            text-align: center;
            font-size: 8px;
            padding: 12px;
        }

        #bottom li {
            width: 33%;
        }

        #bottom li a {
            text-align: center;
            font-size: 10px;
        }

        .a {
            font-size: 8px;
        }

        /* header */
        .rightmenu {

            float: left;

        }

        /* スマホの時に上のナブバーを透明にする */
        .navbar-default {
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-collapse,
        .navbar-default .navbar-form {
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-toggle {
            border-color: transparent;
        }

        .navbar-default .navbar-nav .open .dropdown-menu>.active>a,
        .navbar-default .navbar-nav .open .dropdown-menu>.active>a:focus,
        .navbar-default .navbar-nav .open .dropdown-menu>.active>a:hover {
            color: transparent;
            background-color: transparent;
        }


        .navbar-default .navbar-toggle:focus,
        .navbar-default .navbar-toggle:hover {
            border-color: transparent;
        }

        .navbar-default .navbar-nav>.open>a,
        .navbar-default .navbar-nav>.open>a:focus,
        .navbar-default .navbar-nav>.open>a:hover {
            color: transparent;
            background-color: transparent;
            border-color: transparent;
        }

        .navbar-default .navbar-toggle:focus,
        .navbar-default .navbar-toggle:hover {
            background-color: transparent;
            border-color: transparent;
        }

        /* 左上の歯車ナビゲーションのアクティブ時を透明化したい */
        .btn-default:hover {
            color: transparent;
            background-color: transparent;
            border-color: transparent;
        }

        default.dropdown-toggle:focus,
        .open>.btn-default.dropdown-toggle:hover {
            color: transparent;
            background-color: transparent;
            border-color: transparent;
        }

        default:active,
        .open>.btn-default.dropdown-toggle {
            color: transparent;
            background-color: transparent;
            border-color: transparent;
        }
    }

    .nav-tabs>li {
        float: left;
        margin-bottom: -2px;
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus,
    .nav-tabs>li.active>a:hover {
        color: #fdfdfe;
        cursor: default;
        background-color: #428bca;
        border: 0px solid #5bc0de;

        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);

    }

    @media screen and (min-width: 730px) {
        .navbar-nav>li:not(:last-child) {
            margin-right: 20px;
        }
    }
    @media (min-width: 768px){
.navbar-nav>li>a {
    padding-top: 12px;
    padding-bottom: 12px;
    margin-top: 21px;
    margin-bottom: 21px;
    border-radius: 4px;
}
    }

</style>
