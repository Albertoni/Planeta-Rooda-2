<?php

require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

class Flash{
//dados
	/*
	* Animação da tela de carregamento do planeta.
	*/
	private $animacao;
	/*
	* Cima para baixo, baixo para cima, etc.
	*/
	private $direcaoAnimacao;
	/*
	* O fundo que o quarto do aluno deve ter. Este fundo deve refletir o terreno pelo qual o personagem acessou seu quarto.
	*/
	private $fundoQuarto;
	
	/*
	* Para o caso de acesso a funcionalidade ou link externo, contêm os links necessários.
	*/
	private $linkServidor;
	private $linkColorBox;

//métodos
	function Flash(){
		
	}
	
	public function setLinks($linkColorBox_param, $linkServidor_param){
		$this->linkColorBox = $linkColorBox_param;
		$this->linkServidor = $linkServidor_param;
	}
	
	/*
	* Função para chamar o flash!
	* @param terrenoId_param O id no banco de dados do terreno para o qual o usuário irá.
	* @param linkServidor_param Link absoluto para o servidor.
	*/
	public function chamar($terrenoId_param, $linkServidor_param){
		//<param name="flashID" value="loader.swf" />
		//id="flashID"
		$this->decidirAnimacao($terrenoId_param);
		
		echo '	<div id="conteudoFlash">
					<object id="flashId1" name="flashName1" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%" align="middle">
						<param name="quality" value="high" />
						<param name="allowScriptAccess" value="always"/>
						<param name="wmode" value="window" />
						<!--[if !IE]>-->
						<embed id="flashID" name="loader" src="loader.swf" quality="high" bgcolor="#ffffff" wmode="window" 
							flashvars = "animacao='.$this->animacao.'&direcaoAnimacao='.$this->direcaoAnimacao.'&fundoQuarto='.$this->fundoQuarto.'&linkServidor='.$linkServidor_param.'"
							width="100%" height="100%"align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" 
							pluginspage="http://www.macromedia.com/go/getflashplayer" >
						<!--<![endif]-->
							<param name= "flashvars" value="animacao='.$this->animacao.'&direcaoAnimacao='.$this->direcaoAnimacao.'&fundoQuarto='.$this->fundoQuarto.'&linkServidor='.$linkServidor_param.'" />
						</object>
					</object>
				</div>
				<div id="conteudoScreenshot" style="position:absolute; top: -10000px;">
					<img id="objetoScreenshot" src="blank.gif" style="margin: 0 auto; display:block;" />
				</div>
				<script type="text/javascript">
					swfobject.registerObject("flashID");
				</script>';
				//<object name="flashName" id="flashID" type="application/x-shockwave-flash" data="loader.swf" width="100%" height="100%">
	}
	
	/*
	* Dado um terreno, define os dados de animação da tela de carregamento do flash.
	* @param terrenoId_param O id de algum terreno no banco de dados.
	* @return nada.
	*/
	private function decidirAnimacao($terrenoId_param){
		global $tabela_personagens;
		
		$personagem_id = $_SESSION['SS_personagem_id'];
		
		$conexao_aparencia_planeta = new conexao();
		$conexao_aparencia_planeta->solicitar("SELECT *
									FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
									WHERE T.terreno_id = $terrenoId_param");
		
		$animacao = "";
		$direcaoAnimacao = '';
		$fundoQuarto = "";
		if($conexao_aparencia_planeta->resultado['Aparencia'] == '6'){
			$animacao = "predio";
			$direcaoAnimacao = '1';
			$conexao_personagem = new conexao();
			$conexao_personagem->solicitar("SELECT * 
											FROM $tabela_personagens 
											WHERE personagem_id=$personagem_id");
			$terreno_acesso_agora_id = $conexao_personagem->resultado['personagem_terreno_id'];
			if($terrenoId_param == $terreno_acesso_agora_id){
				$id_terreno_que_contem_ultimo_predio_acessado = $conexao_personagem->resultado['personagem_ultimo_terreno_id'];
			} else {
				$id_terreno_que_contem_ultimo_predio_acessado = $conexao_personagem->resultado['personagem_terreno_id'];
			}
			$conexao_aparencia_planeta->solicitar("SELECT *
									FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
									WHERE T.terreno_id = $id_terreno_que_contem_ultimo_predio_acessado");
			$fundoQuarto = $conexao_aparencia_planeta->resultado['Aparencia'];
		} else {
			$conexao_personagem = new conexao();
			$conexao_personagem->solicitar("SELECT * 
											FROM $tabela_personagens 
											WHERE personagem_id=$personagem_id");
			$terreno_acesso_agora_id = $conexao_personagem->resultado['personagem_terreno_id'];
			$conexao_aparencia_planeta->solicitar("SELECT *
									FROM terrenos AS T JOIN Planetas AS P ON P.Id = T.terreno_grupo_id
									WHERE T.terreno_id = $terreno_acesso_agora_id");
			if($conexao_aparencia_planeta->resultado['Aparencia'] == '6'){
				$animacao = "predio";
				$direcaoAnimacao = '2';
				$fundoQuarto = $conexao_aparencia_planeta->resultado['Aparencia'];
			} else {
				$animacao = "foguete";
			}
		}
		
		$this->animacao = $animacao;
		$this->direcaoAnimacao = $direcaoAnimacao;
		$this->fundoQuarto = $fundoQuarto;
	}
	
	
}

?>