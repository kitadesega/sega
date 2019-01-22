<?php
//  HTTPヘッダーで文字コードを指定
header( "Content-Type:text/html; charset=UTF-8" );
session_start( );
include( "parts/function.php" );

$userid = $_SESSION['user'];

if ( isset( $_POST['comment'] ) ) {
    $comment = $_POST['comment'];
    NGO( "INSERT INTO `message`( `id`, `user_id`, `text` ) VALUES ( '','$userid','$comment' )" );
    
}

$SqlRes = NGO("select * from message where user_id = '$userid'");
while ($msg = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $msgAry[] = $msg;
}

//返信があれば表示
$SqlRes = NGO("select * from adminmessage");
while ($adminmsg = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
    $adminmsgAry[] = $adminmsg;
}
?>
<!doctype html>
<style>
    .button_wrapper{
        text-align:center;
    }
</style>
<script type="text/javascript">
    $(function() {
        $('input').click(function() {
            var checked_length = $('input:checked').length;
            // 選択上限は5つまで
            if (checked_length >= 5) {
                $('input:not( :checked )').attr('disabled', 'disabled');
            } else {
                $('input:not( :checked )').removeAttr('disabled');
            }
        });
    });

</script>
<?php include( "parts/header.php" ); ?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                            お問い合わせ
                        </div>
                        <?php if ( !isset( $_POST['comment'] ) ) { ?>
                        <form method="post" action="Contact.php">
                            <div class="form-group">
                                <label for="email"></label>
                                <input type="text" class="form-control" name="user" placeholder="<?php echo $_SESSION['userN']; ?>" size="10" readonly="readonly">
                            </div>

                            <div class="form-group">
                                <label for="comment">ご意見をどうぞ</label>
                                <textarea class="form-control" rows="3" name="comment" placeholder="ご意見をどうぞ"></textarea>
                            </div>

                            <input type="submit" class="btn btn-primary" value="送信">
                        </form>
                        <?php }else{ ?>
                        <h1>ご意見ありがとうございました。</h1>
                        <?php } ?>

                    </div>
                    
                    <!--panel-body-->
                </div>
                
                <!--col-xs-12 col-md-7 -->

                <!--row -->
                <div class="col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">
                            <p class="text-center" style = "font-size:20px">管理者からの返信</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($msgAry)){
                          foreach ($msgAry as $msg){ ?>
                        <table class = "table table-bordered"width="100%" align="center" cellpadding="3" cellspacing="0">
                                <tbody>
                                    
                                   
                                    <tr>
                                        <td>
                                        <div class="panel-body">
                                         <div class="col-sm-10" style="padding: 3px;">
                                                <div class="well"><?php echo $msg['text']; ?></div>
                                                <?php  foreach ($adminmsgAry as $admin){
                                                if($msg['id'] == $admin['message_id']){ ?>
                                            <div class="panel panel-info">
                                            <div class="panel-heading">管理者からの返答</div>
                                                <div class="panel-body">
                                                <?php echo $admin['text'] ?>
                                                </div>
                                                    <?php } ?>
                                                    <?php } ?>
                                            </div>
                                        　</div>
    
                                        </div>
                                        </td>
                                    </tr>    
                                </tbody>
                        </table>
                        
                    
                        <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>
