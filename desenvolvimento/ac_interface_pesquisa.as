import mx.utils.Delegate;
import flash.geom.Point;
/*

		Uma interface que possui um botão de pesquisa, com uma barra para digitação do dado a ser pesquisado.
		Ao clicar no botão de pesquisa, faz aparecer uma tela com os resultados da pesquisa e dois botões, 
		um para ir para o próximo resultado e outro para ir para o resultado anterior.

*/
class ac_interface_pesquisa extends ac_interface_menu{
//Dados	
	//---- Servidor
	private var endereco_arquivo_pesquisa_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.
	private var dado_pesquisado:String = new String();
	private var pos_tupla_resultado_pesquisa:Number = POS_ANTERIOR_PESQUISA;//Contém a posição na tupla no (array do) resultado da última pesquisa feita.
	private var numero_de_resultados_ultima_pesquisa:Number = 0;
	private var POS_ANTERIOR_PESQUISA:Number = 0;
	
	//---- Botões
	private var btPesquisar:c_btPesquisar;
	private var POSX_BT_PESQUISAR:Number = 410;
	private var POSY_BT_PESQUISAR:Number = 21.45;
	
	private var btProximo:c_btProximo;
	private var POSX_BT_PROXIMO:Number = 522.95;
	private var POSY_BT_PROXIMO:Number = 175;
	
	private var btAnterior:c_btAnterior;
	private var POSX_BT_ANTERIOR:Number = POSX_BT_PROXIMO - 30;
	private var POSY_BT_ANTERIOR:Number = POSY_BT_PROXIMO;
	
	//---- Erro
	private var MENSAGEM_ERRO_FALTA_RESULTADOS:String = "A pesquisa não retornou resultados.";
	private var MENSAGEM_ERRO_COMUNICACAO_BD:String = "Houve um erro na comunicação com o banco de dados.";
	
	//---- Interface
	private var POSX_BARRA_PESQUISA:Number = 0;
	private var POSY_BARRA_PESQUISA:Number = 21.45;
	
	private var POSX_TEXTO_RESULTADO:Number = 0;
	private var POSY_TEXTO_RESULTADO:Number = 57.45;
	
//Métodos
	public function inicializacoes():Void{
		super.inicializacoes();

		attachMovie("btPesquisar", "btPesquisar", getNextHighestDepth());
		btPesquisar.inicializar();
		btPesquisar._x = POSX_BT_PESQUISAR;
		btPesquisar._y = POSY_BT_PESQUISAR;
		btPesquisar.addEventListener("btPesquisarPressionado", Delegate.create(this, iniciarPesquisa));	
		
		attachMovie("btProximo", "btProximo", getNextHighestDepth());
		btProximo.inicializar();
		btProximo._x = POSX_BT_PROXIMO;
		btProximo._y = POSY_BT_PROXIMO;
		btProximo.addEventListener("btProximoPress", Delegate.create(this, proximo));	
		
		attachMovie("btAnterior", "btAnterior", getNextHighestDepth());
		btAnterior.inicializar();
		btAnterior._x = POSX_BT_ANTERIOR;
		btAnterior._y = POSY_BT_ANTERIOR;
		btAnterior.addEventListener("btAnteriorPress", Delegate.create(this, anterior));	
		
		this['barraPesquisa']._x = POSX_BARRA_PESQUISA;
		this['barraPesquisa']._y = POSY_BARRA_PESQUISA;
		this['fundoBarraPesquisa']._x = POSX_BARRA_PESQUISA;
		this['fundoBarraPesquisa']._y = POSY_BARRA_PESQUISA;
		this['textoResultado']._x = POSX_TEXTO_RESULTADO;
		this['textoResultado']._y = POSY_TEXTO_RESULTADO;
		
		esconderResultado();
	}
	
	//---- Interface
	public function mostrar():Void{
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
	}
	public function mostrarResultado():Void{
		controlarVisibilidadeSetasProximoEAnterior();
		this['textoResultado']._visible = true;
	}
	private function esconderResultado():Void{
		btProximo._visible = false;
		btAnterior._visible = false;
		this['textoResultado']._visible = false;
	}
	
	private function comunicarFaltaDeResultados():Void{
		mostrarResultado();
		this['textoResultado'].text = MENSAGEM_ERRO_FALTA_RESULTADOS;
	} 
	private function comunicarErroNaComunicaçãoComServidor():Void{
		esconderResultado();
		if(_visible){
			if(recebe.mensagemDeErro == '' or recebe.mensagemDeErro == undefined){
				c_aviso_com_ok.mostrar(MENSAGEM_ERRO_COMUNICACAO_BD);
			} else {
				c_aviso_com_ok.mostrar(recebe.mensagemDeErro);
			}
		}
	} 
	private function comunicarResultadosDaPesquisa():Void{} //Deve ser implementada em cada classe que use este template.
	
