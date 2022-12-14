<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortofolioBeliModel;
use App\Models\PortofolioJualModel;
use DB;
use Validator;

class ReportAPIController extends Controller{

    public function getReport(Request $request){

        $rules=array(
            'user_id' => 'required',
            'tahun' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            return $validate->errors();
        }else{
            $user_id = $request->user_id;
            $tahun = $request->tahun;
            $beli = PortofolioBeliModel::selectRaw('tb_saham.`nama_saham`')
            ->selectRaw("SUM(tb_portofolio_beli.`harga_beli` - (tb_portofolio_beli.`harga_beli`*tb_portofolio_beli.`fee_beli_persen`/100)) AS beli_bersih")
            ->selectRaw('SUM(tb_portofolio_beli.`volume`) AS total_volume')
            ->where('tb_portofolio_beli.user_id', $user_id)
            ->whereRaw('YEAR(tb_portofolio_beli.`tanggal_beli`) = ' . $tahun)
            ->join('tb_saham', 'tb_portofolio_beli.id_saham', '=', 'tb_saham.id_saham')
            ->groupBy("tb_saham.nama_saham")
            ->get();

            $jual = PortofolioJualModel::selectRaw('tb_saham.`nama_saham`')
                ->selectRaw("SUM(tb_portofolio_jual.`harga_jual` - (tb_portofolio_jual.`harga_jual`*tb_portofolio_jual.`fee_jual_persen`/100)) AS jual_bersih")
                ->selectRaw('SUM(tb_portofolio_jual.`volume`) AS total_volume')
                ->where('tb_portofolio_jual.user_id', $user_id)
                ->whereRaw('YEAR(tb_portofolio_jual.`tanggal_jual`) = ' . $tahun)
                ->join('tb_saham', 'tb_portofolio_jual.id_saham', '=', 'tb_saham.id_saham')
                ->groupBy("tb_saham.nama_saham")
                ->get();

            $data = compact(['beli'],['jual']);
            $databeli = compact(['beli']);
            $datajual = compact(['jual']);

            if($beli->isNotEmpty() && $jual->isNotEmpty()){
                $data = compact(['beli'],['jual']);
                return response()->json(['messsage'=>'Berhasil', 'data'=>$data ],200);
            }else if($beli->isNotEmpty() && $jual->isEmpty()){
                $databeli = compact(['beli']);
                return response()->json(['messsage'=>'Berhasil, namun data jual tidak ditemukan', 'data'=>$databeli ],200);
            }else if($jual->isNotEmpty() && $beli->isEmpty()){
                $datajual = compact(['jual']);
                return response()->json(['messsage'=>'Berhasil, namun data beli tidak ditemukan', 'data'=>$datajual ],200);
            }else{
                return response()->json(['messsage'=>'Data tidak ditemukan'],404);
            }  
        }
    }
        

}
