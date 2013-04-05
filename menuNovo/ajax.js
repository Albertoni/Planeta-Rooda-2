window.carregaHTML = (function() {
	function newAjax() {
		if(window.XMLHttpRequest){//Mozilla, Safari, ...
			aux_ajax=new XMLHttpRequest();
		}
		else if(window.ActiveXObject){//IE
			try{
				aux_ajax = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					aux_ajax = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(e){}
			}
		}

		if(!aux_ajax){
			alert("Ops, seu navegador não suporta essa funcionalidade do Planeta Rooda");
			return false;
		}
		return aux_ajax;
	}

	return (function (script_url,pars, handler){
		var a=newAjax();
		if(a){
			a.open('POST',script_url,true);
			a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			a.send(pars);
			a.onreadystatechange=handler;
		}
	});
})();
