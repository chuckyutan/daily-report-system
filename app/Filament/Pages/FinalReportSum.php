<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use App\Models\FinalReport;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Widgets\FinalReportStatsOverview;

class FinalReportSum extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.final-report-sum';
    protected static ?string $navigationLabel = '最終報告';
    protected static ?string $title = '最終報告';

    private array $stats;

    /**
     * ページマウント時に全支店合計の stats をロード
     */
    public function mount(): void
    {
        $this->loadStats();
    }

    public function getTitle(): string
    {
        return $this->getTableFilterState('report_date')['report_date'] . ' 最終報告';
    }

    /**
     * 全支店の合計値を取得
     */
    protected function loadStats(): void
    {
        $reportDate = $this->getTableFilterState('report_date');
        $reports = $this->getTableQuery()->whereDate('report_date', $reportDate)->get();

        $this->stats = [
            'total_care_count'      => $reports->sum('total_care_count'),
            'total_care_volume'     => $reports->sum('total_care_volume'),
            'total_vehicle_volume'  => $reports->sum('total_vehicle_volume'),
            'total_contract_count'  => $reports->sum('total_contract_count'),
        ];
    }

    /**
     * 集計用の selectRaw 式を共通化
     */
    protected function getTotalsSelect(): array
    {
        return [
            'SUM(care_count) as total_care_count',
            'SUM(r_care_volume + care_volume) as total_care_volume',
            'SUM(r_new_volume + r_continue_volume + r_care_volume + filter_volume + house_goods_volume + care_volume) as total_vehicle_volume',
            'SUM(r_new_count + r_continue_count + rgh_count + care_count + tele_distribution_count + tele_visit_count + cleaning_new_count + cleaning_continue_count) as total_contract_count',
        ];
    }

    /**
     * 支店ごとの集計クエリ
     */
    protected function getTableQuery(): Builder
    {
        return FinalReport::query()
            ->join('users',    'final_reports.user_id',  '=', 'users.id')
            ->join('branches', 'users.branch_id',        '=', 'branches.id')
            ->selectRaw(
                implode(', ', array_merge([
                    'branches.id as id',
                    'branches.branch_name as branch_name',
                    'ANY_VALUE(final_reports.report_date) as report_date',
                ], $this->getTotalsSelect()))
            )
            ->groupBy('branches.id')
            ->orderBy('branches.branch_name');
    }


    protected function getTableFilters(): array
    {
        return [
            Filter::make('report_date')
                ->label('レポート日')
                ->form([
                    DatePicker::make('report_date')
                        ->default(today()->toDateString())
                        ->label('日付'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['report_date'])) {
                        $query->whereDate('report_date', $data['report_date']);
                    }
                })
        ];
    }

    /**
     * テーブルに表示するカラム設定
     */
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('branch_name')
                ->label('支店'),

            TextColumn::make('total_care_count')
                ->label('合計のケア件数'),

            TextColumn::make('total_care_volume')
                ->label('合計のケア本数'),

            TextColumn::make('total_vehicle_volume')
                ->label('最終合計車両本数'),

            TextColumn::make('total_contract_count')
                ->label('契約件数'),
        ];
    }

    protected function getTableRecordUrlUsing(): ?callable
    {
        return static function ($record) {
            return FinalReportSumByBranch::getUrl(['branchId' => $record->id, 'reportDateString' => $record->report_date]);
        };
    }

    public function getHeaderWidgets(): array
    {
        return [
            FinalReportStatsOverview::make([
                'stats' => $this->stats,
            ]),
        ];
    }

    public function updatedTableFilters(): void
    {
        $this->loadStats();
    }
}
