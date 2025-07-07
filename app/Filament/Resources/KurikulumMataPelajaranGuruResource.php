<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KurikulumMataPelajaranGuruResource\Pages;
use App\Models\KurikulumMataPelajaranGuru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KurikulumMataPelajaranGuruResource extends Resource
{
    protected static ?string $model = KurikulumMataPelajaranGuru::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Mapping Kurikulum';
    protected static ?string $pluralModelLabel = 'Mapping Kurikulum';

    protected static ?string $navigationGroup = 'Manajemen Kurikulum';

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Staff Kurikulum') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kurikulum_id')
                ->relationship('kurikulum', 'nama_kurikulum')
                ->label('Kurikulum')
                ->required(),

            Forms\Components\Select::make('mata_pelajaran_id')
                ->relationship('mataPelajaran', 'nama_mapel')
                ->label('Mata Pelajaran')
                ->required(),

            Forms\Components\Select::make('guru_id')
                ->relationship('guru', 'nama')
                ->label('Guru Pengampu')
                ->required(),

            Forms\Components\TextInput::make('jam_pelajaran')
                ->numeric()
                ->minValue(1)
                ->label('Jumlah Waktu Pelajaran')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('kurikulum.nama_kurikulum')
                ->label('Kurikulum')
                ->searchable(),

            Tables\Columns\TextColumn::make('mataPelajaran.nama_mapel')
                ->label('Mata Pelajaran')
                ->searchable(),

            Tables\Columns\TextColumn::make('guru.nama')
                ->label('Guru'),

            Tables\Columns\TextColumn::make('jam_pelajaran')
                ->label('Waktu Pelajaran')
                ->sortable()
                ->summarize(
                    Tables\Columns\Summarizers\Sum::make()
                        ->label('Total Waktu')
                        ->formatStateUsing(fn($state) => $state . ' menit'),
                ),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('kurikulum_id')
                    ->label('Kurikulum')
                    ->relationship('kurikulum', 'nama_kurikulum')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama_mapel')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('guru_id')
                    ->label('Guru')
                    ->relationship('guru', 'nama')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKurikulumMataPelajaranGurus::route('/'),
            'create' => Pages\CreateKurikulumMataPelajaranGuru::route('/create'),
            'edit' => Pages\EditKurikulumMataPelajaranGuru::route('/{record}/edit'),
        ];
    }
}
