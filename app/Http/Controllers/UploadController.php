<?php

namespace App\Http\Controllers;

use App\Models\TempFile;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(request $request)
    {
        if($request->hasFile('profile')) {
            $file = $request->file('profile');
            $clientName = $file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $clientName);
            $folder = uniqid() . '_' . now()->timestamp;
            $file->storeAs('profiles/tmp/' .$folder, $fileName);
            TempFile::create([
                'fileName' => $fileName,
                'folder' => $folder
            ]);

            return $folder;
        }

        return '';
    }
}
