<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanSiswaResource\Pages;
use App\Filament\Resources\TagihanSiswaResource\RelationManagers;
use App\Models\PembayaranUangPangkal;
use App\Models\PesertaDidik;
use App\Models\TagihanSiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TagihanSiswaResource extends Resource
{
    protected static ?string $model = TagihanSiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pembayaran Awal Masuk';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $label = 'Pembayaran Awal Masuk';

    protected static ?string $pluralLabel = 'Pembayaran Awal Masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil peserta_didik yang sesuai user_id
        $pesertaDidik = PesertaDidik::where('user_id', $user->id)->first();

        return $table
            ->query(
                static::getModel()::query()
                    ->when($pesertaDidik, function ($query) use ($pesertaDidik) {
                        $query->where('peserta_didik_id', $pesertaDidik->id);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal Tagihan')
                    ->money('IDR', true) // Format sebagai Rupiah
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cicilan_ke')
                    ->label('Cicilan Ke-')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_bayar')
                    ->label('Status Pembayaran')
                    ->badge() // Tambahkan badge biar tampil beda
                    ->colors([
                        'danger' => 'belum',
                        'success' => 'lunas',
                    ])
                    ->sortable()
                    ->searchable(),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(), 
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
            'index' => Pages\ListTagihanSiswas::route('/'),
            'edit' => Pages\TagihanAwalSiswaBaru::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Peserta Baru') || Auth::user()->hasRole('Peserta Actif') || Auth::user()->hasRole('Siswa Baru') || Auth::user()->hasRole('Menunggu Mendapat Kelas');
    }

}
