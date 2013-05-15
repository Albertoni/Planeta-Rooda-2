<?php
/*
*	Sistema do forum
*
*/
	
class itemMsg { //estrutura para o item post do forum, chamado de mensagem
	var $msgId = 0;
	var $msgPai = 0;
	var $msgData = '';
	var $msgUserId = 0;
	var $msgUserName = '';
	var $msgTitulo = '';
	var $msgTexto = '';
	var $msgQntFilhos = 0;
	var $msgLink = '';
	var $msgGrau = 0;
	
	function itemMsg($id, $uid, $uname, $pai, $qnt, $data, $conteudo, $grau,$titulo=''){
		$this->msgId = $id;
		$this->msgPai = $pai;
		$this->msgData = $data;
		$this->msgUserId = $uid;
		$this->msgUserName = $uname;
		$this->msgTitulo = $titulo;
		$this->msgTexto = $conteudo;
		$this->msgQntFilhos = $qnt;
		$this->msgGrau = $grau;
	}
}

function pegaData(){
	$today = getdate();
	$dia = $today['mday'];
	$mes = $today['mon'];
	$ano = $today['year'];
	$hora = $today['hours']."h";
	$minutos = $today['minutes']."min";
	$segundos = $today['seconds'];
	return "$dia/$mes/$ano,$hora $minutos";  // Esse return explica tudo.
}

function string2consulta($t, $str){ // Usado em pesquisa_forum pra repassar pro pesquisa da classe forum, constroi uma substring de pesquisa.
	if (trim($str) != ""){
		if ($t == '0'){
			$campo = 'msg_titulo';
		}else if ($t == '1'){
				$campo = 'usuario_nome';
			}else if ($t == '2'){
					$campo = 'msg_conteudo';
				}else{
					$campo = '';
				}
		
		$elementos = explode(" ", trim($str));
		$cel = count($elementos);
		for ($i=0; $i <$cel; $i++){
			if (empty($elementos[$i]))
				unset ($elementos[$i]);
			else
				$elementos[$i] = "$campo LIKE '%$elementos[$i]%'";
		}
		$consulta = implode(" AND ",$elementos);
		return "($consulta)";
	}else{
		return false;
	}
}

function troca(&$v1, &$v2){ // Eu acho que troca o valor de v1 com o de v2. Sinceramente espero que faça isso, pelo menos.
	$vaux = $v1;
	$v1 = $v2;
	$v2 = $vaux;
}


//ordena
function quicksort(&$vet, $ini, $fim){
	if ($ini < $fim){
		$k = divide($vet, $ini, $fim);
		quicksort($vet, $ini, $k-1);
		quicksort($vet, $k+1, $fim);
	}
}		//divide o array em dois
		function divide(&$vet, $ini, $fim){
			$i = $ini;
			$j = $fim;
			$dir = 1;

			while ($i < $j){
				if ($vet[$i]->msgId > $vet[$j]->msgId){
					troca($vet[$i], $vet[$j]);
					$dir = - $dir;
				}
				if ($dir == 1) {
					$j--;
				}else{
					$i++;
				}
			}
			return $i;
		}



class forum { //estrutura para o item post do forum, chamado de mensagem
	var $forumId = 0;
	var $mensagem = array();
	var $titulo = 'MENSAGENS';
	var $contador = 0;
	var $erro = '';
	var $topico = -1;
	
	/*\
	 *Funções:
	 *contaPáginas()
	 *pesquisa($pg, $tipo, $consulta)
	 *topicos($pg)
	 *
	\*/
	
	function forum($fid){	// Construtor, somente seta o id do forum
		$this->forumId = $fid;
	}
	
	function contaPaginas(){
		return ceil($this->contador /10); // Auto-explicativa
	}
	
	
	function pesquisa($pg, $tipo, $consulta){
		global $tabela_usuarios, $tabela_forum;
	
		$this->titulo = 'RESULTADOS DA PESQUISA';
		$this->erro = '';
		
		unset($this->mensagem); // TODO: Porque isso existe?
		
		$pg = ($pg > 0)? $pg : 1;
		$pagina = ($pg-1)*10;
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa1->erro;
			return false;
		}
		
