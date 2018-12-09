<?php
//  HTTPヘッダーで文字コードを指定
header("Content-Type:text/html; charset=UTF-8");
session_start();
include("../parts/function.php");
$SqlRes = NGO("select * from tag");
while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
//Decisionの値が0以外であれば結合されている
    if($Row['Decision'] == 0){
    $TagAry[] = $Row;
    }
}
if (isset($TagAry)) {
    $NumTag = count($TagAry);
}

//tagchangeから変更ボタンを押して遷移
if (isset($_POST['henkou'])) {
    $tag_id = $_POST['tag'];
    $tag_name = $_POST['tagname'];
}

//タグ名を変えた時に動く処理
if (isset($_POST['tagnameC'])) {
    $tag_id = $_POST['tag_id'];
    $tagnameC = $_POST['tagnameC'];
NGO("update tag set tag_name = '$tagnameC' where id = '$tag_id'");
header('Location: tag_change.php');
}

//tagchangeから削除ボタンを押して削除処理
if (isset($_POST['sakuzyo'])) {
    
    $tag_id = $_POST['tag'];
    $tag_name = $_POST['tagname'];
    NGO("DELETE FROM `tag` WHERE id = '$tag_id'");
    header('Location: tag_change.php');
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
      <p>タグ名変更</p>

      <!-- Team Members Row -->
        <div class="row">
           
            
            <div class="col-sm-10 form-inline" style="padding: 3px;">
                 <form method="POST" action="tag_changeex.php">
                <input type="text" class="form-control input-sm" name="tagnameC" placeholder="タグ名" size="20" value = "<?php echo $tag_name  ?>">
                <input type="hidden" name="tag_id" value="<?php echo $tag_id ?>" />
            </div>
        </div>


        <div class="row" style="padding: 30px;">
           <input type="submit" name ="hantei"class="btn btn-success"value="変更">
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
