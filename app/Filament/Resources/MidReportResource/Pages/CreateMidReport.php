<?php

namespace App\Filament\Resources\MidReportResource\Pages;

use App\Filament\Resources\MidReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMidReport extends CreateRecord
{
    protected static string $resource = MidReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        if (!isset($data['report_date'])) {
            $data['report_date'] = now()->format('Y-m-d');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}