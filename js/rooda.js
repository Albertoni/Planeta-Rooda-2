
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

;(function(export_to){

// -- AjaxForm ---------------------------------------------------------------
// 
//   Usage example:
//   var my_form = new ROODA.AjaxForm("form_id",["value_id_1", "value_id_2"]);
//
//   my_form.onResponse = function ()
//   {
//			var value_1 = this.response.value_id_1;
//			var res_dom = this.responseDocument;
//   }
//
// ---------------------------------------------------------------------------
	function AjaxUploadForm(form_id,data_requested) {
		var form = document.getElementById(form_id);
		
		if (!form || form.tagName !== "FORM"){
			console.error("new AjaxForm(): invalid form_id (1st parameter)");
		} else {
			// public variables/functions
			this.data_requested = data_requested; // parameter
			this.onResponse = function(){};       // user should define this
			this.response = {};                   // filled automatically
			this.form = form;
			// end of public variables

			// Dont mess with the proprieties below
			this.iframe = document.createElement("iframe");
			this.iframe.parent = this;
			
			// Set iframe's name and form's target
			this.iframe.name = this.form.name+"_AjaxTarget";
			this.form.target = this.iframe.name;
			
			
			this.iframe.onload = function (event) {
				
				// Define the real 'onload' after first 'onload' since it will
				// be triggered first when appended to the document.
				// On firefox the event will be triggered even if you define it after appending to the DOM
				// so you must define it before so it behaves alike in all browsers.
				
				this.onload = function(event) {
					
					// Get all the data requested
					this.parent.responseDocument = this.contentDocument;
					for (var i=0; i<this.parent.data_requested.length;i+=1) {
						var data = this.parent.data_requested[i];
						var elem = this.parent.responseDocument.getElementById(data);
						
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
			this.iframe.style.display="none";
			// Append the iframe to the document
			document.body.appendChild(this.iframe);
		}
	}
	export_to.AjaxUploadForm = AjaxUploadForm;

	function AjaxForm(form_id) {
		var form = getElementById(form_id);
		if (form && form.tagName === "FORM") {
			this.form = form;
			this.form.parent = this;
			
			this.request = new XMLHttpRequest();
			this.request.parent = this;
			this.request.onreadystatechange = function ()
			{
				if (this.readyState === 4){
					this.parent.responseDocument = this.responseXML;
				}
			}
			
			this.onResponse = function () {};
			
			this.form.onsubmit = function() {
				
				var requestBody = encodeURIComponent(this.elements[0].name) + "=" + encodeURIComponent(this.elements[0].value);
				
				for (var i = 1;i<this.elements.length;i+=1) {
					requestBody+= "&" + encodeURIComponent(this.elements[i].name) + "=" + encodeURIComponent(this.elements[i].value);
				}
				
				this.parent.request.open("POST",this.form.action);
				this.parent.request.send(requestBody);
				
				this.parent.onResponse();
				
				return false;
			};
		};
	};
	export_to.AjaxForm = AjaxForm;
})(ROODA);
