<?php

namespace App\Http\Controllers;

use App\Models\grupo;
use Illuminate\Http\Request;

class GerenciarGrupo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $grupos = grupo::all();
        $title = 'Todos os grupos';
        $titulo = $title;
        return view('grupos.index',['grupos'=>$grupos,'title'=>$title,'titulo'=>$titulo]);
    }
    public function create()
    {
        $title = 'Cadastrar um grupo';
        $titulo = $title;
        return view('grupos.create',['title'=>$title,'titulo'=>$titulo]);
    }
    public function store(Request $request)
    {
        Grupo::create($request->all());
        return redirect()->route('grupos-index');

    }
    public function edit($id)
    {
        $grupos = Grupo::where('id',$id)->first();
        if(!empty($grupos)){
          $title = 'Editar um grupo';
          $titulo = $title;
          return view('grupos.edit',['grupos'=>$grupos,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('grupos-index');
        }
    }
    public function update(Request $request,$id){
        $data = [
           'grupo'=>$request->grupo,
           'obs'=>$request->obs,
           'ativo'=>$request->ativo
        ];
        Grupo::where('id',$id)->update($data);
        return redirect()->route('grupos-index');
    }
    public function destroy($id)
    {
        Grupo::where('id',$id)->delete();
        return redirect()->route('grupos-index');
    }
}
