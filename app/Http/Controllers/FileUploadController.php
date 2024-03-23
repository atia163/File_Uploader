<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FileUploadController extends Controller
{
    public function index()
    {
        return Inertia('UploadFile');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10 GB limit
        ]);

        $uniqueId = Str::uuid();

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('uploads');

        File::create([
            'unique_id' => $uniqueId,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect('/')->with('success', 'File uploaded successfully.');
    }
}

