<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Image\Image;


class WaterMarkController extends Controller
{
    public function addWaterMark(Request $request)
    {
        $request->validate([
            'fileimage' => 'required|image|mimes:jpeg,png,jpg',
            'message' => 'required|string|min:10|max:20',
        ]);

        $image = $request->file('fileimage');
        $id = substr(uniqid(),2,13) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('uploads', $id, 'public');
        $getFullPath = storage_path('app/public/' . $path);

        $message = $request->message;


        $img = Image::load($getFullPath);
        $img->text($message, fontSize: 24, color: "#ffffff", x: 25, y: 50);

        $img->save($getFullPath);

        return response()->download($getFullPath)->deleteFileAfterSend(true);
    }
}
