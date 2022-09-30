@extends('adminlte::page')

@section('title', 'Lista de publicadores')

@section('content_header')
   <h4>{{$titulo}}</h4>
@stop

@extends('layout.busca')

@section('content')
  <div class="row">
    <div class="modal fade" id="config-imp-cartao" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Atenção</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Informe o Ano de serviço que deseja exibir...</p>
            <form action="{{ route($routa.'.cards') }}" method="GET">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputEmail4">Ano</label>
                  <input type="number" value="{{ date('Y') }}" class="form-control" id="ano" name="ano" placeholder="Selecione o ano">
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Abrir</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!--<button type="button" class="btn btn-primary">Abrir</button>-->
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3 d-print-none">
      @if(isset($meses))
      <select name="mes" class="form-control" id="mes">
          @foreach($meses As $k=>$v)
            <option @if(isset($mes_atual) && $mes_atual==$k) selected @endif value="{{$k}}">{{$v}}</option>
          @endforeach
      </select>
      @endif
    </div>
    <div class="col-md-2 d-print-none">
      <form action="" method="GET">
        <div class="form-row">
          <div class="form-group col-md-12">
            <!--<label for="inputEmail4">Ano</label>-->
            <input type="number" class="form-control" value="@if(isset($_GET['ano'])&&!empty($_GET['ano'])){{$_GET['ano']}}@else{{date('Y')}}@endif" name="ano" id="painel-ano">
          </div>
        </div>
        <!--<div class="form-group col-md-6">
          <button type="submit" class="btn btn-primary">OK</button>
        </div>-->
      </form>
    </div>
    <div class="col-md-4 text-right mb-3 d-print-none">
        <a href="{{route('usuarios.cards')}}" id="open-all-cards" data-target="#config-imp-cartao" class="btn btn-light"> <i class="fa fa-file-pdf"></i> Todos cartões</a>
        <a href="{{ route($routa.'.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Novo publicador </a>
    </div>
    <div class="col-md-12 d-print-none">
      <div class="card card-secondary card-outline">
      <div class="card-header"><h5>Localizar</h5></div>

        <div class="card-body">
            <form id="frm-filtar" action="{{ route($routa.'.index') }}" method="GET">
                <div class="form-row">
                    @if (isset($campos_tabela))
                        @foreach ($campos_tabela as $kbu=>$vbu)
                            @if ($vbu['active'])
                                @php
                                     $kbu = str_replace('[]','',$kbu);
                                    if($vbu['type']!='text' && $kbu=='id'){
                                        $vbu['type'] = 'text';
                                    }
                                    if($kbu!='obs')
                                        $vbu['tam'] = 3;
                                    $cp_busca = isset($vbu['cp_busca'])?$vbu['cp_busca']:$kbu;
                                    if($vbu['type']=='select_multiple'){
                                        $value = @$_GET['filter'][$kbu];
                                    }else{
                                        $value = @$_GET['filter'][$kbu];
                                    }
                                @endphp
                                {{App\Qlib\Qlib::qForm([
                                    'type'=>isset($vbu['type'])?$vbu['type']:'text',
                                    'campo'=>'filter['.$cp_busca.']',
                                    'placeholder'=>isset($vbu['placeholder'])?$vbu['placeholder']:'',
                                    'label'=>$vbu['label'],
                                    'ac'=>'alt',
                                    'value'=>@$value,
                                    'tam'=>isset($vbu['tam'])?$vbu['tam']:'3',
                                    'class_div'=>$vbu['exibe_busca'],
                                    'event'=>isset($vbu['event'])?$vbu['event']:'',
                                    'arr_opc'=>isset($vbu['arr_opc'])?$vbu['arr_opc']:'',
                                    'label_option_select'=>'Todas',
                                ])}}
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <button type="button" id="imprimir-lista-pub" class="btn btn-default"><i class="fas fa-file-archive-o" aria-hidden="true"></i> Imprimir lista</button>
                    @if(isset($_GET['filter']))
                    <button type="button" id="imprimir-cartao-pub" onclick="lib_imprimeCartaoFiltro()" class="btn btn-default"> <i class="fas fa-file-pdf-o" aria-hidden="true"></i> Imprimir cartao</button>
                    <a href=" {{route($routa.'.index')}} " class="btn btn-default" title="Limpar Filtros" type="button"> <i class="fas fa-times"></i> Limpar</a>
                    @endif
                    <button type="button" id="" class="btn btn-outline-success" onclick="lib_roboEtiqueta()" title="Etiquetar Publicadores"> <i class="fa fa-tag"></i></button>
                  </div>
                  <div class="col-md-6 text-right">
                    <label for="">Legenda dos status dos reletórios deste mês</label><br>
                    <label title="Relatório deste mês pendente" style="font-weight:500"><i class="fa fa-circle text-danger"></i> Relatório Pendente</label>
                    <label title="Relatório deste mês enviado para o secretário" style="font-weight:500"><i class="fa fa-circle text-warning"></i> Relatório Enviado</label>
                    <label title="Relatório deste mês foi compilado pelo secretário" style="font-weight:500"><i class="fa fa-circle text-success"></i> Relatório Recebido</label>
                  </div>
                  @include('layout.progressbar')
                </div>
            </form>
          </div>
        </div>
  </div>
  <div class="col-md-12 mens"></div>

    <div class="col-md-12">
      <div class="card card-secondary card-outline">
      <div class="card-header"><h5>{{$titulo}}</h5></div>

        <div class="card-body table-responsive">


              <table class="table dataTable dtr-inline table-hover">
                <thead>
                  <tr>
                    <th class="d-print-none"><input type="checkbox" name="check-all" value=""></th>
                    <th class="d-print-none">Id</th>
                    <th>Nome</th>
                    <th>Privilêgio</th>
                    <th class="text-right">Status</th>
                    <th class="text-center d-print-none">...</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($dados as $key => $usuario)
                    <tr class="{{$usuario->class}} @if (isset($_GET['idCad']) && $_GET['idCad']==$usuario->id) table-info @endif">
                      <td class="d-print-none"> <input type="checkbox" class="checkbox-table" name="check_{{$usuario->id}}" value="s"> </td>
                      <td class="d-print-none"> {{$usuario->id}} </td>
                      <td>
                          <div>{{$usuario->nome}}</div>
                          <div>
                            {!! @$usuario->tags_html !!}
                          </div>
                          <div class=" d-print-block d-none"> <label>Endereço:</label> {{$usuario->endereco}} <label>Tel:</label> {{$usuario->tel}} <label for="">Batismo</label> {{$usuario->data_batismo}}</div>
                        @if (isset($usuario->config['nome_contato_em']))

                            <div class="col-12 px-0 d-print-block d-none">
                                <label for="contato_em">Contato de Emergência:</label>
                                <span>
                                    {{$usuario->config['nome_contato_em']}}
                                </span>
                                <label for="telefone_em">Telefone:</label>
                                <span>
                                    {{$usuario->config['telefone_contato_em']}}
                                </span>
                            </div>
                        @endif
                        </td>
                      <td> @if($usuario->pioneiro=='pa') P. Auxiliar @elseif($usuario->pioneiro=='pr') P. Regular  @elseif($usuario->pioneiro=='p') Publicador @else Publicador @endif </td>
                      <td><div class="text-right" title="Relatório {{$usuario->status}} "> {{$usuario->status}} </div>
                    </td>
                      <td class="d-flex text-right d-print-none">
                         <a href="{{ route('relatorios.create',['id'=>$usuario->id]) }}" title="Relatório mensal" class="btn btn-primary relatorios-create mr-1">
                           <i class="fa fa-list"></i> Relatório
                        </a>
                         <a href="{{ route('usuarios.cartao',['id'=>$usuario->id]) }}" title="Cartão do publicador" class="btn btn-secondary mr-1 print-card">
                           <i class="fa fa-file-pdf"></i> Cartão
                        </a>
                        <a href="{{ route($routa.'.edit',['id'=>$usuario->id]) }}" title="Editar Cadastro" class="btn btn-light mr-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                          </svg>
                        </a>

                        <form action="{{ route('publicadores-destroy',['id'=>$usuario->id]) }}" id="frm-{{$usuario->id}}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="button" data-del="true" data-id="{{$usuario->id}}" name="button" title="Excluir" class="btn btn-light">
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
    </div>
  </div>
  @stop
  @section('css')
    @include('qlib.csslib')
  @stop
  @section('js')
    @include('qlib.jslib')
      <script>
          function confirmDeleteUsuario(obj){
              var id = obj.data('id');
              if(window.confirm('DESEJA MESMO EXCLUIR?')){
                $('#frm-'+id).submit();
              }
              //alert('modal agora');
          }
          $(function(){
              $('.print-card').on('click',function(e){
                  openPageLink(e,$(this).attr('href'),$('#painel-ano').val(),'&popup=s');
              });
              $('.relatorios-create').on('click',function(e){
                  e.preventDefault();
                  var url = $(this).attr('href');
                  //alert( window.location.href);
                  var mes = $('#mes').val();
                  var ano = $('#painel-ano').val();
                  window.location = url+'?redirect='+btoa(window.location.href)+'&m='+mes+'&ano='+ano;
                  //openPageLink(e,$(this).attr('href'),$('#painel-ano').val());
              });
              $('#open-all-cards').on('click',function(e){
                  openPageLink(e,$(this).attr('href'),$('#painel-ano').val());
              });
              $('[data-del="true"]').on('click',function(e){
                e.preventDefault();
                confirmDeleteUsuario($(this));
              });
              $('#grupos').on('change',function(){
                  var grupo = $(this).val();
                  //alert(grupo);
                  $('#frm-filtar').submit();
              });
              $('.btn').on('click', function () {
                $(this).find('input').removeAttr('checked');
                $(this).removeClass('active');
              });
              $('#imprimir-lista-pub').on('click', function () {
                //var url = $('#frm-filtar').serialize();
                //abrirjanelaPadrao("{{route($routa.'.index')}}?view=lista&"+url);
                window.print();
              });

          });
      </script>
  @stop
