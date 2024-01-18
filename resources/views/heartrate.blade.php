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

                //ループを使用して[sensingAt, average]の配列を作成する
                // const newArray = values.map(item => [item.sensingAt, item.heartRate.average]);
                // console.log(newArray);

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

            Highcharts.chart('container', {

                title: {
                    text: '心拍数グラフ'
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
        // (async () => {

        //     const data = await fetch(
        //         'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v10.3.3/samples/data/usdeur.json'
        //     ).then(response => response.json());

        //     Highcharts.chart('container', {

        //         title: {
        //             text: '心拍数グラフ'
        //         },

        //         xAxis: {
        //             type: 'datetime'
        //         },
        //         yAxis: {
        //             title: {
        //                 text: '心拍数'
        //             }
        //         },
        //         series: [{
        //             // type: 'line',
        //             // name: 'USD to EUR',
        //             data: data
        //         }]
        //     });
        // })();
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