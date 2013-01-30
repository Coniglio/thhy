<?php
    setlocale( LC_ALL, 'ja_JP.UTF-8' );
    date_default_timezone_set('Asia/Tokyo');

    // 温湿度データの取得
    $kind = $_POST['kind'];
    $no = $_POST['no'];
    $thermo = $_POST['thermo'];
    $hygro = $_POST['hygro'];
    
    //$kind = 1;
    //$no = 1;
    //$thermo = 26.5;
    //$hygro = 49.3;
    $date = date('Y-m-d H:i:s');
    echo $date . ', ' . $kind . ', ' . $no . ', ' . $thermo . ', ' . $hygro . PHP_EOL;

    $server = 'mysql448.db.sakura.ne.jp';
    $mydb = 'thhy_thhy';
    $usr = 'thhy';
    $pass = 't90thbnth';
    $query = "insert into thermo_hygro values($kind,$no,$thermo,$hygro,'$date')";

    // DB接続
    $con = mysql_connect($server, $url, $pass);
    if (! $con) {
        die('cannot connnect: ' . mysql_error());
    }

    // テーブル選択
    $db = mysql_select_db($mydb, $con);
    if (! $db) {
        die("cannot use $mydb" . mysql_error());
    }

    // データを追加
    $result = mysql_query($query);
    if (! $result) {
        die('insert失敗: ' . mysql_error());
    }

    // メモリ解放
    mysql_free_result($result);

    // DBをクローズ
    mysql_close($con);

    header("HTTP/1.0 200 OK", FALSE);