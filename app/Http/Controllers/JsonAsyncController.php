<?php

namespace App\Http\Controllers;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JsonAsyncController extends Controller
{

    protected function getDepth($depthKey)
    {
        $depth = [
            "awake" => 0,
            "REM" => 1,
            "light" => 2,
            "deep" => 3
        ];

        return $depth[$depthKey] ?? null;
    }
    
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

        // if ($data !== null) {
        //     return response()->json($data);
        // }
        
        //jsonをデコード
        $JsonData = json_decode($data, true);

        if ($JsonData === null) {
            return response()->json(['error' => '$JsonData is null'], 400);
        }

        //睡眠開始と終了時刻表示の処理
        $sleepAt = $JsonData["sleepAt"];
        $wakeUpAt = $JsonData["wakeUpAt"];

        $sleepAt = strtotime($sleepAt);
        $wakeUpAt = strtotime($wakeUpAt);

        $newSleepAt = date("Y/m/d H:i:s", $sleepAt);
        $newWakeUpAt = date("Y/m/d H:i:s", $wakeUpAt);

        //睡眠深度表示の処理
        $epochsData = $JsonData["epochs"];

        //protectedで定義したデータを使用して、$epochsDataのdepthの値を変換する処理
        //$rowsに$newTime, $newDepthを返却

        $rows = array_map(function($data){
            $timestamp = strtotime($data["analyzedAt"]);
            $newTime = date("Y/m/d H:i:s", $timestamp);

            $newDepth = $this->getDepth($data["depth"]);

            return [$newTime, $newDepth];

        }, $epochsData);



        // $rows = array_map(function($epoch){
        //     $timestamp = strtotime($epoch["analyzedAt"]);
        //     $newTime = date("Y/m/d H:i:s", $timestamp);

        //     switch ($epoch["depth"]){
        //         case "awake":
        //             $newDepth = "0";
        //             break;
        //         case "REM":
        //             $newDepth = "1";
        //             break;
        //         case "light":
        //             $newDepth = "2";
        //             break;
        //         case "deep":
        //             $newDepth = "3";
        //             break;
        //         default:
        //             $newDepth = null;
        //     }

        //     return [$newTime, $newDepth];
        // }, $epochsData);

        // $rows = [
        //     ["2023/01/01 12:00:00", 0], key = 1
        //     ["2023/01/01 12:30:00", 0], key = 2
        //     ["2023/01/01 13:00:00", 1], key = 3
        //     ["2023/01/01 14:00:00", 1], key = 4 
        //     ["2023/01/01 15:00:00", 0], key = 5
        //     ["2023/01/01 15:30:00", 0], key = 6
        // ];


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

        Log::info('$result:', ['result' => $result]);

        return response()->json(['result' => $result, 'newSleepAt' => $newSleepAt, 'newWakeUpAt' => $newWakeUpAt]);
    }
}
