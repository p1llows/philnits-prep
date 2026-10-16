<?php

namespace App\Filament\Resources\SourcePackageResource\Pages;

use App\Filament\Resources\SourcePackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourcePackage extends EditRecord
{
    protected static string $resource = SourcePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
