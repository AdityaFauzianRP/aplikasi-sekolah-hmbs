<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportTransaksiResource\Pages;
use App\Models\ReportTransaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Support\Facades\Auth;

class ReportTransaksiResource extends Resource
{
    protected static ?string $model = ReportTransaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Transaksi';

    protected static ?string $label = 'Laporan Transaksi';

    protected static ?string $pluralLabel = 'Laporan Transaksi';
    protected static ?string $navigationGroup = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')->required()->label('Nama'),
                Forms\Components\TextInput::make('nisn')->required()->label('NISN'),
                Forms\Components\TextInput::make('transaksi_id')->required()->label('ID Transaksi'),
                Forms\Components\TextInput::make('jenis_transaksi')->required()->label('Jenis Transaksi'),
                Forms\Components\TextInput::make('nominal')->numeric()->required()->label('Nominal'),
                Forms\Components\TextInput::make('biaya_pengembangan')->numeric()->required(),
                Forms\Components\TextInput::make('total_pembayaran')->numeric()->required()->label('Total Pembayaran'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Pembayaran Sukses' => 'Pembayaran Sukses',
                        'Gagal' => 'Gagal',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('nisn'),
                Tables\Columns\TextColumn::make('transaksi_id'),
                Tables\Columns\TextColumn::make('jenis_transaksi'),
                Tables\Columns\TextColumn::make('nominal')->money('IDR'),
                Tables\Columns\TextColumn::make('biaya_pengembangan')->money('IDR'),
                Tables\Columns\TextColumn::make('total_pembayaran')->money('IDR')
                    ->summarize(
                        Sum::make()
                            ->label('Total Pemasukan')
                    ),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => $state === 'Pembayaran Sukses' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('deskripsi')->limit(50),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Waktu Transaksi'),
            ])
            ->filters([])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportTransaksis::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Panitia Ppdb') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
