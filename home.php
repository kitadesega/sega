<?php
ini_set('display_errors', 1);
header('Content-Type:text/html; charset=UTF-8N');
session_start();
include 'parts/function.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}
//ログアウト処理
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header('Location: index.php');
}

//自分のIDを入れる
$user = $_SESSION['user'];

// ユーザーIDからユーザー名を取り出す
$result = NGO('SELECT * FROM users WHERE user_id='.$user.'');

// ユーザー情報の取り出し
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $username   = $row['username'];
    $email      = $row['email'];
    $imgurl     = $row['user_img'];
    $hitokoto   = $row['hitokoto'];
    $_SESSION['userN']      = $username;
    $_SESSION['userNimg']   = $imgurl;
    for ($i = 1; $i <= 5; ++$i) {
        ${'tag'.$i} = $row['tag'.$i];
    }
}

//自分の持っているタグを取り出す
for ($i = 1; $i <= 5; ++$i) {
    ${'tagN'.$i} = NGOpro(${'tag'.$i});
}

//投稿の処理
if (isset($_POST['contents'])) {
    $handle     = $_SESSION['userN'];
    $img        = $_SESSION['userNimg'];
    $contents   = $_POST['contents'];
    $contents   = addslashes($contents);
    NGO("insert into tweet_tbl values
( null,'$user',null,'$handle','$img','$contents',null )");
    header('Location: home.php');
}

//返信の処理
if (isset($_POST['contents2'])) {
    $handle     = $_SESSION['userN'];
    $img        = $_SESSION['userNimg'];
    $contents2  = $_POST['contents2'];
    $content2   = addslashes($contents2);
    $Replyid    = $_POST['Replyid'];
    NGO("insert into tweet_tbl values
( null,'$user','$Replyid','$handle','$img','$contents2',null )");
    header('Location: home.php');
}

//投稿削除のしょり
if (isset($_GET['Auser'])) {
    $deltl = $_GET['Auser'];
    NGO("delete from tweet_tbl where id = '$deltl'");
    header('Location: home.php');
}

//お気に入り取得
$SqlRes = NGO("select * from favorite where user_id = '$user'");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
$My_favo[]=$Row;
}

//タグのデータを摘出
$SqlRes = NGO('select * from tag where Decision = 0');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $all_tag[] = $Row;
}
//宛先無しの投稿を全て取り出す
$SqlRes = NGO('select * from tweet_tbl where target_id IS NULL order by dataTime desc ;');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $SqlRes2        = NGO("select * from users where user_id = " . $Row['user_id'] . "");
    $tmp_u_data     = $SqlRes2->fetch(PDO::FETCH_ASSOC);
    $Row["handle"]  = $tmp_u_data["username"];
    $Row["img"]     = $tmp_u_data["user_img"];
    //お気に入り判定
    $Row["favo_flg"] = false;
    for($i=0;$i<count($My_favo); $i++){
        if($Row["id"] == $My_favo[$i]["favorite_id"]){
            $Row["favo_flg"] = true;
            break;
        }
    }
    if($Row["tag_id"]!="" || $Row["tag_id"]!=NULL){
        $tmp_tag        = $Row["tag_id"];
        $keyIndex       = array_search("$tmp_tag", array_column($all_tag, 'id'));
        $Row['tag_name'] = $all_tag[$keyIndex]['tag_name'];
    }else{
        $Row['tag_name'] = NULL;
    }
    //タグの名前
    $ALLtweet[] = $Row;
}
foreach($ALLtweet as $tweet){
    if($tweet['favo_flg']==true){
        $Favotweet[] = $tweet;
    }
}
if (isset($ALLtweet)) {
    //投稿の数をカウント
    $ALLtweetCNT = count($ALLtweet);
} else {
    $ALLtweetCNT = 0;
}

//フォローしている人のユーザー情報を取り出す
$SqlRes = NGO("select users.user_id,user_img,username,hitokoto from
users join follows on users.user_id=follows.follow_id where follows.user_id = '$user'");

// ユーザー情報の取り出し
$followCNT = '0';
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    //フォローしている人のユーザー情報を配列に入れる
    $followAry[] = $row;
}
//フォローしている人数のカウント
if (isset($followAry)) {
    $followCNT = count($followAry);
    //フォローしている人数の表示用
    $ECHOfollowCNT = count($followAry);
} else {
    $ECHOfollowCNT  = 0;
    $followCNT      = 1;
}

//投稿のIDとフォローしている人、又は自分とIDが一致していれば$tweetAry配列に加える
for ($i = 0; $i < $ALLtweetCNT; ++$i) {
    for ($j = 0; $j < $followCNT; ++$j):
        if (isset($followAry)) {
            if ($ALLtweet[$i]['user_id'] == $followAry[$j]['user_id'] || $ALLtweet[$i]['user_id'] == $user) {
                $tweetAry[] = $ALLtweet[$i];
                break;
            }
        } else {
            if ($ALLtweet[$i]['user_id'] == $user) {
                $tweetAry[] = $ALLtweet[$i];
                break;
            }
        }
    endfor;
}

