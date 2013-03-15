var ROODA = {};

// -- AjaxForm ---------------------------------------------------------------
// 
//   Usage example:
//   var my_form = new ROODA.AjaxForm("form_id",["value_id_1", "value_id_2"]);
//
//   my_form.onResponse = function ()
//   {
//			var value_1 = this.response.value_id_1;
//   }
//
// ---------------------------------------------------------------------------
;(function(export_to){
	function AjaxForm(form_id,data_requested) {
		var form = document.getElementById(form_id);
		
		if (!form){
			console.error("new AjaxForm(): invalid form_id (1st parameter)");
		} else {
			// public variables
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
				
				this.onload = function(event) {
					
					// Get all the data requested
					for (var i in this.parent.data_requested) {
						
						var data = this.parent.data_requested[i];
						var elem = this.contentDocument.getElementById(data);
						
						if(elem) {
							this.parent.response[data] = elem.textContent;
						} else {
							this.parent.response[data] = false;
						}
					}

					// Trigger the user-defined event function
					this.parent.onResponse();
				}
			}

			// Hide the iframe
			this.iframe.style.display="none";
			// Append the iframe to the document
			document.body.appendChild(this.iframe);
		}
	}
	export_to.AjaxForm = AjaxForm;
})(ROODA);
