<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffKurikulumResource\Pages;
use App\Models\StaffKurikulum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StaffKurikulumResource extends Resource
{
    protected static ?string $model = StaffKurikulum::class;


    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin');
    }


    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?string $label = 'Data Staff Kurikulum';
    protected static ?string $pluralLabel = 'Data Staff Kurikulum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('nip')
                    ->maxLength(30),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->required(),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(100),
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Textarea::make('alamat')
                    ->rows(3),
                Forms\Components\TextInput::make('pendidikan_terakhir')
                    ->maxLength(100),
                Forms\Components\TextInput::make('status_kepegawaian')
                    ->maxLength(100),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('staff-kurikulum-foto')
                    ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('telepon'),
                Tables\Columns\TextColumn::make('status_kepegawaian'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStaffKurikulums::route('/'),
            'create' => Pages\CreateStaffKurikulum::route('/create'),
            'edit' => Pages\EditStaffKurikulum::route('/{record}/edit'),
        ];
    }
}
