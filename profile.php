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
    $username = $row['username'];
    $email = $row['email'];
    $imgurl = $row['user_img'];
    $hitokoto = $row['hitokoto'];
    $githubURL = $row['URL'];
    $_SESSION['userN'] = $username;
    $_SESSION['userNimg'] = $imgurl;
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
    $handle = $_SESSION['userN'];
    $img = $_SESSION['userNimg'];
    $contents = $_POST['contents'];
    NGO("insert into tweet_tbl values
( null,'$user',null,'$handle','$img','$contents',null )");
    header('Location: home.php');
}

//返信の処理
if (isset($_POST['contents2'])) {
    $handle = $_SESSION['userN'];
    $img = $_SESSION['userNimg'];
    $contents2 = $_POST['contents2'];
    $Replyid = $_POST['Replyid'];
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

//宛先無しの投稿を全て取り出す
$SqlRes = NGO('select * from tweet_tbl where target_id IS NULL order by dataTime desc ;');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $ALLtweet[] = $Row;
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
    $ECHOfollowCNT = 0;
    $followCNT = 1;
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
//画像削除処理
if (isset($_POST['deleteimg'])) {
    $imgid = $_POST['deleteimg'];
    NGO("DELETE FROM `output` WHERE id = '$imgid'");
    NGO("DELETE FROM `favorite` WHERE favorite_id = '$imgid'");
}

//自分のライブラリ画像を取り出す
$OMG = NGO("select * from output where user_id = '$user'");
while ($myimage = $OMG->fetch(PDO::FETCH_ASSOC)) {
    $imgAry[] = $myimage;
    $tagid = $myimage['tag_id'];
    $sqls = NGO("select * from tag where id = '$tagid'");
    $sqR = $sqls->fetch(PDO::FETCH_ASSOC);
    $tags[] = $sqR;
}
if (isset($imgAry)) {
    $imgCNT = count($imgAry);
} else {
    $imgCNT = 0;
}

//お気に入り処理
if (isset($_POST['favoID'])) {
    $favoID = $_POST['favoID'];
    NGO("INSERT INTO `favorite`(`id`, `user_id`, `favorite_id`) VALUES (null,'$user','$favoID')");
    header('Location: profile.php#tabtest4');
}

//お気に入り解除
if (isset($_POST['anfavoID'])) {
    $anfavoID = $_POST['anfavoID'];
    NGO("DELETE FROM `favorite` WHERE user_id = '$user' AND favorite_id = '$anfavoID'");
    header('Location: profile.php#tabtest4');
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
        if ($Favocheck[$i] == $output['id']){
            $Favolist[] = $output;
        }
    endfor;
}
?>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
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
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #fdfdfe;
    cursor: default;
    background-color: #7ab800;
    border: 1px solid #71c6c1;
    border-bottom-color: transparent;
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

<body>
<?php if (ua_smt() == true) {
//background-color: #fafafa ?>
<style>
body {
	background-color:#fff;
	color: #333333;
}
</style>
<div class = "myprofile-row">
    <div id="" class="myprofile-container">
        <p class="myprofile-head">プロフィール</p> 
        <div class="myprofile-left">
            <div class = "myprofile-image">
                    <img src="img/<?php echo $imgurl; ?>" />
            </div>
            <div class="follow-innfo-container">
                <div class="follow-info">
                    <a href="followlist.php">フォロー
                    <p><?php echo $ECHOfollowCNT; ?></p>
                    </a>
                </div>
                <div class="follow-info">
                    <a href="followerlist.php">フォロワー
                    <p><?php echo $followerCNT; ?></p>
                    </a>
                </div>
            </div>
        </div>
        
        <div class = "myprofile-right">
            <div class = "myprofile-info">
                <a>ユーザネーム</a>
                <p><?php echo $username; ?></p>
            </div>
            <div class = "p_update">
            <a href="home_change.php">
                <button type="button" class="btn btn-sm btn-info btn-round">変更</button>
            </a>
        </div>
        <div class="clear"></div>

            <div class = "myprofile-info" style="word-wrap: break-word;">
                <a>github URL</a>
                <p><a href="<?php echo $githubURL; ?>"><?php echo $githubURL; ?></a></p>
            </div>

            <div class = "myprofile-info">
                <a>紹介文</a>
                <p><?php echo nl2br($hitokoto); ?></p>
            </div>
            <div class = "myprofile-info">
                <a>タグ</a>
            <p>
                <?php
                    for ($i = 1; $i <= 5; ++$i):    //5個設定できるタグの数だけ繰り返し
                        $Name = ${'tagN'.$i}['tag_name'];
                        $TAG = ${'tagN'.$i}['id'];
                        if ($Name != ''):      //タグが入っているかの判定
                    ?>
                    <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px; margin-bottom:5px;" href="user_search.php?kensaku=<?php echo $TAG; ?>" role="button">
                        <?php echo $Name; ?>
                    </a>
                    <?php
                        endif; //タグが入っているどうかの判定の閉じ
                    endfor; //5回繰り返し処理終わり
                    ?>
            </p>
        </div>
    </div>      
    <div class="clear"></div>
</div>
      
<?php }else{ ?>
    <div class="container">
        <div class="row">
            <!-- 残り8列はコンテンツ表示部分として使う -->
            <div class="">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="panel-title">名前：
                            <?php echo $username; ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="dnweb-tabtest">
                            <!--プロフィール表示-->
                            <a class="disabled"><img src="img/<?php echo $imgurl; ?>" class="img-circle" width="100" height="100" /></a>
                            <a href="home_change.php"><button type="button" class="btn btn-primary" style="border-radius: 15px;">変更</button></a><br />
                            <a href="#tabtest5" data-toggle="tab" style="font-size:16px;">フォロワー
                                <?php echo $followerCNT; ?></a><a href="#tabtest2" data-toggle="tab" style="font-size:14px;">/フォロー
                                <?php echo $ECHOfollowCNT; ?></a>
                            <br />
                            <br />
                            <?php
                            for ($i = 1; $i <= 5; ++$i):    //5個設定できるタグの数だけ繰り返し
                                $Name = ${'tagN'.$i}['tag_name'];
                                $TAG = ${'tagN'.$i}['id'];
                                if ($Name != ''):      //タグが入っているかの判定
                            ?>
                            <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php?kensaku=<?php echo $TAG; ?>" role="button">
                                <?php echo $Name; ?>
                            </a>
                            <?php
                                endif; //タグが入っているどうかの判定の閉じ
                            endfor; //5回繰り返し処理終わり
                            ?>
                            <br /><br /><a class="disabled">自己紹介：
                                <?php echo nl2br($hitokoto); ?></a>
                        </div>
                        <!--プロフィール表示ここまで-->
                    </div>
                    <?php } ?>
                    <!-- タブの切替部分 -->
                    <div id="bottom">
                        <ul class="nav nav-tabs panel" style="">
                            <li class="active"><a href="#tabtest1" data-toggle="tab">タイムライン</a></li>
                            <li><a href="#tabtest4" data-toggle="tab">ライブラリ</a></li>
                            <li><a href="#tabtest3" data-toggle="tab">お気に入り</a></li>
                        </ul>
                    </div>
                    <!-- タブのコンテンツ部分 -->
                    <div class="tab-content">
                        <!---------- タイムラインの表示-------->
                        <div class="tab-pane active" id="tabtest1">
                            <!-- <table align="center" cellpadding="3" cellspacing="0">
                        <tbody>
                        <tr>
                        <td> 横幅めいっぱい使いたいのでコメント化一応消さずに残しておく。-->
                            <!-- ▼twitter風ここから -->
                            <div class="twitter__container">
                                <!-- タイトル
                                <div class="twitter__title">
                                </div>-->
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
                                                <form method="POST" action="home.php">
                                                    <textarea name="contents" autofocus rows="8" cols="40"></textarea><br>
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
                                    <table align="center" cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <!-- 記事エリア -->
                                            <div class="twitter__block">
                                                <figure>
                                                    <img src="img/<?php echo $tweet['img']; ?>" class="img-circle" width="30" />
                                                </figure>
                                                <div class="twitter__block-text">
                                                    <div class="name">
                                                        <?php echo $tweet['handle']; ?><span class="name_reply"></span></div>
                                                    <div class="date">10分前</div>
                                                    <div class="text">
                                                        <?php echo $tweet['message']; ?>
                                                    </div>
                                                    <div class="twitter__icon">
                                                        <!--返信ゾーン-------------------------------------------------------------->
                                                        <button class="btn btn-default" data-toggle="modal" data-target="#modal-sample<?php echo $tweet['id']; ?>">
                                                            <span class="twitter-bubble"></span></button>
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
                                            <td align="center">
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
                                                                <form method="POST" action="home.php">
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
                                                    if ($tweet['id'] == $Reply['target_id']):  //返信先のIDと、送り先の無い投稿のIDが一致した場合
                                    ?>
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
                            <!-- </td>
                            </tr>
                            </tbody>
                            </table>横幅いっぱいまで表示したかったのでコメント化したよ、一応残しておく -->
                            <!-- panel-body　閉じ -->

                        </div>
                        <!--"tab-pane active" id="tabtest1" 閉じ -->
                        <!------ フォローリストの表示---->
                        <?php include 'home_tab2.php'; ?>
                        <!------ フォロワーリストの表---->
                        <?php include 'home_tab3.php'; ?>
                        <div class="tab-pane" id="tabtest4">
                            <div class="panel panel-info">
                                <div class="panel-body">

                                    <form action="output.php" method="post">

                                        <input type="submit" class="btn btn-success" value="アップロードページへ">
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table">

                                            <tbody>

                                                <?php for ($i = 0; $i < $imgCNT; ++$i): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $imgAry[$i]['title']; ?>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php?kensaku=<?php echo $TAG; ?>" role="button">
                                                            <?php 
                                                          //タグ番号を文字に変更
                                                          $tagchain = $imgAry[$i]['tag_id'];
                                                          $SqlRes = NGO("select * from tag where id = $tagchain");
                                                          $row = $SqlRes->fetch(PDO::FETCH_ASSOC);
                                                          echo $row['tag_name'];
                                                            ?></a></td>
                                                    <td><img src="img/<?php echo $imgAry[$i]['media']; ?>" width="100" /></td>
                                                    <td>
                                                        <p>
                                                            <?php echo $imgAry[$i]['text']; ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="profile.php#tabtest4" onsubmit="return submitChk()">
                                                            <input type="hidden" name="deleteimg" class="btn btn-danger" value="<?php echo $imgAry[$i]['id']; ?>">
                                                            <input type="submit" name="delete" class="btn btn-danger" value="削除">
                                                        </form>
                                                    </td>
                                                    <td>

                                                        <?php
                                                          $flag = false;
                                                          if (isset($FavocheckCNT)) {
                                                              for ($j = 0; $j < $FavocheckCNT; ++$j):
                                                                  if ($Favocheck[$j] == $imgAry[$i]['id']) {
                                                                      $flag = true;
                                                                  }
                                                                  if ($flag == true) {
                                                                      break;
                                                                  }
                                                              endfor;
                                                          }

                                                          if ($flag == true) {
                                                        ?>
                                                        <form method="post" action="home.php">
                                                            <input type="hidden" name="anfavoID" value="<?php echo $imgAry[$i]['id']; ?>">
                                                            <button class="none" type="submit">
                                                                <img src="svg/twitter-heartON.svg" width="32" height="32">
                                                            </button>
                                                        </form>

                                                        <?php
                                                          } else {
                                                        ?>
                                                        <form method="post" action="home.php">
                                                            <input type="hidden" name="favoID" value="<?php echo $imgAry[$i]['id']; ?>">
                                                            <button class="none" type="submit">
                                                                <img src="svg/twitter-heart.svg" width="32" height="32">
                                                            </button>
                                                        </form>
                                                        <?php
                                                          } ?>
                                                    </td>
                                                </tr>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- tab-contentの閉じ -->
                    </div><!-- dnweb-tabtestの閉じ -->
                </div><!-- panel panel-warningの閉じ -->
            </div><!-- col-xs-12の閉じ -->
        </div><!-- rowの閉じ -->
    </div><!-- containerの閉じ-->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>