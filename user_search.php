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
if (isset($_GET['kensaku'])) {
    $searchTAG = $_GET['kensaku'];
    $SqlRes = NGO('select * from users ');
    while ($user = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $user;
    }
    if (isset($users)) {
        $Numuser = count($users);
        //カウント
    }
    for ($i = 0; $i < $Numuser; ++$i) {
        if ($users[$i]['tag1'] == $searchTAG || $users[$i]['tag2'] == $searchTAG || $users[$i]['tag3'] == $searchTAG ||
            $users[$i]['tag4'] == $searchTAG || $users[$i]['tag5'] == $searchTAG) {
            $userAry[] = $users[$i];
        }
    }
}
//タグによるユーザー検索
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
    $posttag = $_POST['tag'];
    $tagnum = count($posttag);
    $i = 0;
    $sqltag[0] = 'a';
    $sqltag[1] = 'a';
    $sqltag[2] = 'a';
    $sqltag[3] = 'a';
    $sqltag[4] = 'a';
    foreach ($posttag as $value) {
        if ($i < $tagnum) {
            $sqltag[$i] = $value;
        }
        ++$i;
    }

    $SqlRes = NGO('select * from users ');

    while ($user = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $user;
    }
    if (isset($users)) {
        $Numuser = count($users);
        //カウント
    }
    $i = 0;
    for ($i = 0; $i < $Numuser; ++$i) {
        for ($j = 0; $j < 5; ++$j) {
            if ($users[$i]['tag1'] == $sqltag[$j] || $users[$i]['tag2'] == $sqltag[$j] || $users[$i]['tag3'] == $sqltag[$j] ||
                $users[$i]['tag4'] == $sqltag[$j] || $users[$i]['tag5'] == $sqltag[$j]) {
                $userAry[] = $users[$i];
                break;
            }
        }
    }
}

?>
<!DOCTYPE HTML>
<?php include 'parts/css.php'; ?>
<style>
    .button_wrapper{
        text-align:center;
    }
    
</style>
<script type="text/javascript">
    $( function (  ) {
        $( 'input' ).click( function (  ) {
            var checked_length = $( 'input:checked' ).length;

            // 選択上限は5つまで
            if ( checked_length >= 5 ) {
                $( 'input:not( :checked )' ).attr( 'disabled', 'disabled' );
            } else {
                $( 'input:not( :checked )' ).removeAttr( 'disabled' );
            }
        } );
    } );
</script>
<body>
    <?php include 'parts/header.php'; ?>
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
                        <div class="table-responsive"><form method="POST" action="user_search.php">
                            <br/>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" COLSPAN="3">ALL TAG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < $NumTag; $i = $i + 3) {
                                    ?>
                                        <tr>
                                            <?php
                                              $z = 0;
                                              while ($z < 3 && ($i + $z < $NumTag)) {
                                            ?>
                                                <td class="text-left col-xs-4 table-noborder"> <div class="pretty p-icon p-round p-pulse">
                                                        <input type="checkbox" name="tag[]" value="<?php echo $TagAry[$i + $z]['id']; ?>" />
                                                        <div class="state p-success">
                                                            <i class="icon mdi mdi-check"></i>
                                                            <label><a class = "a"><?php echo $TagAry[$i + $z]['tag_name']; ?></a></label>
                                                        </div>
                                                    </div></td>
                                                <?php
                                                  ++$z;
                                              } ?>
                                        </tr>
                                    <?php
                                          }?>
                                </tbody>
                            </table> <div class="button_wrapper">
                                <button type="submit" class="btn btn-primary">検索</button>
                        </form>
                        </div>
                    </div>
                    <br/>
                </div><!--panel-body-->
            </div><!--panel panel-info -->

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                            検索結果
                        </div>
                    </div>
                    <div class="panel-body">
                       <!-- 検索結果のユーザー一覧表示 -->
                       <?php if (isset($userAry)) {
                       ?>
                           <?php foreach ($userAry as $value) {
                           ?>

                            <table class="table table-hover" >
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-2">なまえ</th>
                                                    <th class="col-xs-4">あいこん</th>
                                                    <th class="col-xs-6">ひとこと</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="afterU.php?Fuser=<?php echo $value['user_id']; ?>" class="widelink" ><?php echo $value['username']; ?></a></td>
                                                    <td><img src="img/<?php echo $value['user_img']; ?>" width="100" /></td>
                                                    <td><?php echo $value['hitokoto']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>

                           <?php
                                 } ?>
                       <?php
                             }?>
                    </div>

                </div>

        </div><!--col-xs-12 col-md-7 -->

    </div><!--row -->

</div><!--container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>