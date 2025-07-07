<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPemasukanPpdbResource\Pages;
use App\Filament\Resources\LaporanPemasukanPpdbResource\RelationManagers;
use App\Filament\Resources\LaporanPemasukanPpdbResource\Widgets\PemasukanOverview;
use App\Models\LaporanPemasukanPpdb;
use App\Models\PpdbPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\Summarizers\Sum;

class LaporanPemasukanPpdbResource extends Resource
{
    protected static ?string $model = PpdbPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Laporan Pembayaran PPDB';

    protected static ?string $label = 'Laporan Pembayaran PPDB';

    protected static ?string $pluralLabel = 'Laporan Pembayaran PPDB';

    protected static ?string $navigationGroup = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('No')->rowIndex(),
                TextColumn::make('nomor_transaksi')->label('Nomor Transaksi')->searchable(),
                TextColumn::make('pesertaDidik.nomor_registrasi')->label('Nomor Registrasi Peserta')->searchable(),
                TextColumn::make('pesertaDidik.nama_lengkap')->label('Nama Peserta')->searchable(),

                TextColumn::make('metode_pembayaran')->label('Metode Pembayaran'),
                TextColumn::make('created_at')->label('Tanggal Bayar')->dateTime('d M Y H:i'),
                TextColumn::make('biaya_sekolah')
                    ->label('Biaya Sekolah')
                    ->money('IDR', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total Biaya Sekolah')
                    ),
                TextColumn::make('biaya_mitrans')->label('Biaya Mitrans')->money('IDR', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total Biaya Midtrans')
                    ),
                TextColumn::make('biaya_pengembangan')->label('Biaya Pengembangan')->money('IDR', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total Biaya Pengembangan')
                    ),

                TextColumn::make('jumlah')
                    ->label('Total Bayar')
                    ->money('IDR', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total Bayar')
                    ),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ppdb_id')
                    ->label('Pilih PPDB')
                    ->relationship('ppdb', 'judul_ppdb') // atau 'nama' jika kamu ingin tampilkan nama
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLaporanPemasukanPpdbs::route('/'),
            // 'create' => Pages\CreateLaporanPemasukanPpdb::route('/create'),
            // 'edit' => Pages\EditLaporanPemasukanPpdb::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Panitia Ppdb') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
