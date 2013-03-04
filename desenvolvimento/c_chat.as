import mx.events.EventDispatcher;
import mx.utils.Delegate;

/*
* Classe que representa um chat, seja do tipo que for.
*/
class c_chat extends MovieClip{
//dados	
	/*
	* A classe dispara eventos toda vez que o chat é atualizado com alguma fala nova.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	/*
	* Link do símbolo deste objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "chat";

	/*
	* Array com (ids do banco de dados de) pessoas que estão neste chat.
	* Só é usado quando for pedido.
	*/
	private var ids_pessoas:Array = new Array();
	private var ids_pessoas_em_uso:Boolean = false;
	
	/*
	* Nome deste chat.
	*/
	private var nome:String;
	
	/*
	* Cor deste chat.
	*/
	public static var COR_PRETA:String = "000000";
	private var cor:String = COR_PRETA;
	public static var COR_TURQUESA:String = "00A39F";
	public static var COR_VERDE:String = "25A048";
	public static var COR_ROXO_FORTE:String = "9900CC";
	public static var COR_ROXO_FRACO:String = "C200CC";
	public static var COR_VERMELHA_AVISO:String = "FF0000";
	public static var COR_VERDE_AVISO:String = "009900";
	
	/*
	* Data no servidor da criação desta instância, preenchida na primeira atualização.
	*/
	private var dataCriacao:String = undefined;
	
	/*
	* Data do servidor da última sincronização.
	*/
	private var dataUltimaAtualizacao:String = undefined;
	
	/*
	* Identificação deste chat no banco de dados, caso haja.
	* Se o chat ainda não estiver no banco de dados, será salvo na primeira atualização.
	*/
	private var identificacao:String = c_banco_de_dados.NAO_SALVO;
	
	/*
	* Variáveis de comunicação com o servidor.
	*/
	private var envia:LoadVars;
	private var recebe:LoadVars;
	
	/*
	* Intervalo de tempo entre o início de duas atualizações, em milisegundos.
	*/
	private static var TEMPO_ATUALIZACAO:Number = 2000;
	
	/*
	* Caixa de texto deste chat.
	*/
	private var caixa_texto:TextField;
	
	/*
	* Id da última fala exibida.
	*/
	private var id_ultima_fala:String = "undefined";
	
	/*
	* Indica se deve filtrar as mensagens para que receba somente as enviadas pelo usuário online.
	*/
	private var deve_filtrar_mensagens:Boolean = false;
	
	/*
	* Variável de id do intervalo de atualização do chat.
	*/
	private var idAtualizacao:Number = undefined;
	
	/*
	* Define se este chat está ativo.
	* Quando inativo, um chat não pode ser visto, nem enviar ou receber mensagens.
	*/
	private var estahAtivo:Boolean;
	
//métodos
	/*
	* @param nome_param Um nome para o chat.
	* @param id_param Identificação deste chat no banco de dados, caso haja.
	* @param cor_param A cor do chat.
	*/
	public function inicializar(nome_param:String, id_param:String, cor_param:String){
		if(idAtualizacao == undefined){
			mx.events.EventDispatcher.initialize(this);
		}
		
		estahAtivo = true;
		
		nome = nome_param;
		identificacao = id_param;
		cor = cor_param;
		
		caixa_texto = this['caixa_texto'];
		caixa_texto.html = true;
		caixa_texto.htmlText = new String();
		envia = new LoadVars();
		recebe = new LoadVars();
		
		if(idAtualizacao == undefined){
			setInterval(this, "atualizar", TEMPO_ATUALIZACAO);
		}
	}
	
	/*
	* @param estahAtivo_param Quando inativo, um chat não pode ser visto, nem enviar ou receber mensagens.
	*/
	public function setAtivo(estahAtivo_param:Boolean):Void{
		estahAtivo = estahAtivo_param;
	}
	
	/*
	* @return Booleano indicando se o chat está ativo. Quando inativo, um chat não pode ser visto, nem enviar ou receber mensagens.
	*/
	public function ativo():Boolean{
		return estahAtivo;
	}
	
	/*
	* @param nome_param Nome deste chat.
	*/
	public function definirNome(nome_param:String):Void{
		nome = nome_param;
	}
	/*
	* @return Nome deste chat.
	*/
	public function getNome():String{
		return nome;
	}
	
	/*
	* @return Log formatado com falar separadas por quebras de linhas.
	*/
	public function getLog():String{
		return caixa_texto.text;
	}
	
	/*
	* @param identificacao_param Identificação desta instância no banco de dados.
	*/
	public function definirIdentificacaoBancoDeDados(identificacao_param:String):Void{
		identificacao = identificacao_param;
	}
	/*
	* @return Identificação desta instância no banco de dados.
	*/
	public function getIdentificacaoBancoDeDados():String{
		return identificacao;
	}
	
