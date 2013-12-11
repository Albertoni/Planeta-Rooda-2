import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_pesquisa_planeta extends ac_interface_pesquisa {
//dados	
	//---- Pesquisa
	private var planeta_pesquisa:c_planeta;

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		planeta_pesquisa = new c_planeta();
		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_planetas.php";
	}
	
	//---- Interface
	private function restringirCamposDeTexto():Void{
		this['barraPesquisa'].multiline = false;
	}
	private function comunicarResultadosDaPesquisa():Void{
		var caixa_texto_resultado:MovieClip = this['textoResultado'];
		mostrarResultado();
		
		planeta_pesquisa.converterTipoParaString();
		
		caixa_texto_resultado.text = "";
		
		caixa_texto_resultado.text += "Planeta Pesquisado: ";
		if(informado(this.dado_pesquisado)){ caixa_texto_resultado.text += this.dado_pesquisado + "\n"; }
		else{                                caixa_texto_resultado.text += "Erro no envio dos dados." + "\n";}
		caixa_texto_resultado.text += "Exibindo resultado "+this.pos_tupla_resultado_pesquisa+" de ";
		if(informado(""+this.numero_de_resultados_ultima_pesquisa)){ caixa_texto_resultado.text += this.numero_de_resultados_ultima_pesquisa + " planetas encontrados.\n\n\n"; }
		else{                                                        caixa_texto_resultado.text += "Erro no envio dos dados." + "\n\n\n";}
		caixa_texto_resultado.text += "Tipo do Planeta: ";
		if(informado(this.planeta_pesquisa.tipo)){ caixa_texto_resultado.text += this.planeta_pesquisa.tipo + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Aparência do Planeta: ";
		if(informado(this.planeta_pesquisa.tipo)){ caixa_texto_resultado.text += this.planeta_pesquisa.getNomeAparencia() + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Nome do Planeta: ";
		if(informado(this.planeta_pesquisa.nome)){ caixa_texto_resultado.text += this.planeta_pesquisa.nome + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Terrenos no Planeta: ";
		if(informado(this.planeta_pesquisa.getTerrenos().toString())){ caixa_texto_resultado.text += this.planeta_pesquisa.getDescricaoTerrenos() + "\n"; }
		else{                                                          caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Responsável pelo Planeta: ";
		if(informado(this.planeta_pesquisa.dono)){ caixa_texto_resultado.text += this.planeta_pesquisa.dono + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Níveis com permissão de acesso: ";
		if(informado(this.planeta_pesquisa.dono)){ caixa_texto_resultado.text += this.planeta_pesquisa.getDescricaoPermissaoAcesso() + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Permissão de Edição: ";
		if(informado(this.planeta_pesquisa.dono)){ caixa_texto_resultado.text += this.planeta_pesquisa.getDescricaoPermissaoEdicao() + "\n"; }
		else{                                      caixa_texto_resultado.text += "Não informado." + "\n";}
	}
	
	//---- Pesquisa
	private function dadosRecebidos():Boolean{
		if(recebe.identificacao != undefined 
		   or recebe.tipo != undefined 
	       or recebe.nome != undefined 
		   or recebe.dono != undefined
		   ){
			return true;
		}
		else{
			return false;
		}
		return true;
	}
	private function armazenarResultadosPesquisa():Void{
		var terreno:c_terreno_bd = new c_terreno_bd();
		planeta_pesquisa = new c_planeta();
		
		planeta_pesquisa.identificacao = recebe.identificacao;
		planeta_pesquisa.tipo = recebe.tipo;
		planeta_pesquisa.nome = recebe.nome;
		planeta_pesquisa.dono = recebe.dono;
		planeta_pesquisa.setAparencia(recebe.aparencia);
		for(var i:Number = 0; i<recebe.num_terrenos; i++){
			terreno = new c_terreno_bd();
			terreno.setIdentificacao(recebe["idTerreno"+i]);
			terreno.setNome(recebe["nomeTerreno"+i]);
			planeta_pesquisa.adicionarTerreno(terreno);
		}
		planeta_pesquisa.niveisAcessoPermitido = recebe.niveis_acesso_permitido;
		planeta_pesquisa.niveisEdicaoPermitida = recebe.niveis_edicao_permitida;
	}
	
	//---- Dados
	public function dados():ac_dados{
		var objDados:ac_dados = new ac_dados();
		objDados.planeta = planeta_pesquisa;
		return objDados;
	} 
	
	
}