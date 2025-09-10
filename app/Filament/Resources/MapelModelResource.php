<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapelModelResource\Pages;
use App\Filament\Resources\MapelModelResource\RelationManagers;
use App\Models\MapelModel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MapelModelResource extends Resource
{
    protected static ?string $model = MapelModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('kode_mapel')
                            ->required()
                            ->helperText('IPA, IPS dan str'),
                        Forms\Components\TextInput::make('nama_mapel')
                            ->required()
                            ->helperText('Ilmu Pengetahuan Alam dan str'),
                        Forms\Components\Select::make('id_tingkat')
                            ->relationship('tingkatKelas', 'tingkat')
                            ->native(false)
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->defaultGroup('tingkatKelas.tingkat')
            ->groups([
                Group::make('tingkatKelas.tingkat')
                    ->label('Kelas')
                    ->collapsible(),
            ])
            ->groupingSettingsHidden()
            ->columns([
                Tables\Columns\TextColumn::make('kode_mapel')
                    ->label('Kode Mapel')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_mapel')
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('tingkatKelas.tingkat')
                    ->label('Tingkat Kelas')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
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
            'index' => Pages\ListMapelModels::route('/'),
            'create' => Pages\CreateMapelModel::route('/create'),
            'edit' => Pages\EditMapelModel::route('/{record}/edit'),
        ];
    }
}
