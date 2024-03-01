<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Ramsey\Collection\Set;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use App\Filament\Resources\SaleResource\Pages\EditSale;
use App\Filament\Resources\SaleResource\Pages\ListSales;
use App\Filament\Resources\SaleResource\Pages\CreateSale;
use App\Filament\Resources\SaleResource\RelationManagers;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Wizard::make([
                Step::make('Detail Pembelian')
                    ->schema([
                        Select::make('PelangganID')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->relationship('pelanggan', 'Namapelanggan'),
                    ]),
                Step::make('Order Produk')
                    ->schema([
                        Repeater::make('produk')
                        ->required()
                        ->label('Pilih Produk')
                        ->columns(3)
                        ->defaultItems(1)
                        ->live()
                        ->relationship('saledetails')
                        ->schema([
                            Select::make('ProdukID')
                                ->preload()
                                ->required()
                                ->searchable()
                                ->relationship('produk', 'NamaProduk')
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    $set(['Subtotal' => Product::find($state) ? Product::find($state)->Harga ?? 0 : 0]);
                                }),

                                TextInput::make('JumlahProduk')
                                ->numeric()
                                ->default(1)
                                ->reactive()
                                ->live()
                                ->disabled(fn ($state, $get) => is_null($get('ProdukID')))
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $productModel = Product::find($get('ProdukID'));
                                    if ($state > $productModel->Stok) {
                                        $state = $productModel->Stok;
                                        Notification::make()
                                            ->title('Not enough stock')
                                            ->body('The quantity you requested for product ' . $productModel->NamaProduk . ' is not available. The current stock: ' . $state)
                                            ->seconds(10)
                                            ->danger()
                                            ->send();
                                    }
                                    $set('Subtotal', $productModel->Harga * $state);
                                })
                                ->rules('required'),

                            TextInput::make('Subtotal')
                                ->disabled()
                                ->numeric()
                                ->dehydrated()
                                ->prefix('Rp.')
                                ->required(),

                            ]),

                            TextInput::make('TotalHarga')
                            ->required()
                            ->default(0)
                            ->prefix('Rp.')
                            ->numeric()
                            ->inputMode('decimal')
                            ->mask(fn (Get $get)=>collect($get('produk'))->pluck('Subtotal')->sum()),
                    ]),

            ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('PenjualanID')
                ->searchable()
                ->label('Penjualan ID'),
                TextColumn::make('TanggalPenjualan')
                    ->searchable()
                    ->dateTime('d M Y')
                    ->label('Tanggal Penjualan'),
                TextColumn::make('TotalHarga')
                    ->label('Total Harga'),
                TextColumn::make('pelanggan.NamaPelanggan')
                    ->searchable()
                    ->label('nama pelanggan'),
                TextColumn::make('pelanggan.Alamat')
                    ->searchable()
                    ->label('Alamat'),
                TextColumn::make('saledetails.produk.NamaProduk')
                    ->searchable()
                    ->label('nama product'),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