	/*
	* Indica se as mensagens que recebe devem ser filtradas.
	* Caso sejam, só receberá mensagens enviadas pelo usuário online.
	*/
	public function definirFiltragemMensagens(deve_filtrar_param:Boolean):Void{
		deve_filtrar_mensagens = deve_filtrar_param;
	}
	
	/*
	* Adiciona uma fala a este chat, adicionando-a também ao banco de dados.
	* @param fala_param A fala a ser enviada.
	* @param id_autor_param Identificação no banco de dados do autor da fala.
	* @param autor_param Nome do autor da fala.
	*/
	public function adicionarFala(fala_param:String):Void{
		caixa_texto.htmlText += fala_param;
		caixa_texto.scroll = caixa_texto.maxscroll;
	}

	/*
	* Atualiza o chat, pedindo ao banco de dados novas falas.
	*/
	private function atualizar():Void{
		envia = new LoadVars();
		recebe = new LoadVars();
		
		envia.deve_filtrar_mensagens = deve_filtrar_mensagens;
		envia.identificacao = getIdentificacaoBancoDeDados();
		envia.idUltimaFalaRecebida = id_ultima_fala;
		
		if(estahAtivo){
			recebe.onLoad = Delegate.create(this, dadosRecebidos);
			envia.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_ATUALIZAR_CHAT, recebe, "POST");
		}
	}
	
	/*
	* Recebe os dados enviados pelo servidor após uma chamada à atualizar.
	*/
	private function dadosRecebidos(success):Void{
		var fala:String;
		var falas_recebidas:Array = new Array();
		var mensagem_erro:String;
		
		if(estahAtivo){
			if(success and recebe.erro == c_banco_de_dados.SEM_ERRO){
				if(dataCriacao == undefined){
					dataCriacao = recebe.dataAtualServidor;
				}
				dataUltimaAtualizacao = recebe.dataAtualServidor;
				if(recebe.numero_falas != "" and 0 < recebe.numero_falas){
					id_ultima_fala = recebe.idUltimaFala;
				
					for(var indice:Number = 0; indice < recebe.numero_falas; indice++){
						fala = c_chat.formatarFalaParaHTML(nome, recebe["fala"+indice], recebe["autor_fala"+indice], cor);
						falas_recebidas.push(fala);
						adicionarFala(fala);
					}
					dispatchEvent({target:this, type:"dadosRecebidos", falas : falas_recebidas});
				}
			} else {
				if(recebe.erro != undefined){
					mensagem_erro = c_banco_de_dados.getMensagemErro(recebe.erro);
					fala = c_chat.formatarFalaParaHTML("Aviso", mensagem_erro, "", c_chat.COR_VERMELHA_AVISO);
					falas_recebidas.push(fala);
					dispatchEvent({target:this, type:"dadosRecebidos", falas : falas_recebidas});
				}
			}
		}
	}
	
	/*
	* Faz a formatação de uma fala, colocando o nome do autor e deste chat, com a cor certa e a retorna em formato HTML.
	* @param fala_param A fala em si, sem nome de autor, chat ou cor.
	* @param autor_param Nome do autor da fala.
	* @return Fala formatada em HTML.
	*/
	public static function formatarFalaParaHTML(nome_chat_param:String, fala_param:String, autor_param:String, cor_param:String):String{
		var fala:String = new String();
		if(autor_param != ""){
			fala = "<font color=\"#"+cor_param+"\">"+
		    		"<b>["+nome_chat_param+"]</b> "+autor_param+": "+fala_param+
				"</font><br />";
		} else {
			fala = "<font color=\"#"+cor_param+"\">"+
		    		"<b>["+nome_chat_param+"]</b> "+fala_param+
				"</font><br />";
		}
		
		return fala;
	}
	
	/*
	* Ajusta falas visíveis deste chat segundo scroll parâmetro.
	* @param scroll_param A posição para onde irá o scroll da caixa de texto.
	*/
	public function scrollPara(scroll_param:Number){
		caixa_texto.scroll = scroll_param;
	}
	
	/*
	* @return O número total de linhas deste chat.
	*/
	public function getNumeroLinhas(){
		return caixa_texto.maxscroll + caixa_texto.bottomScroll - caixa_texto.scroll;
	}
	
	/*
	* @return Linhas que o usuário pode ver (que não foram cortadas por falta de espaço nesta caixa de texto).
	*/
	public function getNumeroLinhasVisiveis():Number{
		return caixa_texto.bottomScroll - caixa_texto.scroll + 1;
	}


}