		$pesquisa2 = new conexao();
		if($pesquisa2->erro!= ""){ // Se tiver algum erro, copia da pesquisa pra classe:
			$this->erro = $pesquisa2->erro;
			return false;
		}

		$forum_id = $this->forumId;

		$this->erro = "tipo:$tipo - cons: $consulta"; // Se sobreviveu aos erros, põe infos de debug
		switch ($tipo){
			case 0:
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id'");



				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id' ORDER BY msg_id ASC LIMIT $pagina,10");
			break;


			case 1:
				$pesquisa1->solicitar("select * from $tabela_usuarios where $consulta");
				$consulta = "";
				for ($c=0; $c<$pesquisa1->registros; $c++){
					$uid = $pesquisa1->resultado['usuario_id'];
					if ($c==0)
						$consulta = "msg_usuario ='$uid'";
					else
						$consulta = $consulta . " or msg_usuario ='$uid'";
					$pesquisa1->proximo();
				}
				if ($consulta != "") $consulta = "($consulta)";
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id'");
				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id' ORDER BY msg_id ASC LIMIT $pagina,10");
			break;


			case 2:
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id'");
				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_forum where $consulta and forum_id = '$forum_id' ORDER BY msg_id ASC LIMIT $pagina,10");
			break;


			default: // Pega os tópicos, não os posts.
				$pesquisa1->solicitar("select * from $tabela_forum where msg_pai = '-1' and forum_id = '$forum_id' ORDER BY msg_id ASC LIMIT $pagina,10");
		}
		
		if($pesquisa1->erro!= ""){ // in case of failure, dive for cover
			$this->erro = $pesquisa1->erro;
			return false;
		}

