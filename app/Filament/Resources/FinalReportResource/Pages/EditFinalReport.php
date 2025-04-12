<?php

namespace App\Filament\Resources\FinalReportResource\Pages;

use App\Filament\Resources\FinalReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinalReport extends EditRecord
{
    protected static string $resource = FinalReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }
}