function newAjax() {
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		aux_ajax = new XMLHttpRequest();
		if (aux_ajax.overrideMimeType) {
			aux_ajax.overrideMimeType('text/xml');
			// See note below about this line
		}
	}
	else if (window.ActiveXObject) { // IE
		try {
			aux_ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				aux_ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!aux_ajax) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	return aux_ajax;
}	

function carregaHTML(obj_id,script_url,pars) {
	var a = newAjax();
	var obj = document.getElementById(obj_id);
	if(a) {
		a.open('POST',script_url + '.php',true);
		a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		a.send(pars);
		a.onreadystatechange = function() {
			if(a.readyState == 4) {
				if(a.status == 200) {
					obj.innerHTML = a.responseText;
				} else {
					alert('Erro ao tentar carregar comentários!'+a.statusText);
				}
			}
		}
	}
}
