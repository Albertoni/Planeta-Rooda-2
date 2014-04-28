<?php
mb_internal_encoding('UTF-8');
	
	
	/*================================================
		Sessao até o presente momento
	================================================
					$_SESSION['SS_usuario_id']
					$_SESSION['SS_usuario_nome']
					$_SESSION['SS_usuario_login']
					$_SESSION['SS_usuario_email']
					$_SESSION['SS_personagem_id']
					$_SESSION['SS_terreno_id']
					$_SESSION['SS_link_pai'] (contém o link da página que chamou a aplicação externa)
					$_SESSION['SS_nivel_ultimo']
					S_SESSION['SS_turmas'] (Array)
	================================================
	Obs.: Não temos observações a serem feitas
	==============================================*/

$email_administrador = "admplaneta2@gmail.com";

$BD_host1 = "localhost";
$BD_base1 = "nuted_planeta";
$BD_user1 = "root";
$BD_pass1 = "root";

$linkServidor = "http://sideshowbob/Planeta-Rooda-2/";

$upload_max_filesize = ini_get('upload_max_filesize');

//constantes
define("NL","<BR />\n");	//constante nova linha
define("TIPOBLOG",1);
define("TIPOPORTFOLIO",2);
define("TIPOBIBLIOTECA",3);
define("TIPOPERGUNTA",4);
define("TIPOAULA",5);
define("TIPOCOMUNICADOR",6);
define("TIPOFORUM",7);
define("TIPOARTE",8);
define("TIPOPLAYER",10);

//tipos de planetas - a ordem eh importante - na arvore de planetas, os tipos deles sao crescentes na ordem da raiz para as folhas - planeta-pai nunca pode ter tipo menor ou igual ao do filho	
	define("PLANETARAIZ",0);
	define("PLANETASERIE",1);
	define("PLANETATURMA",2);
	define("PLANETADISCIPLINA",3);
	define("PLANETAALUNO",4);
	define("NUMTIPOSPLANETAS",4); //numero de tipos de planetas --- se se aumentar o numero de tipos de planetas aumentar essa variavel tambem

$debug = true;	// não sei se isso é usado


//nome tabelas
$tabela_acessos_planeta			= 'acessos_planeta';
$tabela_arquivos				= "arquivos";
$tabela_ArteComentarios			= "ArtesComentarios";
$tabela_ArteDesenhos			= "ArtesDesenhos";
$tabela_Aulas					= "Aulas";
$tabela_avatares				= "avatar_usuario";
$tabela_biblioComentarios		= "BibliotecaComentarios";
$tabela_Materiais				= "BibliotecaMateriais";
$tabela_blogs					= "blogblogs";
$tabela_comentarios				= "blogcomentarios";
$tabela_imagem_blog				= "blogimagens";
$tabela_posts					= "blogposts";
$tabela_tags					= "blogTags";
$tabela_forum					= "forum";
$tabela_forumMensagen			= "ForumMensagem";
$tabela_forumTopico				= "ForumTopico";
$tabela_controleFuncionalidades	= "FuncionalidadesTurma";
$tabela_objetos					= "objetos";
$tabela_grupos					= "grupos";
$tabela_permissoes				= "GerenciamentoTurma";
$tabela_links					= "Links";
$tabela_nivel_permissoes		= "nivel_permissoes";
$tabela_PerguntaPerguntas		= "PerguntaPerguntas";
$tabela_PerguntaQuestionarios	= "PerguntaQuestionarios";
$tabela_PerguntaRespostas		= "PerguntaRespostas";
$tabela_personagens				= "personagens";
$tabela_planetas				= "Planetas";
$tabela_playerVideos			= "PlayerVideos";
$tabela_playerComentarios		= "PlayerComentarios";
$tabela_portfolioPosts			= "PortfolioPosts";
$tabela_portfolioProjetos		= "PortfolioProjetos";
$tabela_portfolioComentarios	= "ProjetosComentarios";
$tabela_terrenos				= "terrenos";
$tabela_turmas					= "Turmas";
$tabela_turmasUsuario			= "TurmasUsuario";
$tabela_usuarios				= "usuarios";
$tabela_usuario_download		= 'usuario_download';



/*
//antigas informacoes do BD
$email_administrador = "adidasministradorplaneta@gmail.com";

$BD_host1 = "localhost";
$BD_base1 = "planeta2";
$BD_user1 = "root";
$BD_pass1 = "gamma248";

//nome tabelas
$tabela_forum				= "forum";
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
*/
//Níveis básicos
$nivelAdmin			= 1; // b1
$admin				= "administrador";
$nivelCoordenador	= 2; // b10
$coordenador		= "coordenador";
$nivelProfessor		= 4; // b100
$professor			= "professor";
$nivelMonitor		= 8; // b1000
$monitor			= "monitor";
$nivelAluno			= 16; // b10000
$aluno				= "aluno";
$nivelVisitante		= 256; // b100000000
$visitante			= "visitante";
define("NIVELADMIN", 1);
define("NIVELPROFESSOR", 4);
define("NIVELALUNO", 16);

//Sistemas Básicos
$sistAdmin 		= "Planeta Rooda";
$sistAdminId 	= 1;
$sistAluno 		= "Sistema dos Alunos";
$sistAlunoId 	= 2;
$sistVisi 		= "Sistema Visitante";
$sistVisiId 	= 3;

error_reporting(E_ALL);
?>
