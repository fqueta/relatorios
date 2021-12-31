function openPageLink(ev,url,ano){
  ev.preventDefault();
  var u = url.trim()+'?ano='+ano;
  window.location = u;
}
