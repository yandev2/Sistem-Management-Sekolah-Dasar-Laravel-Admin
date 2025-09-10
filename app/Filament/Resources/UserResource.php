<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $navigationGroup = 'Human Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informasi Guru')
                    ->schema([
                        Forms\Components\TextInput::make('nip')
                            ->maxLength(255)
                            ->label('Nip')
                            ->unique(
                                table: 'users',
                                column: 'nip',
                                ignorable: fn($record) => $record
                            )
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // hash hanya kalau ada input
                            ->dehydrated(fn($state) => filled($state))
                            ->label('Password')
                            ->default('sekolah12345')
                            ->helperText(fn($record) => $record?->exists ? 'Kosongkan jika tidak ingin mengubah password' : ''),
                        Forms\Components\TextInput::make('nomor_hp')
                            ->maxLength(255)
                            ->numeric()
                            ->default(null),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->label('Jenis Kelamin')
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('alamat')
                    ])->columnSpan(1),

                Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto')
                            ->nullable()
                            ->default(null)
                            ->helperText('Foto Guru (tidak wajib)')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->previewable(true)
                            ->panelAspectRatio('8:5')
                            ->disk('public')
                            ->directory('guru')
                            ->openable(),
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->native(false)
                            ->preload()
                            ->required(),
                    ])->columnSpan(1)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('Nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->badge()
                    ->color(fn($state) => $state == 'Perempuan' ? 'success' : 'primary'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('info'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->color('danger'),
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
