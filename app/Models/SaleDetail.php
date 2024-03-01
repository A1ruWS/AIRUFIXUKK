<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'PenjualanID',
        'ProdukID',
        'JumlahProduk',
        'Subtotal',
    ];

    protected $primaryKey = 'DetailID';

    public function penjualan(){
        return $this->hasMany(Sale::class, 'PenjualanID', 'PenjualanID');
    }
    public function produk(){
        return $this->belongsTo(Product ::class, 'ProdukID', 'ProdukID');
    }

}
