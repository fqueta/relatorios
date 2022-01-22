<?php

namespace App\Http\Controllers;

use App\Models\grupo;
use Illuminate\Http\Request;
use App\Qlib\Qlib;

class GerenciarGrupo extends Controller
{
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
        grupo::create($request->all());
        return redirect()->route('grupos-index');
    }
    public function edit($id)
    {
        $grupos = grupo::where('id',$id)->first();
        if(!empty($grupos)){
          $title = 'Editar um grupo';
          $titulo = $title;
          return view('grupos.edit',['grupos'=>$grupos,'title'=>$title,'titulo'=>$titulo]);
        }else{
          return redirect()->route('grupos-index');
        }
    }
    public function update(Request $request,$id){
      dd($id);

        $data = [
           'grupo'=>$request->grupo,
           'obs'=>$request->obs,
           'ativo'=>$request->ativo
        ];
        grupo::where('id',$id)->update($data);
        return redirect()->route('grupos-index');
    }
    public function destroy($id)
    {
        grupo::where('id',$id)->delete();
        return redirect()->route('grupos-index');
    }
}
