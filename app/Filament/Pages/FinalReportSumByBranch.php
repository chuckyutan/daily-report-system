<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\FinalReport;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class FinalReportSumByBranch extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $slug = 'final-report-sum-by-branch/{branchId}/{reportDateString}';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.final-report-sum-by-branch';

    protected static ?string $navigationLabel = null;

    protected static ?string $title = '最終報告詳細';

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
        return "最終報告詳細 {$this->branch->branch_name} {$this->reportDate->toDateString()}";
    }

    protected function getTableQuery(): Builder
    {
        return FinalReport::query()
            ->whereHas('user', fn(Builder $q) => $q->where('branch_id', $this->branch->id)
            )
            ->join('users', 'final_reports.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            // ユーザー単位で集計する
            ->groupBy('user_id')
            // Filament が行を一意に識別するために user_id を id として返す
            ->selectRaw("
    branches.id as id,
    branches.branch_name as branch_name,
        users.name as user_name,
    SUM(care_count)                                                  as total_care_count,
    SUM(r_care_volume + care_volume)                                 as total_care_volume,
    SUM(
        r_new_volume
      + r_continue_volume
      + r_care_volume
      + filter_volume
      + house_goods_volume
      + care_volume
      + tele_distribution_volume
      + tele_visit_volume
    )                                                                 as total_vehicle_volume
")
            ->with('user')
            ->orderBy('user_id', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user_name')
                ->searchable()
                ->label('担当者'),

            TextColumn::make('total_care_count')
                ->label('合計ケア件数'),

            TextColumn::make('total_care_volume')
                ->label('合計合本数'),

            TextColumn::make('total_vehicle_volume')
                ->label('最終合計車両本数'),

            TextColumn::make('created_at')
                ->dateTime('Y/m/d H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->label('作成日時'),
        ];
    }
}
