<div class="modal fade" id="lancar-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">Registrar relatório</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <form id="frm-s4" method="post" action="{{route('relatorios.store')}}">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-hover">
                                    <thead>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <b class="label label-default">Nome: </b>
                                                <span id="lab-nome">
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="col-12 d-flex">
                                                    <div class="label label-default" style="width: 10%">Mês: </div>
                                                    <div id="select_mes" style="width:55%">
                                                    </div>&nbsp;
                                                    <div id="select_ano" style="width:40%">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">{{__('Participou do ministério')}}</td>
                                            <td width="20%">
                                                <input class="form-control text-center" type="checkbox" name="participou" value="s">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">{{__('Estudos bíblicos')}}</td>
                                            <td width="20%">
                                                <input class="form-control text-center" type="tel" name="estudo" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">{{__('Horas (Se for pioneiro auxiliar, regular, especial ou missionário em campo)')}}</td>
                                            <td width="20%">
                                                <input class="form-control text-center" type="tel" name="hora" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{__('Observações')}}
                                                <input class="form-control text-left" type="tel" name="obs" value="">
                                            </td>
                                        </tr>
                                    </tbody>
                                  </table>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="submit_s4();">Salvar</button>
            </div>
        </div>
    </div>
</div>
