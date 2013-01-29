<?php
    setlocale( LC_ALL, 'ja_JP.UTF-8' );
    date_default_timezone_set('Asia/Tokyo');
    
    $date = $_POST['date'];
    list($year, $month, $day) = explode('/', $date);
    
    $filePath = '../log/tempLog/' . $year . '/1-' . $month . $day . '.csv';
    if (! file_exists($filePath)) {
        echo json_encode(array(array('time' => '', 'thermo' => '', 'hygro' => '')));
        return;
    }
    
    $fp = fopen($filePath, 'r');
    if (flock($fp, LOCK_SH)) {
        $array = array();
        while ($data = fgetcsv($fp)) {
            // CSVには日付を予め書き込んでおり、
            // 温度が空であれば温湿度データ無しとして次の参照する。
            if (empty($data[1])) {
                continue;
            }
            
            $array[] = array('time' => $data[0], 'thermo' => $data[1], 'hygro' => $data[2]);
        }
        flock($fp, LOCK_UN);
    } else {
        fclose($fp);
        echo json_encode(array(array('time' => '', 'thermo' => '', 'hygro' => '')));
        return;
    }
    fclose($fp);
    
    echo json_encode($array);
?>