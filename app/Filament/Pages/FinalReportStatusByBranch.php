<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\FinalReport;
use App\Models\MidReport;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class FinalReportStatusByBranch extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.final-report-by-branch';
    protected static ?string $navigationLabel = '支店別日報記載状況';
    protected static ?string $title = '支店別日報記載状況';

    public $branchId;
    public $targetDate;

    public function mount()
    {
        $this->branchId = auth()->user()->branch_id;
        $this->targetDate = now()->format('Y-m-d');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::query()->with('branch')
                    ->where('is_active', true);

                if ($this->branchId) {
                    $query->where('branch_id', $this->branchId);
                }

                return $query;
            })
            ->columns([
                IconColumn::make('mid_report_status_icon')
                    ->label('中間報告済')
                    ->getStateUsing(function ($record) {
                        $today = $this->targetDate;
                        $now = now();
                        $deadline = Carbon::parse($today . ' 14:00:00');

                        if ($now->greaterThanOrEqualTo($deadline)) {
                            $midReport = MidReport::where('user_id', $record->id)
                                ->whereDate('report_date', $today)
                                ->first();

                            return !$midReport;
                        }

                        return false;
                    })
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-circle')
                    ->falseIcon(null)
                    ->trueColor('danger'),

                IconColumn::make('final_report_status_icon')
                    ->label('最終報告済')
                    ->getStateUsing(function ($record) {
                        $today = $this->targetDate;
                        $now = now();
                        $deadline = Carbon::parse($today . ' 20:00:00');

                        if ($now->greaterThanOrEqualTo($deadline)) {
                            $finalReport = FinalReport::where('user_id', $record->id)
                                ->whereDate('report_date', $today)
                                ->first();

                            return !$finalReport;
                        }

                        return false;
                    })
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-circle')
                    ->falseIcon(null)
                    ->trueColor('danger'),

                TextColumn::make('name')
                    ->searchable()
                    ->label('社員'),
                TextColumn::make('branch.branch_name')
                    ->label('支店'),
            ])
            ->filters([
                Filter::make('specific_date')
                    ->form([
                        DatePicker::make('date')
                            ->default(today())
                            ->label('日付')
                            ->displayFormat('Y年m月d日')
                            ->placeholder('日付を選択')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['date'] ?? null) {
                            $this->targetDate = $data['date'];
                        } else {
                            $this->targetDate = now()->format('Y-m-d');
                        }
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['date'] ?? null) {
                            return '日付: ' . date('Y年m月d日', strtotime($data['date']));
                        }

                        return null;
                    })
            ])
            ->defaultSort('name');
    }
    public function getBranchsProperty()
    {
        return Branch::all();
    }

    public function updatedBranchId()
    {
        $this->resetTable();
    }
}