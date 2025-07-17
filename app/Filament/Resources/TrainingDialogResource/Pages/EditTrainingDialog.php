<?php

namespace App\Filament\Resources\TrainingDialogResource\Pages;

use App\Filament\Resources\TrainingDialogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingDialog extends EditRecord
{
    protected static string $resource = TrainingDialogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
