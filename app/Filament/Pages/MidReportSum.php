<?php

namespace App\Filament\Pages;

use App\Models\MidReport;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

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
        $totals = MidReport::join('users', 'mid_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->selectRaw("
                SUM(mid_count) as total_count,
                SUM(mid_care_count) as total_care_count
            ")
            ->first();

        $this->stats = $totals ? $totals->toArray() : [];
    }

    /**
     * 支店ごとの集計クエリ
     */
    protected function getTableQuery(): Builder
    {
        return MidReport::join('users', 'mid_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->selectRaw("
             branches.id as id,
                branches.branch_name as branch_name,
                 SUM(mid_count) as total_count,
                SUM(mid_care_count) as total_care_count
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
            TextColumn::make('total_count')
                ->label('合計の中間本数'),
            TextColumn::make('total_care_count')
                ->label('合計の中間ケア本数'),
        ];
    }

//    public function getHeaderWidgets(): array
//    {
//        return [
//            MidReportStatsOverview::make([
//                'stats' => $this->stats,
//            ]),
//        ];
//    }
}
