<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsenGuruModelResource\Pages;
use App\Filament\Resources\AbsenGuruModelResource\RelationManagers;
use App\Models\AbsenGuruModel;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsenGuruModelResource extends Resource
{
    protected static ?string $model = AbsenGuruModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Absensi Guru';
    protected static ?string $navigationGroup = 'Precence Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('id_guru')
                            ->label('Nama')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->relationship('guru', 'name')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('absen_masuk')
                            ->options([
                                'H' => 'HADIR',
                                'I' => 'IZIN',
                                'S' => 'SAKIT',
                                'A' => 'ALPA',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('absen_keluar')
                            ->options([
                                'H' => 'HADIR',
                                'I' => 'IZIN',
                                'S' => 'SAKIT',
                                'A' => 'ALPA',
                            ])
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('Belum ada data')
            ->columns([
                Tables\Columns\TextColumn::make('guru.name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('absen_masuk')
                    ->badge()
                    ->formatStateUsing(fn($record) => match ($record->absen_masuk) {
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',

                        default => '-',
                    })
                    ->color(fn($record) => match ($record->absen_masuk) {
                        'H' => 'success',
                        'I' => 'info',
                        'S' => 'primary',
                        default => '-',
                    })
                    ->description(function ($record) {
                        return $record->created_at
                            ?  $record->created_at->format('H:i:s')
                            : '-';
                    }),
                Tables\Columns\TextColumn::make('absen_keluar')
                    ->badge()
                    ->formatStateUsing(fn($record) => match ($record->absen_keluar) {
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        default => '-',
                    })
                    ->color(fn($record) => match ($record->absen_keluar) {
                        'H' => 'success',
                        'I' => 'info',
                        'S' => 'primary',
                        default => '-',
                    })
                    ->description(function ($record) {
                        return $record->updated_at
                            ?  $record->updated_at->format('H:i:s')
                            : '-';
                    }),
                Tables\Columns\TextColumn::make('durasi')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        if ($record->updated_at && $record->created_at) {
                            $start = Carbon::parse($record->created_at);
                            $end   = Carbon::parse($record->updated_at);
                            $minutes = $start->diffInMinutes($end);
                            $hours   = floor($minutes / 60);
                            $mins    = $minutes % 60;
                            return " {$hours} jam {$mins} menit";
                        }
                        return '-';
                    }),
            ])
            ->filters([
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
            'index' => Pages\ListAbsenGuruModels::route('/'),
            'create' => Pages\CreateAbsenGuruModel::route('/create'),
            'edit' => Pages\EditAbsenGuruModel::route('/{record}/edit'),
        ];
    }
}
