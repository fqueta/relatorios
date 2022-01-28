<?php

namespace App\Http\Controllers;

use App\Models\User;
use stdClass;
use App\Qlib\Qlib;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DataTables;

class UserController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }
    public function index(User $user)
    {
        //$Users = DB::table('users')->join('model_has_permissions','users.id','=','model_has_permissions.model_id')->get();
        //$Users = User::all();
        $this->authorize('is_admin', $user);
        $title = 'Todos os Usuários';
        $titulo = $title;
        $user_counters = new stdClass;
        $user_counters->all_users = $this->user->all()->count();
        $user_counters->actived_users = $this->user->where('status','actived')->count();
        $user_counters->pre_registred_users = $this->user->where('status','pre_registred')->count();
        $user_counters->inactived_users = $this->user->where('status','inactived')->count();
        $user_counters->male_users = $this->user->where('gender','male')->count();
        $user_counters->female_users = $this->user->where('gender','female')->count();
        $user_counters->administrator_users = $this->user->where('profile','administrator')->count();
        $user_counters->user_users = $this->user->where('profile','user')->count();



        $users = $this->user->paginate(5);
        return view('users.index',['users'=>$users,
                            'user_counters'=>$user_counters,'title'=>$title,'titulo'=>$titulo]);
    }
    public function paginacaoAjax()
    {
        $users = user::orderBy('name');
        return DataTables::of($users)->make(true);
    }
    public function create(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = 'Cadastrar publicador';
        $titulo = $title;
        //$Users = Users::all();
        $arr_user = ['ac'=>'cad'];
        $roles = DB::select("SELECT * FROM roles ORDER BY id ASC");

        return view('users.createdit',['users'=>$arr_user,'roles'=>$roles,'title'=>$title,'titulo'=>$titulo]);
    }

    public function store(request $request)
    {
        $validatedData = $request->validate([
          'name' => ['required','string'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
        $dados = $request->all();
        $dados['password'] = Hash::make($dados['password']);
        //dd($dados);
        $permission = isset($dados['permission'])?$dados['permission']:'user';
        User::create($dados);
        return redirect()->route('users.index');
    }
    public function show($id)
    {
        $Us = DB::table('users')->where('id',$id)->get();
        if(isset($Us[0])){
          $Users = (Array) $Us[0];
        }else{
           $Users = [];
        }
        $roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        $permissions = DB::select("SELECT * FROM permissions ORDER BY id ASC");

        if(!empty($Users)){
          $title = 'Perfil do usuário';
          $titulo = $title;
          $Users['ac'] = 'alt';
          //dd($Users);
          return view('users.show',['users'=>$Users,'roles'=>$roles,'permissions'=>$permissions,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('users.index');
        }
    }
    public function edit($id)
    {
        $Us = DB::table('users')->where('id',$id)->get();
        if(isset($Us[0])){
          $Users = (Array) $Us[0];
        }else{
           $Users = [];
        }
        $roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        $permissions = DB::select("SELECT * FROM permissions ORDER BY id ASC");

        if(!empty($Users)){
          $title = 'Editar um Usuario';
          $titulo = $title;
          $Users['ac'] = 'alt';
          //dd($Users);
          return view('users.createdit',['users'=>$Users,'roles'=>$roles,'permissions'=>$permissions,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('users.index');
        }
    }
    public function update(Request $request,$id){
        $validatedData = $request->validate([
          'name' => ['required','string'],
          'email' => ['required']
        ]);
        $datas = $request->all();
        $data = [];
        foreach ($datas as $key => $value) {
          if($key!='_method'&&$key!='_token'&&$key!='ac'){
            if($key=='password'){
                if($value==null){
                }else{
                  $data[$key] = Hash::make($value);
                }
            }else{
              $data[$key] = $value;
            }
          }
        }
        $ds = $data;
        User::where('id',$id)->update($ds);
        //$mudarPermissao = DB::table('model_has_roles')->where('model_id','=',$id)->update(['role_id'=>$data['role']]);
        return redirect()->route('users.index');
    }
    public function destroy($id)
    {
        User::where('id',$id)->delete();
        return redirect()->route('users.index');
    }
}
