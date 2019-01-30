<?php
header("Content-Type:text/html; charset=UTF-8");
session_start(  );
include( "parts/function.php" );
if ( !isset( $_SESSION['user'] ) ) {
    header( "Location: index.php" );
}

$SqlRes2 = NGO("select users.user_id,user_img,username,hitokoto from
users join follows on users.user_id=follows.follow_id where follows.user_id = " . $_SESSION['user'] . "");
while ($user_data = $SqlRes2->fetch(PDO::FETCH_ASSOC)) {
    $SqlRes3    = NGO("select * from follows where user_id = " . $_SESSION['user'] . " AND follow_id = " . $user_data['user_id'] . " ");
    $follow_flg      = $SqlRes3->fetch(PDO::FETCH_ASSOC);
    if($follow_flg){
        $user_data['follow_flg'] = true;
    }else{
        $user_data['follow_flg'] = false;
    }
    $user_dataAry[] = $user_data;
}
?>
<?php include 'parts/header.php'; ?>
<style>
    .search-user-img{
        height:auto;
    }
    body {
    background-color:#fff;
    color: #333333;
    }
</style>
<body>
<div class = "myprofile-row">
    <p class="myprofile-head">フォロー</p> 
    <div style="border-bottom:1px solid #c8c8c8; "></div>
    <div class="container">
        <div class="row">
            <?php if (isset($user_dataAry)) {
                foreach ($user_dataAry as $value) {?>
            <div class="search-user-container">
                <div class="search-user-contents">
                    <div class="search-user-img">
                        <a>
                            <img src="img/<?php echo $value['user_img']; ?>" />
                        </a>
                    </div>
                    <strong>
                        <a style = "display:block;margin-top:-50px;margin-left:4.5em;"href="afterU.php?Fuser=<?php echo $value['user_id']; ?>" class="widelink" >
                            <?php echo $value['username']; ?>
                        </a>
                    </strong>
                    <div class="sh-text">
                        <p>
                            <?php echo $value['hitokoto']; ?>
                        </p>
                    </div>
                </div>
                <div class="search-follow artist<?PHP echo $value['user_id']; ?> <?PHP echo $value['follow_flg'] ? "on" : "off"; ?>"onclick="toggleFollow(<?PHP echo $value['user_id']; ?>); return false;">
                    <?PHP if($value['follow_flg'] == false){ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline">フォロー</button>
                    <?php }else{ ?>
                        <button type="submit" style = "width:80px"class="btn btn-info btn-sm btn-round btn-outline info-active">フォロー中</button>
                    <?php } ?>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</div>
</body>
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