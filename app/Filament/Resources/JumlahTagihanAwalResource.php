<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JumlahTagihanAwalResource\Pages;
use App\Filament\Resources\JumlahTagihanAwalResource\RelationManagers;
use App\Models\JumlahTagihanAwal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class JumlahTagihanAwalResource extends Resource
{
    protected static ?string $model = JumlahTagihanAwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Setting Cicilan Uang Pangkal';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $label = 'Setting Cicilan Uang Pangkal';

    protected static ?string $pluralLabel = 'Setting Cicilan Uang Pangkal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jurusan_id')
                    ->relationship('jurusan', 'nama')
                    ->required(),
                Forms\Components\Select::make('ppdb_id')
                    ->relationship('ppdb', 'judul_ppdb')
                    ->required(),
                Forms\Components\TextInput::make('total_tagihan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('jumlah_cicilan')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('jurusan.nama')->searchable(),
                Tables\Columns\TextColumn::make('ppdb.judul_ppdb')->searchable(),
                Tables\Columns\TextColumn::make('total_tagihan')
                    ->money('IDR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_cicilan')
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJumlahTagihanAwals::route('/'),
            'create' => Pages\CreateJumlahTagihanAwal::route('/create'),
            'edit' => Pages\EditJumlahTagihanAwal::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
