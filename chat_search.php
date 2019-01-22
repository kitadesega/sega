<?php
header('Content-Type:text/html; charset=UTF-8');
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

require_once 'parts/function.php';

//自分のIDを入れる
$user = $_SESSION['user'];

// ユーザーIDからユーザー名を取り出す
$result = NGO('SELECT * FROM users WHERE user_id='.$user.'');
// ユーザー情報の取り出し
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    for ($i = 1; $i <= 5; ++$i) {
        ${'tag'.$i} = $row['tag'.$i];
    }
}

//自分の持っているタグを取り出す
for ($i = 1; $i <= 5; ++$i) {
    ${'tagN'.$i} = NGOpro(${'tag'.$i});
}
if (!isset($NumRows)) {
    $NumRows = 0;
}

//入力した　キーワード　での検索
if (isset($_POST['Keysearch'])) {
    $key = $_POST['Keysearch'];
    $SqlRes = NGO("select * from room_tbl where roomname LIKE '%$key%';");
    while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $tagN = $Row['tag_id'];
        $SqlRes2 = NGO("select * from tag where id = $tagN");
        $TagAry = $SqlRes2->fetch(PDO::FETCH_ASSOC);
        $tagName[] = $TagAry['tag_name'];

        $RowAry[] = $Row;
    }
    if (isset($RowAry)) {
        $NumRows = count($RowAry);
    }

    if (isset($RowAry)) {
        for ($i = 0; $i < $NumRows; ++$i) {
            $SEGA = ($RowAry[$i]['roomid']);

            $SqlRes = NGO("select count( roomid ) from user_tbl where roomid = $SEGA;");

            $Row = $SqlRes->fetch(PDO::FETCH_ASSOC);

            $SqlRes2 = NGO("select * from room_tbl where roomid = $SEGA;");

            $Row2 = $SqlRes2->fetch(PDO::FETCH_ASSOC);
        }
    }
}