//誰かに対して投稿した投稿を取り出す
$SqlRes = NGO('select * from tweet_tbl where target_id IS NOT NULL;');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $SqlRes2 = NGO("select * from users where user_id = " . $Row['user_id'] . "");
    $tmp_u_data = $SqlRes2->fetch(PDO::FETCH_ASSOC);
    $Row["handle"] = $tmp_u_data["username"];
    $Row["img"]= $tmp_u_data["user_img"];
    $ReplyAry[] = $Row;
}
if (isset($ReplyAry)) {
    //誰かに対して投稿した投稿のカウント
    $ReplyCNT = count($ReplyAry);
}

//フォロワーの情報の取り出し　自分以外のフォロワー表示を作るとき$userを変えるだけでOK
$SqlRes2 = NGO("select users.user_id,user_img,username,hitokoto from
users join follows on users.user_id=follows.user_id where follows.follow_id = '$user'");
while ($row2 = $SqlRes2->fetch(PDO::FETCH_ASSOC)) {
    $followerAry[] = $row2;
    //自分をフォローしてくれてる人のユーザー情報を配列に。
}
$followerCNT = '0';
//自分をフォローしてくれている人の数をカウント
if (isset($followerAry)) {
    $followerCNT = count($followerAry);
}


//お気に入り処理
if (isset($_POST['favoID'])) {
    $favoID = $_POST['favoID'];
    NGO("INSERT INTO `favorite`(`id`, `user_id`, `favorite_id`) VALUES (null,'$user','$favoID')");
    header('Location: home.php#tabtest4');
}

//お気に入り解除
if (isset($_POST['anfavoID'])) {
    $anfavoID = $_POST['anfavoID'];
    NGO("DELETE FROM `favorite` WHERE user_id = '$user' AND favorite_id = '$anfavoID'");
    header('Location: home.php#tabtest4');
}

//お気に入りをしているかしていないかの判断
$SqlRes = NGO("SELECT * FROM favorite where user_id = '$user' ");
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $Favocheck[] = $row['favorite_id'];
}
if (isset($Favocheck)) {
    $FavocheckCNT = count($Favocheck);
} else {
    $FavocheckCNT = 0;
}

//全てのライブラリからお気に入りしているものを取り出す
$OMG = NGO('select * from output');
while ($output = $OMG->fetch(PDO::FETCH_ASSOC)) {
    for ($i = 0; $i < $FavocheckCNT; ++$i):
        if ($Favocheck[$i] == $output['id']) {
            $Favolist[] = $output;
        }

    endfor;
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            $('.nav-tabs a').removeClass('active');
        }
    });

</script>
<style>
    @media screen and (max-width: 680px)and (min-width: 330px) {
textarea {
resize: horizontal;
width:300px;
height:200px;
}
}
@media screen and (max-width: 330px) {
textarea {
resize: horizontal;
width:250px;
height:170px;
}
}

.panel {
margin-bottom: 10px;
background-color: #fff;
padding-bottom: 15px;
border-radius: 2px;
-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
box-shadow: 0 1px 1px rgba(0,0,0,.05);
}

