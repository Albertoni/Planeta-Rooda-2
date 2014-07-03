<?php //>
require_once("cfg.php");
require_once("bd.php");
require_once("usuarios.class.php");
class Arquivo {

	// tabelas que referenciam arquivos e condicao de consulta
	private static $referencias_bd = array(
		"BibliotecaMateriais" => "WHERE tipoMaterial LIKE 'a' AND refMaterial = ",
		"ForumMensagemAnexos" => "WHERE idArquivo = ",
		"PortfolioArquivos"   => "WHERE IdArquivo = ",
		"BlogArquivos"   => "WHERE IdArquivo = "
	);

	protected $id = false; // só mudar se o arquivo for carregado/salvado com sucesso.
	protected $idUsuario;

	protected $titulo = "";
	protected $nome = ''; // nome do arquivo. Deve conter a extensao também
	protected $tipo = ""; // mime-type
	protected $tamanho = 0;
	protected $conteudo;
	protected $md5;
	protected $data;
	protected $erros = array();
	protected $upload = false;
	protected $download = false;

	protected $consulta;

	public function __construct($id = false) {
		if ($id === false) {
			$this->data = date('Y-m-d');
			$this->upload = true;
		} else {
			$this->abrir($id);
		}
		return;
	}

	protected function abrir($id) {
		global $tabela_arquivos;
		$id = (int) $id;
		$bd = new conexao();
		$bd->solicitar(
			"SELECT
			arquivo_id AS id,
			titulo AS 'titulo',
			nome AS 'nome',
			tipo AS 'tipo',
			tamanho AS 'tamanho',
			arquivo AS 'conteudo',
			md5 AS 'md5',
			dataUpload AS 'data',
			uploader_id AS 'idUsuario'
			FROM $tabela_arquivos
			WHERE arquivo_id = '$id'"
		);
		if ($bd->erro) {
			// erro na consulta;
			$this->erros[] = "mysql: " . $bd->erro;
		}
		if ($bd->registros === 1) {
			// carrega arquivo encontrado
			$this->id = $id;
			$this->popular($bd->resultado);
			$this->upload = false;
			$this->download = true;
		} else {
			$this->erros[] = "[arquivo] Arquivo n&atilde;o encontrado";
			if ($bd->registros > 1)
				$this->erros[] = "[arquivo] Vários arquivos encontrados";
		}
		return;
	}

	protected function popular($resultadoBd) {
		$this->id        = (int) $resultadoBd['id'];
		$this->conteudo  = $resultadoBd['conteudo']; // conteudo do arquivo
		$this->md5       = $resultadoBd['md5']; // conteudo do arquivo
		$this->titulo    = $resultadoBd['titulo'];     // titulo do arquivo
		$this->nome      = $resultadoBd['nome'];
		$this->tipo      = $resultadoBd['tipo'];
		$this->tamanho   = (int) $resultadoBd['tamanho'];
		$this->data      = $resultadoBd['data'];
		$this->idUsuario = (int) $resultadoBd['idUsuario'];
	}

	public function getId() { return $this->id; }
	public function getConteudo() { return $this->conteudo; }
	public function getTitulo() { return $this->titulo; }
	public function getNome() { return $this->nome; }
	public function getTipo() { return $this->tipo; }
	public function getTamanho() { return $this->tamanho; }
	public function getData() { return $this->data; }
	public function getIdUsuario() { return $this->idUsuario; }
	public function getErros(){
		$erros = array();
		foreach ($this->erros as $value) {
			$erros[] = $value;
		}
		return $erros;
	}

	// função de valor semantico (arquivo existe no banco de dados se tiver id)
	public function existe() {
		return (bool) $this->id;
	}

