<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");
//indexから遷移して来た
if (isset($_POST["user_id"])) {
    $_SESSION['user_idc'] = $_POST["user_id"];
}
//SESSIONを変数に
$user_id = $_SESSION['user_idc'];


//タグを変更した時の処理
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
    $posttag = $_POST['tag'];
    $tagnum = count($posttag);
    $i = 0;
    for ($k = 0; $k < 5; $k++) {
        $sqltag[$k] = "a";
    }
    foreach ($posttag as $value) {
        if ($i < $tagnum) {
            $sqltag[$i] = $value;
        }
        $i++;
    }
    NGO("update users set tag1 = '$sqltag[0]',tag2 = '$sqltag[1]',tag3 = '$sqltag[2]',tag4 = '$sqltag[3]',tag5 = '$sqltag[4]'"
            . "where user_id = '$user_id '");
}

//タグの情報取り出し
$SqlRes = NGO("select * from tag");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $TagAry[] = $Row;
}
if (isset($TagAry)) {
    $NumTag = count($TagAry);
}

//個人情報取り出すところ
$SqlRes = NGO("SELECT * FROM users where user_id = '$user_id'");
$Row = $SqlRes->fetch(PDO::FETCH_ASSOC);

//個人情報のタグだけを取り出すfor文
for ($k = 1; $k < 6; $k++) {
    ${"tag" . $k} = $Row['tag' . $k];
}

//自分の持っているタグの番号と一致するタグを取り出す。後で取り出した配列の名前と比較する
for ($i = 1; $i <= 5; $i++) {
    ${"tagN" . $i} = NGOpro(${"tag" . $i});
}

//テキストとか画像を変える
if (isset($_POST["name_c"]) && $_POST['delete'] == "変更") {
    $savedir = ".././img/";    //  master 
$upfname = $_FILES["upfname"]["name"];
$newfilename = $_POST["filename"];
    $fileext = substr($upfname, strrpos($upfname, "."));
if ($fileext != "") {
    $savefilename = $newfilename . $fileext;
    move_uploaded_file($_FILES["upfname"]["tmp_name"], $savedir . $savefilename);
    NGO("update users set
    user_img = '" . $savefilename . "' where user_id = '" . $user_id . "'");
}
    
    NGO("update users set
username= '" . $_POST["name_c"] . "' ,hitokoto= '" . $_POST["text_c"] . "'  where user_id = '" . $user_id . "'");
    header('Location: index.php');
}
//プロフィール画像をデフォルトにする
if (isset($_POST['reset'])){
    NGO("update users set
    user_img = 'default.png' where user_id = '" . $user_id . "'");
    
}
//ユーザー削除処理
if (isset($_POST['delete']) && $_POST['delete'] == "削除") {
    delete('users',$user_id);
    delete('tweet_tbl',$user_id);
    delete('chat_tbl',$user_id);
    delete('follows',$user_id);
    NGO("DELETE FROM `room_tbl` WHERE edituser_id = '$user_id'");
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="ja">

    <?php include("parts/head.php"); ?>
    <?php include("../parts/css.php"); ?>
    <body>

        <!-- Navigation -->
        <?php include("parts/header.php"); ?>

        <!-- Page Content -->
        <div class="container">

            <!-- Introduction Row -->
            <h1 class="my-4">神
                <small>管理者は神</small>
            </h1>
            <p>管理者は神なんですよ</p>
            <!-- Team Members Row -->
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="my-4">会員情報変更</h2>
                </div>
                <div class="col-lg-6 col-sm-6 text-center mb-4">
                    <form method="post" action="user_change.php"enctype="multipart/form-data">
                        <!-- ユーザー名入力エリア -->
                        <div class="form-group">
                            <label>ユーザー名変更</label>
                            <input type="text" class="form-control" name="name_c" value="<?php print $Row["username"]; ?>">
                        </div>
                        <!-- テキストエリア -->
                        <div class="form-group">
                            <label>テキスト変更</label>
                            <textarea class="form-control" rows="3" name="text_c"><?php print $Row["hitokoto"]; ?></textarea>
                        </div>
                        <!-- ファイルアップロード -->
                        <div class="form-group">
                            <img class="rounded-circle img-fluid d-block mx-auto" src="../img/<?php print $Row["user_img"]; ?>"
                                 width="100" alt="">

                            <label>プロフィール画像変更</label>
                            <input type="file" name="upfname">
                            <input type="hidden" name="filename" value="<?php print $Row["username"]; ?>" />
                        </div>
                        <?php
                        for ($i = 1; $i <= 5; $i++) {     //5個設定できるタグの数だけ繰り返し
                            $Name = ${"tagN" . $i}['tag_name'];
                            if ($Name != "") {      //タグが入っているかの判定
                                ?>
                                <a class="btn btn-warning btn-sm" href="#" role="button">
                                    <?php echo $Name; ?>
                                </a>
                                <?php
                            }//タグが入っているどうかの判定の閉じ
                        }//5回繰り返し処理終わり
                        ?>
                        <br/><br/>
                        <!-- 送信ボタン -->
                        <input type="submit" name = "delete"class="btn btn-danger" value="変更">　<input type="submit" name = "delete"class="btn btn-danger" value="削除">
                    </form><br/>
                    <form method="post" action="user_change.php"enctype="multipart/form-data">
                         <input type="submit" name = "reset"class="btn btn-danger" value="プロフィール画像をデフォルトに">
                    </form>
                </div>
                <!-- ここから右側のタグ -->
                <div class="col-lg-6 col-sm-6 text-center mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" COLSPAN="4">ALL TAG</th>
                            </tr>
                        </thead>
                        <form method="POST" action="user_change.php">
                            <tbody>
                                <?php for ($i = 0; $i < $NumTag; $i = $i + 4) { ?>
                                    <tr>
                                        <?php
                                        $z = 0;
                                        while ($z < 4 && ($i + $z < $NumTag)) {
                                            ?>
                                            <td class="text-left">
                                                <div class="pretty p-icon p-round p-pulse">
                                                    <input type="checkbox" name="tag[]"
                                                           value="<?php echo $TagAry[$i + $z]['id'] ?>"/>
                                                    <div class="state p-success">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label><?php echo $TagAry[$i + $z]['tag_name'] ?></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php
                                            $z++;
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                    </table>
                    <div class="button_wrapper">
                        <input type="submit" class="btn btn-danger" value="タグ変更">
                        </form></div>
                </div>
            </div>
            <br/>


        </div>
        <!-- /.container -->

        <!-- Footer -->
        <?php include("parts/footer.php"); ?>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
