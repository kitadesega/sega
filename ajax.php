
        <?php
        if (!isset($_SESSION)) {
            session_start();
        }
        require_once 'parts/function.php';
        $chatid = $_SESSION['chatid'];
//$chatid = $_GET["chatid"];
//}

        date_default_timezone_set('Asia/Tokyo'); //タイムゾーンを「アジア／東京」に設定している
        $year = date('Y年');
        $moon = date('m月');
        $day = date('d日');
        $week = array('日', '月', '火', '水', '木', '金', '土');
        $week = '（'.$week[date('w')].'）';
        $hour = date('H時');
        $minute = date('i分');
        $second = date('s秒');
        $date = $year.$moon.$day.$week.$hour.$minute.$second;

        $SqlRes = NGO("select * from chat_tbl where roomid = '{$chatid}' order by dataTime desc;");
while ($row = $SqlRes->fetch(PDO::FETCH_ASSOC)): ?>
<!doctype html>
<html>
    <body>
    <div id="ajaxreload">
        <?php if (($row['user_id']) == ($_SESSION['user'])): ?>
            <div class="line__right">
            <?php else: ?>
            <div class="line__left">
                <figure class="chat">
                    <img src="img/<?= $row['img']; ?>" />
                </figure>
            <div class="line__left-text">
            <div class="name"><a class = "unko"><?php echo htmlspecialchars($row['handle'], ENT_QUOTES, 'UTF-8'); ?></a></div>
        <?php endif; ?>
        <div class="text"><a class = "unko"><?php echo htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'); ?></a></div></div>
    </div>
<?php endwhile; ?>
    </body>
</html>