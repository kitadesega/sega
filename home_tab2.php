<div class="tab-pane" id="tabtest2">
<div class="panel panel-info"> 
                                <div class="panel-body">

                                   <?php if(isset($followAry)){
                                       foreach ($followAry as $follow): ?>
                                        <table class="table table-hover" >
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-4">なまえ</th>
                                                    <th class="col-xs-2">あいこん</th>
                                                    <th class="col-xs-6">ひとこと</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="afterU.php?Fuser=<?php echo $follow["user_id"]; ?>" class="widelink" ><?php print $follow["username"]; ?></a></td>
                                                    <td><img src="img/<?php print $follow["user_img"]; ?>" width="100" /></td>
                                                    <td><?php print $follow["hitokoto"]; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endforeach;
                                   }?>
                                </div>
                           </div>
                        </div>