<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbResource\Pages;
use App\Models\Ppdb;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class PpdbResource extends Resource
{
    protected static ?string $model = Ppdb::class;
    protected static ?string $navigationLabel = 'Aktivitas PPDB';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Management PPDB';

    protected static ?string $label = 'Aktivitas PPDB';
    protected static ?string $pluralLabel = 'Aktivitas PPDB';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Tabs::make('Formulir PPDB')
                ->columnSpanFull() // <-- ini untuk full width
                ->tabs([
                    Tabs\Tab::make('Informasi Umum')
                        ->schema([
                            TextInput::make('judul_ppdb')
                                ->label('Judul PPDB')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('kuota_peserta_ppdb')
                                ->label('Kuota Peserta')
                                ->numeric()
                                ->required(),

                            DatePicker::make('tanggal_mulai_ppdb')
                                ->label('Tanggal Mulai')
                                ->required(),

                            DatePicker::make('tanggal_selesai_ppdb')
                                ->label('Tanggal Selesai')
                                ->required(),
                        ]),
                    Tabs\Tab::make('Seleksi PPDB')
                        ->schema([
                            Repeater::make('tahap_seleksi')
                                ->label('Tahap Seleksi')
                                ->relationship('tahapSeleksi') // ini nama fungsi relasi di model Ppdb
                                ->schema([
                                    TextInput::make('nama_tahap')
                                        ->label('Nama Tahap')
                                        ->required(),

                                    DatePicker::make('tanggal_mulai')
                                        ->label('Tanggal Mulai')
                                        ->required(),

                                    DatePicker::make('tanggal_selesai')
                                        ->label('Tanggal Selesai')
                                        ->required(),
                                ])
                                ->createItemButtonLabel('Tambah Tahap')
                                ->columns(3)
                        ]),
                ])
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('judul_ppdb')->label('Judul'),
                TextColumn::make('kuota_peserta_ppdb')->label('Kuota'),
                TextColumn::make('tanggal_mulai_ppdb')->label('Mulai')->date(),
                TextColumn::make('tanggal_selesai_ppdb')->label('Selesai')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
            
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPpdbs::route('/'),
            'create' => Pages\CreatePpdb::route('/create'),
            'edit' => Pages\EditPpdb::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Panitia Ppdb') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
