<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\MidReport;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class MidReportSumByBranch extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'mid-report-sum-by-branch/{branchId}/{reportDateString}';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mid-report-sum-by-branch';

    protected static ?string $navigationLabel = null;

    protected static ?string $title = '中間報告社員別詳細';

    protected static bool $shouldRegisterNavigation = false;

    public Branch $branch;
    public Carbon $reportDate;

    public function mount(int $branchId, string $reportDateString): void
    {
        $this->branch     = Branch::find($branchId);
        $this->reportDate = Carbon::parse($reportDateString);
    }

    public function getTitle(): string
    {
        return "中間報告詳細 {$this->branch->branch_name} {$this->reportDate->toDateString()}";
    }

    protected function getTableQuery(): Builder
    {
        return MidReport::query()
            ->whereHas('user', fn(Builder $q) => $q->where('branch_id', $this->branch->id)
            )
            ->join('users', 'mid_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            // ユーザー単位で集計する
            ->groupBy('user_id')
            // Filament が行を一意に識別するために user_id を id として返す
            ->selectRaw("
    MIN(branches.id) as id,
    MIN(branches.branch_name) as branch_name,
        MIN(users.name) as user_name,
    SUM(mid_count)                                                  as total_care,
    SUM(mid_care_count)                                 as total_care_count,
    SUM(
        remaining_visits
    )                                                                 as total_remaining_visits
")
            ->with('user')
            ->orderBy('user_id', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user_name')
                ->label('担当者'),

            TextColumn::make('total_care')
                ->label('中間本数合計'),

            TextColumn::make('total_care_count')
                ->label('中間ケア本数合計'),

            TextColumn::make('total_remaining_visits')
                ->label('残り訪問予定数合計'),
        ];
    }
}
