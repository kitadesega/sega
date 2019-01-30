<div id="ajaxreloada"><?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once( 'parts/function.php' );

    $chatid = $_SESSION['chatid'];
    $SqlRes = NGO("select * from user_tbl where roomid = '{$chatid}' ");

    $RowAry[] = 0;
    $NumRows = 0;

    while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {

        $RowAry[] = $Row;
    }

    $NumRows = count($RowAry);
    ?>

    <a style ="font-size:16px;">入室者一覧：<span style="color:red;">
    <?php if (ua_smt() == true) { ?>
            <?php
            if ($NumRows >= 0) {
                for ($i = 1; $i < $NumRows; $i++) {
                    ?>

                    <form method="post" style="display: inline" name="form1" action="afterU.php" target="_blank">
                        <input type="hidden" name="Fuser" value="<?php echo $RowAry[$i]["user_id"]; ?>"></a>
                        <input type="hidden" name="chatname" value="<?php echo $chatname ?>">
                        <?php if ($i != 0) { ?>
                            <a class="square_btn" user_id="2" href="javascript:form1[<?php echo $i; ?>].submit()" target="_blank">
                                <i class="fa fa-caret-right low"></i><?php echo $RowAry[$i]["handle"]; ?>
                            </a><?php } ?>
                    </form>

                    <?php
                }
            }
            ?>
    <?php }else{ ?>
        <?php
            if ($NumRows >= 0) {
                for ($i = 0; $i < $NumRows; $i++) {
                    ?>

                    <form method="post" style="display: inline" name="form1" action="afterU.php" target="_blank">
                        <input type="hidden" name="Fuser" value="<?php echo $RowAry[$i]["user_id"]; ?>"></a>
                        <input type="hidden" name="chatname" value="<?php echo $chatname ?>">
                        <?php if ($i != 0) { ?>
                            <a class="square_btn" user_id="2" href="javascript:form1[<?php echo $i; ?>].submit()" target="_blank">
                                <i class="fa fa-caret-right low"></i><?php echo $RowAry[$i]["handle"]; ?>
                            </a><?php } ?>
                    </form>

                    <?php
                }
            }
            ?>
    <?php } ?></span><br />
        <a style ="font-size:16px;">入室者数：<?php
            $SqlRes = NGO("select count( roomid ) from user_tbl where roomid = $chatid");

            $Row = $SqlRes->fetch(PDO::FETCH_ASSOC)
            ?><span style="color:red;"><?= $Row["count( roomid )"] ?></span></a></div>