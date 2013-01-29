var dateFormat = new DateFormat('yyyy/MM/dd');
var date = '';

$(function() {
    // 初期表示・再描画時は当日の温湿度データ
    date = dateFormat.format(new Date());
    
    // 温湿度グラフを描画
    draw();
});

/**
 * 温湿度グラフを描画します。
 */
function draw() {
    // 温湿度グラフのタイトルを設定
    TempGraph.setTitle(date.split('/')[0] + '年' +  date.split('/')[1] + '月' + date.split('/')[2] + '日');
    
    // 温湿度データを取得
    var thermoHygro = getThermoHygro(date);
    if (thermoHygro['themo'] == '') { // 温湿度データ取得失敗
        // 温湿度グラフをクリア
        clearTempLogger();
        return;
    }
    
    // 温湿度グラフを表示
    TempGraph.show('linechart', 13,  [thermoHygro['thermo'], thermoHygro['hygro']]);
}

/**
 * 温湿度データ・センサー情報をクリアします。
 */
function clearTempLogger() {
    // 温湿度グラフ名を設定
    TempGraph.setTitle(date.split('/')[0] + '年' +  date.split('/')[1] + '月' + date.split('/')[2] + '日');

    // 空のグラフを描画
    TempGraph.show('linechart', 13, [[{}], [{}]]);
}

/**
 * 温湿度データを返します。
 */
function getThermoHygro(date) {
    var thermoHygro = [];
    $.ajax({
        type: 'POST',
        url: '../php/getThermoHygro.php',
        async: false,
        data: {'date' : date},
        dataType: 'json'
    }).done(function (json) {
        var num = json.length;
        var thermo = [];
        var hygro = [];
        for (var i = 0; i < num; i++) {
            thermo.push([json[i].time, json[i].thermo]);
            hygro.push([json[i].time, json[i].hygro]);
        }
        thermoHygro['thermo'] = thermo;
        thermoHygro['hygro'] = hygro;
    }).fail(function (data) {
        alert('温湿度データの取得に失敗しました。');
    });
    return thermoHygro;
}