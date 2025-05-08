<?php

namespace App\Filament\Pages;

use App\Models\MidReport;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                MIN(branches.branch_name) as branch_name,
                 SUM(mid_count) as total_count,
                SUM(mid_care_count) as total_care_count,
                SUM(remaining_visits) as total_remaining_visits,
                MIN(mid_reports.report_date) as report_date
            ")
            ->groupBy('branches.id')
            ->orderBy('branches.branch_name', 'asc');
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
            TextColumn::make('total_count')
                ->label('中間本数合計'),
            TextColumn::make('total_care_count')
                ->label('中間ケア本数合計'),
            TextColumn::make('total_remaining_visits')
                ->label('残り訪問予定数合計'),
        ];
    }

    protected function getTableRecordUrlUsing(): ?callable
    {
        return static function ($record) {
            return MidReportSumByBranch::getUrl(['branchId' => $record->id, 'reportDateString' => $record->report_date]);
        };
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return '中間報告データが見つかりません';
    }

    protected function getActions(): array
    {
        return [
            Action::make('output')
                ->label('営業売上報告書（中間）ダウンロード')
                ->icon('heroicon-o-document-arrow-down')
                ->action('output'),
        ];
    }

    public function output(): void
    {
        $this->loadStats();
    }
}
