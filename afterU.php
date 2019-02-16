<?php
header( 'Content-Type: text/html; charset=UTF-8' );
session_start();
include( "parts/function.php" );

//フォロー処理
if (isset( $_POST["user"])) {

    $my = $_SESSION['user'];
    $user = $_POST["user"];

	NGO( "INSERT INTO follows ( user_id,follow_id ) VALUES ( '$my','$user' ) ;" );
	NGO( "INSERT INTO notice ( user_id,target_id ) VALUES ( '$my','$user' ) ;" );

    header( "Location: afterU.php" );
}

//フォロー解除処理
if (isset( $_POST["anuser"])) {
    $my = $_SESSION['user'];
    $user = $_POST["anuser"];

    NGO( "DELETE FROM `follows` WHERE user_id = '$my' AND follow_id = '$user';" );

    header( "Location: afterU.php" );
}

$Auser = "";
if ( isset( $_POST["Auser"] ) ) {
    $Auser = $_POST["Auser"];
    $_SESSION["Auser"] = $_POST["Auser"];
}

$my = $_SESSION['user'];

// 自分以外のユーザーの情報呼出し
if ( isset( $_GET["Fuser"] ) ) {
$_SESSION["Auser"] = $_GET["Fuser"];
	if($_GET["Fuser"] == $my){
		header( "Location:profile.php" );
	}
}


if ( isset( $_POST["Fuser"] ) ) {
    $_SESSION["Auser"] = $_POST["Fuser"];
    }
	$user = $_SESSION["Auser"];
// ユーザーIDからユーザー名を取り出す
$result = NGO( "SELECT * FROM users WHERE user_id=" . $user . "" );
// ユーザー情報の取り出し
while ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) {
    $user_id = $row['user_id'];
    $username = $row['username'];
    $email = $row['email'];
    $imgurl = $row['user_img'];
	$hitokoto = $row['hitokoto'];
	$githubURL = $row['URL'];
    $tag1 = $row['tag1'];
    $tag2 = $row['tag2'];
    $tag3 = $row['tag3'];
    $tag4 = $row['tag4'];
    $tag5 = $row['tag5'];
}
//自分の持っているタグを取り出す
for ($i = 1; $i <= 5; $i++) {
    ${"tagN" . $i} = NGOpro(${"tag" . $i});
}

