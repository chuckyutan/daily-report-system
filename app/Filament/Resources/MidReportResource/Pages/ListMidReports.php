<?php

namespace App\Filament\Resources\MidReportResource\Pages;

use App\Filament\Resources\MidReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMidReports extends ListRecords
{
    protected static string $resource = MidReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}