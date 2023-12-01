@isset($cartao['dados'])
    <div class="row ml-0 mr-0 mb-3">

        <div class="col-md-12 text-center">
        <h5>REGISTRO DE PUBLICADOR DE CONGREGAÇÃO</h5>
        </div>
        <div class="col-md-12">
        <span class="label label-default">Nome:</span> <b> {{ $cartao['dados']->nome }}</b>
        </div>
        <div class="col-8">
        <span class="label label-default">Data de nascimento:</span> <b> {{ $cartao['dados']->data_nasci }}</b>
        </div>
        <div class="col-4 text-right">
        <span class="label label-default">Sexo:</span> <b> {{ $cartao['dados']->sexo }}</b>
        </div>
        <div class="col-8">
        <span class="label label-default">Data de batismo:</span> <b> {{ $cartao['dados']->data_batismo }}</b>
        </div>
        <div class="col-4 text-right">
        <b> {{ $cartao['dados']->tipo }}</b>
        </div>
        <div class="row w-100 ml-0 mr-0">
        <div class="col-2">
            <label for="anciao"> <input @if($cartao['dados']->fun=='anc') checked @endif onclick="editCampoCartao(this);" type="checkbox" {!!$data_campo!!} data-id="{{$cartao['dados']->id}}" value="anc" name="fun" id="anciao"> {{__('Ancião')}}</label>
        </div>
        <div class="col-2">
            <label for="sm"> <input @if($cartao['dados']->fun=='sm') checked @endif onclick="editCampoCartao(this);" type="checkbox" {!!$data_campo!!} value="sm" name="fun" id="sm"> {{__('Servo ministerial')}}</label>
        </div>
        <div class="col-2">
            <label for="pioneiro"> <input {!!$data_campo!!} @if($cartao['dados']->pioneiro=='pr') checked @endif onclick="editCampoCartao(this);" type="checkbox" value="pr" name="pioneiro" id="pioneiro"> {{__('Pioneiro Regular')}}</label>
        </div>
        {{-- <b>{{ $cartao['dados']->pioneiro }}</b> --}}
        </div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered table-hover" id="pub-{{$cartao['dados']->id}}">
            <thead>
                <tr>
                <th class="text-center">
                    Ano de serviço<br>{{ $cartao['ano_servico'] }}
                </th>
                <th class="text-center">
                    Participou <br>no<br>ministério
                </th>
                <th class="text-center">
                    Estudos<br>biblicos
                </th>
                <th class="text-center">
                    Pioneiro<br>
                    Auxiliar
                </th>
                <th class="text-center">
                    Horas
                </th>
                <th class="text-center" style="width:30%">
                    Observações
                </th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartao['atividade'] As $key=>$relatorio)
                @php
                    $data_mes_ano = ' data-id_grupo="'.$cartao['dados']->id_grupo.'" data-idp="'.$cartao['dados']->id.'" data-mes="'.$key.'" data-ano="'.$cartao['Schema'][$key]['ano_servico'].'"';
                @endphp
                <tr id="{{$cartao['dados']->id.'_'.$relatorio->mes}}" class="@if(isset($relatorio->class)){{$relatorio->class}}@endif" title="">
                <td class="text-left">
                    <input type="hidden" name="var_cartao" value="{{base64_encode(json_encode($cartao))}}">
                    {{-- <input type="hidden" name="ano" value="{{ $cartao['Schema'][$key]['ano_servico'] }}">
                    <input type="hidden" name="mes" value="{{ $key }}">
                    <input type="hidden" name="id_publicador" value="{{ $cartao['dados']->id }}">
                    <input type="hidden" name="id_grupo" value="{{ $cartao['dados']->id_grupo }}"> --}}
                    <input type="hidden" name="ac" value="{{ $relatorio->ac }}">
                    <input type="hidden" name="id" value="{{ $relatorio->id }}">
                    <input type="hidden" name="privilegio" value="{{ @$relatorio->privilegio }}">
                    {{ $cartao['Schema'][$key]['mes'] }}
                </td>
                <td class="text-center">
                    <input type="checkbox" @if ($relatorio->participou=='s') checked @endif onclick="editCampoCartao(this)" id="participou-{{$cartao['dados']->id.'_'.$relatorio->mes}}" name="participou" {!!$data_mes_ano!!} />
                </td>
                <td class="text-center">
                    <input type="tel" class="form-control text-center" name="estudo" value="{{ $relatorio->estudo }}" onchange="editCampoCartao(this);" {!!$data_mes_ano!!} />
                </td>
                <td class="text-center">
                    <input type="checkbox" @if (@$relatorio->privilegio=='pa') checked @endif onclick="editCampoCartao(this)" name="privilegio" id="privilegio-{{$cartao['dados']->id.'_'.$relatorio->mes}}" {!!$data_mes_ano!!} />
                </td>
                <td class="text-center">
                    <input type="tel" id="hora-{{$cartao['dados']->id.'_'.$relatorio->mes}}" class="form-control text-center" name="hora" value="{{ $relatorio->hora }}" onchange="editCampoCartao(this);" {!!$data_mes_ano!!} />
                </td>
                <td class="text-center">
                    <input type="tel" class="form-control" name="obs" value="{{ $relatorio->obs }}" onchange="editCampoCartao(this);" {!!$data_mes_ano!!} />
                </td>

                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tf-1">
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Total</th>
                @foreach($cartao['totais'] As $k=>$total)
                    <th class="text-center">{{ $total }}</th>
                @endforeach
                <th>&nbsp;</th>
                </tr>
                {{-- <tr class="tf-2">
                <th>Média</th>
                @foreach($cartao['medias'] As $k=>$m)
                    <th class="text-center">{{ $m }}</th>
                @endforeach
                <th>&nbsp;</th>
                </tr> --}}
            </tfoot>
            </table>
        </div>
    </div>
@endisset
