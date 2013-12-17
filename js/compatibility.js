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
