<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function process(Request $request)
    {

        $file = $request->file("jsonFile");
        $JsonData = file_get_contents($file->getRealPath());
        //インポートしたjsonデータを新しいデータ形式に変更
        $JsonData = json_decode($JsonData, true);

        $epochsData = $JsonData["epochs"];

        //睡眠開始と終了時刻の処理
        $sleepAt = $JsonData["sleepAt"];
        $wakeUpAt = $JsonData["wakeUpAt"];

        $sleepAt = strtotime($sleepAt);
        $wakeUpAt = strtotime($wakeUpAt);

        $newSleepAt = date("Y/m/d H:i:s", $sleepAt);
        $newWakeUpAt = date("Y/m/d H:i:s", $wakeUpAt);


        //新しいデータを格納する配列
        $rows = [];

        foreach ($epochsData as $epoch) {
            $analyzedAt = $epoch["analyzedAt"];
            $depth = $epoch["depth"];

            $timestamp = strtotime($analyzedAt);
            $newTime = date("Y/m/d H:i:s", $timestamp);

            switch ($depth) {
                case "awake":
                    $newDepth = "0";
                    break;
                case "REM":
                    $newDepth = "1";
                    break;
                case "light":
                    $newDepth = "2";
                    break;
                case "deep":
                    $newDepth = "3";
                    break;
            }

            $rows[] = [$newTime, $newDepth];
        };

        $result = [];
        $cnt = 0;
        foreach ($rows as $key => $row) {
            // 配列が作られていない場合は開始時間と睡眠深度をセットする
            if (empty($result[$cnt])) {
                $result[$cnt]['x'] = strtotime($row[0]) * 1000;
                $result[$cnt]['y'] = (int)$row[1];
            }
            // 終了時間は毎回更新する
            $result[$cnt]['x2'] = strtotime($row[0]) * 1000;

            // 次のデータがあり、睡眠深度が変わった場合は配列を1つ進める
            if (!empty($rows[$key + 1]) && ($result[$cnt]['y'] !== (int)$rows[$key + 1][1])) {
                $cnt++;
            }
        }
        // dd($result);
        return view("index", compact('result', 'newSleepAt','newWakeUpAt'));
    }
}

//日付を表示
//非同期通信

