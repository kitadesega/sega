<?php
header("Content-Type:text/html; charset=UTF-8");
session_start(  );
include( "parts/function.php" );
if ( !isset( $_SESSION['user'] ) ) {
    header( "Location: index.php" );
}

$SqlRes = NGO( "SELECT * FROM notice WHERE target_id=" . $_SESSION['user'] . " OR relation_id = " . $_SESSION['user'] . "" );
// ユーザー情報の取り出し
while ( $row = $SqlRes->fetch( PDO::FETCH_ASSOC )) {
    $SqlRes2    = NGO( "SELECT * FROM users WHERE user_id =" . $row['user_id'] . "" );
    $tmp_user   = $SqlRes2->fetch( PDO::FETCH_ASSOC );
    $SqlRes3    = NGO("select * from follows where user_id = " . $_SESSION['user'] . " AND follow_id = " . $row['user_id'] . " ");
    $follow_flg      = $SqlRes3->fetch(PDO::FETCH_ASSOC);
    if($follow_flg){
        $row['follow_flg'] = true;
    }else{
        $row['follow_flg'] = false;
    }
    if($row['relation_id']!=NULL){
    $SqlRes4 = NGO("select * from tweet_tbl where id = " . $row['relation_id'] . "");
    $tweet_row = $SqlRes4->fetch( PDO::FETCH_ASSOC );
    $row['message'] = $tweet_row['message'];
    $row['file'] = $tweet_row['file'];
    }






    $row['username']    = $tmp_user['username'];
    $row['user_img']    = $tmp_user['user_img'];
    $notice_results[]   = $row;
}

?>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
<?php include( "parts/header.php" ); ?>
<style>
html{
    overflow-x : hidden;
    overflow-y : auto;
}

body{
    overflow-x : hidden;
    overflow-y : auto;
}
</style>
<body>
<?php if (ua_smt() == true) { ?>
<style>
body {
	background-color:#fff;
	color: #333333;
}

.twitter__container .twitter__block {
    width: 100%;
    display: block;
    padding: 10px;
    margin-bottom: 5px;
    border-bottom:none ;
    overflow: hidden;
}
</style>

<div class = "profile-edit">
<p class="myprofile-head">お知らせ</p> 
    <div class = "profile-edit-container">
        <?php if(isset($notice_results)){ ?>
            <div class="twitter__container">
    <?php foreach ($notice_results as $item_value):?>
        <div class = "notice_item-content">
            <div class="notice-icon-top">
                <a href="#">
                    <img src="img/<?php echo $item_value['user_img']; ?>" />
                </a>
            </div>
            <div class = "block">
            <?php 
            if($item_value['relation_id']==NULL){
                echo "<strong>".$item_value['username']."</strong>";
                echo "さんにフォローされました";?>
                <div style="margin-bottom:5px;"class="search-follow artist<?PHP echo $item_value['user_id']; ?> <?PHP echo $item_value['follow_flg'] ? "on" : "off"; ?>"onclick="toggleFollow(<?PHP echo $item_value['user_id']; ?>); return false;">
                    <?PHP if($item_value['follow_flg'] == false){ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline">フォロー</button>
                    <?php }else{ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline info-active">フォロー中</button>
                    <?php } ?>
                </div>
                <?php }else{ 

                    echo "<strong>".$item_value['username']."</strong>";
                    echo "さんがあなたの投稿にいいねいしました。";
                    ?>
                            <br />
                                <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
                                <div class="twitter__contents">
                                    <table cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <!-- 記事エリア -->
                                            <div class="twitter__block">
                                            <div class="notice-icon-top">
                                                
                                                    <img src="img/<?php echo $item_value['user_img']; ?>"/>
                                            </div>
                                                <div class="twitter__block-text">
                                                    <div class="name">
                                                        <?php echo $item_value['handle']; ?>
                                                        <span class="name_reply"><?php if($item_value['tag_name']!=NULL){ ?>
                                                            <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php" role="button">
                                                                <?php echo $item_value['tag_name']; ?>
                                                            </a>
                                                            <?php }?>
                                                        </span>
                                                    </div>
                                                    <div class="text">
                                                        <?php echo $item_value['message']; ?>
                                                        <?php if($item_value['file']!="" || $item_value['file']!=NULL){ ?>
                                                            <br/>
                                                        <a href="img/<?php echo $item_value['file']; ?>" data-lightbox="<?php echo $item_value['file']; ?>">
                                                            <img src="img/<?php echo $item_value['file']; ?>"width="100px">
                                                        </a>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                    <div align="right">
                                                        <span class="com_foot"> ...
                                                            <?php echo $item_value['dataTime']; ?></span>
                                                        <?php if ($item_value['user_id'] == $user): //投稿が自分のかどうか?>
                                                        [<a href="home.php?Auser=<?php echo $tweet['id']; ?>">削除</a>]
                                                        <?php endif; //投稿が自分のかどうか判定終わり?>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </tbody>
                                    </table>
                                </div>
              <?php  } ?>
            </div>
        </div>
    <?php endforeach; ?>
                    <?php }?>
    </div> 
</div>

                <?php }else{ ?>

<div class = "profile-edit">
    <div class = "profile-edit-container">
    <?php foreach ($notice_results as $item_value):?>
        <div class = "notice_item-content">
            <div class="notice-icon-top">
                <a href="#">
                    <img src="img/<?php echo $item_value['user_img']; ?>" />
                </a>
            </div>
            <div class = "block">
            <?php 
                echo "<strong>".$item_value['username']."</strong>";
                echo "さんにフォローされました";?>
                <div style="margin-bottom:5px;"class="search-follow artist<?PHP echo $item_value['user_id']; ?> <?PHP echo $item_value['follow_flg'] ? "on" : "off"; ?>"onclick="toggleFollow(<?PHP echo $item_value['user_id']; ?>); return false;">
                    <?PHP if($item_value['follow_flg'] == false){ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline">フォロー</button>
                    <?php }else{ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline info-active">フォロー中</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div> 
</div>
                    <?php } ?></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
function toggleFollow(artistId){
    var btn = $('.artist'+artistId);
    var evt = $('.'+artistId);
    var followed = btn.hasClass('on');
    $.post(
        'follow_ajax.php',
        {'userID':artistId, 'follow':followed?1:0, 'ajax':1},
        function(response,status){
                if(followed){
                    console.log(btn);
                    btn.removeClass('on');
                    btn.addClass('off');
                    btn.html('<button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline">フォロー</button>');
                }else{
                    btn.removeClass('off');
                    btn.addClass('on');
                    btn.html('<button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline info-active">フォロー中</button>');
                }
            
            
        }
    );
}
</script>


</body>
</html>
