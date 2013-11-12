<?php
require_once("funcoes_aux.php");
require_once("cfg.php");
require_once("bd.php");

/*
* Representa aquela r�gua que fica em cima das telas de funcionalidades.
* 
*/
class reguaNavegacao {
//dados
	/*
	* Array com nomes dos n�veis da hierarquia na r�gua, do maior ao menor.
	* Assim, a primeira ocorr�ncia aparece primeiro, da esquerda para a direita.
	*/
	private $nomesNiveis;
	
	/*
	* Array associativo de nomes de n�veis para seus links.
	* Exemplo: linksNiveis['planetaRooda']='http://www.nuted.ufrgs.br/planeta2/'
	*/
	private $linksNiveis;
	
	/*
	* Array associativo de nomes de n�veis para seu tipo de link: associativo ou concatenado.
	* No link absoluto, redireciona-se para o link. No concatenado, concatena-se o link ao final da url atual.
	*/
	private $tiposLinksNiveis;

	/*
	* Array associativo de nomes de n�veis para seu booleanos que indicam se clicar neste n�vel fechar� a colorbox.
	*/
	private $fecharColorboxNiveis;
	
	/*
	* Tipos de links.
	*/
	const TIPO_LINK_ABSOLUTO='1';
	const TIPO_LINK_CONCATENADO='2';
	
//m�todos
	/*
	* Cria um novo objeto r�gua, mas n�o a coloca na p�gina.
	* A r�gua j� � criada com os dois n�veis padr�es:
	* 	1) A tela inicial do planeta.
	*	2) O terreno em que o usu�rio est�.
	*/
	function reguaNavegacao(){
		global $tabela_terrenos;
		global $tabela_usuarios;
		global $tabela_personagens;
		
		$this->nomesNiveis = array();
		$this->linksNiveis = array();
		$this->tiposLinksNiveis = array();
		$this->fecharColorboxNiveis = array();
		$this->adicionarNivel("Planeta ROODA", $linkServidor);
		
		$idUsuario = isset($_SESSION['SS_usuario_id']) ? (int) $_SESSION['SS_usuario_id'] : 0;
		$conexaoTerreno = new conexao();
		$conexaoTerreno->solicitar("SELECT *
									FROM $tabela_personagens JOIN $tabela_usuarios ON usuario_personagem_id=personagem_id
															 JOIN $tabela_terrenos ON terreno_id=personagem_terreno_id
									WHERE usuario_id=$idUsuario");
		$nomeTerreno = $conexaoTerreno->resultado['terreno_nome'];
		$idTerreno = $conexaoTerreno->resultado['terreno_id'];
		if($nomeTerreno == ''){
			$nomeTerreno = 'Terreno sem nome';
		}
		//$this->adicionarNivel($nomeTerreno, "http://sideshowbob/asd/desenvolvimento/");
		
		// TODO: DEBUG: REMOVER APOS O CURSO
		$this->adicionarNivel($nomeTerreno, "http://www.nuted.ufrgs.br/planeta2/listaFuncionalidades.php?terreno=$idTerreno");
	}
	
	/*
	* Coloca a r�gua na p�gina, com um simples echo.
	*/
	public function imprimir(){
		echo "<p id=\"hist\">";
		foreach($this->nomesNiveis as $nomeNivel){
			$this->imprimirNivel($nomeNivel, $this->linksNiveis[$nomeNivel], $this->tiposLinksNiveis[$nomeNivel], $this->fecharColorboxNiveis[$nomeNivel]);
			echo " &gt; ";
		}
		echo "</p>";
	}
	
	/*
	* Imprime um n�vel da r�gua, com um simples echo.
	* @param nomeNivel_param O nome do n�vel que ser� impresso.
	* @param linkNivel_param O link que � acessado quando o usu�rio clica no n�vel.
	* @param tipoLink_param O tipo de link, se � absoluto ou concatenado, conforme definido no in�cio desta classe.
	* @param fechaBox_param Booleano que indica se deve fechar a colorbox ao ser clicado.
	* ATEN��O: Caso n�o haja link, o redirecionamento ser� ignorado.
	*/
	private function imprimirNivel($nomeNivel_param, $linkNivel_param, $tipoLink_param, $fechaBox_param){
		if($linkNivel_param != ''){
			$nomeFuncaoOnClick='';
			if($fechaBox_param){
				$nomeFuncaoOnClick = 'fecharColorBox';
			} else {
				$nomeFuncaoOnClick = 'redirecionar';
			}
			switch($tipoLink_param){
				case reguaNavegacao::TIPO_LINK_ABSOLUTO: echo "<a onclick=\"".$nomeFuncaoOnClick."('".$linkNivel_param."', 'absoluto')\">".$nomeNivel_param."</a>";
					break;
				case reguaNavegacao::TIPO_LINK_CONCATENADO: echo "<a onclick=\"".$nomeFuncaoOnClick."('".$linkNivel_param."', 'concatenado')\">".$nomeNivel_param."</a>";
					break;
			}
		} else {
			echo "<a>".$nomeNivel_param."</a>";
		}
	}
	
	/*
	* Adiciona um n�vel na hierarquia desta r�gua.
	* A �nica diferen�a entre as duas fun��es que seguem � a forma que os links s�o tratados.
	* No link absoluto, redireciona-se para o link. No concatenado, concatena-se o link ao final da url atual.
	* @param nomeNivel_param O nome do n�vel que ser� adicionado e ser� vis�vel na hierarquia.
	* @param linkNivel_param O link que � acessado quando o usu�rio clica no n�vel.
	* @param fechaBox_param Booleano que indica se deve fechar a colorbox ao ser clicado.
	* @param tipoLink_param O tipo de link, se � absoluto ou concatenado, conforme definido no in�cio desta classe.
	* Exemplo:  Planeta ROODA > Terreno tal > Biblioteca (3 hierarquias com 3 nomes)
	* ATEN��O: Nomes de n�veis n�o podem ser repetidos!
	*/
	public function adicionarNivel($nomeNivel_param, $linkNivel_param='', $fechaBox_param=true, $tipoLink_param=NULL){
		$tipoLink=$tipoLink_param;
		if(is_null($tipoLink_param) or $tipoLink_param=='' or $tipoLink_param==NULL){
			$tipoLink = reguaNavegacao::TIPO_LINK_ABSOLUTO;
		}
		array_push($this->nomesNiveis, $nomeNivel_param);
		$this->tiposLinksNiveis[$nomeNivel_param] = $tipoLink;
		$this->linksNiveis[$nomeNivel_param] = $linkNivel_param;
		$this->fecharColorboxNiveis[$nomeNivel_param] = $fechaBox_param;
	}
	
	/*
	* Caso haja, retira o �ltima n�vel adicionado � r�gua.
	*/
	public function removerNivel(){
		$nomeNivelRetirado = $this->nomesNiveis[count($this->nomesNiveis)-1];
		unset($this->nomesNiveis[count($this->nomesNiveis)-1]);
		
		unset($this->tiposLinksNiveis[$nomeNivelRetirado]);
		unset($this->linksNiveis[$nomeNivelRetirado]);
		unset($this->fecharColorboxNiveis[$nomeNivelRetirado]);
	}
}

?>
