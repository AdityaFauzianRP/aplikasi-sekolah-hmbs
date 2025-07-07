<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataPelajaranResource\Pages;
use App\Models\MataPelajaran;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Staff Kurikulum') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin');
    }

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $pluralModelLabel = 'Mata Pelajaran';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_mapel')
                    ->label('Kode Mata Pelajaran')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jurusan_id')
                    ->label('Jurusan')
                    ->options(function () {
                        return Jurusan::pluck('nama', 'id')->toArray();
                    })
                    ->nullable()
                    ->searchable(),
                Forms\Components\Select::make('jenjang_pendidikan')
                    ->label('Jenjang Pendidikan')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'SMK' => 'SMK',
                    ])
                    ->required(),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_mapel')->label('Kode'),
                Tables\Columns\TextColumn::make('nama_mapel')->label('Nama'),
                Tables\Columns\TextColumn::make('jurusan.nama')->label('Jurusan')->sortable(),
                Tables\Columns\TextColumn::make('jenjang_pendidikan')->label('Jenjang'),
                Tables\Columns\TextColumn::make('semester')->label('Semester'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataPelajarans::route('/'),
            'create' => Pages\CreateMataPelajaran::route('/create'),
            'edit' => Pages\EditMataPelajaran::route('/{record}/edit'),
        ];
    }
}
