<?php

namespace App\Filament\Resources\SourcePackageResource\Pages;

use App\Filament\Resources\SourcePackageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSourcePackage extends CreateRecord
{
    protected static string $resource = SourcePackageResource::class;

    protected function afterCreate(): void
    {
        \App\Services\Audit::log('package.uploaded', $this->record, [
            'package_id' => $this->record->id,
            'name' => $this->record->name,
            'exam_session_label' => $this->record->exam_session_label,
        ]);
    }
}
