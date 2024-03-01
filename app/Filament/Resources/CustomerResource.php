<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Filament\Resources\CustomerResource\Pages\ManageCustomers;

class CustomerResource extends Resource
{
    protected static ?string $navigationGroup = 'Market';
    public static ?string $navigationLabel = 'Pembeli';

    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('NamaPelanggan')
                    ->label('Customer Name')
                    ->required(),
                TextInput::make('Alamat')
                    ->label('Address')
                    ->required(),
                TextInput::make('NomorTelepon')
                    ->label('Phone Number')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('PelangganID')
                ->searchable()
                ->label('Customer ID'),
                TextColumn::make('NamaPelanggan')
                    ->searchable()
                    ->label('Customer Name'),
                TextColumn::make('Alamat')
                    ->searchable()
                    ->label('Address'),
                TextColumn::make('NomorTelepon')
                    ->searchable()
                    ->label('Phone Number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
