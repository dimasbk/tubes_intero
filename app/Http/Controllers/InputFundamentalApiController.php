<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InputFundamental;
use App\Models\SahamModel;
use Validator;

class InputFundamentalApiController extends Controller
{
    public function read_all_data(Request $request){
        $rules=array(
            'user_id' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            //check jika ada value kosong
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'error'    => $validate->errors()
            ],401);

        }else{
            $data = InputFundamental::where('user_id', '=', $request->user_id)->first();
            if($data != null){
                return response()->json([
                    'messsage'=>'Berikut data yang sudah ada', 
                    'result'=>$data,
                ],200);
            }else{
                //Jika table di database kosong       
                return response()->json([
                    'messsage'=>'Data tidak ada, silahkan masukan data'
                ], 404);
            }
        }
    }

    public function insert_data(Request $request){
        $rules=array(
            //Value yang harus diisi
            'id_saham' => 'required',
            'aset' => 'required',
            'simpanan' => 'required',
            'pinjaman' => 'required',
            'saldo_laba' => 'required',
            'ekuitas' => 'required',
            'jml_saham_edar' => 'required',
            'pendapatan' => 'required',
            'laba_kotor' => 'required',
            'laba_bersih' => 'required',
            'harga_saham' => 'required',
            'operating_cash_flow' => 'required',
            'investing_cash_flow' => 'required',
            'total_dividen' => 'required',
            'stock_split' => 'required',
            'tahun' => 'required',
            'user_id'=> 'required'
        );

        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            //check jika ada value kosong
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'error'    => $validate->errors()
            ],401);
        }else{
            //Semua value ada

            $saham_validation = SahamModel::where('id_saham', '=', $request->id_saham)->first();
            if ($saham_validation === null) {
            // Id Saham tidak ditemukan
                return response()->json([
                    'success' => false,
                    'message' => 'Id Saham tidak ditemukan',
                ],404);
            }


            //perhitungan eps
            $stck_split = $request->stock_split;
            $lb_bersih = $request->laba_bersih;
            $jmlh_saham_bredar = $request->jml_saham_edar;
            $eps = $lb_bersih/($jmlh_saham_bredar*$stck_split);

            //insert data
            $insert = InputFundamental::create([
                'id_saham' => $request->id_saham,
                'aset' => $request->aset,
                'simpanan' => $request->simpanan,
                'pinjaman' => $request->pinjaman,
                'saldo_laba' => $request->saldo_laba,
                'ekuitas' => $request->ekuitas,
                'jml_saham_edar' => $request->jml_saham_edar,
                'pendapatan' => $request->pendapatan,
                'laba_kotor' => $request->laba_kotor,
                'laba_bersih' => $request->laba_bersih,
                'harga_saham' => $request->harga_saham,
                'operating_cash_flow' => $request->operating_cash_flow,
                'investing_cash_flow' => $request->investing_cash_flow,
                'total_dividen' => $request->total_dividen,
                'stock_split' => $request->stock_split,
                'tahun' => $request->tahun,
                'eps'=> $eps,
                'user_id'=>$request->user_id,
            ]);
            $insert->save();
            return response()->json([
                'messsage'=>'Data Berhasil dimasukan',
                'data'=>$insert
            ],201);
        }
    }
}
