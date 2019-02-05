<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
include("../parts/function.php");
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
$SqlRes = NGO("SELECT * FROM users");
$NumRows = "";
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $RowAry[] = $row;
}
if (isset($RowAry)) {
    $NumRows = count($RowAry);
}
?>
<!DOCTYPE html>
<html lang="ja">

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
                    <h2 class="my-4">会員一覧</h2>
                </div>
                <?php for ($i = 0; $i < $NumRows; $i++) { ?>
                    <div class="col-lg-3 col-sm-6 text-center mb-4">
                    <div class = "myprofile-image">
                        <img src="../img/<?php print $RowAry[$i]["user_img"]; ?>" width="100" alt="">
                    </div>
                        <h3>
                            <small><?php print $RowAry[$i]["user_id"]; ?>:<?php echo $RowAry[$i]["username"]; ?></small>
                        </h3>
                        <form method="post" action="user_change.php">
                            <input type="submit" class="btn btn-danger"value="変更">
                            <input type="hidden" name ="user_id" value="<?php print $RowAry[$i]["user_id"]; ?>">
                        </form><br/>
                        <form method="post" action="alldisp.php">
                            <input type="hidden" name ="selectlogid" value="<?php print $RowAry[$i]["user_id"]; ?>">
                            <input type="submit" class="btn btn-warning"value="投稿ログ">
                        </form>
                        <p>email:<?php print $RowAry[$i]["email"]; ?></p>
                        <p>text:<?php print $RowAry[$i]["hitokoto"]; ?></p>

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
