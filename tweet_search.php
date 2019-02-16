<?php
//  HTTPヘッダーで文字コードを指定
header('Content-Type:text/html; charset=UTF-8');
session_start();
include 'parts/function.php';
$id = $_SESSION['user'];
$SqlRes = NGO('select * from tag where Decision = 0');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $TagAry[] = $Row;
}
if (isset($TagAry)) {
    $NumTag = count($TagAry);
}

//プロフィールからタグをクリックして遷移してきた時の検索処理
if (isset($_POST['tag'])) {
    $search = $_POST['tag'];
    $SqlRes = NGO("select * from tweet_tbl where tag_id = $search");
    while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $SqlRes2    = NGO( "SELECT * FROM users WHERE user_id =" . $row['user_id'] . "" );
        $tmp_user   = $SqlRes2->fetch( PDO::FETCH_ASSOC );
        $row['username']    = $tmp_user['username'];
        $row['user_img']    = $tmp_user['user_img'];
        $tweet_Ary[] = $row;
    }
    
}
//タグによるユーザー検索
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
    $posttag = $_POST['tag'];
    $tagnum = count($posttag);
    $i = 0;
    foreach ($posttag as $value) {
        if ($i < $tagnum) {
            $SqlRes = NGO("select * from tweet_tbl where tag_id = $value");
            while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
                $SqlRes2    = NGO( "SELECT * FROM users WHERE user_id =" . $row['user_id'] . "" );
                $tmp_user   = $SqlRes2->fetch( PDO::FETCH_ASSOC );
                $row['username']    = $tmp_user['username'];
                $row['user_img']    = $tmp_user['user_img'];
                $tweet_Ary[] = $row;
            }
        }
        ++$i;
    }

  
}
?>
<!DOCTYPE HTML>
<style>
    .button_wrapper{
        text-align:center;
    }
</style>
<?php include 'parts/header.php'; ?>

<body>
<?php if (ua_smt() == true) { ?>
<style>
body {
    background-color:#fff;
    color: #333333;
}
ul {
  list-style: none;
}

input#search-text {
border: 1px solid #c8c8c8; 
border-radius:20px;
box-shadow: none;
padding: 8px 10px;
width:300px;
}

input#search-text:focus {
    outline: none;
}
.tag-area{
height:120px;
border: 1px solid #c8c8c8; 
overflow: auto;
}


</style>
<div class="container">
    <div class="row">
        <form method="POST" action="tweet_search.php">
            <br/>
            <div class="text-center">
                <a href="user_search.php" class="btn btn-warning btn-sm" role="button">ユーザー検索へ</a>
            </div>
                    <h1 style="text-align:center">
                        <th style="text-align:center">投稿検索</th>
                    </h1>
                    <div style="text-align:center;">
                    <div class = "profile-edit-name">
                    <input type="text" id="search-text" placeholder="検索ワードを入力">
                    </div>
                    </div>
                        <div class="tag-area">
                            <ul class="target-area">
                            <?php foreach($TagAry as $tag){ ?>
                                <li style="float:left;">
                                    <div class="pretty p-icon p-round p-pulse">
                                        <input type="checkbox" name="tag[]" value="<?php echo $tag['id']; ?>">
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
                <div class="button_wrapper">
                    <button type="submit" class="btn btn-primary">検索</button>
                </div>
        </form>
                <br/>
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title" style="text-align:center">
                    検索結果
                </div>
            </div>
        </div>
        <div style="border-bottom:1px solid #c8c8c8; "></div>
        <div class="twitter__container">
        <?php if (isset($tweet_Ary)) {
            foreach($tweet_Ary as $item_value){ ?>
            <div class="twitter__contents">
                <table cellpadding="3" cellspacing="0">
                    <tbody>
                        <!-- 記事エリア -->
                        <div class="twitter__block">
                            <div class="twitter__icon">
                                <a href="afterU.php?Fuser=<?php echo $item_value['user_id']; ?>">
                                    <img src="img/<?php echo $item_value['user_img']; ?>"/>
                                </a>
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
                                <div class="date">10分前</div>
                                <div class="text">
                                    <?php echo $item_value['message']; ?><br/>
                                    <?php if($tweet['file']!="" || $tweet['file']!=NULL){ ?>
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
            </div>                   <?php } }?>
         </div>
    </div>
</div>
<?php }else{ ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-7">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        タグリスト
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <form method="POST" action="user_search.php">
                            <br/>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" COLSPAN="3">ALL TAG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < $NumTag; $i = $i + 3) {?>
                                    <tr>
                                        <?php $z = 0;while ($z < 3 && ($i + $z < $NumTag)) { ?>
                                            <td class="text-left col-xs-4 table-noborder">
                                                <div class="pretty p-icon p-round p-pulse">
                                                    <input type="checkbox" name="tag[]" value="<?php echo $TagAry[$i + $z]['id']; ?>" />
                                                    <div class="state p-success">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label><a class = "a"><?php echo $TagAry[$i + $z]['tag_name']; ?></a></label>
                                                    </div>
                                                </div>
                                            </td>
                                        <?php  ++$z; } ?>
                                    </tr>
                                    <?php
                                }?>
                                </tbody>
                            </table>
                            <div class="button_wrapper">
                                <button type="submit" class="btn btn-primary">検索</button>
                        </form>
                        </div>
                    </div>
                    <br/>
                </div>
                <!--panel-body-->
            </div>
            <!--panel panel-info -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        検索結果
                    </div>
                </div>
                <div class="panel-body">
                    <div class="twitter__container">
                    <?php 
                    if (isset($tweet_Ary)) {
                        foreach($tweet_Ary as $item_value){ ?>
                        <div class="twitter__contents">
                            <table cellpadding="3" cellspacing="0">
                                <tbody>
                                    <!-- 記事エリア -->
                                    <div class="twitter__block">
                                        <div class="twitter__icon">
                                            <img src="img/<?php echo $item_value['user_img']; ?>" class="img-circle" width="30" />
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
                                            <div class="date">10分前</div>
                                            <div class="text">
                                                <?php echo $item_value['message']; ?><br/>
                                                <a href="img/<?php echo $item_value['file']; ?>" data-lightbox="<?php echo $item_value['file']; ?>">
                                                    <img src="img/<?php echo $item_value['file']; ?>"width="100px">
                                                </a>
                                                    
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
                        </div>                   <?php } }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
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
