<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'TotalHarga',
        'PelangganID',
        'TanggalPenjualan',
    ];

    protected $primaryKey = 'PenjualanID';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($penjualan) {
            $penjualan->tanggalpenjualan = now()->toDateString();
        });
    }

    protected $guarded = [];
    public function pelanggan()
    {
        return $this->belongsTo(customer::class, 'PelangganID', 'PelangganID');
    }
    public function produk()
    {
        return $this->belongsTo(Product::class);
    }
    public function saledetails()
    {
        return $this->hasMany(SaleDetail::class, 'PenjualanID', 'PenjualanID');
    }

}
