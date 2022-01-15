@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')

<form class="" action="@if($usuario['ac']=='cad'){{ route('usuarios.store') }}@elseif($usuario['ac']=='alt'){{ route('usuarios.update',['id'=>$usuario['id']]) }}@endif" method="post">
    @if($usuario['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="nome">Nome completo</label>
          <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" aria-describedby="nome" placeholder="Nome do publicador" value="@if(isset($usuario['nome'])){{$usuario['nome']}}@elseif($usuario['ac']=='cad'){{old('nome')}}@endif" />
          @error('nome')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="tel">Celular</label>
          <input type="tel" class="form-control @error('tel') is-invalid @enderror" id="tel" data-mask="(99)9999-99999" name="tel" aria-describedby="tel" placeholder="(00)00000-0000" value="@if(isset($usuario['tel'])){{$usuario['tel']}}@elseif($usuario['ac']=='cad'){{old('tel')}}@endif">
          @error('tel')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror

        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="endereco">Endereço</label>
          <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" aria-describedby="endereco" placeholder="Ex: Rua Costa reis,234 Bela Aurora" value="@if(isset($usuario['endereco'])){{$usuario['endereco']}}@elseif($usuario['ac']=='cad')
          {{old('endereco')}}@endif">
          @error('endereco')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror

        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="data_nasci">Data de nascimento</label>
          <input type="tel" class="form-control @error('data_nasci') is-invalid @enderror" id="data_nasci" name="data_nasci" aria-describedby="data_nasci" placeholder="00/00/0000" value="@if(isset($usuario['data_nasci'])){{$usuario['data_nasci']}}@elseif($usuario['ac']=='cad'){{old('data_nasci')}}@endif">
          @error('data_nasci')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror

        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="data_batismo">Data de batismo</label>
          <input type="tel" class="form-control" id="data_batismo" name="data_batismo" aria-describedby="data_batismo" placeholder="00/00/0000" value="@if(isset($usuario['data_batismo'])){{$usuario['data_batismo']}}  @elseif($usuario['ac']=='cad'){{old('data_batismo')}}@endif">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="ativo">Ativo</label>
          <select class="form-control" name="ativo">
            <option value="s" @if(isset($usuario['ativo'])&&$usuario['ativo']=='s') selected @endif >Ativar</option>
            <option value="n"  @if(isset($usuario['ativo'])&&$usuario['ativo']=='n') selected @endif >Destivar</option>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="obs">Observação</label><br>
          <textarea name="obs" class="form-control" rows="8" cols="80">@if(isset($usuario['obs'])){{$usuario['obs']}}@elseif($usuario['ac']=='cad'){{old('obs')}}@endif</textarea>
        </div>
      </div>
      <div class="col-md-12">
        <div class=form-group"">
          <a href=" {{route('usuarios.index')}} " class="btn btn-light"> Voltar</a>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </div>
      @csrf
    </div>
</form>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
@stop
