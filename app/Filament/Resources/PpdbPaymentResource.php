<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbPaymentResource\Pages;
use App\Models\PpdbPayment;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Auth;

class PpdbPaymentResource extends Resource
{
    protected static ?string $model = PpdbPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pembayaran PPDB';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $label = 'Pembayaran PPDB';

    protected static ?string $pluralLabel = 'Pembayaran PPDB';

    public static function form(Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peserta_didik_id')
                    ->label('Peserta Didik')
                    ->relationship('pesertaDidik', 'nama_lengkap') // Pastikan relasi ada di model
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nomor_transaksi')
                    ->label('Nomor Transaksi')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\DateTimePicker::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->required(),

                Forms\Components\Select::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'Transfer' => 'Transfer',
                        'Tunai' => 'Tunai',
                        'E-Wallet' => 'E-Wallet',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('biaya_sekolah')
                    ->label('Biaya Sekolah')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\TextInput::make('biaya_mitrans')
                    ->label('Biaya Mitrans')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\TextInput::make('biaya_pengembangan')
                    ->label('Biaya Pengembangan')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->maxSize(2048)
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Terverifikasi' => 'Terverifikasi',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->required()
                    ->default('Menunggu Verifikasi'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_transaksi')->label('Nomor Transaksi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pesertaDidik.nama_lengkap')->label('Peserta Didik')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tanggal_bayar')->label('Tanggal Bayar')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')->label('Metode Pembayaran')->sortable(),
                Tables\Columns\TextColumn::make('jumlah')->label('Jumlah')->money('idr', true)->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Terverifikasi' => 'Terverifikasi',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\PaymentReceipt::route('/'),
            'create' => Pages\CreatePpdbPayment::route('/create'),
            'edit' => Pages\EditPpdbPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Peserta');
    }
}
