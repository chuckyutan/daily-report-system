<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '出勤報告';
    protected static ?string $modelLabel = '出勤報告';
    protected static ?string $pluralModelLabel = '出勤報告';
    protected static ?string $navigationGroup = '報告';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 入力者（User とのリレーション）
                Select::make('user_id')
                    ->label('入力者')
                    ->relationship('user', 'name')
                    ->required(),
                // 担当車両（Vehicle とのリレーション：任意）
                Select::make('vehicle_id')
                    ->label('担当車両')
                    ->relationship('vehicle', 'name')
                    ->nullable(),
                // 報告日
                DatePicker::make('report_date')
                    ->label('報告日')
                    ->required(),
                // 開始現地
                TextInput::make('start_location')
                    ->label('開始現地')
                    ->maxLength(255)
                    ->nullable(),
                // 出発 or 勤務開始時間
                TimePicker::make('start_time')
                    ->label('出発／勤務開始時間')
                    ->nullable(),
                // 訪問予定件数
                TextInput::make('planned_visits')
                    ->label('訪問予定件数')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('入力者')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vehicle.name')
                    ->label('担当車両')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('report_date')
                    ->label('報告日')
                    ->date(),
                TextColumn::make('start_location')
                    ->label('開始現地')
                    ->searchable(),
                TextColumn::make('start_time')
                    ->label('出発／勤務開始時間')
                    ->time(),
                TextColumn::make('planned_visits')
                    ->label('訪問予定件数')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
