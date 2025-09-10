<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsenSiswaModelResource\Pages;
use App\Filament\Resources\AbsenSiswaModelResource\RelationManagers;
use App\Models\AbsenSiswaModel;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsenSiswaModelResource extends Resource
{
    protected static ?string $model = AbsenSiswaModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Absensi Siswa';
    protected static ?string $navigationGroup = 'Precence Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_siswa')
                    ->relationship('siswa', 'nama_siswa')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'H' => 'HADIR',
                        'I' => 'IZIN',
                        'S' => 'SAKIT',
                        'A' => 'ALPA',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->defaultGroup('siswa.kelas.nama_kelas')
            ->groups([
                Group::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->collapsible(),
            ])
            ->groupingSettingsHidden()
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_siswa')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($record) => match ($record->status) {
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alpha',
                        default => '-',
                    })
                    ->color(fn($record) => match ($record->status) {
                        'H' => 'success',
                        'I' => 'info',
                        'S' => 'primary',
                        'A' => 'danger',
                        default => '-',
                    }),
                Tables\Columns\TextColumn::make('keterangan'),
            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->native(false)
                    ->relationship('siswa.kelas', 'nama_kelas')
                    ->getOptionLabelFromRecordUsing(fn($record): string =>  $record->nama_kelas)
                    ->preload()
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('Dari tanggal'),
                        DatePicker::make('Sampai tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Dari tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date)
                                    ->when(
                                        $data['Sampai tanggal'],
                                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date)
                                    )
                            );
                    }),
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
            'index' => Pages\ListAbsenSiswaModels::route('/'),
            'create' => Pages\CreateAbsenSiswaModel::route('/create'),
            'edit' => Pages\EditAbsenSiswaModel::route('/{record}/edit'),
        ];
    }
}
