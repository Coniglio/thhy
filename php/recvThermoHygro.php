<?php
    setlocale( LC_ALL, 'ja_JP.UTF-8' );
    date_default_timezone_set('Asia/Tokyo');

    $kind = $_POST['kind'];
    $no = $_POST['no'];
    $thermo = $_POST['thermo'];
    $hygro = $_POST['hygro'];

    echo $kind . ', ' . $no . ', ' . $thermo . ',' . $hygro . PHP_EOL;

    $time = time();
    $filePath = '../log/tempLog/' . date() . '/1-' . $month . $day . '.csv';

    // 温湿度データファイルが無ければ作成
    if (! file_exists($filePath)) {
        $contens = touch($filePath);
    }

    $fp = fopen($filePath, 'a');
    if (flock($fp, LOCK_EX)) {
        fwrite($fp, $date . ',' . $thermo . ',' . $hygro);
    }

    echo '200 OK' . PHP_EOL;
?>