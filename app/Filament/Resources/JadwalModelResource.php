<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalModelResource\Pages;
use App\Filament\Resources\JadwalModelResource\RelationManagers;
use App\Models\JadwalModel;
use App\Models\MapelModel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;


class JadwalModelResource extends Resource
{
    protected static ?string $model = JadwalModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Jadwal Pelajaran';
    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('id_kelas')
                            ->label('Kelas')
                            ->native(false)
                            ->relationship('kelas', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->nama_kelas)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('id_mapel')
                            ->label('Mata Pelajaran')
                            ->native(false)
                            ->options(function (callable $get) {
                                $idKelas = $get('id_kelas');
                                if (!$idKelas) {
                                    return [];
                                }
                                $kelas = \App\Models\KelasModel::find($idKelas);
                                if (!$kelas) {
                                    return [];
                                }
                                return MapelModel::where('id_tingkat', $kelas->id_tingkat)
                                    ->pluck('kode_mapel', 'id')
                                    ->toArray();
                            })
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('hari')
                            ->preload()
                            ->native(false)
                            ->options([
                                'SENIN'     => 'Senin',
                                'SELASA'    => 'Selasa',
                                'RABU'      => 'Rabu',
                                'KAMIS'     => 'Kamis',
                                'JUMAT'     => "Jum'at",
                                'SABTU'     => 'Sabtu',
                            ])
                            ->required(),
                    ])
                    ->columns(3),
                Forms\Components\TimePicker::make('jam_masuk')
                    ->required(),
                Forms\Components\TimePicker::make('jam_keluar')
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->defaultGroup('kelas.tingkatKelas.tingkat')
            ->groups([
                Group::make('kelas.tingkatKelas.tingkat')
                    ->label('Kelas')
                    ->collapsible(),
            ])
            ->groupingSettingsHidden()
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('mapel.kode_mapel')
                    ->label('Mata Pelajaran')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hari')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_masuk'),
                Tables\Columns\TextColumn::make('jam_keluar'),
            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->multiple()
                    ->native(false)
                    ->relationship('kelas', 'nama_kelas')
                    ->getOptionLabelFromRecordUsing(fn($record): string =>  $record->nama_kelas)
                    ->preload()
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalModels::route('/'),
            'create' => Pages\CreateJadwalModel::route('/create'),
            'edit' => Pages\EditJadwalModel::route('/{record}/edit'),
        ];
    }
}
