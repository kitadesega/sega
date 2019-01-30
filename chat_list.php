<?php
header('Content-Type:text/html; charset=UTF-8');
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

require_once 'parts/function.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

$platform = '';
if (isset($_POST['platform'])) {
    $platform = $_POST['platform'];
}
    $SqlRes = NGO('select * from room_tbl');
    while ($Row = $SqlRes->fetch(PDO::FETCH_ASSOC)) {
        $tagN = $Row['tag_id'];
        $SqlRes2 = NGO("select * from tag where id = $tagN");
        $TagAry = $SqlRes2->fetch(PDO::FETCH_ASSOC);
        $tagName[] = $TagAry['tag_name'];
        $RowAry[] = $Row;
    }
    if (isset($RowAry)) {
        $NumRows = count($RowAry);
    }

if (!isset($NumRows)) {
    $NumRows = 0;
}

if (isset($RowAry)) {
    for ($i = 0; $i < $NumRows; ++$i) {
        $SEGA = ($RowAry[$i]['roomid']);
        $SqlRes = NGO("select count(roomid) from user_tbl where roomid = $SEGA;");
        $RowAry[$i]['room_list'] = $SqlRes->fetch(PDO::FETCH_ASSOC);
        $SqlRes2 = NGO("select * from room_tbl where roomid = $SEGA;");
        $RowAry[$i]['room_count'] = $SqlRes2->fetch(PDO::FETCH_ASSOC);
    }
}

?>
	<?php include 'parts/header.php'; ?>
	<style>
		.kadomaru{
		        border-radius: 10px;
		    }
		    
		    .uekadomaru{
		       border-radius: 10px 10px 0px 0px;
		    }
	</style>
	<body>
		<div class="container">
			<div class="text-center" style="padding-bottom:30px; <?php if (ua_smt() == true) { ?>margin-top:30px;<?php } ?>"> <a href="chat_search.php" class="btn btn-primary btn-lg">グループ検索はこちら</a>
				<?php if (ua_smt() == true) { ?>
                <br/><br/>
				<?php } ?>
                <a href="chat_edit.php" class="btn btn-success btn-lg">グループ作成はこちら</a>
            </div>
			<div class="col-xs-12">
				<?php for ($i = 0; $i < $NumRows; ++$i) { ?>
				<?php if (ua_smt() == true) { ?>
				<div class="col-xs-12">
					<div class="panel panel-default kadomaru">
						<div class="panel-heading uekadomaru">
							<h3 class="panel-title text-center">
								<?php echo $RowAry[$i]['roomname']; ?>
							</h3>
							<h3 class="panel-title text-center" style="font-size:16px"><span style="color:red;"><?php echo $RowAry[$i]["room_list"]["count(roomid)"] ?></span>/
								<?php if ($Row2['limit'] == $Row['count(roomid)']) { ?>
                                <span style="color:green;"><?php
                            } else { ?>
                            <span style="color:red;">
                            <?php } ?> <?= $RowAry[$i]["limit"] ?></span> </h3>
						</div>
						<div class="panel-body">
							<form style="display: inline" method="post" action="chatin.php"> <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>"> <input type="submit" class="btn btn-info" value="入室"> </form>
							<?php if ($RowAry[$i]['edituser'] == $_SESSION['userN']) {?>
							    <form style="display: inline" method="post" action="chat_edit.php"> <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>"> <input type="submit" class="btn btn-danger" value="削除"> </form>
							<?php } ?>
								<a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="" role="button">
									<?php echo $tagName[$i]; ?> </a>
						</div>
						<div class="panel-body">
							<a style="font-size:12px;">
								<?php echo $RowAry[$i]['text']; ?>
							</a>
						</div>
					</div>
				</div>
				<?php
        } else {
            ?>
					<div class="col-xs-3">
						<div class="panel panel-default kadomaru">
							<div class="panel-heading uekadomaru">
								<h3 class="panel-title text-center">
									<?php echo $RowAry[$i]['roomname']; ?>
								</h3>
								<h3 class="panel-title text-center" style="font-size:16px"><span style="color:red;"><?php echo $RowAry[$i]["room_list"]["count(roomid)"] ?></span>/
									<?php if ($Row2['limit'] == $Row['count(roomid)']) {
                                    ?> <span style="color:green;"><?php
                                } else { ?><span style="color:red;"><?php } ?> <?= $RowAry[$i]["limit"] ?></span> 
                                </h3>
							</div>
							<div class="panel-body">
								<form style="display: inline" method="post" action="chatin.php"> <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>"> <input type="submit" class="btn btn-default" value="入室"> </form>
								<?php if ($RowAry[$i]['edituser'] == $_SESSION['userN']) {?>
								<form style="display: inline" method="post" action="chat_edit.php"> <input type="hidden" name="chatid" value="<?php echo $RowAry[$i]['roomid']; ?>"> <input type="submit" class="btn btn-danger" value="削除"> </form>
								<?php } ?>
									<a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="" role="button">
										<?php echo $tagName[$i]; ?> </a>
							</div>
							<div class="panel-body">
								<a style="font-size:12px;">
									<?php echo $RowAry[$i]['text']; ?>
								</a>
							</div>
						</div>
					</div>
					<?php } ?>
						<?php } ?>
			</div>
		</div>
		</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</body>
	</html>