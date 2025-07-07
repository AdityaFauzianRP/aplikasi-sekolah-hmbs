<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KurikulumResource\Pages;
use App\Models\Kurikulum;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class KurikulumResource extends Resource
{
    protected static ?string $model = Kurikulum::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Data Kurikulum';

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Staff Kurikulum') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kurikulum')
                    ->schema([
                        Forms\Components\TextInput::make('kode_kurikulum')
                            ->label('Kode Kurikulum')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nama_kurikulum')
                            ->label('Nama Kurikulum')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('jenjang_pendidikan')
                            ->label('Jenjang Pendidikan')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                                'SMK' => 'SMK',
                            ])
                            ->required(),
                        Forms\Components\Select::make('jurusan_id')
                            ->label('Jurusan')
                            ->options(Jurusan::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('tahun_mulai')
                            ->label('Tahun Mulai')
                            ->required()
                            ->maxLength(4),
                        Forms\Components\TextInput::make('tahun_selesai')
                            ->label('Tahun Selesai')
                            ->maxLength(4),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Tidak Aktif' => 'Tidak Aktif',
                            ])
                            ->default('Aktif')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kurikulum')->label('Kode Kurikulum')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nama_kurikulum')->label('Nama Kurikulum')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('jenjang_pendidikan')->label('Jenjang Pendidikan')->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama')->label('Jurusan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tahun_mulai')->label('Tahun Mulai')->sortable(),
                Tables\Columns\TextColumn::make('tahun_selesai')->label('Tahun Selesai')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListKurikulums::route('/'),
            'create' => Pages\CreateKurikulum::route('/create'),
            'edit' => Pages\EditKurikulum::route('/{record}/edit'),
        ];
    }
}
