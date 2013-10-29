
var submitFormFunction = (function (successHandler, failHandler) {
    return (function (oFormElement) {
        AJAXSubmit(oFormElement, successHandler, failHandler);
    });
});