//ルームの　タグ　での検索処理
if (isset($_POST['TAGsearch'])) {
    $TAGsearch = $_POST['TAGsearch'];
    $SqlRes = NGO("select * from room_tbl where tag_id = '$TAGsearch'");
    while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $tagN = $Row['tag_id'];
        $SqlRes2 = NGO("select * from tag where id = $tagN");
        $TagAry = $SqlRes2->fetch(PDO::FETCH_ASSOC);
        $tagName[] = $TagAry['tag_name'];

        $RowAry[] = $Row;
    }
    if (isset($RowAry)) {
        $NumRows = count($RowAry);
    }

    if (isset($RowAry)) {
        for ($i = 0; $i < $NumRows; ++$i) {
            $SEGA = ($RowAry[$i]['roomid']);

            $SqlRes = NGO("select count( roomid ) from user_tbl where roomid = $SEGA;");

            $Row = $SqlRes->fetch(PDO::FETCH_ASSOC);

            $SqlRes2 = NGO("select * from room_tbl where roomid = $SEGA;");

            $Row2 = $SqlRes2->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>
<?php include 'parts/header.php'; ?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">

                    </div>
                    <div class="panel-body">
                        <div class ="col-xs-12">
                            <label>キーワード検索</label>
                            <form style="display: inline" method="post" action="chat_search.php">
                            <div class="input-group" style ="width:70%;">
                                <input type="text" class="form-control" name = "Keysearch">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">検索</button>
                                </span>
                            </div>
                            </form>
                            <br/><div class="input-group text-center " style = "width:70%;">
                                <label>タグで検索</label>
                            <form style="display: inline" method="post" action="chat_search.php">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">検索</button>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-inline">

                                    <?php
                                    for ($i = 1; $i <= 5; ++$i):    //5個設定できるタグの数だけ繰り返し
                                        $Name = ${'tagN'.$i}['tag_name'];
                                        $TAG = ${'tagN'.$i}['id'];
                                        if ($Name != ''):      //タグが入っているかの判定
                                    ?>
                                            <div class="pretty p-icon p-round p-pulse" style="margin-top:10px;">
                                                <input type="radio" name="TAGsearch"  value="<?php echo $TAG; ?>" />
                                                <div class="state p-success">
                                                    <i class="icon mdi mdi-check"></i>
                                                    <label><a class = "a"><?php echo $Name; ?></a></label>
                                                </div>
                                                <?php
                                        endif; //タグが入っているどうかの判定の閉じ
                                                ?></div><?php
                                    endfor; //5回繰り返し処理終わり
                                                        ?>
                                </div>
                            </form><br/><br/>
                            </div>
                        </div>
                    </div>
                </div>

                <?php for ($i = 0; $i < $NumRows; ++$i) {
                ?>
                    <?php if (ua_smt() == true) {
                    ?>
                        <div class ="col-xs-12">
                            <div class="panel panel-default kadomaru">
                                <div class="panel-heading uekadomaru">
                                    <h3 class="panel-title text-center"><?php echo $RowAry[$i]['roomname']; ?></h3>
                                    <h3 class="panel-title text-center" style = "font-size:16px"><span style="color:red;"><?= $Row['count( roomid )']; ?></span>/
                                    <?php if ($Row2['limit'] == $Row['count( roomid )']) {
                                    ?>
                                            <span style="color:green;"><?php
                                          } else {
                                                                       ?><span style="color:red;"><?php
                                          } ?> <?= $Row2['limit']; ?></span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <form style="display: inline" method="post" action="chatin.php">
                                        <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>">

                                        <input type="submit" class="btn btn-info" value="入室" >
                                    </form>
                                    <?php if ($RowAry[$i]['edituser'] == $_SESSION['userN']) {
                                    ?>
                                        <form style="display: inline" method="post" action="chat_edit.php">
                                            <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>">
                                            <input type="submit" class="btn btn-danger" value="削除">
                                        </form>
                                    <?php
                                          } ?>   
                                    <a class="btn btn-warning btn-sm iphone5" style = "border-radius: 60px;"href="" role="button">
                                        <?php echo $tagName[$i]; ?>
                                    </a>
                                </div>
                                <div class ="panel-body">

                                    <a style = "font-size:12px;"><?php echo $RowAry[$i]['text']; ?></a>
                                </div>
                            </div>
                        </div>
                    <?php
                          } else {
                    ?>
                        <div class ="col-xs-3">
                            <div class="panel panel-default kadomaru">
                                <div class="panel-heading uekadomaru">
                                    <h3 class="panel-title text-center"><?php echo $RowAry[$i]['roomname']; ?></h3>
                                    <h3 class="panel-title text-center" style = "font-size:16px"><span style="color:red;"><?= $Row['count( roomid )']; ?></span>/
                                    <?php if ($Row2['limit'] == $Row['count( roomid )']) {
                                    ?>
                                            <span style="color:green;"><?php
                                          } else {
                                                                       ?><span style="color:red;"><?php
                                          } ?> <?= $Row2['limit']; ?></span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <form style="display: inline" method="post" action="chatin.php">
                                        <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>">

                                        <input type="submit" class="btn btn-default" value="入室" >
                                    </form>
                                    <?php if ($RowAry[$i]['edituser'] == $_SESSION['userN']) {
                                    ?>
                                        <form style="display: inline" method="post" action="chat_edit.php">
                                            <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>">
                                            <input type="submit" class="btn btn-danger" value="削除">
                                        </form>
                                    <?php
                                          } ?>   
                                    <a class="btn btn-warning btn-sm iphone5" style = "border-radius: 60px;"href="" role="button">
                                        <?php echo $tagName[$i]; ?>
                                    </a>
                                </div>
                                <div class ="panel-body">

                                    <a style = "font-size:12px;"><?php echo $RowAry[$i]['text']; ?></a>
                                </div>
                            </div>
                        </div>
                    <?php
                          }
                      } ?>

            </div>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>