//投稿の処理
if (isset($_POST["contents"])) {
    $handle = $_SESSION['userN'];
    $img = $_SESSION['userNimg'];
    $contents = $_POST["contents"];
    NGO("insert into tweet_tbl values
    ( null,'$user',null,'$handle','$img','$contents',null )");
    header('Location: home.php');
}

//返信の処理
if (isset($_POST["contents2"])) {

    $handle = $_SESSION['userN'];
    $img = $_SESSION['userNimg'];
    $contents2 = $_POST["contents2"];
    $Replyid = $_POST["Replyid"];
    NGO("insert into tweet_tbl values
    ( null,'$user','$Replyid','$handle','$img','$contents2',null )");
    header('Location: home.php');
}

//投稿削除のしょり
if (isset($_GET["Auser"])) {
    $deltl = $_GET["Auser"];
    NGO("delete from tweet_tbl where id = '$deltl'");
    header('Location: home.php');
}

//タグのデータを摘出
$SqlRes = NGO('select * from tag where Decision = 0');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $all_tag[] = $Row;
}
//お気に入り取得
$SqlRes = NGO("select * from favorite where user_id = '$user'");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
$My_favo[]=$Row;
}
//宛先無しの投稿を全て取り出す
$SqlRes = NGO("select * from tweet_tbl where target_id IS NULL AND user_id = $user order by dataTime desc ;");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $SqlRes2        = NGO("select * from users where user_id = " . $Row['user_id'] . "");
    $tmp_u_data     = $SqlRes2->fetch(PDO::FETCH_ASSOC);
    $Row["handle"]  = $tmp_u_data["username"];
    $Row["img"]     = $tmp_u_data["user_img"];
    //お気に入り判定
    $Row["favo_flg"] = false;
    if(isset($My_favo)){
    for($i=0;$i<count($My_favo); $i++){
        if($Row["id"] == $My_favo[$i]["favorite_id"]){
            $Row["favo_flg"] = true;
            break;
        }
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
if(isset($ALLtweet)){
    foreach($ALLtweet as $tweet){
        if($tweet['favo_flg']==true){
            $Favotweet[] = $tweet;
        }
    }
}

if (isset($ALLtweet)) {
    //投稿の数をカウント
    $ALLtweetCNT = count($ALLtweet);
}else{
    $ALLtweetCNT = 0;
}

//フォローしている人のユーザー情報を取り出す
$SqlRes = NGO("select users.user_id,user_img,username,hitokoto from
users join follows on users.user_id=follows.follow_id where follows.user_id = '$user'");

// ユーザー情報の取り出し
$followCNT = "0";
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    //フォローしている人のユーザー情報を配列に入れる
    $followAry[] = $row;
}
//フォローしている人数のカウント
if (isset($followAry)) {
    $followCNT = count($followAry);
   	$ECHOfollowCNT = count($followAry);
} else {
	$ECHOfollowCNT = 0;
    $followCNT = 1;
}

//投稿のIDとフォローしている人、又は自分とIDが一致していれば$tweetAry配列に加える
for ($i = 0; $i < $ALLtweetCNT; $i++) {
    for ($j = 0; $j < $followCNT; $j++):

        if (isset($followAry)) {
            if ($ALLtweet[$i]['user_id'] == $followAry[$j]['user_id'] || $ALLtweet[$i]['user_id'] == $user):
                $tweetAry[] = $ALLtweet[$i];
            endif;
        }elseif ($ALLtweet[$i]['user_id'] == $user) {
            $tweetAry[] = $ALLtweet[$i];
        }

    endfor;
}

$SqlRes = NGO('select * from tweet_tbl where target_id IS NOT NULL;');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $SqlRes2    = NGO("select * from users where user_id = " . $Row['user_id'] . "");
    $tmp_u_data = $SqlRes2->fetch(PDO::FETCH_ASSOC);
    $Row["handle"] = $tmp_u_data["username"];
    $Row["img"] = $tmp_u_data["user_img"];
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

$followerCNT = "0";
//自分をフォローしてくれている人の数をカウント
if(isset($followerAry)){
$followerCNT = count($followerAry);
}


//フォローしているかしていないかの判断処理
$SqlRes = NGO("SELECT COUNT(*) AS CNT FROM follows WHERE user_id = '$my' AND follow_id = '$user_id'");
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $sonzai = $row['CNT'];
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
}


//お気に入り処理
if (isset($_POST["favoID"])) {
    $favoID = $_POST['favoID'];
    NGO("INSERT INTO `favorite`(`id`, `user_id`, `favorite_id`) VALUES (null,'$my','$favoID')");
    header('Location: afterU.php#tabtest4');
}

//お気に入り解除
if (isset($_POST["anfavoID"])) {
    $anfavoID = $_POST['anfavoID'];
    NGO("DELETE FROM `favorite` WHERE user_id = '$my' AND favorite_id = '$anfavoID'");
    header('Location: afterU.php#tabtest4');
}

//お気に入りをしているかしていないかの判断
$SqlRes = NGO("SELECT * FROM favorite where user_id = '$my' ");
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $Favocheck[] = $row['favorite_id'];
}
if (isset($Favocheck)) {

    $FavocheckCNT = count($Favocheck);
}else{
    $FavocheckCNT = 0;
}

