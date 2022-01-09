<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\usuario;
use App\Models\Relatorio;
use App\Qlib\Qlib;
use App\Http\Requests\StorePostRequest;
class GerenciarUsuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
         $this->middleware('auth');
     }

    public function index()
    {
      $usuarios = usuario::all();
      $title = 'Todos os publicadores';
      $titulo = $title;
      return view('usuarios.index',['usuarios'=>$usuarios,'title'=>$title,'titulo'=>$titulo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Cadastrar publicador';
        $titulo = $title;
        $arr_user = ['ac'=>'cad'];
        return view('usuarios.createdit',['usuario'=>$arr_user,'title'=>$title,'titulo'=>$titulo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $dados = $request->all();
        $dados['data_nasci'] = Qlib::dtBanco($dados['data_nasci']);
        $dados['data_batismo'] = Qlib::dtBanco($dados['data_batismo']);
        //dd($dados);
        usuario::create($dados);
        return redirect()->route('usuarios.index');

    }

    public function show(usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(usuario $id)
    {
      $arr_user = Qlib::lib_json_array($id);
      //$usuario = usuario::where('id',$id)->first();
      if(is_array($arr_user)){
        $title = 'Editar cadastro';
        $titulo = $title;
        $arr_user['ac'] = 'alt';
        return view('usuarios.createdit',['usuario'=>$arr_user,'title'=>$title,'titulo'=>$titulo]);
      }else{
        return redirect()->route('usuarios.index');
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, usuario $id)
    {
      $data = [];
      foreach ($request->all() as $key => $value) {
        if($key!='_method'&&$key!='_token'&&$key!='ac'){
          if($key=='data_batismo'){
              if($value!='0000-00-00'){
                $data[$key] = $value;
              }else{
                $data[$key] = Qlib::dtBanco($data[$key]);
              }
          }else{
            $data[$key] = $value;
          }
        }
      }
      $salvarUsuario=false;
      if(!empty($data)){
        if(Qlib::isJson($id)){
            $arr = Qlib::lib_json_array($id);
            if(isset($arr['id'])){
               $id = $arr['id'];
            }
        }
        $salvarRelatorios=usuario::where('id',$id)->update($data);
        if($salvarRelatorios){
          return redirect()->route('usuarios.index');
        }else{
          return redirect()->route('usuarios.edit',['id'=>$id,'mens'=>'Erro ao salvar']);
        }
      }else{
        return redirect()->route('usuarios.edit',['id'=>$id,'mens'=>'Erro ao receber dados']);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(usuario $usuario)
    {
        //
    }

    public function cardData($id){
        //$usuarios = usuario::all();

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
        //$compleSql = " id_publicador='$id' AND ano='$ano_servico'";
        $dados = DB::select("select * from usuarios WHERE id='$id'");
        //$total_horas = DB::select("select SUM(hora) from relatorios WHERE $compleSql");
        $arr_sequecia_meses = [
            '9'=>['mes'=>'Setembro','ano'=>$ano,'ano_servico'=>$ano-1],
            '10'=>['mes'=>'Outubro','ano'=>$ano,'ano_servico'=>$ano-1],
            '11'=>['mes'=>'Novembro','ano'=>$ano,'ano_servico'=>$ano-1],
            '12'=>['mes'=>'Dezembro','ano'=>$ano,'ano_servico'=>$ano-1],
            '1'=>['mes'=>'Janeiro','ano'=>$ano,'ano_servico'=>$ano],
            '2'=>['mes'=>'Fevereiro','ano'=>$ano,'ano_servico'=>$ano],
            '3'=>['mes'=>'Marco','ano'=>$ano,'ano_servico'=>$ano],
            '4'=>['mes'=>'Abril','ano'=>$ano,'ano_servico'=>$ano],
            '5'=>['mes'=>'Maio','ano'=>$ano,'ano_servico'=>$ano],
            '6'=>['mes'=>'Junho','ano'=>$ano,'ano_servico'=>$ano],
            '7'=>['mes'=>'Julho','ano'=>$ano,'ano_servico'=>$ano],
            '8'=>['mes'=>'Agosto','ano'=>$ano,'ano_servico'=>$ano],
        ];
        $cartao['ano'] = $ano_atual;
        $cartao['ano_servico'] = $ano_servico;
        if(!empty($dados)){
          $cartao['dados'] = $dados[0];
          $cartao['dados']->data_batismo = Qlib::dataExibe($cartao['dados']->data_batismo);
          $cartao['dados']->data_nasci = Qlib::dataExibe($cartao['dados']->data_nasci);

          if(isset($dados[0]->genero) && $dados[0]->genero =='m'){
            $cartao['dados']->sexo = 'Masculino';
          }elseif ($dados[0]->genero =='f') {
            $cartao['dados']->sexo = 'Feminino';
          }else {
            $cartao['dados']->sexo = false;
          }

          if(isset($dados[0]->tipo) && $dados[0]->tipo =='o.o'){
            $cartao['dados']->tipo = 'Outras ovelhas';
          }else {
            $cartao['dados']->tipo = 'Ungido';
          }

          if(isset($dados[0]->fun) && $dados[0]->fun =='anc'){
            $cartao['dados']->fun = 'Ancião';
          }elseif (isset($dados[0]->fun) && $dados[0]->fun =='sm') {
            $cartao['dados']->fun = 'Servo ministerial';
          }else {
            $cartao['dados']->fun = false;
          }

          if(isset($dados[0]->pioneiro) && $dados[0]->pioneiro =='pr'){
            $cartao['dados']->pioneiro = 'Pioneiro regular';
          }elseif (isset($dados[0]->pioneiro) && $dados[0]->pioneiro =='pe') {
            $cartao['dados']->pioneiro = 'Pioneiro especial';
          }else {
            $cartao['dados']->pioneiro = false;
          }
        }else{
          $cartao['dados'] = array();
        }
        $cartao['Schema'] = $arr_sequecia_meses;
        $meses_relatados = 0;
        $totalPublicacao = NULL;
        $totalHoras = NULL;
        $totalVideos = NULL;
        $totalRevisitas = NULL;
        $totalEstudos = NULL;
        //dd($arr_sequecia_meses);
        foreach ($arr_sequecia_meses as $key => $value) {
              $sql = "select * from relatorios WHERE id_publicador='$id' AND mes='$key' AND ano='".$value['ano_servico']."' ORDER BY id ASC";
              $atividade  = DB::select($sql);
              if(!empty($atividade)){
                $cartao['atividade'][$key]  = $atividade[0];
                $cartao['atividade'][$key]->ac = 'alt';
                $totalPublicacao += isset($atividade[0]->publicacao)?$atividade[0]->publicacao : 0;
                $totalHoras += isset($atividade[0]->hora) ? $atividade[0]->hora : 0;
                $totalVideos += isset($atividade[0]->video) ? $atividade[0]->video : 0;
                $totalRevisitas += isset($atividade[0]->revisita) ? $atividade[0]->revisita : 0;
                $totalEstudos += isset($atividade[0]->estudo) ? $atividade[0]->estudo : 0;
                $meses_relatados++;
              }else{
                $ativi['id'] = 0;
                $ativi['publicacao'] = 0;
                $ativi['video'] = 0;
                $ativi['hora'] = 0;
                $ativi['estudo'] = 0;
                $ativi['revisita'] = 0;
                $ativi['obs'] = false;
                $ativi['mes'] = $key;
                $ativi['ac'] = 'cad';
                $atividade[0] = (object)$ativi;
                $cartao['atividade'][$key]  = $atividade[0];
                $totalPublicacao += isset($atividade[0]->publicacao)?$atividade[0]->publicacao : 0;
                $totalHoras += isset($atividade[0]->hora) ? $atividade[0]->hora : 0;
                $totalVideos += isset($atividade[0]->video) ? $atividade[0]->video : 0;
                $totalRevisitas += isset($atividade[0]->revisita) ? $atividade[0]->revisita : 0;
                $totalEstudos += isset($atividade[0]->estudo) ? $atividade[0]->estudo : 0;
                //$meses_relatados++;
              }
              $cartao['sql'][$key]['sql'] = $sql;
              //$atividades =
        };
        $cartao['totais']['publicacao'] = $totalPublicacao;
        $cartao['totais']['videos'] = $totalVideos;
        $cartao['totais']['horas'] = $totalHoras;
        $cartao['totais']['revisitas'] = $totalRevisitas;
        $cartao['totais']['estudos'] = $totalEstudos;


        $cartao['meses_relatados'] = $meses_relatados;
        if($cartao['meses_relatados']>0){
          $mediasPublicacao = ($cartao['totais']['publicacao'] / $cartao['meses_relatados']) ;
          $mediasvideos = ($cartao['totais']['videos'] / $cartao['meses_relatados']) ;
          $mediasHoras = ($cartao['totais']['horas'] / $cartao['meses_relatados']) ;
          $mediasRevisitas = ($cartao['totais']['revisitas'] / $cartao['meses_relatados']) ;
          $mediasEstudos = ($cartao['totais']['estudos'] / $cartao['meses_relatados']) ;
        }else{
          $mediasHoras = 0;
          $mediasPublicacao = 0;
          $mediasvideos = 0;
          $mediasHoras = 0;
          $mediasRevisitas = 0;
          $mediasEstudos = 0;
        }
        $cartao['medias']['publicacao'] = round($mediasPublicacao) ;
        $cartao['medias']['videos'] = round($mediasvideos) ;
        $cartao['medias']['horas'] = round($mediasHoras) ;
        $cartao['medias']['revisitas'] = round($mediasRevisitas) ;
        $cartao['medias']['estudos'] = round($mediasEstudos) ;
        $url = url()->current();
        $cartao['url'] = $url;
        //dd($cartao);
        return $cartao;
    }
    public function cartao($id){
      $title = '';
      $titulo = '';
      $cartao = $this->cardData($id);
      //$parent = usuario::where('parent','=',$id);
      $parent = DB::select("SELECT * FROM usuarios WHERE parent ='".$id."'");
      if($parent){
        $l = 0;
        foreach ($parent as $key => $value) {
          if($l==1){
            $l = 0;
          }else{
            $l++;
          }
          if($l==0){
            $mb = 'mb-20';
          }elseif($l==1){
            $mb = 'mb-200';
          }else{
            $mb = '';
          }
          $cartao['parent'][$key] = $this->cardData($value->id);
          $cartao['parent'][$key]['page']['lin'] = $l;
          $cartao['parent'][$key]['page']['mb'] = $mb;
        }
      }
      //dd($cartao);
      return view('usuarios.cartao',['cartao'=>$cartao,'titulo'=>$title,'title'=>$titulo]);
    }
    public function cards(){
        $compleSql = false;
        $limit = '';
        $dadosPubs = DB::select("select * from usuarios $compleSql Order By nome ASC $limit");
        $cartao = [];
        if(!empty($dadosPubs)){
          $l = 0;
          foreach ($dadosPubs as $key => $value) {
            if($l==2){
              $l = 1;
            }else{
              $l++;
            }
            if($l==1){
              $mb = 'mb-20';
            }elseif($l==2){
              $mb = 'mb-200';
            }else{
              $mb = '';
            }
            $cartao[$key] = $this->cardData($value->id);
            $cartao[$key]['page']['lin'] = $l;
            $cartao[$key]['page']['mb'] = $mb;
          }
          $title = '';
          $titulo = '';
          //dd($cartao);
          return view('usuarios.cards',['cards'=>$cartao,'titulo'=>$title,'title'=>$titulo]);
        }
    }
}
