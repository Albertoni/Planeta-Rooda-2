<?php
/*
*	Sistema do blog
*
*/

if (class_exists('Post') != true){ // conserta bugs raros mas incomodativos
class Post { //estrutura para o item post do blog
	var $id = 0;
	var $blogId = 0;
	var $userId = 0;
	var $title = '';
	var $text = 0;
	var $isPublic = false;
	var $date = '';
	var $author = "?";
	var $e = "";
	var $tags = array();

	function open($id_post) {
		global $tabela_posts;
		$id_post = (int) $id_post;
		$q = new conexao();
		$q->solicitar("select * from $tabela_posts where Id = $id_post");
		if($q->erro == "") {
			$a = $q->resultado; // PORQUE MEU DEUS
			$this->id = $a['Id'];
			$this->blogId = $a['BlogId'];
			$this->userId = $a['UserId'];
			$this->title = $a['Title'];
			$this->text = $a['Text'];
			$this->isPublic = $a['IsPublic'];
			$this->date = $a['Date'];

			$tempUser = new Usuario();
			$tempUser->openUsuario($a['UserId']);
			$this->author = $tempUser;
		} else {
			$this->e = "Id informado não existe na tabela de posts";
		}
	}

	function save() {
		global $tabela_posts;
		$q = new conexao();
		if($this->id == 0) {
			$q->inserir($this->toDBArray(),$tabela_posts);
			$this->id = (int) $q->ultimo_id();
		} else {
			$q->atualizar($this->id,$this->toDBArray(),$tabela_posts);
		}
	}

	function toDBArray() {
		unset($dados);
		$dados['Id'] = $this->id;
		$dados['BlogId'] = $this->blogId;
		$dados['UserId'] = $this->userId;
		$dados['Title'] = $this->title;
		$dados['Text'] = $this->text;
		$dados['IsPublic'] = $this->isPublic;
		$dados['Date'] = $this->date;
		return($dados);
	}
	
	function Post($id=0, $blog_id="", $user_id="", $title="", $text="", $is_public=1, $date=""){
		$this->id = $id;
		$this->blogId = $blog_id;
		$this->userId = $user_id;
		$this->title = $title;
		$this->text = $text;
		$this->isPublic = $is_public;
		$this->date = $date;
		$this->author = new Usuario();
		if($this->userId!="")
			$this->author->openUsuario($this->userId);
	}

	function getTitle() {
		return $this->title;
	}

	function setTitle($title) {
		$this->title = $title;
	}

	function getId() {
		return $this->id;
	}

	function getBlogId() {
		return $this->blogId;
	}

	function setId($id) {
		$this->id = $id;
	}

	function getText() {
		return $this->text;
	}

	function getDate($format="d/m/Y H:i:s") {
		if($format=="")
			$r = $this->date;
		else
			$r = date($format,strtotime($this->date));
		return $r;
	}

	function getAuthor() {
		return $this->author;
	}
	
	function getIsPublic() {
		return $this->isPublic;
	}
	
	
	// Funções abaixo disso made by João
	function getPostTags($post=0, $edicao=0) { // Isso pega as tags do BD e deixa elas em um array.
		global $tabela_tags;
		$consulta = new conexao();
		if ($post == 0) {
			$consulta->solicitar("SELECT Tags FROM $tabela_tags WHERE Id = ".$this->getId());
			if (isset($consulta->resultado['Tags'])){
				$this->tags = explode(';', $consulta->resultado['Tags']); // Passa pra um array.
			} else {
				if ($edicao == 0)
					$this->tags[] = "Postagem sem tags";
				else
					$this->tags[] = "";
			}
		} else {
			$consulta->solicitar("SELECT Tags FROM $tabela_tags WHERE Id = $post");
			if (isset($consulta->resultado['Tags'])){
				$this->tags = explode(';', $consulta->resultado['Tags']); // Passa pra um array.
			} else {
				if ($edicao == 0)
					$this->tags[] = "Postagem sem tags";
				else
					$this->tags[] = "";
			}
		}
	}
	
	function printPostTags() {
		if (empty($this->tags)) {
			$this->getPostTags();
		}
		$tags = "";
		for ($i=0,$size=count($this->tags); $i<$size; $i++){
			if (isset($this->tags[$i+1])) { // Se não for o ultimo...
				$tags .= ucfirst($this->tags[$i]).", ";
			} else {
				$tags .= ucfirst($this->tags[$i]);
			}
		}
		return $tags;
	}

	function imprimePost($cor, $usuario, $permissoes, $turma){

	echo "				<div class=\"cor$cor\">
				<ul class=\"sem_estilo\">
					<li class=\"tabela_blog\">
						<span class=\"titulo\">
							<a href=\"blog_singlepost.php?post_id=".$this->getId()."&amp;blog_id=".$this->getBlogId()."&amp;turma=$turma\">".$this->getTitle()."</a>
						</span>
						<span class=\"data\">
							".$this->getDate()."
						</span>
					</li>
					<li class=\"tabela_blog\">
						<div style=\"overflow-x:auto; width:384px\">
							".$this->getText()."
						</div>
					</li>
					<li class=\"tabela_blog\">
						<ul>";

	$arquivo = new ArquivosPost();
	$arquivo->abrirPost($this->getId());

	while ($arquivo->existe()) {
								echo '								<li><a href="abreArquivo.php?a='.$arquivo->getId().'&amp;p='.$this->getId().'" target="_blank">'.$arquivo->getNome()."</a></li>\n";
								$arquivo->proximo();
							}

	echo "							</ul>
						</li>
					<li class=\"tabela_blog\">
						Por ".$this->getAuthor()->getName()."<br />";

	if ($usuario->podeAcessar($permissoes["blog_editarPost"], $turma)){
		echo"\n						<a href=\"blog_postagem.php?blog_id=".$this->getBlogId()."&post_id=".$this->getId()."&turma=".$turma."\">Editar</a>";
	}
	if ($usuario->podeAcessar($permissoes["blog_excluirPost"], $turma)){
		echo"\n						<div style=\"float:right\"><a href=\"javascript:deletar_post(".$this->getBlogId().",".$this->getId().",".$turma.")\">Deletar</a></div><br />";
	}
	
	// placeholder para link dos comentários (vai ser substituido via javascript)
	echo"\n						<input type=\"hidden\" name=\"comentarios\" value=\"".$this->getId()."\"><br />
						Tags: ".$this->printPostTags()."
					</li>
				</ul>
				</div>";
}
}
}

