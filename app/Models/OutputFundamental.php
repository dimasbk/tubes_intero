<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputFundamental extends Model
{
    public $timestamps = false;
    protected $table = 'tb_output';
    protected $primary_key = 'id_output';
    protected $fillable = [
        'id_saham', 
        'loan_to_depo_ratio', 
        'annualized_roe', 
        'dividen', 
        'dividen_yield',
        'dividen_payout_ratio', 
        'pbv', 
        'annualized_per',
        'annualized_roa',
        'gpm', 
        'npm', 
        'eer', 
        'ear', 
        'market_cap', 
        'market_cap_asset_ratio', 
        'cfo_sales_ratio',
        'capex_cfo_ratio',
        'peg',
        'market_cap_cfo_ratio',
        'harga_saham_sum_dividen',
        'tahun',
        'user_id'
    ];
}
