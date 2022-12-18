<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OutputFundamental;
use App\Models\InputFundamental;
use Validator;


class OutputFundamentalApiController extends Controller
{
    public function insert_data_output(Request $request){
        $rules=array(
            //Value yang harus diisi
            'id_input' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            //check jika ada value kosong
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'error'    => $validate->errors()
            ],422);

        }else{
            //Semua value ada

            $id_input_validation = InputFundamental::where('id_input', '=', $request->id_input)->first();
            if ($id_input_validation === null) {
            // Id Input  tidak ditemukan
                return response()->json([
                    'success' => false,
                    'message' => 'Id Input Fundamental tidak ditemukan',
                ],404);
            }

            //Melakukan fetch row data
            $data_input = $id_input_validation;
            
            //Pembuatan variable
            $userid= $data_input->user_id;
            $id_saham=$data_input->id_saham; 
            $aset = $data_input->aset; 
            $simpanan = $data_input->simpanan; 
            $pinjaman = $data_input->pinjaman; 
            $saldo_laba = $data_input->saldo_laba; 
            $ekuitas = $data_input->ekuitas; 
            $jml_saham_edar = $data_input->jml_saham_edar; 
            $pendapatan = $data_input->pendapatan; 
            $laba_kotor = $data_input->laba_kotor; 
            $laba_bersih = $data_input->laba_bersih; 
            $harga_saham = $data_input->harga_saham; 
            $operating_cash_flow = $data_input->operating_cash_flow; 
            $investing_cash_flow = $data_input->investing_cash_flow; 
            $total_dividen = $data_input->total_dividen; 
            $stock_split = $data_input->stock_split; 
            $tahun = $data_input->tahun; 
            $eps = $data_input->eps;
            
            
            
            $eps_last_year = $tahun-1;
            $eps_last_year_validation = InputFundamental::where('tahun','=', $eps_last_year)->where('id_saham','=', $data_input->id_saham)->first();
            if ($eps_last_year_validation === null) {
            // eps tahun lalu tidak ditemukan
                return response()->json([
                    'success' => false,
                    'message' => 'EPS tahun sebelumnya tidak ditemukan',
                ],404);
            }

            //Menyimpan EPS tahun lalu
            $eps_last_year_result =  $eps_last_year_validation->eps;
            //Persentase eps tahun ke-n sesuai input
            $percentage_eps=($eps-$eps_last_year_result)/$eps_last_year_result;

            // perhitungan tb_output
            $loan_to_depo_ratio=round($pinjaman/$simpanan, 2);
            $annualized_roe= round($laba_bersih/$ekuitas, 2);
            $dividen= round($total_dividen/$jml_saham_edar/$stock_split, 2);
            $dividen_yield =  round($dividen/$harga_saham, 2);
            $dividen_payout_ratio = round($total_dividen/$laba_bersih, 2);
            $annualized_per =  round($harga_saham/$eps, 2); 
            $annualized_roa =  round($laba_bersih/$aset, 2); 
            $gpm = round($laba_kotor/$pendapatan, 2);
            $npm = round($laba_bersih/$pendapatan, 2);
            $eer = round($saldo_laba/$ekuitas, 2); 
            $ear = round($ekuitas/$aset, 2); 
            $market_cap = $harga_saham*$jml_saham_edar*$stock_split; 
            $market_cap_asset_ratio = round($market_cap/$aset, 2); 
            $cfo_sales_ratio = round($operating_cash_flow/$pendapatan, 2); 
            $capex_cfo_ratio = round($investing_cash_flow/$operating_cash_flow, 2); 
            $market_cap_cfo_ratio = round($market_cap/$operating_cash_flow, 2); 
            $peg = $annualized_per/($percentage_eps*100);
            $harga_saham_sum_dividen = $harga_saham+$dividen;
            $pbv = round($market_cap/$ekuitas, 2); 
    
            //insert data
            $insert = OutputFundamental::create([
                'id_saham' => $id_saham,
                'loan_to_depo_ratio'=> $loan_to_depo_ratio,
                'annualized_roe'=> $annualized_roe,
                'dividen'=> $dividen,
                'dividen_yield'=> $dividen_yield,
                'dividen_payout_ratio'=> $dividen_payout_ratio,
                'pbv'=> $pbv,
                'annualized_per'=> $annualized_per,
                'annualized_roa'=> $annualized_roa,
                'gpm'=> $gpm, 
                'npm'=> $npm,
                'eer'=> $eer,
                'ear'=> $ear,
                'market_cap'=> $market_cap, 
                'market_cap_asset_ratio'=> $market_cap_asset_ratio, 
                'cfo_sales_ratio'=> $cfo_sales_ratio,
                'capex_cfo_ratio'=> $capex_cfo_ratio,
                'market_cap_cfo_ratio'=> $market_cap_cfo_ratio,
                'peg'=> $peg,
                'harga_saham_sum_dividen'=> $harga_saham_sum_dividen,
                'tahun'=> $tahun,
                'user_id'=> $userid,
            ]);
            $insert->save();
            return response()->json([
                'messsage'=>'Data Berhasil dimasukan',
                'data'=>$insert
            ],201);
        }
    }
}
