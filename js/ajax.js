(function (exports) {
"use strict";
/* 
 *   :: XMLHttpRequest.prototype.sendAsBinary() Polifyll ::
 * 
 *   https://developer.mozilla.org/en-US/docs/DOM/XMLHttpRequest#sendAsBinary()
 */
if (!XMLHttpRequest.prototype.sendAsBinary) {
  XMLHttpRequest.prototype.sendAsBinary = function (sData) {
    var nBytes = sData.length, ui8Data = new Uint8Array(nBytes);
    for (var nIdx = 0; nIdx < nBytes; nIdx++) {
      ui8Data[nIdx] = sData.charCodeAt(nIdx) & 0xff;
    }
    /* send as ArrayBufferView...: */
    this.send(ui8Data);
    /* ...or as ArrayBuffer (legacy)...: this.send(ui8Data.buffer); */
  };
}
/* 
 *   :: AJAX Form Submit Framework ::
 * 
 *   https://developer.mozilla.org/en-US/docs/DOM/XMLHttpRequest/Using_XMLHttpRequest
 * 
 *   This framework is released under the GNU Public License, version 3 or later.
 *   http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * 
 *   Syntax:
 * 
 *    AJAXSubmit(HTMLFormElement,function);
 */
exports.AJAXSubmit = (function () {
 
  function ajaxSuccess () {
    /* console.log("AJAXSubmit - Success!"); */
    alert(this.responseText);
    /* you can get the serialized data through the "submittedData" custom property: */
    /* alert(JSON.stringify(this.submittedData)); */
  }
 
  function submitData (oData) {
    /* the AJAX request... */
    var oAjaxReq = new XMLHttpRequest();
	 //oAjaxReq.responseType = "document";
    oAjaxReq.submittedData = oData;
    oAjaxReq.onreadystatechange = function () {
      if (this.readyState !== this.DONE) {
        // requisição em andamento, não fazer nada.
        return;
      }
      if (this.status === 200) {
        if (typeof oData.successHandler === 'function')
        {
          oData.successHandler.call(this);
        }
      } else {
        if (typeof oData.failHandler === 'function') {
          oData.failHandler.call(this);
        }
      }
    };
    if (oData.technique === 0) {
      /* method is GET */
      oAjaxReq.open("get", oData.receiver.replace(/(?:\?.*)?$/, oData.segments.length > 0 ? "?" + oData.segments.join("&") : ""), true);
      oAjaxReq.send(null);
    } else {
      /* method is POST */
      oAjaxReq.open("post", oData.receiver, true);
      if (oData.technique === 3) {
        /* enctype is multipart/form-data */
        var sBoundary = "---------------------------" + Date.now().toString(16);
        oAjaxReq.setRequestHeader("Content-Type", "multipart\/form-data; charset=UTF-8; boundary=" + sBoundary);
        oAjaxReq.sendAsBinary("--" + sBoundary + "\r\n" + oData.segments.join("--" + sBoundary + "\r\n") + "--" + sBoundary + "--\r\n");
      } else {
        /* enctype is application/x-www-form-urlencoded or text/plain */
        oAjaxReq.setRequestHeader("Content-Type", oData.contentType + "; charset=UTF-8");
        oAjaxReq.send(oData.segments.join(oData.technique === 2 ? "\r\n" : "&"));
      }
    }
  }
 
  function processStatus (oData) {
    if (oData.status > 0) { return; }
    /* the form is now totally serialized! do something before sending it to the server... */
    /* doSomething(oData); */
    /* console.log("AJAXSubmit - The form is now serialized. Submitting..."); */
    submitData (oData);
  }
 
  function pushSegment (oFREvt) {
    this.owner.segments[this.segmentIdx] += oFREvt.target.result + "\r\n";
    this.owner.status--;
    processStatus(this.owner);
  }
 
  function plainEscape (sText) {
    /* how should I treat a text/plain form encoding? what characters are not allowed? this is what I suppose...: */
    /* "4\3\7 - Einstein said E=mc2" ----> "4\\3\\7\ -\ Einstein\ said\ E\=mc2" */
    return sText.replace(/[\s\=\\]/g, "\\$&");
  }
 
  function SubmitRequest (oTarget,successHandler,failHandler) {
    var nFile, sFieldType, oField, oSegmReq, oFile, bIsPost = oTarget.method.toLowerCase() === "post";
    /* console.log("AJAXSubmit - Serializing form..."); */
    this.contentType = bIsPost && oTarget.enctype ? oTarget.enctype : "application\/x-www-form-urlencoded";
    this.technique = bIsPost ? this.contentType === "multipart\/form-data" ? 3 : this.contentType === "text\/plain" ? 2 : 1 : 0;
    this.receiver = oTarget.action;
    this.status = 0;
    this.segments = [];
    this.successHandler = successHandler;
    this.failHandler = failHandler;
    var fFilter = this.technique === 2 ? plainEscape : escape;
    for (var nItem = 0; nItem < oTarget.elements.length; nItem++) {
      oField = oTarget.elements[nItem];
      if (!oField.hasAttribute("name")) { continue; }
      sFieldType = oField.nodeName.toUpperCase() === "INPUT" ? oField.getAttribute("type").toUpperCase() : "TEXT";
      if (sFieldType === "FILE" && oField.files.length > 0) {
        if (this.technique === 3) {
          /* enctype is multipart/form-data */
          for (nFile = 0; nFile < oField.files.length; nFile++) {
            oFile = oField.files[nFile];
            oSegmReq = new FileReader();
            /* (custom properties:) */
            oSegmReq.segmentIdx = this.segments.length;
            oSegmReq.owner = this;
            /* (end of custom properties) */
            oSegmReq.onload = pushSegment;
            this.segments.push("Content-Disposition: form-data; name=\"" + oField.name + "\"; filename=\""+ oFile.name + "\"\r\nContent-Type: " + oFile.type + "; charset=UTF-8\r\n\r\n");
            this.status++;
            oSegmReq.readAsBinaryString(oFile);
          }
        } else {
          /* enctype is application/x-www-form-urlencoded or text/plain or method is GET: files will not be sent! */
          for (nFile = 0; nFile < oField.files.length; this.segments.push(fFilter(oField.name) + "=" + fFilter(oField.files[nFile++].name)));
        }
      } else if ((sFieldType !== "RADIO" && sFieldType !== "CHECKBOX") || oField.checked) {
        /* field type is not FILE or is FILE but is empty */
        this.segments.push(
          this.technique === 3 ? /* enctype is multipart/form-data */
            "Content-Disposition: form-data; name=\"" + oField.name + "\"\r\n\r\n" + oField.value + "\r\n"
          : /* enctype is application/x-www-form-urlencoded or text/plain or method is GET */
            fFilter(oField.name) + "=" + fFilter(oField.value)
        );
      }
    }
    processStatus(this);
  }
 
  return function (oFormElement,successHandler,failHandler) {
    if (!oFormElement.action) { return; }
    new SubmitRequest(oFormElement,successHandler,failHandler);
  };
 
})();

exports.AJAXOpen = function (url, handler) {
  var oAjaxReq = new XMLHttpRequest();
  if (typeof handler === "function") {
    oAjaxReq.onreadystatechange = handler;
  }
  oAjaxReq.open("GET",url);
  oAjaxReq.send();
};

// AJAXGet("http://google.com/", { success: function () { alert("success"); }, fail: function () { alert("fail"); } });
exports.AJAXGet = function (url, handlers) {
  var oAjaxReq = new XMLHttpRequest();
  oAjaxReq.onreadystatechange = function () {
    if (this.readyState !== this.DONE) {
      // requisição em andamento, não fazer nada.
      return;
    }
    if (this.status === 200) {
      if (typeof handlers.success === 'function')
      {
        handlers.success.call(this);
      }
    } else {
      if (typeof handlers.fail === 'function') {
        handlers.fail.call(this);
      }
    }
  }
  oAjaxReq.open("GET",url);
  oAjaxReq.send();
};

exports.AJAXPost = function(url,handler,dataObject) {
  var i, values = [], body = "", oAjaxReq = new XMLHttpRequest();
  if (typeof handler === "function") {
    oAjaxReq.onreadystatechange = handler;
  } else {
    oAjaxReq.onreadystatechange = function (e) {
      e = e || event;
      if (this.readyState !== this.DONE) {
        // requisição em andamento, não fazer nada.
        return;
      }
      if (this.status === 200) {
        if (typeof handler.success === 'function')
        {
          handler.success.call(this);
        }
      } else {
        if (typeof handler.fail === 'function') {
          handler.fail.call(this);
        }
      }
    }
  }
  if (typeof dataObject === "object") {
    for (i in dataObject) {
      if (dataObject.hasOwnProperty(i)) {
        values.push(encodeURIComponent(i) + "=" + encodeURIComponent(dataObject[i]));
      }
    }
  }
  body = values.join("&");
  oAjaxReq.open("POST",url);
  oAjaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
  oAjaxReq.setRequestHeader("Connection", "close");
  oAjaxReq.send(body);
}
}(window));


