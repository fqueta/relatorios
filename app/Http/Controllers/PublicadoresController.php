<?php

namespace App\Http\Controllers;

use stdClass;
use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\grupo;
use App\Models\Publicador;
use App\Models\Relatorio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicadoresController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'publicadores';
        $this->label = 'Publicador';
        $this->view = 'padrao';
    }
    public function queryPublicador($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        $publicador =  Publicador::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$publicador =  DB::table('publicadores')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $publicador_totais = new stdClass;
        $campos = isset($_SESSION['campos_publicadores_exibe']) ? $_SESSION['campos_publicadores_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $publicador->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $publicador->where($key,'LIKE','%'. $value. '%');
                            if($campos[$key]['type']=='select'){
                                $value = $campos[$key]['arr_opc'][$value];
                            }
                            $arr_titulo[$campos[$key]['label']] = $value;
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $publicador;
                if($config['limit']=='todos'){
                    $publicador = $publicador->get();
                }else{
                    $publicador = $publicador->paginate($config['limit']);
                }
        }else{
            $fm = $publicador;
            if($config['limit']=='todos'){
                $publicador = $publicador->get();
            }else{
                $publicador = $publicador->paginate($config['limit']);
            }
        }
        $publicador_totais->todos = $fm->count();
        $publicador_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $publicador_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $publicador_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['publicador'] = $publicador;
        $ret['publicador_totais'] = $publicador_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$publicador_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$publicador_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$publicador_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$publicador_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function queryPublicador2()
    {
        $meses = Qlib::Meses();
        $userLogado = Auth::user();
        $user = $userLogado->id;
        $campos = isset($_SESSION['campos_publicadores_exibe']) ? $_SESSION['campos_publicadores_exibe'] : $this->campos();
        if(isset($_GET['filter'])){
            //Qlib::lib_print($campos);
            $compleSql="WHERE ativo='s'";
            //dd($_GET['filter']);
            foreach ($_GET['filter'] as $key => $valor) {
                $key = str_replace('[','',$key);
                if(is_array($valor)){
                    $compleOr = false;
                    $i=0;
                    foreach ($valor as $k => $v) {
                        if(!empty($v)){

                            if($i==0){
                                $or = ' AND (';
                                $and = false;
                            }else{
                                $or = false;
                                $and = 'OR';
                            }
                            if($key=='priv'){
                                $campo_bus = 'pioneiro';
                            }elseif($key=='func'){
                                $campo_bus = 'fun';
                            }else{
                                $campo_bus = $key;
                            }
                            if($k==0 && $key=='priv'){
                                $compleOr .="$or $and $campo_bus=''";
                                $i++;
                            }elseif($key=='tags'){
                                $compleOr .="$or $and $key LIKE '%\"$v\"%'";
                                $i++;
                            }else{
                                $compleOr .="$or $and $campo_bus='$v'";
                                $i++;
                            }
                        }
                        if($compleOr){
                            $compleOr .= ')';
                        }
                        $compleSql .= $compleOr;
                    }
                }else{
                    /*
                    if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
                        $arr_titulo[$campos[$key]['label']] = isset($campos[$key]['arr_opc'][$valor])?$campos[$key]['arr_opc'][$valor]:false;
                    }else{
                        $arr_titulo[$campos[$key]['label']]=$valor;
                    }*/
                    if($key=='inativo'&&$valor!=''){
                        $compleSql .=" AND inativo='$valor'";
                    }elseif($key=='id_grupo'&&$valor!=''){
                        $compleSql .=" AND id_grupo='$valor'";
                    }else{
                        if(!empty($valor))
                        $compleSql .=" AND $key LIKE '%$valor%'";
                    }
                }
            }


        }else{
            //$usuarios = usuario::OrderBy('nome','asc')->get();
            $compleSql = false;
        }
        $sql = "SELECT * FROM publicadores $compleSql ORDER BY nome ASC";
        $usuarios = DB::select($sql);
        //Verificação de envio de relatorio
        if($usuarios){
            $GerenciarRelatorios = new GerenciarRelatorios($this->user);
            foreach ($usuarios as $k => $val) {
                if(isset($val->config)){
                    $val->config=Qlib::lib_json_array($val->config);
                }
                $usuarios[$k]->relatorio = $GerenciarRelatorios->verificarRelatorioMensal(['id_publicador'=>$val->id]);
                $usuarios[$k]->compilado = $GerenciarRelatorios->verificarRelatorioMensal(['id_publicador'=>$val->id,'tipo'=>'compilado']);
                if($usuarios[$k]->compilado){
                    $usuarios[$k]->class = 'text-success';
                    $usuarios[$k]->status = 'Compilado';
                }elseif($usuarios[$k]->relatorio){
                    $usuarios[$k]->class = 'text-success';
                    $usuarios[$k]->status = 'Enviado';
                }else{
                    $usuarios[$k]->status = 'Pendente';
                    $usuarios[$k]->class = 'text-danger';
                }
                if(!empty($val->tags) && $val->tags!='[]'){
                    $arr_tags = Qlib::lib_json_array($val->tags);
                    $ta_html = false;
                    $tm = '<span class="badge badge-primary">{tag}</span> ';
                    foreach ($arr_tags as $kt => $vt) {
                        if(isset($campos['tags[]']['arr_opc'])){
                            $vt = $campos['tags[]']['arr_opc'][$vt];
                        }
                        $ta_html .= str_replace('{tag}',$vt,$tm);
                    }
                    $usuarios[$k]->tags_html = $ta_html;
                }else{
                    $usuarios[$k]->tags_html = false;
                }
            }
        }
        $grupos = grupo::all();
        $title = 'Todos os publicadores';
        $titulo = $title;
        //dd($usuarios);
        $view = isset($_GET['view']) ? $_GET['view'] : 'index';
        $dt = Qlib::anoTeocratico();
        $mes_atual = isset($_GET['m']) ? $_GET['m'] : $dt['mes'];
        $ano = isset($_GET['ano']) ? $_GET['ano'] : $dt['ano'];
        $mes = $mes_atual;
        // if($mes == '01'){
        //     $mes = '12';
        //     $ano = (date('Y') - 1);
        // }else{
        //     $mes--;
        // }
        $controllerRelatorio = new GerenciarRelatorios($this->user);
        $id_grupo = isset($_GET['fil']['id_grupo'])?$_GET['fil']['id_grupo']:false;
        $estatisticas = $controllerRelatorio->estatisticas($mes,$ano,$id_grupo);
        $ret['publicador'] = $usuarios;
        $ret['grupos'] = $grupos;
        $ret['campos'] = $campos;
        return $ret;
    }
    public function campos(){
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2'],
            'nome'=>['label'=>'Nome do Publicador(a)','active'=>true,'placeholder'=>'Ex.: Cadastrado','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'12'],
            'endereco'=>['label'=>'Endereço Completo','active'=>true,'placeholder'=>'Ex.: Rua Almir Sathler 01 Ap 25 N18 Teixeiras','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'data_nasci'=>['label'=>'Data de nascimento','cp_busca'=>'','active'=>false,'type'=>'date','tam'=>'3','exibe_busca'=>'d-none','event'=>''],
            'data_batismo'=>['label'=>'Data de batismo','cp_busca'=>'','active'=>false,'type'=>'date','tam'=>'3','exibe_busca'=>'d-none','event'=>''],
            'tel'=>['label'=>'Telefone','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-none','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);'],
            'genero'=>[
                'label'=>'Sexo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['m'=>'Masculino','f'=>'Feminino'],
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>false,
                'class'=>'',
            ],
            'inativo'=>[
                'label'=>'Situação',
                'active'=>true,
                'type'=>'select',
                //'arr_opc'=>['n'=>'Ativo','s'=>'Inativo','i'=>'Inrregular'],
                'arr_opc'=>['n'=>'Ativo','s'=>'Inativo'],
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>false,
                'class'=>'',
            ],
            'id_grupo'=>[
                'label'=>'Grupo de campo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT id,grupo FROM grupos WHERE ativo='s'",'grupo','id'),
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>false,
                'class'=>'',
            ],
            'pioneiro'=>[
                'label'=>'Privilêgio',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['p'=>'Publicador','pa'=>'P.Auxiliar','pr'=>'P.Regular',],
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>false,
                'class'=>'',
            ],
            'fun'=>[
                'label'=>'Designação',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['anc'=>'Ancião','sm'=>'S.Ministerial',],
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>true,
                'class'=>'',
            ],
            'tipo'=>[
                'label'=>'Esperança',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['o.o'=>'Outras ovelhas','u'=>'Ungido',],
                'event'=>'',
                'tam'=>'2',
                'exibe_busca'=>true,
                'option_select'=>false,
                'class'=>'',
            ],
            'tags[]'=>[
                'label'=>'Etiqueta',
                'active'=>true,
                'type'=>'select_multiple',
                //'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM tags WHERE ativo='s' AND pai='1'",'nome','id'),
                'arr_opc'=>[
                    'inrregular'=>'Inrregular',
                    'sem_revisitas_6meses'=>'Sem Revisitas a 6 meses',
                ],
                'exibe_busca'=>'d-block',
                'event'=>'',
                'class'=>'',
                'option_select'=>false,
                'tam'=>'10',
                'cp_busca'=>'tags]['
            ],
            'sep1'=>['label'=>'Contato de Emergência','active'=>false,'tam'=>'12','script'=>'<h5>Contato de Emergência</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
            'config[nome_contato_em]'=>['label'=>'Nome','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'10','placeholder'=>'','cp_busca'=>'config][nome_contato_em'],
            'config[telefone_contato_em]'=>['label'=>'Telefone','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','tam'=>'2','placeholder'=>'','cp_busca'=>'config][telefone_contato_em'],

            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-none','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],

        ];
    }
    public function index(User $user)
    {
        $this->authorize('is_admin', $user);
        $meses = Qlib::Meses();
        $title = 'Publicadores Cadastrados';
        $titulo = $title;
        $queryPublicador = $this->queryPublicador2($_GET);
        $queryPublicador['config']['exibe'] = 'html';
        $routa = $this->routa;
        $dt = Qlib::anoTeocratico();
        $mes_atual = isset($_GET['m']) ? $_GET['m'] : $dt['mes'];
        $ano = isset($_GET['ano']) ? $_GET['ano'] : $dt['ano'];
        $mes = $mes_atual;
        // if($mes == '01'){
        //     $mes = '12';
        //     // $ano = (date('Y') - 1);
        // }else{
        //     $mes--;
        // }
        $controllerRelatorio = new GerenciarRelatorios($this->user);
        $id_grupo = isset($_GET['fil']['id_grupo'])?$_GET['fil']['id_grupo']:false;
        $estatisticas = $controllerRelatorio->estatisticas($mes,$ano,$id_grupo);

        return view('publicadores.index',[
            'dados'=>$queryPublicador['publicador'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryPublicador['campos'],
            //'publicador_totais'=>$queryPublicador['publicador_totais'],
            //'titulo_tabela'=>$queryPublicador['tituloTabela'],
            //'arr_titulo'=>$queryPublicador['arr_titulo'],
            //'config'=>$queryPublicador['config'],
            'routa'=>$routa,
            'ano'=>$ano,
            'view'=>'publicadores','meses'=>$meses,'mes_atual'=>$mes,'estatisticas'=>$estatisticas,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        //$this->authorize('create', $this->routa);
        $title = 'Cadastrar publicador';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-publicadores',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];
        $campos = $this->campos();
        return view($this->view.'.createedit',[
            'config'=>$config,
            'title'=>$title,
            'titulo'=>$titulo,
            'campos'=>$campos,
            'value'=>$value,
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => ['required','string','unique:publicadores'],
        ]);
        $userLogado = Auth::user();
        $user = $userLogado->id;

        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';
        $dados['autor'] = isset($dados['autor'])?$dados['autor']:$user;
        //dd($dados);

        $salvar = Publicador::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($publicador,User $user)
    {
        $id = $publicador;
        $dados = Publicador::where('id',$id)->get();
        $routa = 'publicadores';
        //$this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de publicadores';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $listFiles = false;
            $campos = $this->campos();
            /*
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }*/
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-publicadores',
                'route'=>$this->routa,
                'id'=>$id,
            ];

            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'exec'=>true,
            ];
            return view($this->view.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->view.'.index',$ret);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => ['required'],
        ]);
        $data = [];
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'){
                if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        //$data[$key] = Qlib::dtBanco($value);
                        $data[$key] = $value;
                    }
                }elseif($key == 'renda_familiar') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['ativo'] = isset($data['ativo'])?$data['ativo']:'n';
        $data['autor'] = $userLogadon;
        $data['tags'] = isset($data['tags'])?$data['tags']:[];

        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        if(!$data['data_nasci']){
            unset($data['data_nasci']);
        }
        if(!$data['data_batismo']){
            unset($data['data_batismo']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Publicador::where('id',$id)->update($data);
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = $this->routa.'.edit';
            $ret = [
                'exec'=>false,
                'id'=>$id,
                'mens'=>'Erro ao receber dados',
                'color'=>'danger',
            ];
        }
        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$id;
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function destroy($id,Request $request)
    {
        //$this->authorize('delete', $this->routa);
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'publicadores';
        if (!$post = Publicador::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Publicador::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
    public function edit_campo_cartao(Request $request){
        $d = $request->all();
        $ret['exec']=false;
        if(isset($d['campo']) && isset($d['mes']) && isset($d['ano']) && isset($d['id_publicador'])){
            $d[$d['campo']] = @$d['value'];
            $campo = $d['campo'];
            unset($d['value'],$d['campo']);
            if($campo=='fun' || $campo=='pioneiro'){
                $df[$campo] = $d[$campo];
                if($df[$campo]=='n'){
                    $df[$campo]='';
                }
                $s = Publicador::where('id',$d['id_publicador'])->update($df);
            }else{
                $s = Relatorio::where('id_publicador','=',$d['id_publicador'])->where('mes','=',$d['mes'])->where('ano','=',$d['ano'])->update($d);
                if(!$s){
                    $s = Relatorio::create($d);
                    if(isset($s->id)){
                        $ret['exec'] = true;
                        $ret['idCad'] = $s->id;
                    }
                }else{
                    $ret['exec'] = true;
                }
            }
        }
        return response()->json($ret);
    }
}
