<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '車両';
    protected static ?string $modelLabel = '車両';
    protected static ?string $pluralModelLabel = '車両';
    protected static ?string $navigationGroup = 'マスタ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('branch_id')
                    ->label('支店')
                    ->relationship('branch', 'branch_name')
                    ->required(),
                TextInput::make('name')
                    ->label('車両名')
                    ->required()
                    ->maxLength(255),
                TextInput::make('vehicle_type')
                    ->label('車両タイプ')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('vehicle_number')
                    ->label('車両番号')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.branch_name')
                    ->label('支店')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('車両名')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vehicle_type')
                    ->label('車両タイプ')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vehicle_number')
                    ->label('車両番号')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
