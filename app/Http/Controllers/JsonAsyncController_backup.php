<?php

namespace App\Http\Controllers;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JsonAsyncController extends Controller
{
    public function process(Request $request)
    {
        if ($request === null) {
            return response()->json(['error' => '$request is null'], 400);
        }

        // 'JsonFile'キーの値（UploadedFileオブジェクト）を取得
        $uploadedFile = $request->file('JsonFile');

        // アップロードされたファイルの内容を取得
        $data = file_get_contents($uploadedFile->getRealPath());

        if ($data === null) {
            return response()->json(['error' => '$data is null'], 400);
        }

        $JsonData = json_decode($data, true);

        if ($JsonData === null) {
            return response()->json(['error' => '$JsonData is null'], 400);
        }

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

        //睡眠深度の値を変換
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

        //睡眠深度ごとにグループ化
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

        Log::info('Result:', ['result' => $result]);
        
        // echo json_encode($result);
        // return view("index", compact('result'));

        return response()->json(['result' => $result, 'newSleepAt' => $newSleepAt, 'newWakeUpAt' => $newWakeUpAt]);
    }
}
