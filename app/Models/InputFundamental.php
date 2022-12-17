<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputFundamental extends Model
{
    public $timestamps = false;
    protected $table = 'tb_input';
    protected $primary_key = 'id_input';
    protected $fillable = [
        'id_saham', 
        'aset', 
        'simpanan', 
        'pinjaman', 
        'saldo_laba',
        'ekuitas', 
        'jml_saham_edar', 
        'pendapatan',
        'laba_kotor',
        'laba_bersih', 
        'harga_saham', 
        'operating_cash_flow', 
        'investing_cash_flow', 
        'total_dividen', 
        'stock_split', 
        'tahun',
        'eps',
        'user_id',
    ];
}
