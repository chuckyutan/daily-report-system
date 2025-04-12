<?php

namespace App\Filament\Resources\FinalReportResource\Pages;

use App\Filament\Resources\FinalReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinalReports extends ListRecords
{
    protected static string $resource = FinalReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}