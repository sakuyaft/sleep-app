<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ミリ波グラフ</title>
</head>

<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    {{-- <script src="https://code.highcharts.com/modules/export-data.js"></script> --}}

    <form enctype="multipart/form-data">
        @csrf
        <input type="file" id="JsonFile" name="JsonFile">
        <button type="button" onclick="jsonUpload()">jsonアップロード</button>
    </form>
    {{-- 
    <div id="container">
        <figure class="highcharts-figure">
            <div id="graph"></div>
            <p id="sleep-at"></p>
            <p id="wakeup-at"></p>
            <button id="delete">削除する</button>
        </figure>
    </div> --}}

    <script>
        //カウンターセット
        let chartCounter = 1;

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

        function jsonUpload() {

            const jsonFileInput = document.getElementById('JsonFile');
            const fd = new FormData();
            fd.append('JsonFile', jsonFileInput.files[0]);


            fetch('{{ route('json_async') }}', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then((response) => {
                    if (!response.ok) {
                        console.log('error!');
                    } else {
                        console.log('ok!')
                    };
                    return response.json();

                })
                .then((data) => {
                    console.log(data);

                    //新しいcontainerを作成
                    const newContainer = document.createElement('div');
                    newContainer.id = 'container' + chartCounter;
                    document.body.appendChild(newContainer);

                    //新しいグラフを追加
                    const newChart = document.createElement('div');
                    newChart.id = 'graph' + chartCounter;
                    newContainer.appendChild(newChart);

                    //グラフ描画の処理
                    Highcharts.chart(newChart.id, {
                        chart: {
                            type: 'xrange'
                        },
                        title: {
                            text: '睡眠グラフ'
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
                            data: data.result,

                            borderColor: 'gray',
                            pointWidth: 40,
                            dataLabels: {
                                enabled: true
                            }
                        }]
                    });

                    //睡眠開始表示の処理
                    const sleepAttime = document.createElement('p');
                    sleepAttime.id = 'sleep-at' + chartCounter;
                    newContainer.appendChild(sleepAttime);
                    document.getElementById(sleepAttime.id).innerHTML = "睡眠開始: " + data.newSleepAt;

                    //睡眠終了表示の処理
                    const wakeUpTime = document.createElement('p');
                    wakeUpTime.id = 'wakeup-at' + chartCounter;
                    newContainer.appendChild(wakeUpTime);
                    document.getElementById(wakeUpTime.id).innerHTML = "睡眠終了: " + data.newWakeUpAt;

                    //削除ボタンを作成
                    const newBtn = document.createElement('button');
                    newBtn.id = 'delete' + chartCounter;
                    newContainer.appendChild(newBtn);
                    document.getElementById(newBtn.id).innerHTML = '削除';

                    // 削除ボタンにクリックイベントを追加
                    newBtn.addEventListener('click', function() {
                        const index = newBtn.id.replace('delete', '');
                        deleteChart(index);
                    });

                    chartCounter++;
                })
                .catch(error => {
                    console.log(error);
                })
        };

        //削除処理の内容
        function deleteChart(chartCounter) {
            const containerToRemove = document.getElementById('container' + chartCounter);
            if (containerToRemove) {
                containerToRemove.remove();
            }
        }

    </script>
    <style>
        #graph {
            width: 100%;
            height: 300px;
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