if (class_exists('Blog') != true){ // conserta bugs raros mas incomodativos
class Blog {
	var $id = 0;
	var $title = "";
	var $ownersIds = array();
	var $tipo = 0;
	var $posts = array();
	var $size = 0;
	var $owners = array();
	var $paginacao = 0;
	var $num_paginas = 0;
	var $existe = 0;
	var $tags = array();
	var $turma = 0;
	
	function Blog($id, $turma = 0){
		global $tabela_blogs;
		$userId = $_SESSION['SS_usuario_id'];
		$q = new conexao();

		if($id === "meu_blog"){
			$q->solicitar("SELECT * FROM $tabela_blogs WHERE OwnersIds = '$userId' AND Turma = '$turma'");
		}else{
			$q->solicitar("SELECT * FROM $tabela_blogs WHERE Id = '$id'");
		}

		if(!$q->itens){// Não tem blog, precisa criar
			if($id === "meu_blog"){
				$this->setTitle("Meu Webfólio");
				$this->setOwnersIds(array($userId));
				$this->setOwners();
				$this->setTipo(1);
				$this->setTurma($turma);

				$this->salvarBlog();
			}else{
				die("Ops, aconteceu um erro (o dono do webf&oacute;lio ainda n&atildeo entrou nele), tente novamente mais tarde.");
			}
		}else{
			$this->setExiste(1);
			$this->setId($q->resultado['Id']);
			$this->setTitle($q->resultado['Title']);
			$this->setOwnersIds(explode(';',$q->resultado['OwnersIds']));
			$this->setOwners();
			$this->setTipo($q->resultado['Tipo']);
			$this->setPosts();
			$this->setSize(sizeof($this->posts));
			$this->setPaginacao(6);
			$this->setNumPaginas();
			$this->setBlogTags($q->resultado['Id']);
			$this->turma = $q->resultado['Turma'];
		}
	}

	function getMeuBlog($turma) {
		global $tabela_blogs;
		global $usuario_id;
		$consulta = new conexao();
		$consulta->solicitar("SELECT * FROM $tabela_blogs WHERE OwnersIds = '$usuario_id' AND Tipo = 1");
		if(!$consulta->itens) {
			$blog = new Blog(0);
			$aux_id = $blog->getId();
		}else{
			$aux_id = $consulta->itens[0]['Id'];
		}
		return $aux_id;
	}

	function toDBArray() {
		unset($dados);
		$dados['Id'] = $this->id;
		$dados['OwnersIds'] = implode(';',$this->getOwnersIds());
		$dados['Title'] = $this->getTitle();
		$dados['Tipo'] = $this->getTipo();
		return($dados);
	}

	function setExiste($existe)			{$this->existe = $existe;}
	function setId($id)					{$this->id = $id;}
	function setTitle($title)			{$this->title = $title;}
	function setTipo($tipo)				{$this->tipo = $tipo;}
	function setSize($size)				{$this->size = $size;}
	function setTurma($turma)			{$this->turma = $turma;}
	function setNumPaginas()			{$this->num_paginas = ceil($this->getSize()/$this->paginacao);}
	function setOwnersIds($owners_ids)	{$this->ownersIds = $owners_ids;}
	function setPaginacao($paginacao)	{$this->paginacao = $paginacao;}

	function setOwners(){
		foreach($this->ownersIds as $owner_id) {
			$id = (int)$owner_id;
			$aux_owner = new Usuario();
			$aux_owner->openUsuario($id);
			$this->owners[] = $aux_owner;
		}
	}
	
	function setPosts() {
		global $tabela_posts;
		$consulta = new conexao();
		$consulta->solicitar("select Id from $tabela_posts where BlogId = $this->id ORDER BY Date DESC");

		for($i=0 ; $i<count($consulta->itens);$i++){
			$tempPost = new Post();
			$tempPost->open($consulta->resultado['Id']);
			$this->posts[] = $tempPost;
			$consulta->proximo();
		}
	}

	function getTipo()		{return $this->tipo;}
	function getOwnersIds()	{return $this->ownersIds;}
	function getExiste()	{return $this->existe;}
	function getId()		{return $this->id;}
	function getTitle()		{return $this->title;}
	function getTurma()		{return $this->turma;}
	function getSize()		{return $this->size;}
	function getOwners()	{return $this->owners;}
	function getPaginacao()	{return $this->paginacao;}
	function getNumPaginas(){return $this->num_paginas;}

	function mostraPaginacao($post_ini) {
		if($post_ini>0) {
			echo "<a href=\"blog.php?blog_id=$this->id&ini=0\"><< Primeira</a>";
			echo "<a href=\"blog.php?blog_id=$this->id&ini=". ($post_ini-$this->getPaginacao()) ."\">< Anterior</a>";
		} else {
			echo "<span><< Primeira</span>";
			echo "<span>< Anterior</span>";
		}

		for($i=1;$i<=$this->getNumPaginas();$i++) { 
			if($post_ini==($i-1)*$this->getPaginacao()) {
				echo "<span class=\"numero_atual\">$i</span>";
			} else {
				echo "<a href=\"blog.php?blog_id=$this->id&ini=".($i-1)*$this->getPaginacao()."\" class=\"numero\">$i</a>";
			}
		}
		
		if($post_ini<($this->getNumPaginas()-1)*$this->getPaginacao()) {
			echo "<a href=\"blog.php?blog_id=$this->id&ini=" .($post_ini+$this->getPaginacao()) . "\">Pr&oacute;xima ></a>";
			echo "<a href=\"blog.php?blog_id=$this->id&ini=".($this->getNumPaginas()-1)*$this->getPaginacao()."\">&Uacute;ltima >></a>";
		} else {
			echo "<span>Pr&oacute;xima ></span>";
			echo "<span>&Uacute;ltima >></span>";
		}
	}
	
	function setBlogTags($blog) { // Isso pega todas as tags usadas no blog. Made by João
		global $tabela_tags;
		$consulta = new conexao();
		$consulta->solicitar("SELECT Tags FROM $tabela_tags where BlogId = $blog");
		if (isset($consulta->resultado['Tags'])){
			$tamanho_lista = count($consulta->itens);
			for ($i=0; $i < $tamanho_lista; $i++){
				$this->tags = array_merge($this->tags, explode(';', $consulta->resultado['Tags'])); // Passa pra um array.
				$consulta->proximo();
			}
			$this->tags = array_unique($this->tags); // Remove todas as repetidas.
			sort($this->tags); // Tem que fazer sort depois de separar as tags, devido ao jeito que são storadas.
		} else {
			$this->tags[] = "Blog sem tags";
		}
	}
	
	function isOwner($id){
		foreach ($this->ownersIds as $dono){
			if ($id == $dono){
				return true;
			}
		}
		return false; // caso nenhuma tenha dado true...
	}

	function salvarBlog(){
		$q = new conexao();
		if($this->existe === 0){ // não foi aberto nem salvo anteriormente
			$q->solicitar("INSERT INTO blogblogs (Id, Title, Tipo, OwnersIds, Turma)
				VALUES (DEFAULT, '$this->title', $this->tipo, '".implode(';',$this->getOwnersIds())."', $this->turma)");
			$this->setId((int) $q->ultimo_id());
			$this->setExiste(1);
		}else{
			$q->solicitar("UPDATE blogblogs SET Title='$this->title', Tipo='$this->tipo', OwnersIds='".implode(';',$this->getOwnersIds())."' WHERE Id = $this->id");
		}

		// echo "DEBUG".$q->erro;
	}

	public function deletar($objetoUsuario){
		if ( ($this->isOwner($objetoUsuario->getId())) || ($objetoUsuario->getNivel($turma) == NIVELPROFESSOR) ){
			$q = new conexao();
			$id = $this->id;
			$q->solicitar("DELETE FROM blogTags WHERE BlogId = $id");
			$q->solicitar("DELETE FROM blogimagens WHERE id = $id");
			$q->solicitar("DELETE FROM BlogArquivos WHERE exists(
							SELECT 1 FROM blogposts
							WHERE blogposts.Id = BlogArquivos.idPost
								AND blogposts.BlogId = $id
							)");
			$q->solicitar("DELETE FROM blogposts WHERE BlogId = $id");
			$q->solicitar("DELETE FROM blogblogs WHERE Id = $id");
		}
	}
}
}

if (class_exists('lista_posts') != true){ // conserta bugs raros mas incomodativos
class lista_posts{
	var $lista = array();
	var $tamanho_lista = 0;
	
	function lista_posts($id, $tabela_posts){
		$consulta = new conexao();
		$this->lista = array();
		$lista_interna = array();
		
		$consulta->solicitar("SELECT Id, Title, Date FROM $tabela_posts WHERE (BlogId=$id AND IsPublic = 1) ORDER BY Date DESC"); // Pega só os posts publicos.
		
		$this->tamanho_lista = count($consulta->itens); // Seta o valor pro for ali embaixo
		
		$ano_atual = 9999999; // I do believe this code will last more than humanity in a working status.
		$mes_atual = 13;
		
		for ($i=0; $i < $this->tamanho_lista; $i++){
			$elemento['ano'] = substr($consulta->resultado['Date'], 0, 4); // Pega os 4 chars do ano.
			$elemento['mes'] = substr($consulta->resultado['Date'], 5, 2); // See above
			$elemento['titulo'] = $consulta->resultado['Title'];
			$elemento['post_id'] = $consulta->resultado['Id'];
			
			if ($elemento['ano'] != $ano_atual) {
				if ($ano_atual != 9999999){
					$lista_interna[] = "\nend_month"; // pusha as coisas
					$lista_interna[] = "\nend_year"; // se não é o ano inicial, acaba o anterior
				}
				$ano_atual = $elemento['ano'];
				$lista_interna[] = "\nnew_year";
				$lista_interna[] = $elemento['ano'];
				$mes_atual = 13;
			}
			
			
			
			if ($elemento['mes'] < $mes_atual) {
				if ($mes_atual != 13){
					if ($lista_interna[$i-1] == "\nend_year"){ // NÃO PEÇA. FUNCIONA. DÁ UM PRINT_R PRA VER.
						$lista_interna[] = "\nend_year";
						$lista_interna[$i-1] = "\nend_month";
					} else {
						$lista_interna[] = "\nend_month"; // se não é o mes inicial, acaba o anterior
					}
				}
				$lista_interna[] = "\nnew_month";
				$lista_interna[] = $elemento['mes'];
				$mes_atual = $elemento['mes'];
			}
			
			$lista_interna[] = $elemento['titulo'] ."\n". $elemento['post_id']; // O título SEMPRE vai estar lá.
				// Esse \n aí serve pra separar, já que teoricamente o usuario não conseguiria enviar um \n via input.
				// Sem falar que tem um str_replace que tira \n do título dos posts. Pra GARANTIR GARANTIDO. ~ João, 01 set. 2011
			
			$consulta->proximo();
		}
		$this->lista = $lista_interna;
		$this->tamanho_lista = count($lista_interna); // Seta o valor pra ser utilizado fora do construtor.
	}
}
}

function getMonth($number){ // usada no blog.php
	switch ($number){
		case 1:
			return "Janeiro";
			break;
		case 2:
			return "Fevereiro";
			break; // Esses breaks tão aí pra garantir a pegada.
		case 3:
			return "Março";
			break;
		case 4:
			return "Abril";
			break;
		case 5:
			return "Maio";
			break;
		case 6: 
			return "Junho";
			break;
		case 7:
			return "Julho";
			break;
		case 8:
			return "Agosto";
			break;
		case 9:
			return "Setembro";
			break;
		case 10:
			return "Outubro";
			break;
		case 11:
			return "Novembro";
			break;
		case 12:
			return "Dezembro";
			break;
	}
	return "Alguma coisa deu muito errado no blog.class.php. Avise um desenvolvedor disso.";
}

function numeroMensagens($num){ // Usada em listas de blogs
	if ($num == 1)
		return "Uma mensagem";
	else
		return $num." mensagens";
}

function getTextSample($blog_id){ // Vide comentário acima.
	global $tabela_posts;
	$b = new conexao();
	$b->solicitar("SELECT Text FROM $tabela_posts WHERE BlogId = $blog_id ORDER BY Date DESC");
	
	$texto = $b->resultado['Text'];
	
	if (strlen($texto) > 500) {
		/*$texto = substr($texto, 0, 500); // Pega no max 500 caracteres.
		$pos = strrpos($texto, " "); // Acha a posição do ultimo espaço.
		$texto = substr($texto, 0, $pos); // Remove o que tem depois do espaço.
		$texto .= "..."; // Pra deixar bonitinho*/
		
		$texto=substr(substr($texto,0,500),0,strrpos(substr($texto,0,500)," "))."..."; // Mesma coisa da de cima.
	}
	return $texto;
}

function getPrintableOwners($blog_id){
	global $tabela_blogs;
	global $tabela_usuarios;
	$b = new conexao(); // Para os ids dos donos
	$nome = new conexao(); // Para cada nome.
	$nomes = array(); // Para todos os nomes juntos
	$zona = ""; // Onde rola a putaria.
	
	$b->solicitar("SELECT OwnersIds FROM $tabela_blogs WHERE Id = $blog_id");
	$donos = explode(';', $b->resultado['OwnersIds']); // Passa pra um array
	
	foreach($donos as $dono){
		if ($dono != false) { // if ($dono == IDValido), acredite, é necessário.
			$nome->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $dono");
			$nomes[] = $nome->resultado['usuario_nome'];
		}
	}
	
	// Todos os nomes foram pegos. Agora se gera o texto bunitim
	
	for ($nossa_a_cami_ta_gostosa_hoje=0,$size=count($nomes); $nossa_a_cami_ta_gostosa_hoje<$size; $nossa_a_cami_ta_gostosa_hoje++){
		if (isset($nomes[$nossa_a_cami_ta_gostosa_hoje+1])) { // Se não for o ultimo...
			$zona .= $nomes[$nossa_a_cami_ta_gostosa_hoje].", "; // Tem virgula.
		} else {
			$zona .= $nomes[$nossa_a_cami_ta_gostosa_hoje];
		}
	}
	
	return $zona;
}

function imprimeDono($owner, $usuario_id){
if($owner->getId() == $usuario_id){
	$nome = '<i>'.$owner->getName().'</i>';
}else{
	$nome = $owner->getName();
}
$idDono = $owner->getId();

echo "					<li class=\"tabela_blog\">
						<center>
						$nome
						</center>
					</li>
					<li>
						<center><img src=\"image_output.php?file=$idDono&userpic=1\" alt=\"Avatar de $nome\" /></center>
						<br />
					</li>";
}

function imprimeTags($tag, $blog_id){
if ($tag != "Blog sem tags") // Gaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaambiarra
		echo "						<li class=\"tabela_blog\">
								<a href=\"blog_tags.php?tag=".urlencode($tag)."&blog_id=$blog_id\">".ucfirst($tag)/*Primeira letra em maiúscula.*/."</a>
						</li>";
	else
		echo "						<li class=\"tabela_blog\">
								Blog sem tags
						</li>";
}

function imprimeLink($link){
echo "						<li class=\"tabela_blog\">
							<a href=\"$link\" target=\"_blank\">$link</a>
							<div class=\"bts_caixa\"><img class=\"apagar\" src=\"../../images/botoes/bt_x.png\" /></div>
						</li>";
}

function imprimeListaPosts($blog_id, $turma){
	global $tabela_posts;
	$posts = new lista_posts($blog_id, $tabela_posts);
	
	for ($i=0; $i < $posts->tamanho_lista; $i++){
		if($posts->lista[$i][0] == "\n"){ // Caso seja um marcador de fim de alguma coisa...
			switch (substr($posts->lista[$i], 1)){
			case "end_month":
				echo "
				</ul>
			</li>";
				break;


			case "end_year":
				echo "
		</ul>
	</li>";
				break;


			case "new_year":
				$i += 1;// GAMBIARRAS 8D
						// Ele incrementa em um tanto aqui quando na abaixo porque o new_algo não contem os dados do mes/ano. Precisa incrementar pra pegar ele.
				echo "
	<li class=\"post_ano\">
		<a href=\"javascript:abre_topico(".$i.");\" class=\"no_underline\">".$posts->lista[$i]."</a>
	</li>
	<li class=\"tabela_oculta\" id=\"topico_oculto".$i."\"> <!--safadeza_oculta-->
		<ul>";
				break;


			case "new_month":
				$i += 1;
				echo "
			<li class=\"post_mes\">
				<a href=\"javascript:abre_topico(".$i.");\" class=\"no_underline\">".getMonth($posts->lista[$i])."</a>
			</li>
			<li class=\"tabela_oculta\" id=\"topico_oculto".$i."\">
				<ul>";
				break;
			}
		} else {
			$array = explode("\n", $posts->lista[$i]);
			$nome	= $array[0];
			$id		= $array[1];
			echo "
					<li class=\"post_topico\">
						<a href=\"blog_singlepost.php?blog_id=".$blog_id."&post_id=".$id."&turma=".$turma."\" class=\"no_underline\">".$nome."</a>
					</li>";
		}
		
		
	}
}

