<?

$funcArray = array(
//		nome                          				descricao,												      pagina anterior                
	"anotacoes"			 => array("<a href='anotacoes/' target='principal'>Anotações</a>",							"turmaSessao"),
	"arte"				 => array("<a href='arte/' target='principal'>Arte</a>",									"turmaSessao"),
  	"batepapo"			 => array("<a href='batepapo/' target='principal'>Bate-Papo</a>",						    "turmaSessao"),
	"biblioteca"		 => array("<a href='biblioteca/' target='principal'>Biblioteca</a>",						"turmaSessao"),
	"carteira"			 => array("<a href='carteira/' target='principal'>Carteira</a>",							"turmaSessao"),
	"config1"			 => array("<a href='config/' target='principal'>Configurações (Fundo)</a>",				    "turmaSessao"),
	"config2"			 => array("<a href='config/cor.php' target='principal'>Configurações (Cor)</a>",			"turmaSessao"),
	"contatos"			 => array("<a href='contatos/' target='principal'>Contatos</a>",							"turmaSessao"),
	"diario"			 => array("<a href='diario/' target='principal'>Diário</a>",         			            "turmaSessao"),
	"diarioVer"	         => array("Ver Diario dos Alunos",									     				    "diario"),
	"docPedagogica"      => array("<a href='docPedagogica/' target='principal'>Documentação Pedagogica</a>",        "turmaSessao"),
	"planetapergunta"    => array("<a href='planetapergunta/' target='principal'>Planeta Pergunta</a>",        		"turmaSessao"),
	"forum"				 => array("<a href='forum/' target='principal'>Fórum</a>",								    "turmaSessao"),
	"forumMensagem"		 => array("Mensagens",																	    "forum"),
	"forumNovo"			 => array("Novo Tópico",																	"forum"),
	"gerencia"			 => array("<a href='gerencia/' target='principal'>Dados da Turma</a>",					    "turmaSessao"),
	"projeto"			 => array("(projeto)",											     						"projetos"),
	"projetoArquivos"	 => array("Arquivos do Projetos",									     					"projeto"),
	"projetoCriar"		 => array("Criar Projetos", 										    					"projetos"),
	"projetoDiario"		 => array("Diário do Projeto",    									 						"projeto"),
	"projetoEditar"		 => array("Editar Projeto",     															"projeto"),
	"projetoEditarMsg"	 => array("Editar Mensagem",     															"projeto"),
	"projetoHistorico"	 => array("Histórico do Projeto",     														"projeto"),
	"projetoNovaMsg"	 => array("Nova Mensagem",     																"projeto"),
	"projetos"			 => array("<a href='projetos/' target='principal'>Projetos</a>",     						"turmaSessao"),
	"turmas"			 => array("<a href='turmas.php' target='principal'>Minhas Turmas</a>",						"#"),
	"turmaSessao"		 => array("(nomeTurma)",																	"turmas"),
);

$codigo = "";

function atualizaVoltar($voltar) {
?>
	<script type='text/javascript'>
		parent.document.getElementById('voltar').value = "<?=$voltar; ?>";
	</script>
<?	
}

function mostraHierarquia($var) {
?>
	<script type='text/javascript'>
		parent.document.getElementById('hierarquia').innerHTML = "<?=$var; ?>";
	</script>
<?	
}

function buscaPai($pagina) {
	global $funcArray;
	global $codigo;

	$pagina2 = $funcArray[$pagina][1];	
	$temp    = $funcArray[$pagina2][0];

	if ($temp != "") {
		if ($temp == "(nomeTurma)") {
			$turma    = sessaoTurma();
			$codTurma = $turma["codTurma"];
			if ($turma["codTurma"] != "") {
				$select   = "SELECT nomeTurma,nomeDisciplina FROM Turmas WHERE codTurma=$codTurma";
				$turma    = db_busca($select);			
				$temp     = "<a href='turmaSessao.php?codTurma=$codTurma' target='principal'>" . $turma[0]["nomeDisciplina"] ." - ". $turma[0]["nomeTurma"] . "</a>";

				$var = "$temp" . " > ";
			}
		}
		else {
			if ($temp == "(projeto)") {
				$select = "SELECT titulo FROM Projetos WHERE codProjeto=$codigo";
				$result = db_busca($select);

				$temp   = "<A href='projetos/projeto.php?proj=$codigo' target='principal'>".$result[0]["titulo"]."</A>";
			}
			$var = "$temp" . " > ";
		}
	}

	$temp2 = "";

	if ($pagina2 != '#')
		$temp2 = buscaPai($pagina2);

	$var = $temp2 . $var;
	return "$var";
}

