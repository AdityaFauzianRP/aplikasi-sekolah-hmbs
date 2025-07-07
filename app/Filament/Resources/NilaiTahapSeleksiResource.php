<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiTahapSeleksiResource\Pages;
use App\Models\NilaiTahapSeleksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NilaiTahapSeleksiResource extends Resource
{
    protected static ?string $model = NilaiTahapSeleksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Pemberkasan PPDB';

    protected static ?string $navigationLabel = 'Nilai Tahap Seleksi';

    protected static ?string $pluralModelLabel = 'Nilai Tahap Seleksi';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('peserta', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peserta_id')
                    ->label('Peserta')
                    ->relationship('peserta', 'nama_lengkap')  // pastikan model peserta punya atribut 'nama'
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('tahap_seleksi_id')
                    ->label('Tahap Seleksi')
                    ->relationship('tahapSeleksi', 'nama') // pastikan model tahapSeleksi punya atribut 'nama'
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status_lulus')
                    ->label('Status Lulus')
                    ->options([
                        'TAHAP PENILAIAN' => 'Tahap Penilaian',
                        'LULUS' => 'Lulus',
                        'TIDAK LULUS' => 'Tidak Lulus',
                    ])
                    ->default('TAHAP PENILAIAN')
                    ->required(),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nomor')
                    ->label('Nomor')
                    ->rowIndex()
                    ->sortable(false),
                Tables\Columns\TextColumn::make('peserta.nama_lengkap')->label('Peserta')->searchable(),
                Tables\Columns\TextColumn::make('tahapSeleksi.nama_tahap')->label('Tahap Seleksi')->searchable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai'),


                Tables\Columns\BadgeColumn::make('status_lulus')
                    ->label('Status Lulus')
                    ->colors([
                        'warning' => 'TAHAP PENILAIAN',
                        'success' => 'LULUS',
                        'danger' => 'TIDAK LULUS',
                    ]),

                Tables\Columns\TextColumn::make('keterangan')->label('Keterangan')->limit(50),


            ])
            ->filters([
                //
            ])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilaiTahapSeleksis::route('/'),
            // 'create' => Pages\CreateNilaiTahapSeleksi::route('/create'),
            // 'edit' => Pages\EditNilaiTahapSeleksi::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return \App\Models\PesertaDidik::where('user_id', $user->id)
            ->where('status_ppdb', '!=', 'Peserta Baru')
            ->exists();
    }
}
