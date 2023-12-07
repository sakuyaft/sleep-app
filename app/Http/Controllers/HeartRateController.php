<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeartRateController extends Controller
{
    public function process (Request $request){
        
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

        if ($data !== null) {
            return response()->json($data);
        }
        
        //jsonをデコード
        $JsonData = json_decode($data, true);

        if ($JsonData === null) {
            return response()->json(['error' => '$JsonData is null'], 400);
        }

        //睡眠開始と終了時刻表示の処理


        return response()->json(['result' => $result, 'newSleepAt' => $newSleepAt, 'newWakeUpAt' => $newWakeUpAt]);

    }
}