	public function temErros(){
		return (0 !== count($this->erros));
	}
	// metodo: getAssoc
	// Gera array associativo simples com informações do arquivo.
	// Util para geração JSON.
	public function getAssoc() {
		$assoc = array();
		$assoc['id'] = $this->getId();
		$assoc['titulo'] = $this->getTitulo();
		$assoc['nome'] = $this->getNome();
		$assoc['tipo'] = $this->getTipo();
		$assoc['tamanho'] = $this->getTamanho();
		return $assoc;
	}
	// METODOS RELACIONSADOS A UPLOAD
	public function salvar() {
		global $tabela_arquivos;
		if ($this->titulo === '' || $this->nome === '' || $this->tipo === '' || $this->tamanho <= 0 || !$this->idUsuario) {
            if($this->titulo === ''){
                $this->errors[] = '[arquivo] Arquivo não pode ser enviado. Código de erro 1.1.';
            }
            if($this->nome === ''){
                $this->errors[] = '[arquivo] Arquivo não pode ser enviado. Código de erro 1.2.';
            }
            if($this->tipo === ''){
                $this->errors[] = '[arquivo] Arquivo não pode ser enviado. Código de erro 1.3.';
            }
            if($this->tamanho <= 0){
                $this->errors[] = '[arquivo] Arquivo não pode ser enviado. Código de erro 1.4.';
            }
            if(!$this->idUsuario){
                $this->errors[] = '[arquivo] Arquivo não pode ser enviado. Código de erro 1.5.';
            }

			return false;
		}
		// NOVO ARQUIVO
		if ($this->upload && !$this->download) {
			// novo arquivo
			$bd = new conexao();
			// sanitizando dados para o banco de dados
			$md5      = $bd->sanitizaString($this->md5);
			$nome     = $bd->sanitizaString($this->nome);
			$uploader = (int) $this->idUsuario;

			$campos[]  = 'titulo';
			$valores[] = $bd->sanitizaString($this->titulo);
			$campos[]  = 'nome';
			$valores[] = $nome;
			$campos[]  = 'tipo';
			$valores[] = $bd->sanitizaString($this->tipo);
			$campos[]  = 'tamanho';
			$valores[] = (int) $this->tamanho;
			$campos[]  = 'arquivo';
			$valores[] = $bd->sanitizaString($this->conteudo);
			$campos[]  = 'md5';
			$valores[] = $md5;
			$campos[]  = 'dataUpload';
			$valores[] = $bd->sanitizaString($this->data);
			$campos[]  = 'uploader_id';
			$valores[] = $uploader;
			// aproveitar o arquivo que ja foi enviado pelo usuario anteriormente, se for igual.
			$bd->solicitar("SELECT arquivo_id AS id FROM $tabela_arquivos 
							WHERE uploader_id = $uploader
							AND md5 = '$md5'
							AND nome = '$nome'");
			if ($bd->resultado) {
				$this->abrir($bd->resultado['id']);
				return true;
			}
			// executando consulta
			$bd->solicitar(
				"INSERT INTO $tabela_arquivos (" . implode(", ", $campos) . ")
				VALUES ('" . implode("', '", $valores) . "')"
			);
			if ($bd->erro !== "") {
				$this->erros[] = "[arquivo] BD: {$bd->erro}";
			} else {
				$this->id = $bd->ultimo_id();
				$this->upload = false;
				$this->download = true;
				return true;
			}
		}
		// MUDANDO ARQUIVO ANTIGO
		else if ($this->download && !$this->upload) {
			$bd = new conexao();
			// sanitizando dados para o banco de dados
			$campos[]  = 'titulo';
			$valores[] = $bd->sanitizaString($this->titulo);
			$campos[]  = 'nome';
			$valores[] = $bd->sanitizaString($this->nome);
			$campos[]  = 'tipo';
			$valores[] = $bd->sanitizaString($this->tipo);
			$campos[]  = 'tamanho';
			$valores[] = (int) $this->tamanho;
			$campos[]  = 'arquivo';
			$valores[] = $bd->sanitizaString($this->conteudo);
			$campos[]  = 'md5';
			$valores[] = $bd->sanitizaString($this->md5);
			$campos[]  = 'dataUpload';
			$valores[] = $bd->sanitizaString($this->data);
			$campos[]  = 'uploader_id';
			$valores[] = (int) $this->idUsuario;
			$sqlset = array();
			// construindo a sintaxe do sql
			foreach ($campos as $num => $campo) {
				$sqlset[] = "{$campo} = '{$valores[$num]}'";
			}
			// executando consulta
			$bd->solicitar(
				"UPDATE $tabela_arquivos
				SET " . implode(", ", $sqlset) . "
				WHERE arquivo_id = {$this->id}"
			);
			if ($bd->erro !== '') {
				$this->erros[] = $bd->erro;
				return false;
			}
			return true;
		} else {
			$this->erros[] = "[arquivo] Este arquivo não pode ser enviado. Código de erro 2.";
			return false;
		}
	}
	public function setIdUsuario($id) {
		$id = (int) $id;
		if ($id === 0) {
			$this->erros[] = "[arquivo] Id inválido (nulo)";
		} else {
			$this->idUsuario = $id;
		}
	}

	// ex: $arquivo->setArquivo($_FILES['arquivo']);
	public function setArquivo($FILE) {
		$maxFileSize = self::getTamanhoMaximo();
		if (!isset($FILE['tmp_name']) || !$FILE['tmp_name']) {
			$this->erros[] = "[arquivo] Parametro inv&aacute;lido (Arquivo::setArquivo($FILE))";
		}
		if(!filesize($FILE['tmp_name'])) {
			$this->erros[] = "[arquivo] Arquivo vazio ou inv&aacute;lido.";
		}
		if($maxFileSize < $FILE['size']) {
			$this->erros[] = "[arquivo] Arquivo maior que o tamanho aceitável.";
		} else {
			$this->tamanho = (int) $FILE['size'];
			$arquivo = fopen($FILE['tmp_name'], 'r');
			$this->setConteudo(fread($arquivo, $FILE['size']));
			$this->setNome($FILE['name']);
			$this->setTipo( ( ($FILE['type'] != '') ? $FILE['type'] : 'application/octet-stream' ) ); // If theres no mimetype, sets it to a safe default. Fuck raccoons.
			if (!$this->getTitulo()) {
				$this->setTitulo($FILE['name']);
			}
		}
	}

	// ex: $arquivo->setConteudo($blob);
	private function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
		$this->md5 = md5($conteudo);
	}