		for ($c=0; $c<$pesquisa1->registros; $c++){
		
			// Seta as variáveis para a pesquisa seguinte
			$id_msg = $pesquisa1->resultado['msg_id'];
			$titulo = $pesquisa1->resultado['msg_titulo'];
			$msg = $pesquisa1->resultado['msg_conteudo'];
			$dono = $pesquisa1->resultado['msg_usuario']; // ESSA É A ID DO DONO
			$data = $pesquisa1->resultado['msg_data'];
			$pai = $pesquisa1->resultado['msg_pai'];
			
			// Pega o nome do criador do post
			$pesquisa2->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			$nome = $pesquisa2->resultado['usuario_nome'];
			
			// Pega o numero de filhos da mensagem.
			$pesquisa2->solicitar("select * from $tabela_forum where msg_pai = '$id_msg'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			$quantidade = $pesquisa2->registros;

			if ($pai != '-1'){ // Caso NÃO seja um tópico, pega o título do pai.
				$pesquisa2->solicitar("select * from $tabela_forum where msg_id = '$pai' and forum_id = '$forum_id' LIMIT 1");
				if($pesquisa2->erro!= ""){
					$this->erro = $pesquisa2->erro;
					return false;
				}
				$titulo = $pesquisa2->resultado['msg_titulo'];
			}
			
			$post_id=$pesquisa2->resultado['msg_id'];



			$editavel = permissao($dono, $post_id); //lembrete:fazer PERMISSAO virar OO
			$this->mensagem[$c] = new itemMsg($id_msg, $dono, $nome, $pai, $quantidade, $data, $msg, $editavel, 0);
			$pesquisa1->proximo();
		}
		return true;
	}
	
	
	
	
	function topicos($pg){
		$this->titulo = 'TÓPICOS';
		unset($this->mensagem);
		$pg = ($pg > 0)? $pg : 1;
		$pagina = ($pg-1)*10;
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		
		$pesquisa2 = new conexao();
		if($pesquisa2->erro!= ""){
			$this->erro = $pesquisa2->erro;
			return false;
		}

		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		
		$pesquisa1->solicitar("select * from $tabela_forum where msg_pai = '-1' and forum_id = '$forum_id'");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$this->contador = $pesquisa1->registros;
		$pesquisa1->solicitar("select * from $tabela_forum where msg_pai = '-1' and forum_id = '$forum_id' ORDER BY msg_id DESC LIMIT $pagina,10");

		for ($c=0; $c<$pesquisa1->registros; $c++){
			$id_msg = $pesquisa1->resultado['msg_id'];
			$titulo = $pesquisa1->resultado['msg_titulo'];
			$msg = $pesquisa1->resultado['msg_conteudo'];
			$dono = $pesquisa1->resultado['msg_usuario'];
			$data = $pesquisa1->resultado['msg_data']; //verificar se existe campo DATA no BD
			
			$pesquisa2->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			$nome = $pesquisa2->resultado['usuario_nome'];
			$pesquisa2->solicitar("select * from $tabela_forum where msg_pai = '$id_msg'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			$quantidade = $pesquisa2->registros;
			
			$post_id=$pesquisa2->resultado['msg_id'];
			
			$this->mensagem[$c] = new itemMsg($id_msg, $dono, $nome, -1, $quantidade, $data, $msg, 0,$titulo);
			$pesquisa1->proximo();
		}
		return true;
	}




	function salvaMensagem($nova, $pai, $dono, $titulo, $conteudo){
		global $tabela_forum;
		$forum_id = $this->forumId;
		
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}

		$data = pegaData();
		
		if ($nova){ // Se for uma mensagem nova
			$titulook = $pesquisa1->sanitizaString($titulo);
			$conteudook = $pesquisa1->sanitizaString($conteudo);
			$pesquisa1->solicitar("INSERT INTO $tabela_forum (msg_usuario, msg_titulo, msg_conteudo, msg_pai, forum_id, msg_data) VALUES ('$dono','$titulook','$conteudook','$pai','$forum_id', '$data')");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}


		}else{ // se for edição de uma já existente
			$titulook = $pesquisa1->sanitizaString($titulo);
			$conteudook = $pesquisa1->sanitizaString($conteudo);
			
			$pesquisa1->solicitar(	"UPDATE $tabela_forum
									SET msg_titulo='$titulook', msg_conteudo='$conteudook', msg_data='$data'
									WHERE (msg_id = '$pai' AND forum_id = '$forum_id' AND msg_usuario = '$dono') LIMIT 1");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}
		}
		return true;
	}
	
	
	
	
	function excluiMensagem($topico){
		global $tabela_forum;
		$forum_id = $this->forumId;
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa1->solicitar("DELETE FROM $tabela_forum WHERE msg_id = '$topico' AND forum_id = '$forum_id' LIMIT 1");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa1->solicitar("DELETE FROM $tabela_forum WHERE msg_pai = '$topico' AND forum_id = '$forum_id'");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		return true;
	}




	function pegaDadosDaMensagem($topico){
		unset($this->mensagem);
		$pesquisa1 = new conexao();
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa2 = new conexao();
		if($pesquisa2->erro!= ""){
			$this->erro = $pesquisa2->erro;
			return false;
		}

		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		
		$pesquisa1->solicitar("select * from $tabela_forum where msg_id = '$topico' and forum_id = '$forum_id' LIMIT 1");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$this->contador = $pesquisa1->registros;
		$titulo = $pesquisa1->resultado['msg_titulo'];
		$id_msg = $pesquisa1->resultado['msg_id'];
		$msg = $pesquisa1->resultado['msg_conteudo'];
		$dono = $pesquisa1->resultado['msg_usuario'];
		$data = $pesquisa1->resultado['msg_data'];
		$pai = $pesquisa1->resultado['msg_pai'];
		$pesquisa1->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono'");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$nome = $pesquisa2->resultado['usuario_nome'];
		$qnt = 0;
		if ($pai == '-1'){
			$pesquisa1->solicitar("select * from $tabela_forum where msg_pai = '$topico' and forum_id = '$forum_id'");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}
			$qnt = $pesquisa1->registros;
		}
		$editavel = permissao($dono); //lembrete:fazer PERMISSAO virar OO
		$this->mensagem[0] = new itemMsg($id_msg, $dono, $nome, $pai, $qnt, $data, $msg, $editavel, 0);
		
