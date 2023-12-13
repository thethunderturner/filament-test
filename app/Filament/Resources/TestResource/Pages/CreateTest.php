<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use App\Models\TestFiles;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Ramsey\Uuid\Uuid;

class CreateTest extends CreateRecord
{
    protected static string $resource = TestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {    
        // Get the model class name from the resource
        $modelClassName = $this->getResource()::getModel();
    
        foreach ($data["attachment"] as $file) {
            TestFiles::create([
                "file_name" => $file,
                "file_uuid" => Uuid::uuid4(),
                "model_name" => $modelClassName, // Save the model class name
                // "user_id" => auth()->user()->id,
            ]);
        }
    
        return $data;
    }
}
