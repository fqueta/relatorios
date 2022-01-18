<?php

namespace App\Http\Controllers;

use App\Models\relatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Qlib\Qlib;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function teste(){
      $teste = [1,2,3,4];
      Qlib::printV($teste);
      //return view('teste');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ano_servico = date('Y');
        $ano_atual = $ano_servico;
        $mes_atual = (int)date('m');
        if($mes_atual > 8){
          $ano_servico++;
        }
        $ano = isset($_GET['ano'])?$_GET['ano']:$ano_servico;
        if($ano < $ano_servico){
            $ano_servico = $ano;
        }else{
          if($mes_atual > 8){
            $ano = $ano+1;
          }
        }

        $mes = isset($_GET['mes'])?$_GET['mes']:date('m');
        //$relatorio = relatorio::where('mes','=',$mes)->orWhere('ano','=',$ano)->get();
        $relatorios = DB::select("SELECT DISTINCT mes,ano,id_publicador,obs FROM relatorios WHERE mes='$mes' AND ano='$ano' ");
        //echo '<pre>';
        //print_r($relatorio);
        //echo '</pre>';
        $publicadores['relatorios'] = $relatorios;
        $totalPubMes = '';
        $totalVid = '';
        $resumo = [
            'publicadores'=>$publicadores
        ];

        return view('home',['resumo'=>$resumo]);
    }
    public function resumo(){

    }
}
