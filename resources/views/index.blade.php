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

    <form enctype="multipart/form-data">
        @csrf
        <input type="file" id="JsonFile" name="JsonFile">
        <button type="button" onclick="jsonUpload()">jsonアップロード</button>
    </form>

    <p id="sleep-at"></p>
    <p id="wakeup-at"></p>

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>

    <script>
        function jsonUpload() {

            const jsonFileInput = document.getElementById('JsonFile');
            const fd = new FormData();
            fd.append('JsonFile', jsonFileInput.files[0]);
            console.log(fd);


            fetch('{{ route('json_async') }}', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then((response) => {
                    if (!response.ok) {
                        console.log('error!!');
                    } else {
                        console.log('ok!!')
                    };
                    return response.json();

                })
                .then((data) => {
                    console.log(data);

                    //グラフ描画の処理
                    Highcharts.chart('container', {
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
                            name: 'Project 1',
                            data: data.result,

                            borderColor: 'gray',
                            pointWidth: 40,
                            dataLabels: {
                                enabled: true
                            }
                        }]
                    });
                        //睡眠開始と終了時間表示の処理
                        document.getElementById("sleep-at").innerHTML = "睡眠開始: " + data.newSleepAt;
                        document.getElementById("wakeup-at").innerHTML = "睡眠終了: " + data.newWakeUpAt;

                })
                .catch(error => {
                    console.log(error);
                })
        };
    </script>
    <style>
        #container {
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
