<div class="tab-pane" id="tabtest3">
<div class="twitter__container">
                            <br />
                                <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
                                <div class="twitter__contents scroll">
                                    <?php
                                    if (isset($Favotweet)):
                                        foreach ($Favotweet as $tweet): ?>

                                    <?php if($tweet['file']!="" || $tweet['file']!=NULL){ ?>
                                    <table cellpadding="3" cellspacing="0">
                                        <tbody>
                                            <!-- 記事エリア -->
                                            <div class="twitter__block">
                                                <figure>
                                                    <img src="img/<?php echo $tweet['img']; ?>" class="img-circle" width="30" />
                                                </figure>
                                                <div class="twitter__block-text">
                                                    <div class="name">
                                                        <?php echo $tweet['handle']; ?>
                                                        <span class="name_reply"><?php if($tweet['tag_name']!=NULL){ ?>
                                                            <a class="btn btn-warning btn-sm iphone5" style="border-radius: 60px;" href="user_search.php" role="button">
                                                                <?php echo $tweet['tag_name']; ?>
                                                            </a>
                                                            <?php }?>
                                                        </span>
                                                    </div>
                                                    <div class="date">10分前</div>
                                                    <div class="text">
                                                        <?php echo $tweet['message']; ?><br/>
                                                        <a href="img/<?php echo $tweet['file']; ?>" data-lightbox="<?php echo $tweet['file']; ?>">
                                                            <img src="img/<?php echo $tweet['file']; ?>"width="100px">
                                                        </a>
                                                            
                                                    </div>
                                                    <div class="twitter__icon item<?PHP echo $tweet['id']; ?> <?PHP echo $tweet['favo_flg'] ? "on" : "off"; ?>" style="margin-top:5px;float:left;" onclick="toggleBookmark(<?PHP echo $tweet['id']; ?>,<?PHP echo $tweet['user_id']; ?>); return false;">
                                                        <?PHP if($tweet['favo_flg'] == false){ ?>
                                                            <span class="twitter-heart" ></span>
                                                        <?php }else{ ?>
                                                            <span class="twitter-heartON"></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div align="right">
                                                        <span class="com_foot"> ...
                                                            <?php echo $tweet['dataTime']; ?></span>
                                                        <?php if ($tweet['user_id'] == $user): //投稿が自分のかどうか?>
                                                        [<a href="home.php?Auser=<?php echo $tweet['id']; ?>">削除</a>]
                                                        <?php endif; //投稿が自分のかどうか判定終わり?>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php }?>
                                    <?php
                                        endforeach; // foreach ($tweetAry as $tweet):
                                    endif; //if (isset($tweetAry)):
                                    ?>
                                </div>
                            </div>
</div>
