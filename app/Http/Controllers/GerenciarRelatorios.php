<?php

namespace App\Http\Controllers;

use App\Models\relatorio;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
class GerenciarRelatorios extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $relatorios = grupo::all();
        $title = 'Todos os relatorios';
        $titulo = $title;
        return view('relatorios.index',['relatorios'=>$relatorios,'title'=>$title,'titulo'=>$titulo]);
    }
    public function create()
    {
        $title = 'Cadastrar um grupo';
        $titulo = $title;
        return view('relatorios.create',['title'=>$title,'titulo'=>$titulo]);
    }
    public function store(Request $request)
    {
        //return redirect()->route('relatorios-index');
        $dados = $request->all();
        //$dados['enviado_por'] = '{"user_id":"4","nome":"Waldir Bertges","ip":"177.104.65.201"}';
        $arr_obs = ['p'=>'','pa'=>'Pioneiro Auxiliar','pr'=>'Pioneiro Regular'];
        if(isset($dados['id_publicador'])){
          $dadosPub = DB::select("SELECT pioneiro FROM usuarios WHERE id='".$dados['id_publicador']."'");
          if($dadosPub){
            if(empty($dadosPub[0]->pioneiro)){
              $privilegio = 'p';
            }else{
              $privilegio = trim($dadosPub[0]->pioneiro);
            }
          }else{
            $privilegio = 'p';
          }
        }
        //dd($dadosPub);
        $dados['privilegio'] = $privilegio;
        if($privilegio!='p'){
          $dados['obs'] = $arr_obs[$privilegio].' '.$dados['obs'];
        }
        //dd($dados);
        $salvarRelatorios = relatorio::create($dados);
        /*if(isset($dados['var_cartao']) && !empty($dados['var_cartao'])){
            $json_cartao = base64_decode($dados['var_cartao']);
            $arr_cartao = json_decode($json_cartao,true);
            $dados['id_publicador'] = isset($arr_cartao['dados']['id'])?$arr_cartao['dados']['id']:0;
            $dados['id_grupo'] = isset($arr_cartao['dados']['id_grupo'])?$arr_cartao['dados']['id_grupo']:0;
            $dados['ano'] = isset($arr_cartao['ano_servico'])?$arr_cartao['ano_servico']:0;
            $dados['mes']
            //var_dump($arr_cartao);
            echo $arr_cartao['ano'];
        }*/
        $ret['exec'] = false;
        if($salvarRelatorios){
          $GerenciarUsuarios = new GerenciarUsuarios;
          $ret['exec'] = true;
          $ret['salvarRelatorios'] = $salvarRelatorios;
          $ret['mens'] = 'Registro gravado com sucesso!';
          $ret['cartao']=$GerenciarUsuarios->cardData($dados['id_publicador']);
        }else{
          $ret['mens'] = 'Erro ao gravar!';
        }
        return json_encode($ret);
        //echo json_encode($dados);
    }
    public function edit($id)
    {
        $relatorios = grupo::where('id',$id)->first();
        if(!empty($relatorios)){
          $title = 'Editar um grupo';
          $titulo = $title;
          return view('relatorios.edit',['relatorios'=>$relatorios,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('relatorios-index');
        }
    }
    public function update(Request $request,$id=false){
      //if($request)
      $data = [];
      foreach ($request->all() as $key => $value) {
        if($key!='var_cartao'&&$key!='ac'){
          $data[$key] = $value;
        }
      }
      $arr_obs = ['p'=>'','pa'=>'Pioneiro Auxiliar','pr'=>'Pioneiro Regular'];
      if(isset($data['id_publicador'])){
        $dadosPub = DB::select("SELECT pioneiro FROM usuarios WHERE id='".$data['id_publicador']."'");
        if($dadosPub){
          if(empty($dadosPub[0]->pioneiro)){
            $privilegio = 'p';
          }else{
            $privilegio = trim($dadosPub[0]->pioneiro);
          }
        }else{
          $privilegio = 'p';
        }
      }
      $data['privilegio'] = $privilegio;
      if($privilegio!='p'){
        $data['obs'] = str_replace($arr_obs[$privilegio],'',$data['obs']);
        $data['obs'] = $arr_obs[$privilegio].' '.$data['obs'];
      }
      $salvarRelatorios=false;
      if(!empty($data)){
        $salvarRelatorios=relatorio::where('id',$data['id'])->update($data);
      }
      //dd($salvarRelatorios);
      if($salvarRelatorios){
        $GerenciarUsuarios = new GerenciarUsuarios;
        $ret['exec'] = true;
        $ret['salvarRelatorios'] = $data;
        $ret['mens'] = 'Registro gravado com sucesso!';
        $ret['cartao']=$GerenciarUsuarios->cardData($data['id_publicador']);
      }else{
        $ret['exec'] = false;
        $ret['mens'] = 'Erro ao gravar!';
      }
      return json_encode($ret);
        /*
        $data = [
           'grupo'=>$request->grupo,
           'obs'=>$request->obs,
           'ativo'=>$request->ativo
        ];
        grupo::where('id',$id)->update($data);
        return redirect()->route('relatorios-index');
        */
    }
    public function destroy($id)
    {
        grupo::where('id',$id)->delete();
        return redirect()->route('relatorios-index');
    }
}
