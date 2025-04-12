<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '社員';
    protected static ?string $modelLabel = '社員';
    protected static ?string $pluralModelLabel = '社員';
    protected static ?string $navigationGroup = 'マスタ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('名前')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('メール')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('パスワード')
                    ->password()
                    ->required(fn(string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->maxLength(255)
                    // 新規作成時のみ暗号化して保存。編集時は空欄の場合は変更しない処理などを追加してください。
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                Select::make('role')
                    ->label('役割')
                    ->options([
                        'admin'   => 'Admin',
                        'manager' => 'Manager',
                        'staff'   => 'Staff',
                    ])
                    ->required(),
                Select::make('branch_id')
                    ->label('支店')
                    ->relationship('branch', 'branch_name')
                    ->nullable(),
                Toggle::make('is_active')
                    ->label('アクティブ')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('名前')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('メール')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('role')
                    ->label('役割')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('branch.branch_name')
                    ->label('支店')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('アクティブ')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('作成日')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
