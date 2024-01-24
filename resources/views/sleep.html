<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ミリ波睡眠グラフ</title>
</head>

<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <h1>ミリ波睡眠グラフ</h1>
    <form name="inputForm" id="inputForm">
        <input type="file" name="fileInput" id="fileInput">
    </form>

    <figure class="highcharts-figure">
    </figure>

    <script>
        //時間をUNIX*1000のデータ形式に変更
        function getStrtotime(inputData) {
            let date = new Date(inputData);
            let newData = date.getTime();
            return newData;
        }

        //睡眠深度を数値に変換
        function getDepth(depth) {
            const depthMapping = {
                "awake": 0,
                "REM": 1,
                "light": 2,
                "deep": 3,
            };

            return depthMapping[depth];
        }

        let epochsDatas = '';
        let results = [];
        let chartCounter = 1;
        let newSleepAt = '';
        let newWakeUpAt = '';

        // ファイルの読み込みとオブジェクト形式に変換
        const form = document.forms.inputForm;
        form.fileInput.addEventListener('change', function(e) {
            const result = e.target.files[0];
            const reader = new FileReader();

            reader.readAsText(result);

            reader.addEventListener('load', function() {
                const jsonFile = JSON.parse(reader.result);
                console.log(jsonFile);

                // 外部のepochsDatasに値を設定
                epochsDatas = jsonFile["epochs"];
                console.log(epochsDatas);

                //睡眠開始と終了時刻の処理
                const sleepAt = getStrtotime(jsonFile["sleepAt"]);
                const wakeUpAt = getStrtotime(jsonFile["wakeUpAt"]);

                console.log(sleepAt);
                console.log(wakeUpAt);

                const sleepAtDate = new Date(sleepAt);
                const wakeUpDate = new Date(wakeUpAt);


                console.log(sleepAtDate);
                console.log(wakeUpDate);

                newSleepAt = sleepAtDate.getFullYear() + '/' + (sleepAtDate.getMonth() + 1) + '/' +
                    sleepAtDate.getDate() + ' ' + sleepAtDate.getHours() + ':' + sleepAtDate.getMinutes() + ':' +
                    sleepAtDate.getSeconds();

                newWakeUpAt = wakeUpDate.getFullYear() + '/' + (wakeUpDate.getMonth() + 1) + '/' +
                    wakeUpDate.getDate() + ' ' + wakeUpDate.getHours() + ':' + wakeUpDate.getMinutes() + ':' +
                    wakeUpDate.getSeconds();

                console.log(newSleepAt);
                console.log(newWakeUpAt);

                // 睡眠深度変換の処理と睡眠深度ごとにグループ化
                let results = [];
                let cnt = 0;

                epochsDatas.forEach(function(epochData, index) {
                    const time = getStrtotime(epochData["analyzedAt"]);
                    const newDepth = getDepth(epochData["depth"]);

                    // 配列が作られていない場合は開始時間と睡眠深度をセットする
                    if (!results[cnt]) {
                        results[cnt] = {
                            x: time,
                            y: newDepth
                        };
                    }

                    // 終了時間は毎回更新する
                    results[cnt].x2 = time;

                    // 次のデータがあり、睡眠深度が変わった場合は配列を1つ進める
                    if (epochsDatas[index + 1] && newDepth !== getDepth(epochsDatas[index + 1][
                            "depth"
                        ])) {
                        cnt++;
                    }

                });
                console.log(results);
                drawGraph(results);

            });
        });



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



            //睡眠開始表示の処理
            const sleepAttime = document.createElement('p');
            sleepAttime.id = 'sleep-at' + chartCounter;
            newContainer.appendChild(sleepAttime);
            document.getElementById(sleepAttime.id).innerHTML = "入眠: " + newSleepAt;

            //睡眠終了表示の処理
            const wakeUpTime = document.createElement('p');
            wakeUpTime.id = 'wakeup-at' + chartCounter;
            newContainer.appendChild(wakeUpTime);
            document.getElementById(wakeUpTime.id).innerHTML = "起床: " + newWakeUpAt;

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

            //グラフ描画の処理
            Highcharts.chart(newChart, {
                chart: {
                    type: 'xrange'
                },
                title: {
                    text: 'ミリ波睡眠グラフ'
                },

                time: {
                    useUTC: false,
                    timezone: 'Asia/Tokyo',
                },
                accessibility: {
                    point: {
                        descriptionFormat: '{add index 1}. {yCategory}, {x:%A %e %B %Y} to {x2:%A %e %B %Y}.'
                    }
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    categories: ['Awake', 'REM', 'Light', 'Deep'],
                    reversed: true
                },
                series: [{
                    name: 'グラフ' + chartCounter,
                    data: results,

                    borderColor: 'gray',
                    pointWidth: 40,
                    dataLabels: {
                        enabled: true
                    }
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

            });

            chartCounter++;
            const form = document.forms.inputForm;
            form.fileInput.value = '';

        }


        //削除処理の内容
        function deleteChart(chartCounter) {
            const containerToRemove = document.getElementById('container' + chartCounter);
            if (containerToRemove) {
                containerToRemove.remove();
            }
        }
    </script>
    <style>
        h1{
            text-align: center;
        }
        
        #inputForm {
            text-align: center;
        }


        div[id^="graph"] {
            width: 70%;
            height: 300px;
            margin: auto
        }

        p[id^="sleep-at"] {
            text-align: center;
        }

        p[id^="wakeup-at"] {
            text-align: center;
        }


        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 3000px;
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

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
</body>

</html>
