<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
header('Expires:');
header('Cache-Control:');
header('Pragma:');
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");

$SqlRes = NGO("select * from room_tbl");

while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $chatroom[] = $Row;
}
if (isset($chatroom)) {
    $Numroom= count($chatroom);
} else {
    $Numroom = 0;
}

//各ルームに何人いるか調べる
  for ($i = 0; $i < $Numroom; $i++) {
      $chatroomid = ($chatroom[$i]["roomid"]);
      $SqlRes = NGO("select count(roomid) from user_tbl where roomid = $chatroomid;");
      $Row = $SqlRes->fetch(PDO::FETCH_ASSOC);
  }
if (isset($_POST['sakuzyo'])) {
    $sakuzyoid = $_POST['chatid'];
    NGO("DELETE FROM `room_tbl` WHERE roomid = '$sakuzyoid'");
    NGO("DELETE FROM `user_tbl` WHERE roomid = '$sakuzyoid'");
    NGO("DELETE FROM `chat_tbl` WHERE roomid = '$sakuzyoid'");
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
                    <h2 class="my-4">チャットの一覧</h2>
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>ルーム名</th>
                                    <th>ボタン</th>
                                    <th>入室者数</th>
                                    <th>制作者</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < $Numroom; $i++) {
                                    ?>
                                    <tr>
                                        <th><a style ="font-size:16px;"><?php print $chatroom[$i]["roomname"]; ?></a></th>
                                        <td> <form style="display: inline" method="post" action="chatlog_disp.php">
                                                <input type="hidden" name="chatid" value="<?php print $chatroom[$i]["roomid"]; ?>">
                                                <input type="submit" name = "henkou" class="btn btn-default" value="変更" >
                                            </form>
                                                <form style="display: inline" method="post" action="chatlog.php">
                                                    <input type="hidden" name="chatid" value="<?php print $chatroom[$i]["roomid"]; ?>">
                                                    <input type="submit" name = "sakuzyo" class="btn btn-danger" value="削除">
                                                </form>
                                             </td>
                                        <td><span style="color:red;"><?= $Row["count(roomid)"] ?></span>/<?php if ($chatroom[$i]["limit"] == $Row["count(roomid)"]) {
                                        ?>
                                                <span style="color:green;"><?php
                                    } else {
                                        ?><span style="color:red;"><?php
                                    } ?> <?= $chatroom[$i]["limit"] ?></span>
                                        </td>
                                        <td><span style="color:red;">ID:<?php echo $chatroom[$i]["edituser_id"]; ?>　　Name:<?php echo $chatroom[$i]["edituser"] ?></span></td>
                                    </tr>
                                <?php
                                } ?>
                            </tbody>
                        </table>
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
