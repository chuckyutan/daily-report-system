<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\Reactive;

class FinalReportStatsOverview extends BaseWidget
{
    protected $listeners = [
        'finalReportDateChanged' => '$refresh',
    ];

    #[Reactive]
    public array $stats;

    protected function getStats(): array
    {
        return [
            Stat::make('合計のケア件数', $this->stats['total_care_count'] ?? 0)
                ->icon('heroicon-o-heart')
                ->color('success'),

            Stat::make('合計のケア本数', $this->stats['total_care_volume'] ?? 0)
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),

            Stat::make('最終合計車両本数', $this->stats['total_vehicle_volume'] ?? 0)
                ->icon('heroicon-o-truck')
                ->color('warning'),

            Stat::make('契約件数', $this->stats['total_contract_count'] ?? 0)
                ->icon('heroicon-o-document-text')
                ->color('danger'),
        ];
    }
}
