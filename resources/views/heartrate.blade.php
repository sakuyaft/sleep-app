<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ミリ波心拍数グラフ</title>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>

<body>
    <form name="inputForm">
        <input type="file" name="fileInput" id="fileInput">
    </form>

    <figure class="highcharts-figure">
        <div id="container"></div>
        <div id="sleepAttime"></div>
        <div id="wakeUpTime"></div>
    </figure>

    <script>
        //時間をUNIX*1000のデータ形式に変更
        function getStrtotime(inputData) {
            let date = new Date(inputData);
            let newData = date.getTime();
            return newData;
        }

        let values = [];
        let heartRateDatas = [];

        // ファイルの読み込み
        const form = document.forms.inputForm;
        form.fileInput.addEventListener('change', function(e) {
            const result = e.target.files[0];
            const reader = new FileReader();

            reader.readAsText(result);

            reader.addEventListener('load', function() {
                //オブジェクト形式に変換
                const jsonFile = JSON.parse(reader.result);
                console.log(jsonFile);

                // resultsに値を設定
                values = jsonFile["values"];
                console.log(values);

                //睡眠開始終了時間の取得・処理
                let firstSensingAt, lastSensingAt;
                if (values.length > 0) {
                    firstSensingAt = getStrtotime(values[0]["sensingAt"]);
                    lastSensingAt = getStrtotime(values[values.length - 1]["sensingAt"]);

                    // const firstSensingAt = values[0]["sensingAt"];
                    // const lastSensingAt = values[values.length - 1]["sensingAt"];
                    console.log("firstSensingAt", firstSensingAt);
                    console.log("lastSensingAt", lastSensingAt);
                }

                const sleepAtDate = new Date(firstSensingAt);
                const wakeUpDate = new Date(lastSensingAt);

                console.log("sleepAtDate", sleepAtDate);
                console.log("wakeUpDate:", wakeUpDate);

                newSleepAt = sleepAtDate.getFullYear() + '/' + (sleepAtDate.getMonth() + 1) + '/' +
                    sleepAtDate.getDate() + ' ' + sleepAtDate.getHours() + ':' + sleepAtDate.getMinutes() + ':' +
                    sleepAtDate.getSeconds();

                newWakeUpAt = wakeUpDate.getFullYear() + '/' + (wakeUpDate.getMonth() + 1) + '/' +
                    wakeUpDate.getDate() + ' ' + wakeUpDate.getHours() + ':' + wakeUpDate.getMinutes() + ':' +
                    wakeUpDate.getSeconds();

                console.log(newSleepAt);
                console.log(newWakeUpAt);

                //心拍数平均データの取得・処理
                let results = [];

                values.forEach(value => {
                    const time = getStrtotime(value["sensingAt"]);
                    const average = value["heartRate"]["average"];

                    results.push([time, average]);

                });
                console.log(results);

                drawGraph(results);
            });
        });

        function drawGraph(results) {
            //睡眠開始と終了の時間表示
            const sleepAttime = document.getElementById('sleepAttime');
            const wakeUpTime = document.getElementById('wakeUpTime');
            
            // 新しい要素を作成して内容を追加
            const newSleepAt = "2024-01-19 12:34:56";
            const newSleepAtElement = document.createElement('div');
            newSleepAtElement.innerHTML = "睡眠開始: " + newSleepAt;

            //新しい要素をsleepAttimeに追加
            sleepAttime.appendChild(newSleepAtElement);

            console.log("newSleepAt:", newSleepAt);

            Highcharts.chart('container', {

                title: {
                    text: '心拍数グラフ'
                },

                time: {
                    useUTC: false,
                    timezone: 'Asia/Tokyo',
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    title: {
                        text: '心拍数'
                    }
                },
                series: [{
                    // type: 'line',
                    // name: 'USD to EUR',
                    data: results
                }]
            })
        }
    </script>

    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 360px;
            max-width: 800px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }
    </style>

</body>

</html>