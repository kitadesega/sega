<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");


//タグの情報取り出し
$SqlRes = NGO("select * from tag where Decision = '0'");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $TagAry[] = $Row;
}
if (isset($TagAry)) {
    $NumTag = count($TagAry);
}

$SqlRes = NGO("select * from tag where Decision = '1'");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $NotTagAry[] = $Row;
}
if (isset($TagAry)) {
    $NotNumTag = count($NotTagAry);
}



//タグの復活処理
if (isset($_POST['nottag'])) {
    $nottag = $_POST['nottag'];
    NGO("update tag set Decision = '0' where id = $nottag");
    header("Location: tag_join.php");
}


//ユーザーのタグ結合処理
if (isset($_POST['righttag']) && isset($_POST['lefttag'])){
    $left = $_POST['lefttag'];
    $right = $_POST['righttag'];
NGO("update users set tag1 = '$right' where tag1 = $left;");
NGO("update users set tag2 = '$right' where tag2 = $left;");
NGO("update users set tag3 = '$right' where tag3 = $left;");
NGO("update users set tag4 = '$right' where tag4 = $left;");
NGO("update users set tag5 = '$right' where tag5 = $left;");

NGO("update users set tag1 = '0' where tag1 = tag2;");
NGO("update users set tag1 = '0' where tag1 = tag3;");
NGO("update users set tag1 = '0' where tag1 = tag4;");
NGO("update users set tag1 = '0' where tag1 = tag5;");

NGO("update users set tag2 = '0' where tag2 = tag3;");
NGO("update users set tag2 = '0' where tag2 = tag4;");
NGO("update users set tag2 = '0' where tag2 = tag5;");

NGO("update users set tag3 = '0' where tag3 = tag4;");
NGO("update users set tag3 = '0' where tag3 = tag5;");

NGO("update users set tag4 = '0' where tag4 = tag5;");

NGO("update tag set Decision = '1' where id = $left");

header("Location: tag_join.php");
}

?>
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet">
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
                    <h2 class="my-4"></h2>
                </div>
                <div class="col-lg-5 col-sm-5 text-center mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" COLSPAN="4">不要なタグ</th>
                            </tr>
                        </thead>
                        <form method="POST" action="tag_join.php">
                            <tbody>
                                <?php for ($i = 0; $i < $NumTag; $i = $i + 4) { ?>
                                    <tr>
                                        <?php
                                        $z = 0;
                                        while ($z < 4 && ($i + $z < $NumTag)) {
                                            ?>
                                            <td class="text-left">
                                                <div class="pretty p-icon p-round p-pulse">
                                                    <input type="checkbox" name="lefttag"
                                                           value="<?php echo $TagAry[$i + $z]['id'] ?>"/>
                                                    <div class="state p-success">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label><?php echo $TagAry[$i + $z]['tag_name'] ?></label>
                                                    </div>
                                                </div>
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
               <div class="col-lg2 col-sm-2 text-center mb-4" style="margin-left:70px;margin-top:80px">
                   <h1>→</h1>
               </div>
                <!-- ここから右側のタグ -->
                <div class="col-lg-4 col-sm-4 text-center mb-4" style="">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" COLSPAN="4">結合先タグ</th>
                            </tr>
                        </thead>
                     
                            <tbody>
                                <?php for ($i = 0; $i < $NumTag; $i = $i + 4) { ?>
                                    <tr>
                                        <?php
                                        $z = 0;
                                        while ($z < 4 && ($i + $z < $NumTag)) {
                                            ?>
                                            <td class="text-left">
                                                <div class="pretty p-icon p-round p-pulse">
                                                    <input type="checkbox" name="righttag"
                                                           value="<?php echo $TagAry[$i + $z]['id'] ?>"/>
                                                    <div class="state p-success">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label><?php echo $TagAry[$i + $z]['tag_name'] ?></label>
                                                    </div>
                                                </div>
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
            <div style="text-align:center;margin-left:150px">
                        <input type="submit" class="btn btn-danger" value="タグ結合">
                        </form></div>
            <br/>
            <form method="POST" action="tag_join.php">
            <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" COLSPAN="4">使用不可のタグ</th>
                            </tr>
                        </thead>
                        <form method="POST" action="tag_join.php">
                            <tbody>
                                <?php for ($i = 0; $i < $NotNumTag; $i = $i + 4) { ?>
                                    <tr>
                                        <?php
                                        $z = 0;
                                        while ($z < 4 && ($i + $z < $NotNumTag)) {
                                            ?>
                                            <td class="text-left">
                                                <div class="pretty p-icon p-round p-pulse">
                                                    <input type="radio" name="nottag"
                                                           value="<?php echo $NotTagAry[$i + $z]['id'] ?>"/>
                                                    <div class="state p-success">
                                                        <i class="icon mdi mdi-check"></i>
                                                        <label><?php echo $NotTagAry[$i + $z]['tag_name'] ?></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php
                                            $z++;
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                    </table>
                    <div style="text-align:center">
                        <input type="submit" class="btn btn-danger" value="タグを使用可能にする">
                    </div>
                    </form>
        </div>
        <!-- /.container -->

        <!-- Footer -->
        <?php include("parts/footer.php"); ?>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
