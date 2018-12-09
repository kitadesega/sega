<div class="tab-pane" id="tabtest3">

    <div class="panel-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <!--
                        <th>タイトル</th>
                        <th>タグ</th>
                        <th>メディア</th>
                        <th>テキスト</th>
                        -->
                    </tr>
                </thead>
                <tbody>

                    <?php for ($i = 0; $i < $FavocheckCNT; $i++): ?>
                        <tr>
                            <td><?php echo $Favolist[$i]["title"]; ?></td>
                            <td><a class="btn btn-warning btn-sm iphone5" style = "border-radius: 60px;"href="user_search.php?kensaku=<?php echo $TAG ?>" role="button">
                                 <?php 
                                 //タグ番号を文字に変更
                                    $tagchain = $Favolist[$i]["tag_id"];
                                    $SqlRes = NGO("select * from tag where id = $tagchain");
                                        $row = $SqlRes->fetch(PDO::FETCH_ASSOC);
                                        echo $row['tag_name'];
                                    ?></a></td>
                            <td><img src="img/<?php echo $Favolist[$i]["media"]; ?>" width="100" /></td>
                            <td><?php echo $Favolist[$i]["text"]; ?></td>

                            <td>

                                <form method="post" action="home.php">
                                    <input type="hidden" name="anfavoID"  value="<?php echo $Favolist[$i]["id"]; ?>">
                                    <button class = "none"type="submit">
                                    <img src="svg/twitter-heartON.svg" width="32" height="32">
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="tab-pane" id="tabtest5">
    <div class="panel panel-info">
        <div class="panel-body">
            <?php if(isset($followerAry)):
            foreach ($followerAry as $follower): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-xs-2">なまえ</th>
                            <th class="col-xs-4">あいこん</th>
                            <th class="col-xs-6">ひとこと</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="afterU.php?Fuser=<?php echo $follower["user_id"]; ?>" class="widelink" ><?php print $follower["username"]; ?></a></td>
                            <td><img src="img/<?php print $follower["user_img"]; ?>" width="100" /></td>
                            <td><?php print $follower["hitokoto"]; ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; 
            endif;?>
        </div>
    </div>
</div>