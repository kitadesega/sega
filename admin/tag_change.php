<?php
//  HTTPヘッダーで文字コードを指定
header( "Content-Type:text/html; charset=UTF-8" );
session_start(  );
if ( !isset( $_SESSION['adminuser'] ) ) {
    header( "Location: admin_index.php" );
}
include( "../parts/function.php" );
//タグの情報抜き出す
$SqlRes = NGO( "select * from tag" );
while ( $Row = $SqlRes->fetch( PDO::FETCH_ASSOC ) ) {
    //Decisionの値が0以外であれば結合されている
    if( $Row['Decision'] == 0 ){
    $TagAry[] = $Row;
    }
}
if ( isset( $TagAry ) ) {
    $NumTag = count( $TagAry );
}


//タグを変更した時の処理
if ( isset( $_POST['tag'] ) && is_array( $_POST['tag'] ) ) {
    $posttag = $_POST['tag'];
    $tagnum = count( $posttag );
    $i = 0;
    $sqltag[] = [NULL, NULL, NULL, NULL, NULL];
    foreach ( $posttag as $value ) {
        if ( $i < $tagnum ) {
            $sqltag[$i] = $value;
        }$i++;
    }
    NGO( "update users set tag1 = '$sqltag[0]',tag2 = '$sqltag[1]',tag3 = '$sqltag[2]',tag4 = '$sqltag[3]',tag5 = '$sqltag[4]'"
            . "where user_id = '$id'" );
    header( 'Location: tag_change.php' );
}

//タグを作成した時の処理
if ( isset( $_POST['tagedit'] ) ) {
    $tagedit = $_POST['tagedit'];
    $X = false;
    //全て小文字にして重複を逃れるために頑張る所
    $editchec = mb_convert_kana( $tagedit,'KVRNC' );
    $editcheck = mb_strtolower( $editchec );
    for ( $j = 0; $j < $NumTag; $j++ ) {
    $tagcheck = mb_convert_kana( $TagAry[$j]['tag_name'],'KVRNC' );
    $tagcheck = mb_strtolower( $tagcheck );
    //類似重複してないか
    if( $editcheck == $tagcheck ){
        $X = true;
        break;
         }
    }
    if( $X == false ){
    NGO( "INSERT INTO `tag`( `id`, `tag_name` ) VALUES ( '','$tagedit' )" );
    header( 'Location: tag_change.php' );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include("parts/head.php"); ?>

    
    <body>

        <!-- Navigation -->
        <?php include("parts/header.php"); ?>

        <!-- Page Content -->
        <div class="container">

            <!-- Introduction Row -->
            <h1 class="my-4">神
                <small>管理者は神</small>
            </h1>
            <a href="tag_join.php">タグ結合はこっち</a>

            <!-- Team Members Row -->
            <div class="row">
                <div class="col-lg-6 col-sm-6 text-center mb-4">
                    <form class="form-inline" method="POST" action="tag_change.php">
                        <div class="form-group" >
                            <label>TagName　</label>
                            <input type="text" class="form-control" placeholder="たぐのなまえ" name = "tagedit">
                        </div>
                        <button type="submit" class="btn btn-success">タグの作成</button>
                        <?php if( isset( $X ) && $X == true ){ ?>
                        <h1>似たようなのがもうあるよ！</h1>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" COLSPAN="8">ALL TAG</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php for ( $i = 0; $i < $NumTag; $i = $i + 4 ) { ?>
                            <tr>
                                <?php
                                $z = 0;
                                while ( $z < 4 && ( $i + $z < $NumTag ) ) {
                                    ?>
                                    <td class="text-left" style="border-right-style: none;">
                                        <form method="POST" action="tag_changeex.php">
                                            <input type="hidden" name="tag" value="<?php echo $TagAry[$i + $z]['id'] ?>" />
                                            <input type="hidden" name="tagname" value="<?php echo $TagAry[$i + $z]['tag_name'] ?>" />
                                            <?php echo $TagAry[$i + $z]['tag_name'] ?>
                                            <td style="border-left-style: none;"><input type="submit" name ="henkou"class="btn btn-warning"value="変更"> <input type="submit" name ="sakuzyo" class="btn btn-danger"value="削除"></td>
                                        </form>
                                    </td>
                                    <?php
                                    $z++;
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            </div>

        </div>
        <!-- /.container -->

        <!-- Footer -->
        <?php include( "parts/footer.php" ); ?>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
