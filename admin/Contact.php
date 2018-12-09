<?php
header("Content-Type:text/html; charset=UTF-8");
session_start();
if (!isset($_SESSION['adminuser'])) {
    header("Location: admin_index.php");
}
include("../parts/function.php");
$SqlRes = NGO("SELECT * FROM message");
    $NumRows = "";
    while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $RowAry[] = $row;
        $id = $row['user_id'];
        $ids = NGO("select * from users where user_id = '$id'");
        $idAry[] = $ids->fetch(PDO::FETCH_ASSOC);
    }
    if (isset($RowAry)) {
        $NumRows = count($RowAry);
    }

    //お問い合わせに対しての返信処理
    if ( isset( $_POST['reply'] ) ) {
        $reply = $_POST['reply'];
        $userid = $_POST['userid'];
        NGO( "INSERT INTO `adminmessage`( `id`, `message_id`, `text` ) VALUES ( '','$userid','$reply' )" );
        header("Location: Contact.php");
    }

    
    
?>
<!DOCTYPE html>
<html lang="ja">
<style>
    .modal-dialog-center {
  padding-top: 10%;
    }
    </style>
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
                    <h2 class="my-4">届いたメッセージ</h2>
                </div>
                <?php for ($y = 0; $y < $NumRows; $y++) { ?>
                    <div class="col-lg-3 col-sm-6 text-left mb-4">
                        <form>
                            <table class = "table table-bordered"width="100%" align="center" cellpadding="3" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            &nbsp;&nbsp;
                                            ID:<span class="headmark1"><?php echo $RowAry[$y]['user_id']; ?></span>
                                            <span class="headmark2">　Name:<?php echo $idAry[$y]['username']; ?></span>
                                            
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-10" style="padding: 3px;">
                                                    <textarea class="form-control  input-sm"name="disptext" rows="3"  placeholder="" readonly="readonly"><?php echo $RowAry[$y]['text']; ?></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>    
                                </tbody>
                            </table>
                         <!-- モーダルウィンドウを呼び出すボタン -->
                            </form>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">返信</button>
                            
                        <!-- モーダルウィンドウの中身 -->

                 </div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-dialog-center">
                            <div class="modal-content">
                            
                            <div class="modal-body">
                            <form method="POST" action="Contact.php">
                            <input name="userid" type="hidden" value="<?php echo $RowAry[$y]['id']; ?>">
                                <button type="button" class="close" style ="margin-bottom:10px;"data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <textarea class="form-control  input-sm"name="reply" rows="6"  placeholder="" ></textarea>
                                </div>
                                <div style = "margin:0 auto;padding-bottom:30px;">
                                    <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#myModal">返信</button>
                                </div>
                            </form>
                            </div>
                        </div>
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
