<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranUangPangkalResource\Pages;
use App\Models\PembayaranUangPangkal;
use App\Models\Ppdb;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Textarea, Toggle, Select};
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PembayaranUangPangkalResource extends Resource
{
    protected static ?string $model = PembayaranUangPangkal::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Setting Uang Pangkal';

    protected static ?string $label = 'Setting Uang Pangkal';

    protected static ?string $pluralLabel = 'Setting Uang Pangkal';

    protected static ?string $navigationGroup = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_pembayaran')->required()->maxLength(100),
            Textarea::make('deskripsi')->maxLength(500),
            TextInput::make('nominal')->numeric()->required()->prefix('Rp')->step(1000),
            TextInput::make('tahun_ajaran')->maxLength(20)->placeholder('Contoh: 2025/2026'),

            Select::make('ppdb_id')
                ->label('Gelombang / PPDB')
                ->options(
                    \App\Models\Ppdb::all()->pluck('judul_ppdb', 'id')->prepend('Pilih Gelombang', '')->toArray()
                )
                ->searchable()
                ->nullable(),


            Select::make('jurusan_id')
                ->label('Jurusan')
                ->options(
                    \App\Models\Jurusan::all()->pluck('nama', 'id')->prepend('Pilih Jurusan', '')->toArray()
                )
                ->searchable()
                ->nullable(),


            Toggle::make('is_active')->label('Aktif?')->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pembayaran')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nominal')->money('IDR', true),
                Tables\Columns\TextColumn::make('tahun_ajaran'),
                Tables\Columns\TextColumn::make('ppdb.judul_ppdb')->label('PPDB'),
                Tables\Columns\TextColumn::make('jurusan.nama')->label('Jurusan'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Aktif'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y H:i'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('generateTagihan')
                    ->label('Generate Jumlah Tagihan')
                    ->icon('heroicon-o-calculator')
                    ->action(function (Collection $records) {
                        $grouped = $records->groupBy(fn($record) => $record->ppdb_id . '-' . $record->jurusan_id);

                        foreach ($grouped as $key => $group) {
                            [$ppdbId, $jurusanId] = explode('-', $key);
                            $totalTagihan = $group->sum('nominal');

                            \App\Models\JumlahTagihanAwal::updateOrCreate(
                                [
                                    'ppdb_id' => $ppdbId,
                                    'jurusan_id' => $jurusanId,
                                ],
                                [
                                    'total_tagihan' => $totalTagihan,
                                ]
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->color('success'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPembayaranUangPangkals::route('/'),
            'create' => Pages\CreatePembayaranUangPangkal::route('/create'),
            'edit'   => Pages\EditPembayaranUangPangkal::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
