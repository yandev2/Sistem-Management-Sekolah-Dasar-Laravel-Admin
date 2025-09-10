<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TingkatKelasModelResource\Pages;
use App\Filament\Resources\TingkatKelasModelResource\RelationManagers;
use App\Models\TingkatKelasModel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TingkatKelasModelResource extends Resource
{
    protected static ?string $model = TingkatKelasModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tingkat Kelas';
    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('tingkat')
                            ->helperText('nama tingkat kelas (1 2 3 dan strs)')
                            ->numeric()
                            ->required()
                            ->maxLength(255),
                    ])->columnSpan(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                    ->badge()
                    ->color('info')
                    ->searchable(),
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
            'index' => Pages\ListTingkatKelasModels::route('/'),
            'create' => Pages\CreateTingkatKelasModel::route('/create'),
            'edit' => Pages\EditTingkatKelasModel::route('/{record}/edit'),
        ];
    }
}
