<?php
//  HTTPヘッダーで文字コードを指定
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
include( "parts/function.php" );

//自分のIDを入れる
$user = $_SESSION['user'];

// ユーザーIDからユーザー名を取り出す
$result = NGO("SELECT * FROM users WHERE user_id=" . $user . "");
// ユーザー情報の取り出し
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    for ($i = 1; $i <= 5; $i++) {
        ${"tag" . $i} = $row["tag" . $i];
    }
}

//自分の持っているタグを取り出す
for ($i = 1; $i <= 5; $i++) {
    ${"tagN" . $i} = NGOpro(${"tag" . $i});
}

//チャット退出の処理
if (isset($_POST["fSub2"])) {
    if (isset($_REQUEST['fHandle']))
        $sHandle = $_REQUEST['fHandle'];
    else
        $sHandle = "";
    if (isset($_REQUEST['chatidD']))
        $chatidD = $_REQUEST['chatidD'];
    else
        $chatidD = "";

    $sMsg = "{$sHandle}さんが退室されました。";

    NGO("delete from user_tbl where handle = '$sHandle';");

    NGO("insert into chat_tbl values( null,'" . $_SESSION['user'] . "','" . $sHandle . "','" . $_SESSION['userNimg'] . "','" . $sMsg . "',null,'" . $chatidD . "' );");

    unset($_SESSION['chatid']);
    unset($_SESSION['chatname']);

    header('location:chat_list.php');
}
//チャットを作る処理
if (isset($_POST["editchat"])) {
    $chatname = "";
    if (isset($_POST["chatname"])) {
        $chatname = $_POST["chatname"];
    }

    $ninzu = "4";
    if (isset($_POST["ninzu"])) {
        $ninzu = $_POST["ninzu"];
    }

    $edituser = $_SESSION['userN'];

    $text = $_POST["roomtext"];
    $tag_id = $_POST["tag"];
    
    NGO("insert into room_tbl values
    ( null,'" . $chatname . "','" . $ninzu . "','$tag_id','" . $text . "','" . $edituser . "','" . $_SESSION['user'] . "' )");

    header('Location: chat_list.php');
}
//チャットを削除する処理
if (isset($_POST["chatid"])) {

    $chatid = "";
    if (isset($_POST["chatid"])) {
        $chatid = $_POST["chatid"];
    }
    $edituser = $_SESSION['userN'];
    NGO("delete from room_tbl where roomid = '$chatid'");
    header('Location: chat_list.php');
}

$SqlRes4 = NGO('select * from tag where Decision = 0');
while ($Row = $SqlRes4->fetch(PDO::FETCH_ASSOC)) {
    $TagAry[] = $Row;
}
?>
<script src="js/ajax.js"></script>
<title>ページタイトル</title>
<?php include( "parts/header.php" ); ?>
<body>
<style>
ul {
  list-style: none;
}
.tag-area{
    height:60px;
    overflow: auto;
}
</style>
    <div class="container">
        <div class="col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        ルーム作成
                    </div>
                </div>
                <form method="POST" action="chat_edit.php">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-2">ルーム名</div>
                        <div class="col-sm-10 form-inline" style="padding: 3px;">
                            <input type="text" class="form-control input-sm" name ="chatname" placeholder="ルーム名" size="20">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">人数</div>
                        <div class="col-sm-10 form-inline" style="padding: 3px;">
                            <select class="form-control" id="number" name="ninzu">
                                <?php foreach (range(2, 18) as $ninzu): ?>
                                    <option value="<?= $ninzu ?>"><?= $ninzu ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 form-inline" style="padding: 3px;">
                        <input type="text" id="search-text" placeholder="検索ワードを入力">
                            <div class="tag-area">
                                <ul class="target-area">
                                    <?php foreach($TagAry as $tag){ ?>
                                        <li style="float:left;">
                                            <div class="pretty p-icon p-round p-pulse">
                                                <input type="radio" name="TAGsearch" value="<?php echo $tag['id']; ?>">
                                                <div class="state p-success">
                                                    <i class="icon mdi mdi-check"></i>
                                                    <label><a style="margin-top:5px;"><?php echo $tag['tag_name']; ?></a></label>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div><br/><br/>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">ルーム紹介</div>
                        <div class="col-sm-5" style="padding: 3px;">
                            <textarea class="form-control  input-sm" rows="3" id="comment" name="roomtext"></textarea>
                        </div>
                    </div>

                    <div class="text-center" style="padding: 30px;">
                        <input type="hidden" name="editchat" value="">
                        <button type="submit" class="btn btn-success">作成</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
<script>
 $(function () {
  searchWord = function(){
    var searchText = $(this).val(), // 検索ボックスに入力された値
        targetText;

    $('.target-area li').each(function() {
      targetText = $(this).text();

      // 検索対象となるリストに入力された文字列が存在するかどうかを判断
      if (targetText.indexOf(searchText) != -1) {
        $(this).removeClass('hidden');
      } else {
        $(this).addClass('hidden');
      }
    });
  };

  // searchWordの実行
  $('#search-text').on('input', searchWord);
});
</script>