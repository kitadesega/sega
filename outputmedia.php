<?php include 'parts/header.php'; ?>

                                <div class="panel-body">
                                    <?php foreach ($followerAry as $follower): ?>
                                        <table class="table table-hover">
                                            <thead>
                                             <tr>
                                                    <th class="col-xs-4">なまえ</th>
                                                    <th class="col-xs-2">あいこん</th>
                                                    <th class="col-xs-6">ひとこと</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="afterU.php?Fuser=<?php echo $follower['user_id']; ?>" class="widelink" ><?php echo $follower['username']; ?></a></td>
                                                    <td><img src="img/<?php echo $follower['user_img']; ?>" width="100" /></td>
                                                    <td><?php echo $follower['hitokoto']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php endforeach; ?>
                                </div>

                        </div>