</style>
<?php include 'parts/header.php'; ?>
<style>
body {
	background-color:#ffff;
	color: #333333;
}
</style>
<body>
<div class="container">
<!-- ▼twitter風ここから -->
<div class="twitter__container">
    <!-- 1.モーダルを表示する為のボタン -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-sample">
        ツイート
    </button>
    <!-- 2.モーダルの配置 -->
    <div class="modal" id="modal-sample" tabindex="-1">
        <div class="modal-dialog">
            <!-- 3.モーダルのコンテンツ -->
            <div class="modal-content">
                <!-- 4.モーダルのヘッダ -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modal-label">投稿</h4>
                </div>
                <!-- 5.モーダルのボディ -->
                <div class="modal-body">
                <form method="POST" action="output_upload.php"  enctype="multipart/form-data">
                        <textarea name="contents" autofocus rows="8" cols="40"></textarea>
                        <br>
                        <br/>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                    Choose File<input type="file" style="display:none"name="fname">
                                </span>
                            </label>
                            <input type="text" class="form-control" readonly="">
                        </div>
                        <br/>
                        <input type="text" id="search-text" placeholder="検索ワードを入力">
                        <div class="tag-area">
                        <ul class="target-area">
                        <?php foreach($TagAry as $tag){ ?>
                            <li style="float:left;">
                                    <div class="pretty p-icon p-round p-pulse">
                                        <input type="radio" name="tag" value="<?php echo $tag['id']; ?>">
                                        <div class="state p-success">
                                            <i class="icon mdi mdi-check"></i>
                                            <label><a style="margin-top:5px;"><?php echo $tag['tag_name']; ?></a></label>
                                        </div>
                                    </div>
                            </li>
                        <?php } ?>
                        </ul>
                        </div>
                        <div class="clear"></div>
                        <input type="submit" class="btn btn-primary" 　name="btn1" value="投稿する">
                    </form>
                </div>
            </div>
            <!-- 6.モーダルのフッタ -->
        </div>
    </div><br />
    <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
    <div class="twitter__contents scroll">
        <?php
        if (isset($tweetAry)):
            foreach ($tweetAry as $tweet):
        ?>
        <table cellpadding="3" cellspacing="0">
            <tbody>
                <!-- 記事エリア -->
                <div class="twitter__block">
                    <figure>
                        <img src="img/<?php echo $tweet['img']; ?>" class="img-circle" width="30" />
                    </figure>
                    <div class="twitter__block-text">
                        <div class="name">
                            <?php echo $tweet['handle']; ?><span class="name_reply"><?php if($tweet['tag_name']!=NULL){ ?>
                            <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php" role="button">
                                <?php echo $tweet['tag_name']; ?>
                            </a>
                        <?php }?></span></div>
                        <div class="date">10分前</div>
                        <div class="text">
                            <?php echo $tweet['message']; ?><br/>
                        <?php if($tweet['file']!="" || $tweet['file']!=NULL){ ?>
                            <a href="img/<?php echo $tweet['file']; ?>" data-lightbox="<?php echo $tweet['file']; ?>">
                            <img src="img/<?php echo $tweet['file']; ?>"width="100px">
                        </a>
                        <?php }?>


                        </div>
                        <div class="twitter__icon" style="float:left;">
                            <!--返信ゾーン-------------------------------------------------------------->
                            <button data-toggle="modal" data-target="#modal-sample<?php echo $tweet['id']; ?>">
                                <span class="twitter-bubble"></span></button>
                        </div>
                        <div class="twitter__icon item<?PHP echo $tweet['id']; ?> <?PHP echo $tweet['favo_flg'] ? "on" : "off"; ?>" style="margin-top:5px;float:left;" onclick="toggleBookmark(<?PHP echo $tweet['id']; ?>,<?PHP echo $tweet['user_id']; ?>); return false;">
                            <?PHP if($tweet['favo_flg'] == false){ ?>
                                <span class="twitter-heart" ></span>
                            <?php }else{ ?>
                                <span class="twitter-heartON"></span>
                            <?php } ?>
                        </div>
                        
                        <div align="right">
                            <span class="com_foot"> ...
                                <?php echo $tweet['dataTime']; ?></span>
                            <?php if ($tweet['user_id'] == $user): //投稿が自分のかどうか?>
                            [<a href="home.php?Auser=<?php echo $tweet['id']; ?>">削除</a>]
                            <?php endif; //投稿が自分のかどうか判定終わり?>
                        </div>
                    </div>
                </div>
                <td>
                    <!-- 2.モーダルの配置 -->
                    <div class="modal" id="modal-sample<?php echo $tweet['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <!-- 3.モーダルのコンテンツ -->
                            <div class="modal-content">
                                <!-- 4.モーダルのヘッダ -->
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="modal-label">返信</h4>
                                </div>
                                <!-- 5.モーダルのボディ -->
                                <div class="modal-body">
                                    <form method="POST" action="profile.php">
                                        <textarea class="span4" name="contents2" autofocus rows="5" cols="40"></textarea><br>
                                        <input type="hidden" name="Replyid" value="<?php echo $tweet['id']; ?>">
                                        <br />
                                        <input type="submit" class="btn btn-primary" 　name="btn1" value="返信する">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 返信ゾーン終了 -->
                </td>
                </tr>
            </tbody>
        </table>
        <!-- <hr> 返信があれば表示する -->
        <?php
                if (isset($ReplyCNT)): //返信が存在しているか
                    foreach ($ReplyAry as $Reply):
                        if ($tweet['id'] == $Reply['target_id']):  ?>
                    <table class=" table-responsive" align="center" cellpadding="3" cellspacing="0">
                        <tbody>
                            <div class="twitter__block" style="width:90%;margin-left:10%;">
                                <figure>
                                    <img src="img/<?php echo $Reply['img']; ?>" class="img-circle" width="30" />
                                </figure>
                                <div class="twitter__block-text">
                                    <div class="name">
                                        <?php echo $Reply['handle']; ?><span class="name_reply"></span></div>
                                    <div class="date">10分前</div>
                                    <div class="text">
                                        <?php echo $Reply['message']; ?>
                                    </div>
                                    <div class="twitter__icon">

                                    </div>
                                    <div align="right">
                                        <span class="com_foot"> ...
                                            <?php echo $Reply['dataTime']; ?></span>
                                        <?php if ($Reply['user_id'] == $user) { //返信投稿が自分のかどうか判定?>
                                        [<a href="home.php?Auser=<?php echo $Reply['id']; ?>">削除</a>]
                                        <?php
                                            } //返信投稿が自分のか判定終わり?>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                    <?php
                        endif; // if ($tweet['id'] == $Reply['target_id']):
                    endforeach; //foreach ($ReplyAry as $Reply):
                endif; // if (isset($ReplyCNT)):
            endforeach; // foreach ($tweetAry as $tweet):
        endif; //if (isset($tweetAry)):
        ?>
    </div>
    <!--　▲タイムラインエリア ここまで -->
    </div>
    <!--　▲twitter風ここまで -->
</div><!-- container の閉じ　-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>
