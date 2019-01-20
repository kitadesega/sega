<?php
//  HTTPヘッダーで文字コードを指定
header('Content-Type:text/html; charset=UTF-8');
session_start();
include 'parts/function.php';
$user = $_SESSION['user'];

//廃止されていないタグを出力
$SqlRes = NGO('select * from tag where Decision = 0');
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $TagAry[] = $Row;
}
if (isset($TagAry)) {
    $TagCNT = count($TagAry);
}
$five_flag = true;
// 自分の使用しているタグを抜き出す
$result = NGO('SELECT * FROM users WHERE user_id='.$user.'');
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    for ($i = 1; $i <= 5; ++$i) {
        ${'tag'.$i} = $row['tag'.$i];
        if(${'tag'.$i} == 0){
            $five_flag = false;
        }
    }
}

//タグを変更した時の処理
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
    $posttag = $_POST['tag'];
    $tagnum = count($posttag);
    $i = 0;
    $sqltag[] = ['a', 'a', 'a', 'a', 'a'];
    foreach ($posttag as $value) {
        if ($i < $tagnum) {
            $sqltag[$i] = $value;
        }
        ++$i;
    }
    NGO("update users set tag1 = '$sqltag[0]',tag2 = '$sqltag[1]',tag3 = '$sqltag[2]',tag4 = '$sqltag[3]',tag5 = '$sqltag[4]'"
            ."where user_id = '$user'");
    header('Location: tagdisp.php');
}

//タグを作成した時の処理
if (isset($_POST['tagedit'])) {
    $tagedit = $_POST['tagedit'];
    $X = false;

    //全て小文字にして重複を逃れるために頑張る所
    $editcheck  = mb_convert_kana($tagedit, 'KVRNC');
    $editcheck  = mb_strtolower($editchec);
    for ($j = 0; $j < $TagCNT; ++$j) {
        $tagcheck = mb_convert_kana($TagAry[$j]['tag_name'], 'KVRNC');
        $tagcheck = mb_strtolower($tagcheck);

        if ($editcheck == $tagcheck) {
            $X = true;
            break;
        }
    }

    //重複するタグが無ければタグを作成
    if ($X == false) {
        NGO("INSERT INTO `tag`( `id`, `tag_name` ) VALUES ( '','$tagedit' )");
        header('Location: tagdisp.php');
    }
}
?>
<!doctype html>
<?php include 'parts/css.php'; ?>
<style>
    .button_wrapper{
        text-align:center;
    }
</style>
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
                    <div class="panel-body"><form method="POST" action="tagdisp.php">
                            
                            <br/>
                            <div class="table-responsive">
                            <table class="table table-fixed" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" COLSPAN="4">ALL TAG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < $TagCNT; $i = $i + 3): ?>
                                        <tr>
                                          <?php for ($z = 0; $z < 3 && ($i + $z < $TagCNT); ++$z): ?>
                                            
                                                <td class="text-left col-xs-4 table-noborder"> <div class="pretty p-icon p-round p-pulse">
                                                        <input type="checkbox" name="tag[]" value="<?php echo $TagAry[$i + $z]['id']; ?>" 
                                                               <?php for ($n = 1; $n <= 5; ++$n):
                                                                         if (${'tag'.$n} == $TagAry[$i + $z]['id']): ?>
                                                                     checked = "checked"
                                                                  <?php endif;
                                                                     endfor;
                                                                  ?>/>
                                                        <div class="state p-success">
                                                            <i class="icon mdi mdi-check"></i>
                                                            <label><a class = "a"><?php echo $TagAry[$i + $z]['tag_name']; ?></a></label>
                                                        </div>
                                                    </div></td>
                                              <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div><div class="button_wrapper">
                                <button type="submit" class="btn btn-info">保存</button>
                        </form></div><br/>
                    <form class="form-inline" method="POST" action="tagdisp.php">
                        <div class="form-group" >
                            <label>TagName</label>
                            <input type="text" class="form-control" placeholder="たぐのなまえ" name = "tagedit">
                        </div>
                        <button type="submit" class="btn btn-success">たぐをつくるぼたん</button>
                    </form>
                        <?php if (isset($X) && $X == true) {
                        ?>
                        <h1>似たようなのがもうあるよ！</h1>
                        <?php
                              } ?>
                </div><!--panel-body-->
            </div><!--panel panel-info -->
        </div><!--col-xs-12 col-md-7 -->
    </div><!--row -->
</div><!--container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

<script type="text/javascript">
window.onload = function() {
    var checked_length = $( 'input:checked' ).length;

// 選択上限は5つまで
if ( checked_length >= 5 ) {
    $( 'input:not( :checked )' ).attr( 'disabled', 'disabled' );
} else {
    $( 'input:not( :checked )' ).removeAttr( 'disabled' );
}
}
    $( function () {
        $( 'input' ).click( function firstscript() {
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
</html>