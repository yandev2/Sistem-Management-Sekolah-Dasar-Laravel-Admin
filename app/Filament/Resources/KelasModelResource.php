<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasModelResource\Pages;
use App\Filament\Resources\KelasModelResource\RelationManagers;
use App\Models\KelasModel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasModelResource extends Resource
{
    protected static ?string $model = KelasModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_kelas')
                            ->required()
                            ->helperText('Nama kelas contoh (A B C D dan str)')
                            ->maxLength(255),
                        Forms\Components\Select::make('id_tingkat')
                            ->label('Tingkat Kelas')
                            ->relationship('tingkatKelas', 'tingkat')
                            ->required(),
                        Forms\Components\Select::make('id_wali_kelas')
                            ->label('Wali Kelas')
                            ->relationship('waliKelas', 'name')
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
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->formatStateUsing(fn($state) => 'Kelas' . ' ' . $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tingkatKelas.tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('walikelas.name')
                    ->label('Wali Kelas'),
                Tables\Columns\TextColumn::make('siswa_count')
                    ->label('Jumlah Siswa')
            ])
            ->filters([
                //
            ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('siswa');
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
            'index' => Pages\ListKelasModels::route('/'),
            'create' => Pages\CreateKelasModel::route('/create'),
            'edit' => Pages\EditKelasModel::route('/{record}/edit'),
        ];
    }
}
