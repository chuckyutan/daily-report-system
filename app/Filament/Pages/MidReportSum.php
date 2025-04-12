<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use App\Models\FinalReport;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Widgets\FinalReportStatsOverview;

class MidReportSum extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mid-report-sum';
    protected static ?string $navigationLabel = '中間報告';
    protected static ?string $title = '中間報告';

    private array $stats;

    /**
     * ページマウント時に全支店合計の stats をロード
     */
    public function mount(): void
    {
        $this->loadStats();
    }

    /**
     * 全支店の合計値を取得
     */
    protected function loadStats(): void
    {
        $totals = FinalReport::join('users', 'mid_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->selectRaw("
                SUM(care_count) as total_care_count,
                SUM(r_care_volume + care_volume) as total_care_volume,
                SUM(r_new_volume + r_continue_volume + r_care_volume + filter_volume + house_goods_volume + care_volume + tele_distribution_volume + tele_visit_volume) as total_vehicle_volume,
                SUM(r_new_count + r_continue_count + rgh_count + care_count + tele_distribution_count + tele_visit_count + cleaning_new_count + cleaning_continue_count) as total_contract_count
            ")
            ->first();

        $this->stats = $totals ? $totals->toArray() : [];
    }

    /**
     * 支店ごとの集計クエリ
     */
    protected function getTableQuery(): Builder
    {
        return FinalReport::join('users', 'final_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->selectRaw("
             branches.id as id,
                branches.branch_name as branch_name,
                SUM(care_count) as total_care_count,
                SUM(r_care_volume + care_volume) as total_care_volume,
                SUM(r_new_volume + r_continue_volume + r_care_volume + filter_volume + house_goods_volume + care_volume + tele_distribution_volume + tele_visit_volume) as total_vehicle_volume,
                SUM(r_new_count + r_continue_count + rgh_count + care_count + tele_distribution_count + tele_visit_count + cleaning_new_count + cleaning_continue_count) as total_contract_count
            ")
            ->groupBy('branches.id')
            ->orderBy('branches.branch_name', 'asc');
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

    public function getHeaderWidgets(): array
    {
        return [
            FinalReportStatsOverview::make([
                'stats' => $this->stats,
            ]),
        ];
    }
}
