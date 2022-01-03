<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;


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
        $relatorios = Grupo::all();
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
        $salvarRelatorios = Relatorio::create($dados);
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
        $relatorios = Grupo::where('id',$id)->first();
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
      $salvarRelatorios=false;
      if(!empty($data)){
        $salvarRelatorios=Relatorio::where('id',$data['id'])->update($data);
      }
      //dd($salvarRelatorios);
      if($salvarRelatorios){
        $GerenciarUsuarios = new GerenciarUsuarios;
        $ret['exec'] = true;
        $ret['salvarRelatorios'] = $salvarRelatorios;
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
        Grupo::where('id',$id)->update($data);
        return redirect()->route('relatorios-index');
        */
    }
    public function destroy($id)
    {
        Grupo::where('id',$id)->delete();
        return redirect()->route('relatorios-index');
    }
}
