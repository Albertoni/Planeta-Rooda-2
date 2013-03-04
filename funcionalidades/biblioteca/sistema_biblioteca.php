<?php
$base_biblioteca = '';

function verificaExtensao ($arq){
	$ext = end(explode(".",strtolower($arq)));
	if (($ext == '') || ($ext == 'exe'))
		return false;
	return true;
}

function string2consulta($t, $str){
	if (trim($str) != ""){
		if ($t == '0'){
			$campo = 'biblio_titulo';
		}else if ($t == '1'){
				$campo = 'biblio_autor';
			}else if ($t == '2'){
					$campo = 'biblio_tags';
				}else if ($t == '3'){
						$campo = 'usuario_nome';
					}else{
						return false;
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


function pegaData(){
	$today = getdate();
	$dia = $today['mday'];
	$mes = $today['mon'];
	$ano = $today['year'];
	$hora = $today['hours']."h";
	$minutos = $today['minutes']."min";
	$segundos = $today['seconds'];
	return "$dia/$mes/$ano,$hora $minutos";
}

class itemBiblio { //estrutura para o item post do forum, chamado de mensagem
	var $itemId = 0;
	var $itemData = '';
	var $itemUserId = 0;
	var $itemUserName = '';
	var $itemAutor = '';
	var $itemTitulo = '';
	var $itemTags = 0;
	var $itemPonteiro = '';
	var $itemTipo = 0;
	
	function itemBiblio($id, $uid, $uname, $titulo, $autor, $data, $tags, $ponteiro, $tipo, $permissao){
		$this->itemId = $id;
		$this->itemUserId = $uid;
		$this->itemUserName = $uname;
		$this->itemTitulo = $titulo;
		$this->itemAutor = $autor;
		$this->itemData = $data;
		$this->itemTags = $tags;
		$this->itemPonteiro = $ponteiro;
		$this->itemTipo = $tipo;
		$this->itemPermissao = $permissao;
	}
}

class biblioteca {
	var $bibliotecaId = 0;
	var $item = array();
	var $contador = 0;
	var $BD_host = '';
	var $BD_base = '';
	var $BD_user = '';
	var $BD_pass = '';
	var $BD_biblio_tab = '';
	var $BD_user_tab = '';
	var $erro = '';

	function biblioteca ($id){
		$this->bibliotecaId = $id;
	}
	
	function contaPaginas(){
		return ceil($this->contador /10);
	}
	
	function configBD($BDh,$BDb,$BDu,$BDp,$BDbiblio,$BDuser){
		$this->BD_host = $BDh;
		$this->BD_base = $BDb;
		$this->BD_user = $BDu;
		$this->BD_pass = $BDp;
		$this->BD_biblio_tab = $BDbiblio;
		$this->BD_user_tab = $BDuser;
	}

	function arquivos ($pg){
		$this->erro = '';
		unset($this->item);
		$pg = ($pg > 0)? $pg : 1;
		$pagina = ($pg-1)*10;
		
		$pesquisa1 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa2 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa2->erro!= ""){
			$this->erro = $pesquisa2->erro;
			return false;
		}

		$tabela_usuarios = $this->BD_user_tab;
		$tabela_biblio = $this->BD_biblio_tab;
		$biblio_id = $this->bibliotecaId;
		
		$this->contador = $pesquisa1->registros;
		$pesquisa1->solicitar("select * from $tabela_biblio where biblio_id = '$biblio_id' ORDER BY biblio_id DESC LIMIT $pagina,10");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		
		for ($c=0; $c<$pesquisa1->registros; $c++){
			$itemId = $pesquisa1->resultado['biblio_id'];
			$itemData = $pesquisa1->resultado['biblio_data'];
			$itemUserId =  $pesquisa1->resultado['biblio_uid'];
			$itemAutor =  $pesquisa1->resultado['biblio_autor'];
			$itemTitulo =  $pesquisa1->resultado['biblio_titulo'];
			$itemTags =  $pesquisa1->resultado['biblio_tags'];
			$itemPonteiro =  $pesquisa1->resultado['biblio_ponteiro'];
			$itemTipo =  $pesquisa1->resultado['biblio_tipo'];
			
			$pesquisa2->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$itemUserId'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			
			$itemUserName = $pesquisa2->resultado['usuario_nome'];
			$editavel = permissao($itemUserId);
			$this->item[$c] = new itemBiblio($itemId, $itemUserId, $itemUserName, $itemTitulo, $itemAutor, $itemData, $itemTags, $itemPonteiro, $itemTipo, $permissao);
			$pesquisa1->proximo();
		}
		return true;
	}
	
	function pesquisa($pg, $tipo, $consulta){
		$this->erro = '';
		unset($this->item);
		$pg = ($pg > 0)? $pg : 1;
		$pagina = ($pg-1)*10;
		
		$pesquisa1 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa2 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa2->erro!= ""){
			$this->erro = $pesquisa2->erro;
			return false;
		}

		$tabela_usuarios = $this->BD_user_tab;
		$tabela_biblio = $this->BD_biblio_tab;
		$biblio_id = $this->bibliotecaId;

		switch ($tipo){
			case 0:
			case 1:
			case 2:
				$pesquisa1->solicitar("select * from $tabela_biblio where $consulta and biblio_bid = '$biblio_id'");
				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_biblio where $consulta and biblio_bid = '$biblio_id' ORDER BY biblio_id DESC LIMIT $pagina,10");
			break;
			case 3:
				$pesquisa1->solicitar("select * from $tabela_usuarios where $consulta");
				$consulta = "";
				for ($c=0; $c<$pesquisa1->registros; $c++){
					$uid = $pesquisa1->resultado['usuario_id'];
					if ($c==0)
						$consulta = "biblio_uid ='$uid'";
					else
						$consulta = $consulta . " or biblio_uid ='$uid'";
					$pesquisa1->proximo();
				}
				if ($consulta != "") $consulta = "($consulta)";
				$pesquisa1->solicitar("select * from $tabela_biblio where $consulta and biblio_bid = '$biblio_id'");
				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_biblio where $consulta and biblio_bid = '$biblio_id' ORDER BY biblio_id DESC LIMIT $pagina,10");
			break;
			default:
				$pesquisa1->solicitar("select * from $tabela_biblio where $consulta and biblio_bid = '$biblio_id'");
				$this->contador = $pesquisa1->registros;
				$pesquisa1->solicitar("select * from $tabela_biblio where biblio_bid = '$biblio_id' ORDER BY biblio_id DESC LIMIT $pagina,10");
		}
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}

		for ($c=0; $c<$pesquisa1->registros; $c++){
			$itemId = $pesquisa1->resultado['biblio_id'];
			$itemData = $pesquisa1->resultado['biblio_data'];
			$itemUserId =  $pesquisa1->resultado['biblio_uid'];
			$itemAutor =  $pesquisa1->resultado['biblio_autor'];
			$itemTitulo =  $pesquisa1->resultado['biblio_titulo'];
			$itemTags =  $pesquisa1->resultado['biblio_tags'];
			$itemPonteiro =  $pesquisa1->resultado['biblio_ponteiro'];
			$itemTipo =  $pesquisa1->resultado['biblio_tipo'];
			
			$pesquisa2->solicitar("select usuario_nome from $tabela_usuarios where usuario_id = '$itemUserId'");
			if($pesquisa2->erro!= ""){
				$this->erro = $pesquisa2->erro;
				return false;
			}
			
			$itemUserName = $pesquisa2->resultado['usuario_nome'];
			$editavel = permissao($itemUserId);
			$this->item[$c] = new itemBiblio($itemId, $itemUserId, $itemUserName, $itemTitulo, $itemAutor, $itemData, $itemTags, $itemPonteiro, $itemTipo, $permissao);
			$pesquisa1->proximo();
		}
		return true;
	}

	// Salva o arquivo... seja ele um link ou upload.
	// Se o registro dele não existir, então um novo registro no BD é criado
	// A variável Booleana '$novo' vai ser o controle para novo registro.
	// Caso ela seja False, então a variável $arqId será considerada (e ela diz o ID do registro que deverá ser modificado). 
	function salvaArquivo($novo, $uid, $autor, $titulo, $tags, $tipo, $ponteiro, $arqId){
		$tabela_biblio = $this->BD_biblio_tab;
		$bid = $this->bibliotecaId;
		$data = pegaData();
		
		$pesquisa1 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}

		
		$link = $ponteiro;
		if ($tipo == 1){
			//FAZER O UPLOAD DO ARQUIVO E COLCOAR EM $PONTEIRO O ENDEREÇO PARA O ARQUIVO SALVO
			if (isset($_FILES['imagem'])){
				$arquivo = $_FILES['imagem'];
				if (verificaExtensao($arquivo['name'])){
					$arq_nome = md5(uniqid(time())) . $arquivo['name'];
					$caminho = 'arquivos/'.$arq_nome;
					move_uploaded_file($arquivo['tmp_name'], $caminho);
					$link = $caminho;
				}else{
					$this->erro = 'extensao inválida';
					return false;
				}
			}else{
				$this->erro = 'erro no envio do arquivo';
				return false;
			}
		}
 
		if ($novo){
			$pesquisa1->solicitar("INSERT INTO $tabela_biblio (biblio_bid,biblio_tipo,biblio_ponteiro,biblio_tags,biblio_titulo,biblio_autor,biblio_uid,biblio_data) VALUES ('$bid','$tipo','$ponteiro','$tags','$titulo','$autor','$uid', '$data')");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}
		}else{
			$pesquisa1->solicitar("select * from $tabela_biblio where biblio_id = '$arqId' and biblio_bid = '$bid' LIMIT 1");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}
			if ($pesquisa1->registros != 1){
				$this->erro = 'erro no ID dado ao método';
				return false;
			}
			
			//verifica se tem algum arquivo associado ao registro, pq caso houver, será apagado.
			if ($pesquisa1->resultado['biblio_tipo'] == 1){
				unlink($pesquisa1->resultado['biblio_ponteiro']);
			}
		
			$pesquisa1->solicitar("UPDATE $tabela_biblio SET biblio_data='$data' where biblio_id = '$arqId' and biblio_bid = '$bid' and biblio_uid = '$uid' LIMIT 1");
			if($pesquisa1->erro!= ""){
				$this->erro = $pesquisa1->erro;
				return false;
			}
		}
		return true;
	}

	function excluiArquivo($arqId){
		$tabela_biblio = $this->BD_biblio_tab;
		$bid = $this->bibliotecaId;
		
		$pesquisa1 = new conexao($this->BD_host,$this->BD_base,$this->BD_user,$this->BD_pass);
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		$pesquisa1->solicitar("select * from $tabela_biblio where biblio_id = '$arqId' and biblio_bid = '$bid' LIMIT 1");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		if ($pesquisa1->registros != 1){
			$this->erro = 'erro no ID dado ao método';
			return false;
		}
		
		if ($pesquisa1->resultado['biblio_tipo'] == 1){
			unlink($pesquisa1->resultado['biblio_ponteiro']);
		}
		
		$pesquisa1->solicitar("DELETE FROM $tabela_biblio where biblio_id = '$arqId' and biblio_bid = '$bid'");
		if($pesquisa1->erro!= ""){
			$this->erro = $pesquisa1->erro;
			return false;
		}
		
		return true;
	}
	
	
}
?>