	//---- Pesquisa
	private function exibindoPesquisa():Boolean{
		if(pos_tupla_resultado_pesquisa >= 1){
			return true;
		} else {
			return false;
		}
	}//Indica se uma pesquisa já foi feita e está sendo exibida, com navegação do usuário.
	private function haResultadoSeguinte():Boolean{
		if(pos_tupla_resultado_pesquisa < numero_de_resultados_ultima_pesquisa){
			return true;
		} else {
			return false;
		}
	}
	private function haResultadoAnterior():Boolean{
		if(pos_tupla_resultado_pesquisa > 1){
			return true;
		} else {
			return false;
		}
	}
	public function haResultadoDePesquisaSendoExibido():Boolean{
		if(numero_de_resultados_ultima_pesquisa > 0){
			return true;
		} else {
			return false;
		}
	}
	private function controlarVisibilidadeSetasProximoEAnterior():Void{
		if(haResultadoSeguinte()){
			btProximo._visible = true;
		} else {
			btProximo._visible = false;
		}
		
		if(haResultadoAnterior()){
			btAnterior._visible = true;
		} else {
			btAnterior._visible = false;
		}
	}
	private function dadoValidoParaPesquisa(dado_param:String):Boolean{
		var string_nova = new String();
		
		if(dado_param != "" and dado_param != string_nova){
			return true;
		} else {
			return false;
		}
	}

	//---- Botões
	private function iniciarPesquisa(){
		if (dadoValidoParaPesquisa(this['barraPesquisa'].text)) { 
			mostrarResultado();
			
			dado_pesquisado = this['barraPesquisa'].text;
			pos_tupla_resultado_pesquisa = 1;
			
			pesquisar(dado_pesquisado, 1);//O primeiro resultado é retornado no início da pesquisa.
			this['barraPesquisa'].text = "";
		}
	}
	private function proximo(){
		if (exibindoPesquisa()
			and haResultadoSeguinte()) {
			pos_tupla_resultado_pesquisa += 1;
			pesquisar(dado_pesquisado, pos_tupla_resultado_pesquisa);
		}
	}
	private function anterior(){
		if (exibindoPesquisa() 
			and haResultadoAnterior()) {
			pos_tupla_resultado_pesquisa -= 1;
			pesquisar(dado_pesquisado, pos_tupla_resultado_pesquisa);
		}
	}
	
	//---- Servidor
	private function armazenarResultadosPesquisa():Void{} //Deve ser implementada em cada classe que use este template.
	private function armazenarDadosAtualizadosDaPesquisa():Void{
		numero_de_resultados_ultima_pesquisa = recebe.numDadosEncontrados;
		dado_pesquisado = recebe.dado_pesquisado;
	}
	//Envia o dado pesquisado e a posição do dado que deve ser retornado. Assim, se enviar '3', somente o terceiro dado é retornado.
	public function pesquisar(dado_pesquisado_param:String, pos_tupla_resultado_pesquisa_param:Number):Void{
		recebe = new LoadVars();
		envia = new LoadVars();
		
		dado_pesquisado = dado_pesquisado_param;
		pos_tupla_resultado_pesquisa = pos_tupla_resultado_pesquisa_param;
		
		envia.usuario_id = _root.usuario_status.identificacao;
		envia.dado_pesquisado = dado_pesquisado_param;
		envia.pos_tupla_resultado_pesquisa = pos_tupla_resultado_pesquisa_param;
		
		c_aviso_espera.criarPara(this, "Favor aguardar enquanto a pesquisa é feita...", new Point(_width/2, _height/2));
		
		recebe.onLoad = Delegate.create(this, receberDadosPesquisaPHP);
		envia.sendAndLoad(endereco_arquivo_pesquisa_php, recebe, "POST");
	}
	private function receberDadosPesquisaPHP(success):Void{
	  if(success){
		armazenarDadosAtualizadosDaPesquisa();
		controlarVisibilidadeSetasProximoEAnterior();
		
		if(this.recebe.numDadosEncontrados>0){		
				if( dadosRecebidos() ){ armazenarResultadosPesquisa(); 
										comunicarResultadosDaPesquisa(); } //mostrar informações
				else{                   comunicarErroNaComunicaçãoComServidor(); } //erro na comunicação com BD
		} //mostrar resultados
		else{   comunicarFaltaDeResultados(); } //não houve resultados
		}
		
		c_aviso_espera.destruirDe(this);
	}
	
	//---- Dados
	public function dados():ac_dados{
		return new ac_dados();
	} //Deve ser implementada em cada classe que use este template.
	
	
}







