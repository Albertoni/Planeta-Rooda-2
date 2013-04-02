
if (!Object.create) {
	Object.create = function (o) {
		if (arguments.length > 1) {
			throw new Error('Object.create implementation only accepts the first parameter.');
		}
		function F() {}
		F.prototype = o;
		return new F();
	};
};

// Compatibilidade com IE 5 e IE 6
if (typeof XMLHttpRequest === "undefined") {
  XMLHttpRequest = function () {
    try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
    catch (e) {}
    try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
    catch (e) {}
    try { return new ActiveXObject("Microsoft.XMLHTTP"); }
    catch (e) {}
    // Microsoft.XMLHTTP points to Msxml2.XMLHTTP and is redundant
    throw new Error("This browser does not support XMLHttpRequest.");
  };
};


var ROODA = {};

ROODA.AjaxRequest = (function (url,handler,body) {
	var AjaxRequest = {};
	AjaxRequest.onresponse = handler;
	
	request = new XMLHttpRequest();
	request.parent = AjaxRequest;
	
	request.onreadystatechange = function () {
		if (this.readyState === 4) {
			if (this.state === 200) {
				this.parent.onresponse();
				this.parent.responseDocument = null;
			} else {
			}
		}
	}
	return AjaxRequest;
});

ROODA.AjaxForm = (function(form_id,handler,data_requested){
// -- AjaxForm ---------------------------------------------------------------
// 
//   Usage example:
//   var handler = function() {
//			var str = this.response.value_id_1;
//			str += this.responseDocument.getElementById('test').textContent;
//   var my_form = ROODA.AjaxForm("form_id", function () { window.alert(this.response.value_id_1+this.responseDocument.getElementById('test'))} ,["value_id_1", "value_id_2"]);
//
// ---------------------------------------------------------------------------
	var form = document.getElementById(form_id);

	// VERIFYING PARAMETERS
	if (!form || form.tagName !== "FORM") {
		console.error("new AjaxForm(): invalid form_id (1st parameter)");
		return null;
	} else {
		// PARAMETERS SEEMS OK
		var iframe = document.createElement("iframe");
		var AjaxForm = function () {
			
			// public variables/functions
			this.data_requested = data_requested; // parameter
			this.onResponse = handler || function() {};
			// end of public variables

			// Dont mess with the proprieties below
			iframe.parent = this;
			
			// Set iframe's name and form's target
			iframe.name = form.id+"_AjaxTarget";
			form.target = iframe.name;
			
			
			iframe.onload = function (ev) {
				
				// Define the real 'onload' after first 'onload' since it will
				// be triggered first when appended to the document.
				// On firefox the event will be triggered even if you define it after appending to the DOM
				// so you must define it before so it behaves alike in all browsers.
				
				this.onload = function (ev) {
					
					// Get all the data requested
					this.parent.responseDocument = this.contentDocument;
					
					this.parent.response = {};
					
					for (var i=0; i<data_requested.length;i+=1) {
						var data = data_requested[i];
						var elem = this.contentDocument.getElementById(data);
						
						if(elem) {
							this.parent.response[data] = elem.textContent;
						} else {
							this.parent.response[data] = false;
						}
					}
					
					// Trigger the user-defined event function
					this.parent.onResponse();
				};
			}
			
			// Hide the iframe
			iframe.style.display="none";
			// Append the iframe to the document
			document.body.appendChild(iframe);
		}
		return new AjaxForm();
	}
});
