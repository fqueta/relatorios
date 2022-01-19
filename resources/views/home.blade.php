@extends('adminlte::page')

@section('title', 'Painel')

@section('content_header')
    <!--<h1>Painel</h1>-->
@stop

@section('content')
    <!--<p>Welcome to this beautiful admin panel.</p>-->
    <div class="row">
      <div class="col-md-12">
        <h4>{{$resumo['config_table']['data']['titulo']}}</h5>
      </div>
    @if(isset($resumo['total_resumo']))
    @foreach($resumo['total_resumo'] As $key=>$value)

    <div class="col-md-4">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th colspan="2" class="text-center"><h5>{{$resumo['config_table']['tabelas'][$key]['label']}}</h5></th>
          </tr>
        </thead>
        <tbody>
          @foreach($resumo['total_resumo'][$key] As $k=>$v)
          @if($k!='relatorios')
          <tr>
            <td>@if(isset($resumo['config_table']['titulos'][$k])){{$resumo['config_table']['titulos'][$k]}}@else{{$k}}@endif</td>
            <td>{{$v}}</td>
          </tr>
          @elseif($k=='relatorios')
          <tr>
            <th>Número de relatórios</th>
            <th>{{$v}}</th>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
    @endforeach
    @endif

  </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
