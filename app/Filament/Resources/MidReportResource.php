<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MidReportResource\Pages;
use App\Models\MidReport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MidReportResource extends Resource
{
    protected static ?string $model = MidReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = '中間レポート';
    protected static ?string $modelLabel = '中間レポート';
    protected static ?string $pluralModelLabel = '中間レポート';
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
                            ->label('レポート日'),
                    ]),

                Section::make('レポート詳細')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('mid_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('中間本数'),

                                TextInput::make('mid_care_count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.1)
                                    ->default(0)
                                    ->label('中間ケア本数'),

                                TextInput::make('remaining_visits')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('残り訪問予定数'),
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

                TextColumn::make('mid_count')
                    ->label('中間本数')
                    ->sortable(),

                TextColumn::make('mid_care_count')
                    ->label('中間ケア本数')
                    ->sortable(),

                TextColumn::make('remaining_visits')
                    ->label('残り訪問予定数')
                    ->sortable(),

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
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('report_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMidReports::route('/'),
            'create' => Pages\CreateMidReport::route('/create'),
//            'view' => Pages\ViewMidReport::route('/{record}'),
            'edit' => Pages\EditMidReport::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('report_date', today())->count();
    }
}