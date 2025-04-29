<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinalReportResource\Pages;
use App\Models\FinalReport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Illuminate\Validation\Rules\Unique;

class FinalReportResource extends Resource
{
    protected static ?string $model = FinalReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = '最終レポート';
    protected static ?string $modelLabel = '最終レポート';
    protected static ?string $pluralModelLabel = '最終レポート';
    protected static ?string $navigationGroup = '報告';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('担当者'),

                        DatePicker::make('report_date')
                            ->required()
                            ->displayFormat('Y年m月d日')
                            ->label('レポート日')
                            ->unique(
                                table: 'final_reports',
                                column: 'report_date',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where('user_id', $get('user_id'));
                                }
                            ),
                    ]),

                Section::make('')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TimePicker::make('start_time')
                                    ->seconds(false)
                                    ->label('開始時間'),

                                TimePicker::make('end_time')
                                    ->seconds(false)
                                    ->label('最終時間'),

                                TextInput::make('last_location')
                                    ->label('最終現地'),
                            ]),
                    ]),

                Section::make('R新規')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('r_new_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('件数'),

                                TextInput::make('r_new_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('本数'),

                                TextInput::make('r_new_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('金額'),
                            ]),
                    ]),

                Section::make('R継続')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('r_continue_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('件数'),

                                TextInput::make('r_continue_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('本数'),

                                TextInput::make('r_continue_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('金額'),
                            ]),
                    ]),

                Section::make('RGH施工')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('rgh_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('件数'),

                                TextInput::make('rgh_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('金額'),
                            ]),
                    ]),

                Section::make('その他商品')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('r_care_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('Rケア本数'),

                                TextInput::make('filter_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('フィルター本数'),

                                TextInput::make('filter_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('フィルター金額'),

                                TextInput::make('house_goods_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('ハウスグッズ本数'),

                                TextInput::make('house_goods_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('ハウスグッズ金額'),
                            ]),
                    ]),

                Section::make('ケア')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('care_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('件数'),

                                TextInput::make('care_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('本数'),

                                TextInput::make('care_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('金額'),
                            ]),
                    ]),

                Section::make('テレ配・テレ訪')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('tele_distribution_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('テレ配件数'),

                                TextInput::make('tele_distribution_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('テレ配本数'),

                                TextInput::make('tele_visit_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('テレ訪件数'),

                                TextInput::make('tele_visit_volume')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('テレ訪本数'),
                            ]),
                    ]),

                Section::make('清掃')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('cleaning_new_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('新規清掃件数'),

                                TextInput::make('cleaning_continue_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('継続清掃件数'),
                            ]),
                    ]),

                Section::make('チラシ')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('flyer_m_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('チラシM'),

                                TextInput::make('flyer_house_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('チラシ戸建'),

                                TextInput::make('flyer_shop_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('チラシ店舗'),
                            ]),
                    ]),

                Section::make('その他情報')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('additional_amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('追加金額'),

                                TextInput::make('frame_new_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('新規枠付'),

                                TextInput::make('af_pack_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('AF販売パック数'),
                            ]),
                    ]),

                Section::make('')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('contract_count')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('契約件数'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('担当者'),

                TextColumn::make('report_date')
                    ->date('Y年m月d日')
                    ->sortable()
                    ->label('レポート日'),

                TextColumn::make('r_new_count')
                    ->label('R新規件数')
                    ->sortable(),

                TextColumn::make('r_continue_count')
                    ->label('R継続件数')
                    ->sortable(),

                TextColumn::make('totalAmount')
                    ->label('合計金額')
                    ->getStateUsing(function ($record) {
                        return Number::currency($record->r_new_amount +
                            $record->r_continue_amount +
                            $record->rgh_amount +
                            $record->filter_amount +
                            $record->house_goods_amount +
                            $record->care_amount +
                            $record->additional_amount, 'JPY');
                    })
                    ->alignRight(),

                TextColumn::make('created_at')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('作成日時'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('担当者'),
                Filter::make('report_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('report_date_from')
                            ->label('開始日'),
                        \Filament\Forms\Components\DatePicker::make('report_date_to')
                            ->label('終了日'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['report_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('report_date', '>=', $date),
                            )
                            ->when(
                                $data['report_date_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('report_date', '<=', $date),
                            );
                    }),

                SelectFilter::make('branch')
                    ->relationship('user.branch', 'branch_name')
                    ->label('支店'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
            ])
            ->defaultSort('report_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinalReports::route('/'),
            'create' => Pages\CreateFinalReport::route('/create'),
            'edit' => Pages\EditFinalReport::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('report_date', today())->count();
    }
}