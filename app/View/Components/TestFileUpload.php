<?php

namespace App\View\Components;

use App\Models\TestFiles;
use Closure;
use Filament\Forms\Components\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Filament\Forms\Components\BaseFileUpload;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;
class TestFileUpload extends FileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->getUploadedFileUsing(static function (BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array {
            $storage = Storage::disk(config('app.private_folder'));

            $shouldFetchFileInformation = $component->shouldFetchFileInformation();

            if ($shouldFetchFileInformation) {
                try {
                    if (!$storage->exists($file)) {
                        return null;
                    }
                } catch (UnableToCheckFileExistence $exception) {
                    return null;
                }
            }

            $url = null;

            if ($component->getVisibility() === 'private') {
                try {
                    $url = $storage->temporaryUrl(
                        $file,
                        now()->addMinutes(5),
                    );
                } catch (Throwable $exception) {
                    // This driver does not support creating temporary URLs.
                }
            }

            $fileRecord = TestFiles::where('file_name', $file)->first();

            $uuid = $fileRecord->file_uuid;

            $url ??= config('app.storage') . $uuid . '/' . $file;

            return [
                'name' => "test",
                'size' => $shouldFetchFileInformation ? 3 : 0,
                'type' => $shouldFetchFileInformation ? $storage->mimeType($file) : null,
                'url' => $url,
            ];
        });

        $this->getUploadedFileNameForStorageUsing(

            // Flle name is generated randomly and checked if it exists in the database (unique)
            function () {
                $randomHex = null;
                do {
                    $randomHex = bin2hex(random_bytes(16));
                } while (TestFiles::where('file_name', $randomHex)->first());

                return $randomHex;
            }
        );
    }
}
