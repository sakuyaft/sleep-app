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

        // if ($epochsData !== null) {
        //     return response()->json($epochsData[0]["depth"]);
        // }

        //睡眠深度ごとにグループ化
        $result = [];
        $cnt = 0;
        foreach ($epochsData as $index => $epochData) {

            //時間をUNIX*1000のデータ形式に変更
            $time = strtotime($epochData["analyzedAt"]) * 1000;
            $newDepth = $this->getDepth($epochData["depth"]);

            // 配列が作られていない場合は開始時間と睡眠深度をセットする
            if (empty($result[$cnt])) {
                $result[$cnt]['x'] = $time;
                $result[$cnt]['y'] = $newDepth;
            }
            // 終了時間は毎回更新する
            $result[$cnt]['x2'] = $time;

            if (!empty($epochsData[$index + 1]) && $newDepth !== $this->getDepth($epochsData[$index + 1]["depth"] )){
                $cnt++;
            }
        }

        Log::info('$result:', ['result' => $result]);

        return response()->json(['result' => $result, 'newSleepAt' => $newSleepAt, 'newWakeUpAt' => $newWakeUpAt]);
    }
}