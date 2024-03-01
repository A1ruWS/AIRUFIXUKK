<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Pages\ManageProducts;

class ProductResource extends Resource
{
    protected static ?string $navigationGroup = 'Market';
    public static ?string $navigationLabel = 'Produk';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ProdukID')
                ->label('Product ID')
                ->hiddenOn('create'),
                TextInput::make('NamaProduk')
       	             ->required()
                    ->label('Nama Produk'),
                TextInput::make('Harga')
                    ->numeric()
                    ->required()
                    ->label('Harga')
                    ->step(1000),
                TextInput::make('Stok')
                    ->numeric()
                    ->required()
                    ->label('Stock')
                    ->step(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('ProdukID')
                        ->label('Product ID'),
                    TextColumn::make('NamaProduk')
                        ->label('Nama Produk')
                        ->searchable(),
                    TextColumn::make('Harga')
                        ->numeric()
                        ->label('Harga'),
                    TextColumn::make('Stok')
                        ->numeric()
                        ->label('Stock'),

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
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