		return true;
	}




	function paginas($pg, $qnt){
		$pgs = array();
		
		$primeiro = 1;
		$ant = $pg -1;
		if ($ant < 1) $ant = 1;
		
		$linf = $pg - floor($qnt/2);
		$lsup = $pg + floor($qnt/2);
		$ultimo = ceil($this->contador /10);

		
		if ($ultimo < 1) return false;
		if ($ultimo < $lsup) $lsup = $ultimo;
		
		$prox = $pg + 1;
		if ($prox > $ultimo) $prox = $ultimo;

		if ($linf < 1) $linf = 1;
		
		$pgs[] = $primeiro;
		$pgs[] = $ant;
		$pgs[] = $prox;
		$pgs[] = $ultimo;
		
 		for ($i = $linf; $i <= $lsup; $i++){
 			$pgs[] = $i;
 		}
		
		return $pgs;
	}
	
	
	
	
	
	function paiAbsoluto($id){
		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		$pesquisa = new conexao();
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return null;
		}
		$pesquisa->solicitar("select * from $tabela_forum where msg_id = '$id' and forum_id = '$forum_id' LIMIT 1"); //LE DIREITO Q TU ENTENDE =P
		if ($pesquisa->registros < 1) return null;
		$pai = $pesquisa->resultado['msg_pai'];
		if ($pai == '-1') return $id;
		return $this->paiAbsoluto($pai);
	}
	
	
	
	
	
	function retornaRespostas($id,$primeiro=0){
		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		$pesquisa = new conexao();
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return null;
		}
		$pesquisa->solicitar("select * from $tabela_forum where msg_id = '$id' and forum_id = '$forum_id' LIMIT 1"); //LE DIREITO Q TU ENTENDE =P
		if ($pesquisa->registros < 1) return '';
			$titulo = $pesquisa->resultado['msg_titulo'];
			$id_msg = $pesquisa->resultado['msg_id'];
			$msg = $pesquisa->resultado['msg_conteudo'];
			$dono = $pesquisa->resultado['msg_usuario'];
			$data = $pesquisa->resultado['msg_data'];
			$pai = $pesquisa->resultado['msg_pai'];
			$vetor_data = explode(",",$data);

		$pesquisa->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono' LIMIT 1");
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return null;
		}
		$nome = $pesquisa->resultado['usuario_nome'];
		
		$cabecalho = '<div class="info" style="float:none;border:0;">';
		$cabecalho = $cabecalho.'<p class="nome">'.$nome.'</p>';
		$cabecalho = $cabecalho.'<p class="data"><span style="color:#C60;">'.$vetor_data[0].'</span> às <span style="color:#C60;">'.$vetor_data[1].'</span></p>';
		$cabecalho = $cabecalho.'</div>';

		if ($primeiro == 0) $cabecalho = '';
		$primeiro++;

		if (($pai == $this->topico)||($primeiro == 2)){
			return $cabecalho.$msg;
		}else{
			return $cabecalho.'<div class="anterior">'.$this->retornaRespostas($pai,$primeiro).'</div>'.$msg;
		}
	}
	
	
	
	
	
	function pegaArvore($id,$grau,$cronos){ //retorna um array de resultados. As mensagens, seguidas pelas suas respostas. Dê um print_r se for necessário maior compreensão.
		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		$tudo = array();
		$pesquisa = new conexao();
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return array();
		}
		$pesquisa2 = new conexao();
		if($pesquisa2->erro!= ""){
			$this->erro = $pesquisa2->erro;
			return array();
		}
		$pesquisa->solicitar("select * from $tabela_forum where msg_pai = '$id' and forum_id = '$forum_id' ORDER BY msg_id ASC"); //LE DIREITO Q TU ENTENDE =P
		if ($pesquisa->registros < 1) return array();
		for ($c=0; $c<$pesquisa->registros; $c++){
			$auxiliar = array();
			$titulo = $pesquisa->resultado['msg_titulo'];
			$id_msg = $pesquisa->resultado['msg_id'];
			$msg = $pesquisa->resultado['msg_conteudo'];
			$dono = $pesquisa->resultado['msg_usuario'];
			$data = $pesquisa->resultado['msg_data'];
			$pai = $pesquisa->resultado['msg_pai'];

			$pesquisa2->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono' LIMIT 1");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return array();
			}
			$nome = $pesquisa2->resultado['usuario_nome'];
			$pesquisa2->solicitar("select * from $tabela_forum where msg_pai = '$id_msg'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return array();
			}
			$quantidade = $pesquisa2->registros;

			//$editavel = permissao($dono); //lembrete:fazer PERMISSAO virar OO
			if ($cronos) $msg = $this->retornaRespostas($id_msg);
			$tudo[] = new itemMsg($id_msg, $dono, $nome, $pai, $quantidade, $data, $msg, $grau);
			
			$auxiliar = $this->pegaArvore($pesquisa->resultado['msg_id'],$grau+1,$cronos);
			if ($auxiliar != array())
				$tudo = array_merge($tudo, $auxiliar);
			$pesquisa->proximo();
		}
		return $tudo;
	}
	
	
	
	
	
	function pegaMensagensArvore($id, $pagina, $cronos = true, $ultimaPagina = false){
		unset($this->mensagem);
		$this->topico = $id;
		global $tabela_usuarios;
		global $tabela_forum;
		$forum_id = $this->forumId;
		
		$pagina = ($pagina > 0)? $pagina : 1;
		// TODO: Tinha um if aqui, mas não me lembor pra que, talvez fosse importante
		
		$pagina = ($pagina-1)*10;
		$pesquisa = new conexao();
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return array();
		}
		
		$pesquisa->solicitar("select * from $tabela_forum where msg_id = '$id' and forum_id = '$forum_id' LIMIT 1"); //LE DIREITO Q TU ENTENDE =P
		if ($pesquisa->registros < 1){
			return array();
		}
		
		$titulo = $pesquisa->resultado['msg_titulo'];
		$id_msg = $pesquisa->resultado['msg_id'];
		$msg = $pesquisa->resultado['msg_conteudo'];
		$dono = $pesquisa->resultado['msg_usuario'];
		$data = $pesquisa->resultado['msg_data'];
		$pai = $pesquisa->resultado['msg_pai'];

		$pesquisa->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$dono' LIMIT 1");
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return array();
		}
		$nome = $pesquisa->resultado['usuario_nome'];
		$pesquisa->solicitar("select * from $tabela_forum where msg_pai = '$id_msg'");
		if($pesquisa->erro!= ""){
			$this->erro = $pesquisa->erro;
			return array();
		}
		$quantidade = $pesquisa->registros;
		if ($titulo == ''){
			$titulo = "Respostas";
		}
		$this->titulo = $titulo;
		
		
		// WARNING: WILD GAMBIARRA IS DONE HERE
		if(!isset($grau)) { $grau = NULL; }
		
		$item = new itemMsg($id_msg, $dono, $nome, $pai, $quantidade, $data, $msg, false, $grau);
			
		$this->mensagem = array_merge(array(0 => $item),$this->pegaArvore($id,0,$cronos));
		$this->contador = count($this->mensagem);
		if ($ultimaPagina){
			$pagina = ceil(($this->contador)/10);
			$pagina = ($pagina-1)*10;
		}
		
		if ($cronos) // organiza os itens por data
			quicksort($this->mensagem,0,count($this->mensagem)-1);
		$this->mensagem = array_slice($this->mensagem, $pagina, 10);
		return true;
	}
}


?>
