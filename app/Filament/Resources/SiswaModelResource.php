<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaModelResource\Pages;
use App\Filament\Resources\SiswaModelResource\RelationManagers;
use App\Models\SiswaModel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiswaModelResource extends Resource
{
    protected static ?string $model = SiswaModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $navigationGroup = 'Human Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nis')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nisn')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tahun_masuk')
                            ->displayFormat('Y')
                            ->format('Y')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(3),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_siswa')
                            ->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->label('Jenis Kelamin')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make()
                    ->schema([
                        Forms\Components\Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                            ])
                            ->label('Agama')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('nik')
                            ->maxLength(255)
                            ->numeric()
                            ->hint('Tidak wajib')
                            ->default(null),
                        Forms\Components\TextInput::make('no_kk')
                            ->maxLength(255)
                            ->hint('Tidak wajib')
                            ->numeric()
                            ->default(null),
                        Forms\Components\TextInput::make('nama_orang_tua')
                            ->hint('Tidak wajib')
                            ->default(null),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make()
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('id_kelas')
                            ->label('Kelas')
                            ->required()
                            ->relationship('kelas', 'nama_kelas')
                            ->getOptionLabelFromRecordUsing(fn($record) => ' ' . ($record->nama_kelas)),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
                Forms\Components\FileUpload::make('foto')
                    ->label('')
                    ->nullable()
                    ->default(null)
                    ->helperText('Foto Siswa (tidak wajib)')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->previewable(true)
                    ->panelAspectRatio('8:3.3')
                    ->disk('public')
                    ->directory('siswa')
                    ->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->defaultGroup('kelas.nama_kelas')
            ->groups([
                Group::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->collapsible(),
            ])
            ->groupingSettingsHidden()
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_siswa'),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('tahun_masuk')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
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
            'index' => Pages\ListSiswaModels::route('/'),
            'create' => Pages\CreateSiswaModel::route('/create'),
            'edit' => Pages\EditSiswaModel::route('/{record}/edit'),
        ];
    }
}
