<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");
if (isset($_POST['chatid'])) {
    $_SESSION['chatidS'] = $_POST['chatid'];
}
$chatid = $_SESSION['chatidS'];

if (isset($_POST['chatname'])) {
    $chatname = $_POST['chatname'];
    NGO("update room_tbl set roomname = '$chatname' where roomid = '$chatid'");
}

if (isset($_POST['Log_sakuzyo'])) {
    $Logid = $_POST['Logid'];
    NGO("DELETE FROM `chat_tbl` WHERE id = '$Logid'");
}
if (isset($_SESSION['chatidS'])) {

    $SqlRes = NGO("select * from chat_tbl where roomid = '$chatid' order by dataTime desc");
    while ($Log = $SqlRes->fetch(PDO::FETCH_ASSOC)) {

        $LogAry[] = $Log;
    }

    $roomdate = NGO("select * from room_tbl where roomid = '$chatid'");
    $roomdate = $roomdate->fetch(PDO::FETCH_ASSOC);
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
            <p></p>

            <!-- Team Members Row -->
            <div class="row">
                <div class="col-lg-12">
                    <form style="display: inline" method="post" action="chatlog_disp.php">
                        <h2 class="my-4"><input type="text" name ="chatname" value="<?php echo $roomdate['roomname'] ?>">
                            <input type="hidden" name ="henkou" value="">
                            <input type="submit"  class="btn btn-default" value="変更" ></h2>
                    </form>
                </div>
                <div class="col-12 clearfix">
                    <?php foreach ($LogAry as $log) { ?>
                        <div>
                            <img src="../img/<?= $log["img"] ?>"width="50"height="50" />
                            <?php echo htmlspecialchars_decode($log["handle"]) ?>
                            <?php echo htmlspecialchars_decode($log["message"]) ?>
                            <form style="display: inline" method="post" action="chatlog_disp.php">
                                <input type="hidden" name="Logid" value="<?php print $log["id"]; ?>">
                                <input type="hidden" name="henkou" value="">
                                <input type="submit" name = "Log_sakuzyo" class="btn btn-danger" value="削除">
                            </form>
                        </div>

                    <?php } ?>
                </div>
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
