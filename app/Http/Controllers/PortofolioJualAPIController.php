<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortofolioJualModel;
use App\Models\SahamModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Validator;

class PortofolioJualAPIController extends Controller
{


    public function getdata(Request $request){
        $rules=array(
            'user_id' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            return $validate->errors();
        }else{
            $user_id = $request->user_id;
            $dataporto = PortofolioJualModel::where('user_id', $user_id)->join('tb_saham', 'tb_portofolio_jual.id_saham', '=', 'tb_saham.id_saham')->get();

            if($dataporto->isNotEmpty()){
                return response()->json(['messsage'=>'Berhasil', 'data'=>$dataporto ],200);
            }else{
                return response()->json(['messsage'=>'Data tidak ditemukan'],404);
            }
        }
        
    }
    public function insertData(Request $request){


        $rules=array(
            'id_saham' => 'required',
            'user_id' => 'required',
            'id_jenis_saham' => 'required',
            'volume' => 'required',
            'tanggal_jual' => 'required',
            'harga_jual' => 'required',
            'fee_jual_persen' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            return $validate->errors();
        }else{
    
            $getEmiten = SahamModel::select('nama_saham')
                ->where('id_saham', $request->id_saham)
                ->first();
            $emiten = $getEmiten->nama_saham;

            $response = Http::acceptJson()
            ->withHeaders([
                'X-API-KEY' => 'pCIjZsjxh8So9tFQksFPlyF6FbrM49'
            ])->get('https://api.goapi.id/v1/stock/idx/'.$emiten)->json();

            $data = $response['data']['last_price'];
            $closeprice = $response['data']['last_price']['close'];
            $harga_jual = $request->harga_jual;
            $close_persen = round((($harga_jual - $closeprice)/$harga_jual) * 100);
            
            $insert = PortofolioJualModel::create([
                'id_saham' => $request->id_saham,
                'user_id' => $request->user_id,
                'jenis_saham' => $request->id_jenis_saham,
                'volume' => $request->volume,
                'tanggal_jual' => $request->tanggal_jual,
                'harga_jual' => $request->harga_jual,
                'fee_jual_persen' => $request->fee_jual_persen,
                'close_persen' => $close_persen
            ]);

            $insert->save();
            return response()->json(['messsage'=>'Data Berhasil Masuk', 'data'=>$insert ],201);
        }

    }

    public function editData(Request $request){
        $rules=array(
            'id_saham' => 'required',
            'user_id' => 'required',
            'id_jenis_saham' => 'required',
            'volume' => 'required',
            'tanggal_jual' => 'required',
            'harga_jual' => 'required',
            'fee_jual_persen' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            return $validate->errors();
        }else{
            $dataporto = PortofolioJualModel::where('id_portofolio_jual', $request->id_portofolio_jual)->first();
            
            if($dataporto!=null){
                $getEmiten = SahamModel::select('nama_saham')
                ->where('id_saham', $request->id_saham)
                ->first();

                $emiten = $getEmiten->nama_saham;

                $response = Http::acceptJson()
                ->withHeaders([
                    'X-API-KEY' => 'pCIjZsjxh8So9tFQksFPlyF6FbrM49'
                ])->get('https://api.goapi.id/v1/stock/idx/'.$emiten)->json();

                $data = $response['data']['last_price'];
                $closeprice = $response['data']['last_price']['close'];
                $harga_jual = $request->harga_jual;
                $close_persen = round((($harga_jual - $closeprice)/$harga_jual) * 100);
                
                
                $dataporto->id_saham = $request->id_saham;
                $dataporto->user_id = $request->user_id;
                $dataporto->jenis_saham = $request->id_jenis_saham;
                $dataporto->volume = $request->volume;
                $dataporto->tanggal_jual = $request->tanggal_jual;
                $dataporto->harga_jual = $request->harga_jual;
                $dataporto->fee_jual_persen = $request->fee_jual_persen;
                $dataporto->close_persen = $close_persen;
                $dataporto->save();
                

                return response()->json(['messsage'=>'Data Berhasil di Update' ],200);
            }else{
                return response()->json(['messsage'=>'Data Tidak Ditemukan' ],404);
            }
            
        }

        
    }

    public function deleteData(Request $request){

        $rules=array(
            'id_portofolio_jual' => 'required',
        );
        $validate=Validator::make($request->all(),$rules);
        if ($validate->fails()){
            return $validate->errors();
        }else{
            $dataporto = PortofolioJualModel::where('id_portofolio_jual', $request->id_portofolio_jual)->first();
            //return response()->json(['messsage'=>$dataporto], 404);
            if($dataporto != null){
                $dataporto->delete();
                return response()->json(['messsage'=>'Data Berhasil di Delete' ],200);
            }else{
                
                return response()->json(['messsage'=>'Data tidak ditemukan'], 404);
            }
            
        }
       
    }
}
