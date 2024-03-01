<?php

namespace App\Filament\Resources\SaleResource\Pages;

use Filament\Actions;
use App\Models\Product;
use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        foreach ($this->record->saledetails as $saledetail) {
            $productModel = Product::find($saledetail['ProdukID']);
            if ($productModel->Stok < $saledetail['JumlahProduk']) {
                // Not enough stock available
                Notification::make()
                    ->title('Sales Not Created')
                    ->body('Not enough stock available for product:' . $productModel->NamaProduk)
                    ->seconds(10)
                    ->danger()
                    ->send();

                    $this->record->delete();
                    return;
            }
            $productModel->Stok -= $saledetail['JumlahProduk'];
            $productModel->save();

        }
    }

    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }

    public static function canAccess(array $parameters = []): bool
{
    return auth()->user()->level == 0;
}

    protected static ?string $navigationGroup = 'Market';
    public static ?string $navigationLabel = 'Penjualan';


}


