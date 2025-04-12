<?php

namespace App\Filament\Resources\MidReportResource\Pages;

use App\Filament\Resources\MidReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMidReport extends EditRecord
{
    protected static string $resource = MidReportResource::class;

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