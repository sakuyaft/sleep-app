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

    <form action="{{ route('json_process') }}" method="POST" enctype="multipart/form-data" id="json-upload-file">
        @csrf
        <input type="file" name="jsonFile">
        <button type="submit">jsonアップロード</button>
    </form>

    <p>
        @if (isset($newSleepAt))
            睡眠開始時刻：{{ $newSleepAt }}
        @endif
        <br>
        @if (isset($newWakeUpAt))
            睡眠終了時刻：{{ $newWakeUpAt }}
        @endif
    </p>

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>

    <script>
        var chartData = @json($result ?? '');
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
                data: chartData,

                borderColor: 'gray',
                pointWidth: 40,
                dataLabels: {
                    enabled: true
                }
            }]

        });
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
