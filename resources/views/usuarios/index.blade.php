@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop

@section('content')
  <div class="row">
    <div class="col-md-8 mb-3">
      <p>Todos os publicadores ativos da congregação</p>
    </div>
      <div class="col-md-4 text-right mb-3">
        <a href="{{ route('usuarios-create') }}" class="btn btn-success"> Novo usuario </a>
      </div>
    <div class="col-md-12">

      <table class="table table-bordered dataTable dtr-inline">
        <thead>
          <tr>
            <!--<th>#</th>-->
            <th>Nome</th>
            <th>Ativo</th>
            <th>Obs</th>
            <th class="text-center">...</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usuarios as $key => $usuario)
            <tr>
              <!--<td> {{$usuario->id}} </td>-->
              <td> {{$usuario->nome}} </td>
              <td> @if($usuario->ativo=='s') Sim @elseif($usuario->ativo=='n') Não @endif </td>
              <td> {{$usuario->obs}} </td>
              <td class="text-center d-flex">
                 <a href=" {{ route('usuarios-edit',['id'=>$usuario->id]) }} " class="btn btn-primary mr-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                  </svg>
                </a>
                 <a href=" {{ route('usuarios.cartao',['id'=>$usuario->id]) }} " title="Cartão do publicador" class="btn btn-light mr-2">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">
                     <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                     <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                   </svg>
                </a>
                <form action="{{ route('usuarios-destroy',['id'=>$usuario->id]) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" name="button" title="Excluir" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                      </svg>
                  </button>
                </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @stop
  @section('css')
    <!--  <link rel="stylesheet" href="/css/admin_custom.css">-->
  @stop
  @section('plugins.Datatables', true)

  @section('js')
      <script> $('.dataTable').DataTable({ "paging":   false,}); </script>
  @stop
