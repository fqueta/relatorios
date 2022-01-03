
function abrirjanela(url, nome, w, h, param){
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes');
	 popname.window.focus();
}
function abrirjanela1(url, nome, w, h, param){
	var largura = $( window ).width() - w;
	var altura   = $( window ).height();
	altura =new Number(altura) + new Number(h);
	var left = new Number(largura);
	//left = (left) - (left/Number(2));
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', left='+10+' scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', scrollbars=yes,left='+10+'');
	 popname.window.focus();
}
function abrirjanelaPadrao(url,windo){
	if(typeof windo == 'undefined'){
		windo = "novo_cada";
	}
	var meio = (screen.availWidth - 200)/((screen.availWidth-200)/50);
	if($(window).width() > 900){
		var wid = screen.availWidth - 100;
		//var height = $( window ).height() - ($( window ).height()/4);
		var height = screen.availHeight-90;
	}else{
		var wid = $(window).width();
		var height = screen.availHeight;
		//var height = $(document).height();
		//height = new Number(height) - new Number(100);
	}
	//alert(height);
	abrirjanela(url, windo, wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaFull(url,windo){
	if(typeof windo == 'undefined'){
		windo = "janelaFull";
	}
	var params = [
		'height='+screen.height,
		'width='+screen.width,
		'fullscreen=yes' // only works in IE, but here for completeness
	].join(',');

	var popup = window.open(url, windo, params);
	popup.window.focus();
	popup.moveTo(0,0);
	//abrirjanela(url, windo, screen.availWidth, screen.availHeight, ",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaPadraoConsulta(url){
	if($(window).width() > 800){
		var meio = 1050 / (6);
		var wid = 1050;
		var height = $( window ).height();
	}else{
		var meio = $(window).width() / (6);
		var wid = $(window).width();
		var height = $( window ).height();
	}
	abrirjanela(url, "consultaCliente", wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}

function openPageLink(ev,url,ano){
  ev.preventDefault();
  var u = url.trim()+'?ano='+ano;
	abrirjanelaPadrao(u);
	//window.location = u;
}
function gerenteAtividade(obj,ac){
  var id = obj.attr('id');
  var temaImput = '<input type="{type}" {seletor} style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  var exec = selId.attr('exec');
  if(exec=='s'){
    return
  }
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var s = $('#'+id+' td:eq('+eq+')');
    if(i==0){
      selId.attr('exec','s');
    }
    if(i==5){
       var wid='200';
       var t='text';
       var b='<button type="button" onclick="submitRelatorio(\''+id+'\',\''+ac+'\')" title="Salvar" class="btn btn-primary" name="button"><i class="fa fa-check"></i></button>'+
       '<button type="button" onclick="cancelEdit(\''+id+'\')" title="Cancelar edição" data-toggle="tooltip"  class="btn btn-secondary" name="button"><i class="fa fa-times"></i></button>';
			 if(ac=='alt'){
				 b += '<button type="button" onclick="delRegistro(\''+id+'\')" title="Apagar registro" data-toggle="tooltip"  class="btn btn-danger" name="button"><i class="fa fa-trash"></i></button>';
			 }
       s.addClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.html();
    var c = temaImput.replace('{name}',id+arr[i]);
    c = c.replace('{value}',v);
    c = c.replace('{wid}',wid);
    c = c.replace('{type}',t);
    c = c.replace('{btn}',b);
    c = c.replace('{seletor}',arr[i]);
    s.html(c);
    //array[i]
  }
  $('#'+id+' td:eq(1) input').select();
}
function cancelEdit(id){
  //var temaImput = '<input type="{type}" style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  var temaImput = '{value}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var td = $('#'+id+' td:eq('+eq+')');
    var s = $('#'+id+' input['+arr[i]+']');
    if(i==0){
      selId.removeAttr('exec');
    }
    if(i==5){
       var wid='200';
       var t='text';
       //'<button type="button" onclick="cancelarEdit('+id+')" title="Cancelar edição" data-toggle="tooltip"  class="btn btn-secondary" name="button"><i class="fa fa-times"></i></button>';
       //var b='<button type="button" onclick="submitRelatorio(\''+id+'\',\''+ac+'\')" title="Salvar" class="btn btn-primary" name="button"><i class="fa fa-check"></i></button>'+
       td.removeClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.val();
    var c = temaImput.replace('{value}',v);
    //c = c.replace('{value}',v);
    //c = c.replace('{wid}',wid);
    //c = c.replace('{type}',t);
    //c = c.replace('{btn}',b);
    td.html(c);
  }
}
function delRegistro(id){
	alert(id);
}
function submitRelatorio(id,ac){
  var don = $('#'+id+' input');
  console.log(don);
  var arr = [];
  var seriali = '';
  $.each(don,function(i,k){
    var ke = k.name;
    ke = ke.replace(id,'');
     arr[ke] = k.value;
     seriali += ke+'='+k.value+'&';
    //console.log(k.name);
  });
  var var_cartao = atob(arr['var_cartao']);
    $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           }
       });

       var formData = seriali;
       var state = jQuery('#btn-save').val();
       if(ac=='cad'){
         var type = "POST";
       }else{
         var type = "POST";
       }
       var ajaxurl = $('[name="routAjax_'+ac+'"]').val();
       $.ajax({
           type: type,
           url: ajaxurl,
           data: formData,
           dataType: 'json',
           success: function (data) {
             if(data.exec){
               cancelEdit(id);
             }
             if(data.cartao.totais){
               var array = data.cartao.totais;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-1 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.cartao.medias){
               var array = data.cartao.medias;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
           },
           error: function (data) {
               console.log(data);
           }
       });
  //console.log(arr);

}
