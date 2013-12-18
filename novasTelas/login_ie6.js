function entra(){
var login = document.getElementById("sustenta_login");
var cadastro = document.getElementById("sustenta_cadastro");
if(document.body.clientHeight < login.clientHeight){
	login.style.top = 0;
	login.style.marginTop = 0;
	cadastro.style.top = 0;
	cadastro.style.marginTop = 0;
}
document.getElementById("caixa_login").style.backgroundImage = "url(images/fundos/bloco_login_ie6.png)";
document.getElementById("caixa_criar").style.backgroundImage = "url(images/fundos/bloco_criar_ie6.png)";
document.getElementById("caixa_cadastro").style.backgroundImage = "url(images/fundos/bloco_cadastro_ie6.png)";
document.getElementById("caixa_criar").style.marginTop = "-13px";
document.getElementById("botao_entrar").style.marginTop = "0px";
}