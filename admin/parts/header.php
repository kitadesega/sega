<link href="css/style.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">管理用ページ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/index.php') echo " active"; ?>" href="index.php">ユーザー
                <span class="sr-only">(めいん)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/alldisp.php') echo " active"; ?>" href="alldisp.php">投稿ログ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/tag_change.php') echo " active"; ?>" href="tag_change.php">タグ一覧</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/chatlog.php"') echo " active"; ?>" href="chatlog.php">チャットログ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/file.php') echo " active"; ?>" href="file.php">ファイル管理</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if ($_SERVER['SCRIPT_NAME'] == '/chata/chat21/admin/Contact.php') echo " active"; ?>" href="Contact.php">ユーザーから</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
