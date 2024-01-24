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
    <h1>ミリ波心拍数グラフ</h1>
    <form name="inputForm" id="inputForm">
        <input type="file" name="fileInput" id="fileInput">
    </form>

    <figure class="highcharts-figure"></figure>

    <script>
        //時間をUNIX*1000のデータ形式に変更
        function getStrtotime(inputData) {
            let date = new Date(inputData);
            let newData = date.getTime();
            return newData;
        }

        let values = [];
        let heartRateDatas = [];
        let newSleepAt = '';
        let newWakeUpAt = '';
        let averageRate = '';

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

                    console.log("firstSensingAt", firstSensingAt);
                    console.log("lastSensingAt", lastSensingAt);
                }

                const sleepAtDate = new Date(firstSensingAt);
                const wakeUpDate = new Date(lastSensingAt);

                console.log("sleepAtDate", sleepAtDate);
                console.log("wakeUpDate:", wakeUpDate);

                var YYYY = sleepAtDate.getFullYear(); //年を取得
                var MM = sleepAtDate.getMonth() + 1; //月を取得
                var DD = ("0" + sleepAtDate.getDate()).slice(-2); //日を取得
                var hh = ("0" + sleepAtDate.getHours()).slice(-2); //時間を取得
                var mm = ("0" + sleepAtDate.getMinutes()).slice(-2); //分を取得
                var ss = ("0" + sleepAtDate.getSeconds()).slice(-2); //秒を取得

                newSleepAt =  YYYY + "/" + MM + "/" + DD +" "+ hh +":"+ mm +":"+ ss; 

                var YYYY = wakeUpDate.getFullYear(); //年を取得
                var MM = wakeUpDate.getMonth() + 1; //月を取得
                var DD = ("0" + wakeUpDate.getDate()).slice(-2); //日を取得
                var hh = ("0" + wakeUpDate.getHours()).slice(-2); //時間を取得
                var mm = ("0" + wakeUpDate.getMinutes()).slice(-2); //分を取得
                var ss = ("0" + wakeUpDate.getSeconds()).slice(-2); //秒を取得

                newWakeUpAt = YYYY + "/" + MM + "/" + DD +" "+ hh +":"+ mm +":"+ ss;

                console.log('newSleepAt', newSleepAt);
                console.log('newWakeUpAt', newWakeUpAt);

                //心拍数データの取得・処理
                let results = [];

                values.forEach(value => {
                    const time = getStrtotime(value["sensingAt"]);
                    const average = value["heartRate"]["average"];

                    results.push([time, average]);

                });
                console.log(results);

                //心拍数平均算出
                let sum = 0;

                for (let i = 0; i < results.length; i++) {
                    sum += results[i][1];
                }
                data = sum / results.length
                averageRate = data.toFixed(0)
                console.log(averageRate);
                drawGraph(results);
            });
        });

        let chartCounter = 1;

        function drawGraph(results) {
            //新しいcontainerを作成
            const newContainer = document.createElement('div');
            newContainer.id = 'container' + chartCounter;
            const figureArea = document.querySelector('figure');
            figureArea.appendChild(newContainer);

            //新しいグラフを追加
            const newChart = document.createElement('div');
            newChart.id = 'graph' + chartCounter;
            newContainer.appendChild(newChart);

            // 新しい要素を作成して内容を追加
            const newSleepAtElement = document.createElement('div');
            newSleepAtElement.id = 'sleep-at' + chartCounter;
            newContainer.appendChild(newSleepAtElement);
            document.getElementById(newSleepAtElement.id).innerHTML = "入眠: " + newSleepAt;

            const newWakeUpAtElement = document.createElement('div');
            newWakeUpAtElement.id = 'wakeup-at' + chartCounter;
            newContainer.appendChild(newWakeUpAtElement);
            document.getElementById(newWakeUpAtElement.id).innerHTML = "起床: " + newWakeUpAt;

            //新しい要素をsleepAttimeに追加
            newContainer.appendChild(newSleepAtElement);
            newContainer.appendChild(newWakeUpAtElement);

            console.log("newSleepAt:", newSleepAt);
            console.log("newWakeUpAt:", newWakeUpAt);

            //心拍数平均追加
            const average = document.createElement('div');
            average.id = 'average' + chartCounter;
            newContainer.appendChild(average);
            // document.getElementById(average.id).innerHTML = "心拍数平均: " + averageRate;

            //メニューの日本語化
            Highcharts.setOptions({
                lang: {
                    viewFullscreen: '全画面で表示',
                    contextButtonTitle: '画像としてダウンロード',
                    printChart: 'グラフを印刷',
                    downloadJPEG: 'JPEG画像でダウンロード',
                    downloadPDF: 'PDF文書でダウンロード',
                    downloadPNG: 'PNG画像でダウンロード',
                    downloadSVG: 'SVG形式でダウンロード',
                }
            });

            Highcharts.chart(newChart, {

                title: {
                    text: 'ミリ波心拍数グラフ'
                },

                subtitle: {
                    text: '平均値 : ' + averageRate,
                    style: {
                        fontWeight: 'bold',
                        fontSize: '20px' 
                    }
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
                }],

                exporting: {
                    menuItemDefinitions: {
                        // Custom definition
                        lavel: {
                            onclick: function() {
                                // const chartId = this.id;
                                const containerElement = document.getElementById(newContainer.id);
                                if (containerElement) {
                                    containerElement.remove();
                                }
                            },
                            text: '削除'
                        }
                    },
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadSVG', 'separator', 'lavel'],
                        }
                    }
                }
            })

            chartCounter++;
            const form = document.forms.inputForm;
            form.fileInput.value = '';

            //削除処理の内容
            function deleteChart(chartCounter) {
                const containerToRemove = document.getElementById('container' + chartCounter);
                if (containerToRemove) {
                    containerToRemove.remove();
                }
            }
        }
    </script>

    <style>
        h1 {
            text-align: center;
        }

        div[id^="sleep-at"] {
            text-align: center;
        }

        div[id^="wakeup-at"] {
            text-align: center;
            margin-bottom: 20px;
        }

        #inputForm {
            text-align: center;
        }

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