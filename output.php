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
?>
<script src="js/ajax.js"></script>
<title>ページタイトル</title>
<?php include( "parts/header.php" ); ?>
<body>
    <div class="container">
        <div class="col-xs-8">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        メディア投稿
                    </div>
                </div>
                <form method="POST" action="output_upload.php"  enctype="multipart/form-data">
                    <div class="panel-body">
                       
                            <div class="row">
                                <div class="col-sm-2">タイトル</div>
                                <div class="col-sm-10 form-inline" style="padding: 3px;">
                                    <input type="text" class="form-control input-sm" name="title" placeholder="タイトル" size="50" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">タグ</div>
                                <div class="col-sm- form-inline" style="padding:5px;">
                                    <select name = "tag"class="form-control input-sm" id="pref">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++):    //5個設定できるタグの数だけ繰り返し
                                            $Name = ${"tagN" . $i}['tag_name'];
                                            $TAG = ${"tagN" . $i}['id'];
                                            if ($Name != ""):      //タグが入っているかの判定
                                                ?><option value="<?php echo $TAG ?>"><?php echo $Name ?></option>
                                                <?php
                                            endif;//タグが入っているどうかの判定の閉じ
                                        endfor; //5回繰り返し処理終わり
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">テキスト</div>
                                <div class="col-sm-4" style="padding: 3px;">
                                    <textarea class="form-control  input-sm" rows="3" name="text" placeholder="テキスト" required></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-2">ファイル</div>
                                <div class="col-sm-4" style="padding: 10px;">
                                    <input type="file" class="form-control input-sm" name="fname">
                                </div>
                            </div>

                            <div class="text-center" style="padding: 30px;">
                                <button type="submit" class="btn btn-success" name = "output">送信</button>
                            </div>
                            
                        <!--メディアうｐ-->
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="validator.min.js"></script>
</body>
</html>