	public function setTitulo($titulo){$this->titulo = trim($titulo);}
	public function setNome($nome){$this->nome = trim($nome);}
	public function setTipo($tipo){$this->tipo = trim($tipo);}
	// metodo: sendoUsado
	// Verifica se o arquivo esta sendo usado por alguma funcionalidade
	public function sendoUsado() {
		global $tabela_Materiais;
		$qtd = 0;
		$bd = new conexao();
		// verifica ocorrencias do arquivo na biblioteca
		//$referencias_bd[] = array("tabela", "WHERE condicao");
		foreach (self::$referencias_bd as $tabela => $condicao) {
			$bd->solicitar("SELECT count(1) AS num FROM $tabela $condicao" . (int) $this->id);
			$qtd += (int) $bd->resultado['num'];
		}
		return $qtd;
	}

	// metodo: excluir
	// Exclui arquivo se ele nao estiver sendo usado
	// retorna true se o arquivo foi excluido
	// retorna false se o arquivo esta sendo usado
	public function excluir() {
		if ($this->sendoUsado()) {
			return false;
		}
		if (!$this->upload && $this->download) {
			$bd = new conexao();
			$id = (int) $this->id;
			$bd->solicitar(
				"DELETE FROM arquivos WHERE arquivo_id = $id"
			);
			if ($bd->erro !== '') {
				throw new Exception('BD: ' . $this->consulta->erro, 1);
			}
		}
		$this->limpar();
		return true;
	}

	// metodo: excluir
	// remove todas as referencias do arquivo e exclui o arquivo.
	public function excluirTudo() {
		global $tabela_arquivos;
		if (!$this->upload && $this->download) {
			$bd = new conexao();
			foreach (self::$referencias_bd as $tabela => $condicao) {
				$bd->solicitar("DELETE FROM $tabela $condicao" . (int) $this->id);
				if ($bd->erro !== '') {
					throw new Exception('BD: ' . $bd->erro, 1);
				}
			}
			$bd->solicitar(
				"DELETE FROM $tabela_arquivos WHERE arquivo_id = {$this->id}"
			);
			if ($bd->erro !== '') {
				throw new Exception('BD: ' . $bd->erro, 1);
			}
		}
		$this->limpar();
		return true;
	}

	// metodo: abrirUsuario
	// Abre consulta com todos os arquivos do usuario
	// e carrega o primeiro arquivo encontrado

	// A intenção é ser utilizada assim:
	// while($arquivo->existe()){
	//     $arquivo->fazerCoisas();
	//     $arquivo->proximo();
	// }
	public function abrirUsuario($usuario) {
		global $tabela_arquivos;
		if (is_object($usuario)) {
			if (get_class($usuario) === "Usuario") {
				$usuario = $usuario->getId();
			}
		}
		$usuario = (int) $usuario;
		$this->consulta = new conexao();
		$this->consulta->solicitar(
			"SELECT
			arquivo_id AS id,
			titulo AS 'titulo',
			nome AS 'nome',
			tipo AS 'tipo',
			tamanho AS 'tamanho',
			arquivo AS 'conteudo',
			md5 AS 'md5',
			dataUpload AS 'data',
			uploader_id AS 'idUsuario'
			FROM $tabela_arquivos
			WHERE uploader_id = $usuario"
		);
		if ($this->consulta->erro !== '') {
			throw new Exception('BD: ' . $this->consulta->erro, 1);
			return false;
		}
		$this->popular($this->consulta->resultado);
		return $this->consulta->registros;
	}

	// metodo: limpar
	// Limpa os dados do objeto
	protected function limpar() {
		$this->id = false;
		$this->idUsuario = 0;
		$this->titulo = '';
		$this->nome = '';
		$this->tipo = '';
		$this->tamanho = 0;
		$this->conteudo = '';
		$this->erros = array();
	}

	// metodo: proximo
	// pega proximo arquivo do usuario, quando acabou retorna false
	// só pode ser usado depois de abrir um usuario com o 
	// metodo abrirUsuario
	public function proximo() {
		if ($this->consulta === null) {
			throw new Exception("A consulta não está aberta.", 1);
			return false;
		}
		if ($this->consulta->erro !== '') {
			throw new Exception('BD: ' . $this->consulta->erro, 1);
			return false;
		}
		$this->consulta->proximo();
		if ($this->consulta->resultado) {
			$this->popular($this->consulta->resultado);
			return true;
		}
		$this->limpar();
		return false;
	}
	
