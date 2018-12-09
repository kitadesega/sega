<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");
//削除
if (isset($_POST['sakuzyo'])) {
    $dispid = $_POST['dispid'];
     NGO("DELETE FROM `tweet_tbl` WHERE id = '$dispid'");
     NGO("DELETE FROM `tweet_tbl` WHERE target_id = '$dispid'");
    header('Location: alldisp.php');
}
//変更
if (isset($_POST['henkou'])) {
    $dispid = $_POST['dispid'];
    $disptext = $_POST['disptext'];
    NGO("update tweet_tbl set message = '$disptext' where id = '$dispid '");
    header('Location: alldisp.php');
}

//選択したユーザーの投稿ログを表示
if (isset($_POST['selectlogid'])) {
    $id = $_POST['selectlogid'];
    $SqlRes = NGO("SELECT * FROM tweet_tbl where user_id = $id");
    $tweetCNT = "";
    while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $tweet[] = $row;
    }
    if (isset($tweet)) {
        $tweetCNT = count($tweet);
    }
    //全ての投稿を表示
} else {
    $SqlRes = NGO("SELECT * FROM tweet_tbl");
    $tweetCNT = "";
    while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $tweet[] = $row;
    }
    if (isset($tweet)) {
        $tweetCNT = count($tweet);
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
            <p>管理者は神なんですよ</p>

            <!-- Team Members Row -->
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="my-4">投稿の一覧</h2>
                </div>
                <?php for ($y = 0; $y < $tweetCNT; $y++) { ?>
                    <div class="col-lg-3 col-sm-6 text-left mb-4">
                        <form method="POST" action="alldisp.php">
                            <table class = "table table-bordered"width="100%" align="center" cellpadding="3" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            &nbsp;&nbsp;
                                            <span class="headmark1"><?php echo $tweet[$y]['id']; ?></span>
                                            <span class="headmark2"><img src="../img/<?php print $tweet[$y]['img']; ?>" class="img-circle" width="60" /></span>
                                            <span class="subject">
                                                <?php echo $tweet[$y]['handle']; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <input type="hidden" name="dispid" value="<?php echo $tweet[$y]['id']; ?>" />

                                            <input type="submit" name ="henkou"class="btn btn-warning"value="変更">　<input type="submit" name ="sakuzyo" class="btn btn-danger"value="削除">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-10" style="padding: 3px;">
                                                    <textarea class="form-control  input-sm"name="disptext" rows="3"  placeholder=""><?php echo $tweet[$y]['message']; ?></textarea>
                                                </div>
                                            </div>

                                        </td>

                                    </tr>
                                    <tr>
                                        <td><span class="com_foot"> ...
                                                <?php echo $tweet[$y]['dataTime']; ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                <?php } ?>


            </div>

        </div>
        <!-- /.container -->

        <!-- Footer -->
        <?php include("parts/footer.php"); ?>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