var AJAX = {};
(function (exports) {
exports.post = function(url, dataObject, handler) {
  var i, values = [], body = "", oAjaxReq = new XMLHttpRequest();
  if (typeof handler === "function") {
    oAjaxReq.onreadystatechange = handler;
  } else {
    oAjaxReq.onreadystatechange = function (e) {
      e = e || event;
      if (this.readyState !== this.DONE) {
        // requisição em andamento, não fazer nada.
        return;
      }
      if (this.status === 200) {
        if (typeof handler.success === 'function')
        {
          handler.success.call(this);
        }
      } else {
        if (typeof handler.fail === 'function') {
          handler.fail.call(this);
        }
      }
    }
  }
  if (typeof dataObject === "object") {
    for (i in dataObject) {
      if (dataObject.hasOwnProperty(i)) {
        values.push(encodeURIComponent(i) + "=" + encodeURIComponent(dataObject[i]));
      }
    }
  }
  body = values.join("&");
  oAjaxReq.open("POST",url);
  oAjaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
  oAjaxReq.send(body);
}
exports.get = function (url, handlers) {
  var oAjaxReq = new XMLHttpRequest();
  oAjaxReq.onreadystatechange = function () {
    if (!handlers) return;
    if (this.readyState !== this.DONE) {
      // requisição em andamento, não fazer nada.
      return;
    }
    if (this.status === 200) {
      if (typeof handlers.success === 'function')
      {
        handlers.success.call(this);
      }
    } else {
      if (typeof handlers.fail === 'function') {
        handlers.fail.call(this);
      }
    }
  }
  oAjaxReq.open("GET",url);
  oAjaxReq.send();
};
}(AJAX));