	function baixar($forceDownload = false) {
		try{
			$teste = new Imagem($this->id);
			$isImage = true;
		}catch(Exception $e){
			$isImage = false;
		}

		header("Content-length: {$this->getTamanho()}");
		header("Content-type: {$this->getTipo()}");
		if (!$isImage || $forceDownload){
            $nomeCodificado = rawurlencode($this->getNome());
			header('Content-Disposition: attachment; filename="'.$nomeCodificado.'"');
		}
		print $this->getConteudo();
		return;
	}

	static function getTamanhoMaximo($value = false){
		if ($value === false) {
			$value = ini_get('upload_max_filesize');
		}

		if(is_numeric($value)){
			return $value;
		} else {
			$value_length = strlen($value);
			$qty = substr($value, 0, $value_length - 1);
			$unit = strtolower(substr($value, $value_length - 1));
			switch($unit){
				case 'k':
					$qty *= 1024;
					break;
				case 'm':
					$qty *= 1048576;
					break;
				case 'g':
					$qty *= 1073741824;
					break;
			}
			return $qty;
		}
	}
	// função que salva vários arquivos
	public static function bulkFiles($_files, $userId) {
		$files = array();
		foreach ($_files as $key => $values) {
			$numValues = count($values);
			for ($i = 0; $i < $numValues; $i++) {
				if (!isset($files[$i])) {
					$files[$i] = array();
				}
				$files[$i][$key] = $values[$i];
			}
		}
		$arquivos = array();
		foreach ($files as $f) {
			$a = new Arquivo();
			$a->setArquivo($f);
			$a->setIdUsuario($userId);
			$a->salvar();
			$arquivos[] = $a;
		}
		return $arquivos;
	}
}

class Imagem extends Arquivo {
	private static $supported_mime_types = array(
		"image/png"
	,	"image/jpeg"	
	,	"image/gif"
	);
	private $imagem;
	private $largura;
	private $altura;
	private $transparent_color; // for gif images

	function __construct($id = false) {
		parent::__construct($id);
		if (array_search($this->tipo, self::$supported_mime_types) === false) {
			throw new Exception("Arquivo não é um tipo de imagem suportado. \"{$this->tipo}\"");
		}
		// carrega imagem
		$this->imagem = imagecreatefromstring($this->conteudo);
		$this->largura = imagesx($this->imagem);
		$this->altura = imagesy($this->imagem);
		// adiciona suporte a transparencia
		imagealphablending($this->imagem, true);
		imagesavealpha($this->imagem, true);
	}

	function __destruct() {
		// free(ram)
		imagedestroy($this->imagem);
	}

	// cria miniatura em png
	function miniatura($max_largura = 0, $max_altura = 0) {
		$nova_largura = $this->largura;
		$nova_altura = $this->altura;
		if ($max_largura && $max_altura)
		if (($this->largura > $max_largura) || ($this->altura > $max_altura)) {
			if (($this->largura / $max_largura) > ($this->altura / $max_altura)) {
				$ratio = $max_largura/$this->largura;
				$nova_largura = $max_largura;
				$nova_altura = round($this->altura * $ratio);
			} else {
				$ratio = $max_altura/$this->altura;
				$nova_largura = round($this->largura * $ratio);
				$nova_altura = $max_altura;
			}
		}
		$nova_imagem = imagecreatetruecolor($nova_largura, $nova_altura);

		// com transparencia
		imagealphablending($nova_imagem, true);
		imagesavealpha($nova_imagem, true);
		$transparent = imagecolorallocatealpha($nova_imagem, 0, 0, 0, 127);
		imagefill($nova_imagem, 0, 0, $transparent);
		// copia imagem com novo tamanho
		imagecopyresized($nova_imagem, $this->imagem, 0, 0, 0, 0, $nova_largura, $nova_altura, $this->largura, $this->altura);
		header('Content-type: image/png');
		//header("Content-length: {$this->getTamanho()}");
		imagepng($nova_imagem);
		imagedestroy($nova_imagem); // free(ram);
	}
}
/* /
$arquivo = new Arquivo(222);
$erros = $arquivo->getErros();
if (count($erros) === 0) {
	header("Content-length: {$arquivo->getTamanho()}");
	header("Content-type: {$arquivo->getTipo()}");
	header("Content-Disposition: attachment; filename={$arquivo->getNome()}");
	exit($arquivo->getConteudo());
} else {
	echo '<html><head><meta charset="utf-8"></head><body><ul><li>';
	echo implode("</li><li>", $erros);
	echo '</li></body></html>';
}/* */