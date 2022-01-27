<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Qlib\Qlib;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /*
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    */
    public function index()
    {
        //$Users = DB::table('users')->join('model_has_permissions','users.id','=','model_has_permissions.model_id')->get();
        $Users = User::all();
        $title = 'Todos os Usuários';
        $titulo = $title;
        return view('users.index',['users'=>$Users,'title'=>$title,'titulo'=>$titulo]);
    }
    public function create()
    {
        $title = 'Cadastrar publicador';
        $titulo = $title;
        //$Users = Users::all();
        $arr_user = ['ac'=>'cad'];
        return view('users.createdit',['users'=>$arr_user,'title'=>$title,'titulo'=>$titulo]);
    }
    /*
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ])->givePermissionTo('user');
    }
    */
    public function __construct()
    {
        $this->middleware(['permission:admin']);
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
        User::create($dados)->givePermissionTo($permission);
        return redirect()->route('users.index');
    }
    public function edit($id)
    {
        //$Users = User::where('id',$id)->join('model_has_permissions', 'users.id', '=', 'model_has_permissions.model_id')->get();
        $Us = DB::table('users')->where('id',$id)->join('model_has_permissions', 'users.id', '=', 'model_has_permissions.model_id')->get();
        //Qlib::lib_print($Us[0]);
        if(isset($Us[0])){
          $Users = (Array) $Us[0];
        }else{
           $Users = [];
        }
        $permissions = DB::select("SELECT * FROM permissions ORDER BY id ASC");
        if(!empty($Users)){
          $title = 'Editar um Usuario';
          $titulo = $title;
          $Users['ac'] = 'alt';
          //dd($Users);
          return view('users.createdit',['users'=>$Users,'permissions'=>$permissions,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('users.index');
        }
    }
    public function update(Request $request,$id){
        $validatedData = $request->validate([
          'name' => ['required','string'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
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
        unset($ds['permissao']);
        User::where('id',$id)->update($ds);
        $mudarPermissao = DB::table('model_has_permissions')->where('model_id','=',$id)->update(['permission_id'=>$data['permissao']]);
        return redirect()->route('users.index');
    }
    public function destroy($id)
    {
        User::where('id',$id)->delete();
        return redirect()->route('users.index');
    }
}
