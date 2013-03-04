<?php


	/*================================================
		TEM MAIS UM CFG.PHP NO DIRETORIO ABAIXO, MALANDRO
		E MAIS UM DENTRO DA PASTA BLOG
		FICA DE OLHO NESSA BAGAÇA AÍ VALEU E UM ABRAÇO
		
		VALEU PELA DICA! HEHEHE
	================================================*/



	
	
	/*================================================
		Sessao até o presente momento
	================================================
					$_SESSION['SS_usuario_id']
					$_SESSION['SS_usuario_nome']
					$_SESSION['SS_usuario_nivel_sistema']
					$_SESSION['SS_usuario_login']
					$_SESSION['SS_usuario_email']
					$_SESSION['SS_personagem_id']
					$_SESSION['SS_terreno_id']
					$_SESSION['SS_link_pai'] (contém o link da página que chamou a aplicação externa)
					$_SESSION['SS_nivel_ultimo']
	================================================
	Obs.: 
	==============================================*/

$email_administrador = "admplaneta2@gmail.com";

$BD_host1 = "localhost";
$BD_base1 = "nuted_planeta";
$BD_user1 = "root";
$BD_pass1 = "root";


//constantes
define("NL","<BR />\n");   //constante nova linha
define("TIPOBLOG",1);



//nome tabelas
$tabela_forum				= "forum";
$tabela_biblio				= "Biblioteca";
$tabela_objetos				= "objetos";
$tabela_personagens			= "personagens";
$tabela_terrenos			= "terrenos";
$tabela_grupos				= "grupos";
$tabela_usuarios			= "usuarios";
$tabela_nivel_permissoes	= "nivel_permissoes";
$tabela_posts				= "blogposts";
$tabela_blogs				= "blogblogs";
$tabela_comentarios			= "blogcomentarios";
$tabela_arquivos			= "arquivos";
$tabela_links				= "Links";
$tabela_turmas				= "Turmas";


/*
//antigas informacoes do BD
$email_administrador = "adiministradorplaneta@gmail.com";

$BD_host1 = "localhost";
$BD_base1 = "planeta2";
$BD_user1 = "root";
$BD_pass1 = "gamma248";

//nome tabelas
$tabela_forum				= "forum";
$tabela_objetos 			= "objetos";
$tabela_personagens 		= "personagens";
$tabela_terrenos 			= "terrenos";
$tabela_grupos		 		= "grupos";
$tabela_usuarios 			= "usuarios";
$tabela_nivel_permissoes	= "nivel_permissoes";
$tabela_posts               = "blogposts";
$tabela_blogs               = "blogblogs";
$tabela_comentarios         = "blogcomentarios";
$tabela_arquivos			= "arquivos";
*/
//Níveis básicos
$nivelAdmin 		= 0;
$admin				= "Administrador";
$nivelCoordenador 	= 5;
$coordenador		= "Coordenador";
$nivelProfessor		= 10;
$professor 			= "Professor";
$nivelAluno 		= 20;
$aluno				= "Aluno";
$nivelVisitante		= 100;
$visitante 			= "Visitante";

//Sistemas Básicos
$sistAdmin 		= "Planeta Rooda";
$sistAdminId 	= 1;
$sistAluno 		= "Sistema dos Alunos";
$sistAlunoId 	= 2;
$sistVisi 		= "Sistema Visitante";
$sistVisiId 	= 3;

error_reporting(E_ALL);
?>
