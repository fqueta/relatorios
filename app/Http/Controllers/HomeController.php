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
        if($mes == '01'){
          $mes = '12';
          $ano = date('Y') - 1;
        }
        $compleSql = " WHERE mes='$mes' AND ano='$ano' ";
        //$relatorios = relatorio::where('mes','=',$mes)->orWhere('ano','=',$ano)->get();
        //$complePub = " AND pri";
        //$relatorios = DB::select("SELECT DISTINCT mes,ano,id_publicador,privilegio,obs FROM relatorios $compleSql");
        $relatorios = DB::select("SELECT * FROM relatorios $compleSql");
        //echo '<pre>';
        //print_r($relatorio);
        //echo '</pre>';
        $arr_resumo = [
          'pr'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0],
          'pa'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0],
          'p'=>['relatorios'=>0,'publicacao'=>0,'video'=>0,'hora'=>0,'revisita'=>0,'estudo'=>0]
        ];
        $mesExt = Qlib::Meses();
        $config_table = [
          'tabelas'=>[
            'pr'=>['label'=>'Prioneiros Relegulares'],
            'pa'=>['label'=>'Prioneiros Auxiliares'],
            'p'=>['label'=>'Publicadores'],
          ],
          'titulos'=>[
            'publicacao'=>'Publicação','video'=>'Vídeos mostrados','hora'=>'Horas','revisita'=>'Revisitas','estudo'=>'Estudos bíblicos'
          ],
          'data'=>[
            'titulo'=>'RESUMO DOS RELATORIOS DE '.$mesExt[$mes].' DE '.$ano,
            'mes'=>$mes,
            'ano'=>$ano,
          ]
        ];
        if($relatorios){
          foreach ($relatorios as $key => $value) {
            //if(is_array($value)){
              foreach ($arr_resumo['p'] as $k => $v) {
                if($value->privilegio=='pa'){
                    if(isset($value->$k)){
                        $arr_resumo[$value->privilegio][$k] += $value->$k;
                    }
                }elseif($value->privilegio=='pr'){
                    if(isset($value->$k)){
                        $arr_resumo[$value->privilegio][$k] += $value->$k;
                        //$arr_resumo[$value->privilegio]['relatorios'] ++;
                    }
                }else{
                    if(isset($value->$k)){
                      $arr_resumo['p'][$k] += $value->$k;
                      //$arr_resumo['p']['relatorios'] ++;
                    }
                }
              }
              if($value->privilegio=='pr'){
                  $arr_resumo[$value->privilegio]['relatorios'] ++;
              }elseif($value->privilegio=='pa'){
                  $arr_resumo[$value->privilegio]['relatorios'] ++;
              }else{
                  $arr_resumo['p']['relatorios'] ++;

              }
          }
        }
        //dd($arr_resumo);
        $publicadores['relatorios'] = $relatorios;
        $publicadores['total_relatorios']['geral'] = count($relatorios);
        $publicadores['total_resumo'] = $arr_resumo;
        $publicadores['config_table'] = $config_table;
        $totalPubMes = '';
        $totalVid = '';
        //$resumo = $publicadores;
        return view('home',['resumo'=>$publicadores]);
    }
    public function resumo(){

    }
}