function hierarquia($pagina, $cod="", $codAux="") {
	global $funcArray;
	global $codigo;
	$codigo = $cod;

	if ($pagina == 'turmas')
		$var .= "<a href='turmas.php' target='principal'>Minhas Turmas</a>";
	else {
		$temp = $funcArray[$pagina][0];

		if ($temp == '(nomeTurma)') {
			$turma    = sessaoTurma();
			$codTurma = $turma["codTurma"];
			if ($turma["codTurma"] != "") {
				$select   = "SELECT nomeTurma,nomeDisciplina FROM Turmas WHERE codTurma=$codTurma";
				$turma    = db_busca($select);			
				$temp     = "<a href='turmaSessao.php?codTurma=$codTurma' target='principal'>" . $turma[0]["nomeDisciplina"] ." - ". $turma[0]["nomeTurma"] . "</a>";
			}

			$var = $var . "$temp";
		}
		else {
			if ($temp == "(projeto)") {
				$select = "SELECT titulo FROM Projetos WHERE codProjeto=$codigo";
				$result = db_busca($select);

				$temp   = "<A href='projetos/projeto.php?proj=$codigo' target='principal'>".$result[0]["titulo"]."</A>";
			}
			$var = $var . "$temp";
		}

		$var = buscaPai($pagina) . $var;
		
	}

	$var = "<strong><font color='ffffff' size='2'>".$var."</font></strong>";
	mostraHierarquia($var);

	//controle de retornos e variavel da sessao visao_aparecer, onde controla o acesso a visão
	$voltar = "";
	switch ($pagina) {
		case "biblioteca":       $voltar = "../biblioteca/index.php"; 
		$_SESSION["visao_aparecer"] = "aparecer";break;
		
		case "planetapergunta":
		$_SESSION["visao_aparecer"] = "aparecer";break;
		
		case "config1":
		$_SESSION["visao_aparecer"] = "sumir";break;

		case "config2":
		$_SESSION["visao_aparecer"] = "sumir";break;
		
		case "anotacoes":
		$_SESSION["visao_aparecer"] = "sumir";break;
		
		case "contatos":
		$_SESSION["visao_aparecer"] = "sumir";break;

		case "carteira":
		$_SESSION["visao_aparecer"] = "sumir";break;
		
		case "arte":
		$_SESSION["visao_aparecer"] = "sumir";break;
		
		case "diario":           $voltar = "../diario/index.php"; 
		$_SESSION["visao_aparecer"] = "sumir";break;
		
		case "diarioVer":        $voltar = "../diario/verDiarioDosAlunos.php?codAluno=$codigo"; 
		$_SESSION["visao_aparecer"] = "sumir"; break;
		
		case "docPedagogica":    $voltar = "../docPedagogica/index.php";
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "forum":            $voltar = "../forum/index.php"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "gerencia":   	     $voltar = "../gerencia/index.php"; 
		$_SESSION["visao_aparecer"] = "sumir"; break;
		
		case "projeto":          $voltar = "../projetos/projeto.php?proj=$codigo"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoArquivos":  $voltar = "../projetos/ver_arquivos.php?proj=$codigo"; 
		$_SESSION["visao_aparecer"] = "aparecer";break;
		
		case "projetoCriar":     $voltar = "../projetos/novo_topico.php"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoDiario":    $voltar = "../projetos/diario.php?proj=$codigo"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoEditar":    $voltar = "../projetos/editar_topico.php?proj=$codigo"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoEditarMsg": $voltar = "../projetos/editar_mensagem.php?proj=$codigo&codPost=$codAux"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoHistorico": $voltar = "../projetos/historico.php?proj=$codigo";
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetoNovaMsg":   $voltar = "../projetos/nova_mensagem.php?proj=$codigo"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "projetos":         $voltar = "../projetos/index.php"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
		
		case "turmas":           $voltar = "../turmas.php"; 
		$_SESSION["visao_aparecer"] = "sumir"; break;
		
		case "turmaSessao":      $voltar = "../turmas/index.php"; 
		$_SESSION["visao_aparecer"] = "aparecer"; break;
	}

	if ($voltar != "")
		atualizaVoltar($voltar);

	$usuario = sessaoUsuario();
	visao($usuario["associacao"]);
}

?>