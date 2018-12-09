<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");

//画像ファイルの削除処理
if (isset($_POST['imgsakuzyo'])) {
    $imgid = $_POST['imgid'];
    NGO("DELETE FROM `imageFile` WHERE id = '$imgid'");
}

//画像ファイルの取得
$OMG = NGO("select * from imageFile ");
while ($myimage = $OMG->fetch(PDO::FETCH_ASSOC)) {
    $imgAry[] = $myimage;
}
if (isset($imgAry)) {
    $imageCNT = count($imgAry);
}
?>
<?php
if (isset($_POST['position'])) {
    list($data['x'], $data['y']) = explode(',', $_POST['position']);
    $data['x'] = (int) $data['x'];
    $data['y'] = (int) $data['y'];
}
if (!isset($data['x']))
    $data['x'] = 0;
if (!isset($data['y']))
    $data['y'] = 0;
?>
<!DOCTYPE html>
<html lang="ja">

    <?php include("parts/head.php"); ?>>
    
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
                    <h2 class="my-4">ファイル管理</h2>
                </div>

                <?php for ($c = 0; $c < $imageCNT; $c++) { ?> 
                    <div class="col-lg-4">
                        <table class = "table table-bordered"width="100%" align="center" cellpadding="3" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td><?php print $imgAry[$c]["id"]; ?></td>
                                    <td><?php print $imgAry[$c]["user_id"]; ?></td>
                                    <td><img src="../img/<?php print $imgAry[$c]["url"]; ?>" width="200" /></td>
                                    <td style="border-left-style: none;">
                                        <form class="form-inline" method="POST" action="file.php">
                                            <input type="hidden" name="imgid" value="<?php print $imgAry[$c]["id"]; ?>" />
                                            <input type="hidden" name="position" value="<?php
                                            if (isset($data['x']))
                                                echo htmlspecialchars($data['x']);
                                            if (isset($data['x']) || isset($data['y']))
                                                echo ",";
                                            if (isset($data['y']))
                                                echo htmlspecialchars($data['y']);
                                            ?>"/>
                                            <input type="hidden" name="fav" value="<?php
                                            echo isset($post['id']) ?
                                                    htmlspecialchars($post['id']) : "";
                                            ?>"/>
                                            <input type="submit" name ="imgsakuzyo" class="btn btn-danger"value="削除">
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