//全てのライブラリからお気に入りしているものを取り出す
$OMG = NGO("select * from output");
while ($output = $OMG->fetch(PDO::FETCH_ASSOC)) {

   for ($i = 0; $i < $FavocheckCNT; $i++):
       if($Favocheck[$i] == $output['id']){
           $Favolist[] = $output;
       }
       
   endfor;
    
}


//after側から見たお気に入りをしているかしていないかの判断

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
		textarea {
		        resize: horizontal;
		        width:300px;
		        height:200px;
		    }
	</style>
	<?php include( "parts/header.php" ); ?>
	<body>
		<?php if (ua_smt() == true) { ?>
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
			<div style="margin-left:20px;margin-top:10px;">
			<?php if($_SESSION['userN'] != $username){ ?>
				<?php if($sonzai < 1 ){ ?>
					<form method="post" action="afterU.php">
						<input type="hidden" name="user" value="<?php echo $user_id; ?>">
						<button type="submit" class="btn btn-info">フォロー</button>
					</form>
				<?php }else{ ?>
					<form method="post" action="afterU.php">
						<input type="hidden" name="anuser" value="<?php echo $user_id; ?>">
						<button type="submit" class="btn btn-success">フォロー解除</button>
					</form>
				<?php } 
			}?>
        	</div>
        </div>

        <div class = "myprofile-right">
            <div class = "myprofile-info">
                <a>ユーザネーム</a>
                <p><?php echo $username; ?></p>
            </div>
			
        <div class="clear"></div>

            <div class = "myprofile-info" style="word-wrap: break-word;">
                <a>github URL</a>
                <p><a href="<?php echo $githubURL; ?>"><?php echo $githubURL; ?></a></p>
            </div>

            <div class = "myprofile-info"style="word-wrap: break-word;">
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
		<!-- 残り8列はコンテンツ表示部分として使う -->
		<div class="col-xs-12">
			<div class="panel-body">
				<div id="dnweb-tabtest">
					<!--プロフィール表示--><a class="disabled"><img src="img/<?php echo $imgurl; ?>" class="img-circle" width="100" /></a> <a href="#tabtest5" data-toggle="tab">フォロワー
                                <?php echo $followerCNT ?></a><a href="#tabtest2" data-toggle="tab">/フォロー
                                <?php echo $ECHOfollowCNT ?></a> <br /> <br />
					<?php
                            for ($i = 1; $i <= 5; $i++):    //5個設定できるタグの数だけ繰り返し
                                $Name = ${"tagN" . $i}['tag_name'];
                                $TAG = ${"tagN" . $i}['id'];
                                if ($Name != ""):      //タグが入っているかの判定
                                    ?>
						<a class="btn btn-warning btn-sm" style="border-radius: 60px;" href="user_search.php?kensaku=<?php echo $TAG ?>" role="button">
							<?php echo $Name; ?> </a>
						<?php
                                endif;//タグが入っているどうかの判定の閉じ
                            endfor; //5回繰り返し処理終わり
                            ?> <br /><br /><a class="disabled">メールアドレス：
                                <?php echo $email; ?></a><br /><a class="disabled">一言コメント：
                                <?php echo nl2br($hitokoto); ?></a> </div>
				<?php if($_SESSION['userN'] != $username){ ?>
				<?php if($sonzai < 1 ){ ?>
				<form method="post" action="afterU.php"> <input type="hidden" name="user" value="<?php echo $user_id; ?>"> <input type="submit" class="btn btn-success" value="フォロー"> </form>
				<?php }else{ ?>
				<form method="post" action="afterU.php"> <input type="hidden" name="anuser" value="<?php echo $user_id; ?>"> <input type="submit" class="btn btn-warning" value="フォロー解除"> </form>
				<?php } }?>
				<!--プロフィール表示ここまで-->
			</div>
			<?php } ?>
			<!-- タブの切替部分 -->
			<div id="bottom">
				<ul class="nav nav-tabs panel" style="">
					<li class="active"><a href="#tabtest1" data-toggle="tab">タイムライン</a></li>
					<li><a href="#tabtest4" data-toggle="tab">ライブラリ</a></li>
				</ul>
			</div>
			<!-- タブのコンテンツ部分 -->
			<div class="tab-content">
				<!-------------------------------------------------------------- タイムラインの表示------------------------------------------->
				<div class="tab-pane active" id="tabtest1">
					<div class="panel">
						<div class="row">
							<!-- ▼twitter風ここから -->
							<div class="twitter__container">
								<!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
								<div class="twitter__contents scroll">
                                    <?php
                                    if (isset($ALLtweet)):
                                        foreach ($ALLtweet as $tweet):
                                    ?>
                                    <table cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <!-- 記事エリア -->
                                            <div class="twitter__block">
                                                <a href="afterU.php?Fuser=<?php echo $tweet['user_id']; ?>">
                                                    <div class="twitter__icon">
                                                            <img src="img/<?php echo $tweet['img']; ?>" class="img-circle" width="30" />
                                                    </div>
                                                </a>
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
                                                        <button data-toggle="modal" data-target="#modal-sample<?php echo $tweet['id']; ?>"style="padding:7px;">
                                                            <span style="display:inline-block"class="twitter-bubble"></span></button>
                                                    </div>
                                                    <div style="padding:7px;" class="twitter__icon item<?PHP echo $tweet['id']; ?> <?PHP echo $tweet['favo_flg'] ? "on" : "off"; ?>" style="margin-left:3px;float:left;" onclick="toggleBookmark(<?PHP echo $tweet['id']; ?>,<?PHP echo $tweet['user_id']; ?>); return false;">
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
											</td>
											</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
					<div class="tab-pane" id="tabtest4">
                            <!-- ▼twitter風ここから -->
                            <div class="twitter__container">
                            <br />
                                <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
                                <div class="twitter__contents scroll">
                                    <?php
                                    if (isset($tweetAry)){
                                        foreach ($tweetAry as $tweet){ ?>
                                    <?php if($tweet['file']!="" || $tweet['file']!=NULL){ ?>
                                    <table cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <!-- 記事エリア -->
                                            <div class="twitter__block">
                                                <figure>
                                                    <img src="img/<?php echo $tweet['img']; ?>" class="img-circle" width="30" />
                                                </figure>
                                                <div class="twitter__block-text">
                                                    <div class="name">
                                                        <?php echo $tweet['handle']; ?>
                                                        <span class="name_reply"><?php if($tweet['tag_name']!=NULL){ ?>
                                                            <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php" role="button">
                                                                <?php echo $tweet['tag_name']; ?>
                                                            </a>
                                                            <?php }?>
                                                        </span>
                                                    </div>
                                                    <div class="date">10分前</div>
                                                    <div class="text">
                                                        <?php echo $tweet['message']; ?><br/>
                                                        <a href="img/<?php echo $tweet['file']; ?>" data-lightbox="<?php echo $tweet['file']; ?>">
                                                            <img src="img/<?php echo $tweet['file']; ?>"width="100px">
                                                        </a>
                                                            
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
                                           
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php }?>
                                    <?php
                                        } // foreach ($tweetAry as $tweet):
										 } //if (isset($tweetAry)):
                                    ?>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
<script>
function toggleBookmark(itemId,userId){
	var btn = $('.item'+itemId);
	var Bookmarked = btn.hasClass('on');
	$.post(
		'Ffavorite_ajax.php',
		{'tweet_id':itemId, 'user_id':userId,'bookmark':Bookmarked?1:0, 'ajax':1},
		function(response,status){
				if(Bookmarked){
					btn.removeClass('on');
					btn.addClass('off');
					btn.html('<span class="twitter-heart" ></span>');
				}else{
					btn.removeClass('off');
					btn.addClass('on');
					btn.html('<span class="twitter-heartON" ></span>');
				}
		}
	);
}
</script>