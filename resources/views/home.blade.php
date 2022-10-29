@extends('adminlte::page')

@section('title', 'Painel')

@section('content_header')
    <!--<h1>Painel</h1>-->
@stop

@section('content')
    <!--<p>Welcome to this beautiful admin panel.</p>-->
  @can('is_admin')
  @if(isset($resumo['total_cards']))
  <div class="row">
          @foreach($resumo['total_cards'] As $k=>$vtot)
          <div class="col-lg-2 col-6">
                <!-- small box -->
                <div class="small-box bg-{{$vtot['color']}}">
                  <div class="inner">
                    <h3>{{$vtot['valor']}}</h3>

                    <p>{{$vtot['label']}}</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="{{$vtot['link']}}" class="small-box-footer">Mais infomações <i class="fas fa-arrow-circle-right"></i></a>
                </div>
          </div>
          @endforeach
        </div>
     @endif
        <div class="row">
            <form action="" method="GET" id="fil-data" style="width: 100%">
                <div class="row mx-0">
                    <div class="col-md-2 d-print-none">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="">Ano de entrega</label><br>
                            <input type="number" class="form-control" value="@if(isset($ano)&&!empty($ano)){{$ano}}@else{{date('Y')}}@endif" name="ano" id="painel-ano">
                            </div>
                        </div>
                        <!--<div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary">OK</button>
                        </div>-->
                    </div>
                    <div class="col-md-10 mb-3 d-print-none">
                        @if(isset($meses))
                        <label for="">Mês de entrega</label><br>
                        <select name="mes" class="form-control" id="mes" onchange="$('#fil-data').submit();">
                            @foreach($meses As $k=>$v)
                            <option @if(isset($mes_atual) && $mes_atual==$k) selected @endif value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <h4>{{$resumo['config_table']['data']['titulo']}}</h5>
                    </div>

                </div>
            </form>
        @include('layout.progressbar')
        @if(isset($resumo['total_resumo']))
        @foreach($resumo['total_resumo'] As $key=>$value)

        <div class="col-md-3">
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
                <td class="text-center">{{$v}}</td>
              </tr>
              @elseif($k=='relatorios')
              <tr>
                <th>Número de relatórios</th>
                <th class="text-center">{{$v}}</th>
              </tr>
              @endif
              @endforeach
            </tbody>
          </table>
        </div>
        @endforeach
        @endif
    @else
      <h3>Seja bem vindo para ter acesso entre em contato com o suporte</h3>
    @endcan


  </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
