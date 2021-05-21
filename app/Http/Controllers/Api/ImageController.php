<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\PersonFile;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function get_list_image()
    {
        return response()->json(File::select(['path','id'])->where('type', 'image')->with('persons')->get());
    }
}
