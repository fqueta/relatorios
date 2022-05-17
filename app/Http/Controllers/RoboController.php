<?php

namespace App\Http\Controllers;

use App\Models\Publicador;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoboController extends Controller
{
    public function lerRelatorios($id_publicador=false)
    {
        $ret = false;
        if($id_publicador){
            $pub = Publicador::where('ativo','=','s')->where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','asc')->where('id','=',$id_publicador)->get();
        }else{
            $pub = Publicador::where('ativo','=','s')->where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','asc')->get();
        }
        if(!empty($pub)){
            foreach ($pub as $k => $v) {

                $total=$pub->count();
                $interv = $this->intervaloData();
                $compleSql = false;
                //$sql = "SELECT SUM({campo}) FROM relatorios as tot ";
                $somaHoras = NULL;
                $somaRev = NULL;
                $tags = false;
                if(is_array($interv)){
                    foreach ($interv as $ki => $vi) {
                        $d = explode('/',$vi['data']);
                        $intervData = false;
                        if(isset($d[2])){
                            $intervData = " AND mes='".$d[1]."' AND ano='".$d[2]."'";
                        }
                        $compleSql[$ki] = $intervData;
                        $sum = Qlib::somarCampo('relatorios','hora',
                            "WHERE id_publicador='".$v['id']."' $intervData"
                        );
                        $rev = Qlib::somarCampo('relatorios','revisita',
                            "WHERE id_publicador='".$v['id']."' $intervData"
                        );
                        if($sum){
                            $somaHoras += $sum;
                        }else{
                            if(!$tags)
                            $tags[] = 'inrregular';
                        }
                        if($rev)
                            $somaRev += (int)$rev;
                    }
                }
                $ret[$k]['compleSql'] = $compleSql;
                $ret[$k]['somaHoras'] = $somaHoras;
                $ret[$k]['somaRev'] = $somaRev;
                $ret[$k]['id_publicador'] = $v['id'];
                if(!$somaRev){
                    $tags[] = 'sem_revisitas_6meses';
                }
                $ret[$k]['tags'] = $tags;
                $compleSalv = false;
                $sqlSal = "UPDATE IGNORE publicadores SET updated_at = '".date('Y-m-d H:m:i')."'{compleSalv} WHERE id='".$v['id']."'";
                $data = false;
                if($somaHoras){
                    $compleSalv .= ",inativo='n'";
                    $data['inativo'] = 'n';
                }else{
                    $data['inativo'] = 's';
                    $compleSalv .= ",inativo='s'";
                }
                if($tags){
                    $compleSalv .= ",tags='".Qlib::lib_array_json($tags)."'";
                    $data['tags'] = Qlib::lib_array_json($tags);
                }else{
                    $data['tags'] = [];
                }
                $sqlSal = str_replace('{compleSalv}',$compleSalv,$sqlSal);
                $ret[$k]['sqlSal'] = $sqlSal;
                if($data)
                    $ret[$k]['salvar'] = Publicador::where('id',$v['id'])->update($data);
            }
        }
        $retu['exec'] = false;
        $retu['mens'] = Qlib::formatMensagemInfo('Erro ao executar robo','danger');
        if($ret){
            $retu['exec'] = true;
            $retu['mens'] = Qlib::formatMensagemInfo('Robo Executado com sucesso','success');
            $retu['ret'] = $ret;
        }
        return response()->json($retu);
    }
    public function intervaloData($mesesAtras = 6)
    {
        $ret = false;
        if($mesesAtras){
            $hoje = date('d/m/Y');
            $datai = Qlib::CalcularMeses($hoje,$mesesAtras);
            for ($mes=0; $mes <$mesesAtras ; $mes++) {
                $ret[$mes]['data'] = Qlib::CalcularMeses($hoje,($mesesAtras-$mes));
            }
        }
        return $ret;
    }
    /*
    public function removerEtiqueta($var = null)
    {
        (!$var){

        }
    }*/
}
