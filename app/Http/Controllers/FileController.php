<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\TestFiles;
use Illuminate\Support\Facades\Storage;
use Jeffgreco13\FilamentBreezy\Models\BreezySession;

class FileController extends Controller
{
    public function show($uuid, $filename)
    {
        /**
         * Sanitize and validate the filename input.. For example, a user might try to access files they shouldn't by
         * using ../ sequences in the filename (known as a directory traversal attack).
         */
        $filename = htmlspecialchars($filename);

        $fileRecord = TestFiles::where('file_name', $filename)->first();

        // Initialize Storage
        $storage = Storage::disk(config('app.private_folder'));

        if (!$fileRecord || !$storage->exists($filename)) {
            abort(404);
        }

        // Dynamically get the model class from the file record
        $modelClass = $fileRecord->model_name;

        // Check if the class exists and if the user has the permission
        if (class_exists($modelClass)) {
            if ($fileRecord->file_uuid === $uuid) {

                $headers = [
                    'Content-Disposition' => 'inline; label=Test', // Use the original name for inline display
                ];
                // dd($storage->response($filename, 200, $headers));
                return $storage->response($filename, 200, $headers);
            }
        }

        abort(403); // Forbidden access
    }
}
