/*
* Cria um input cujo nome é nomeTurma e o value é o conteúdo da única caixa de texto de index.php.
*/
function criarInputNomeTurma(){
	var caixaDeTexto = document.getElementsByName('caixaDeTextoNomeTurma')[0];
	var inputNomeTurma = document.getElementsByName('nomeTurma')[0];
	inputNomeTurma.value = caixaDeTexto.value;
}