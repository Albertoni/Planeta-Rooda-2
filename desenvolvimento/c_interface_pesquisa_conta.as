import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_pesquisa_conta extends ac_interface_pesquisa {
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "camposPesquisarContas";

	//---- Pesquisa
	public var conta_pesquisa:c_conta;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();

		conta_pesquisa = new c_conta();

		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_contas.php";
	}
	
	//---- Interface
	private function restringirCamposDeTexto():Void{
		this['barraPesquisa'].multiline = false;
	}
	private function comunicarResultadosDaPesquisa():Void{
		var caixa_texto_resultado:MovieClip = this['textoResultado'];
		mostrarResultado();
		
		
		caixa_texto_resultado.text = "";
		
		caixa_texto_resultado.text += "Conta Pesquisada: ";
		if(informado(dado_pesquisado)){ caixa_texto_resultado.text += this.dado_pesquisado + "\n"; }
		else{                           caixa_texto_resultado.text += "Erro no envio dos dados." + "\n";}
		caixa_texto_resultado.text += "Exibindo resultado "+pos_tupla_resultado_pesquisa+" de ";
		if(informado(""+numero_de_resultados_ultima_pesquisa)){caixa_texto_resultado.text += numero_de_resultados_ultima_pesquisa + " contas encontradas.\n\n\n"; }
		else{                                                  caixa_texto_resultado.text += "Erro no envio dos dados." + "\n\n\n";}
		
		
		caixa_texto_resultado.text += "Login do Usuário: ";
		if(informado(conta_pesquisa.login)){ caixa_texto_resultado.text += conta_pesquisa.login + "\n"; }
		else{                              	 caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Data de Aniversário do Usuário: ";
		if(informado(conta_pesquisa.diaAniversario)
		   and informado(conta_pesquisa.mesAniversario)
		   and informado(conta_pesquisa.anoAniversario)){ caixa_texto_resultado.text += conta_pesquisa.diaAniversario
																					+"/"+conta_pesquisa.mesAniversario
																					+"/"+conta_pesquisa.anoAniversario+"\n"; }
		else{                              	      		  caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Nome do Usuário: ";
		if(informado(conta_pesquisa.nome)){ caixa_texto_resultado.text += this.conta_pesquisa.nome + "\n"; }
		else{                               caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Nome da Mãe do Usuário: ";
		if(informado(conta_pesquisa.nomeMae)){ caixa_texto_resultado.text += this.conta_pesquisa.nomeMae + "\n"; }
		else{                                  caixa_texto_resultado.text += "Não informado." + "\n";}
		
		caixa_texto_resultado.text += "Email do Usuário: ";
		if(informado(conta_pesquisa.email)){ caixa_texto_resultado.text += conta_pesquisa.email + "\n"; }
		else{                                caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Nível do Usuário: ";
		if(informado(conta_pesquisa.nivel)){ caixa_texto_resultado.text += conta_pesquisa.getDescricaoNivel() + "\n"; }
		else{                                caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Apelido do Usuário: ";
		if(informado(conta_pesquisa.apelido)){ caixa_texto_resultado.text += conta_pesquisa.apelido + "\n"; }
		else{                                  caixa_texto_resultado.text += "Não informado." + "\n";}
	}
	
	//---- Pesquisa
	private function dadosRecebidos():Boolean{
		if(recebe.usuario_id != undefined 
		   or recebe.usuario_login != undefined 
		   or recebe.usuario_senha != undefined
		   or recebe.usuario_data_aniversario != undefined
		   or recebe.usuario_nome != undefined
		   or recebe.usuario_nome_mae != undefined
		   or recebe.usuario_email != undefined 
		   or recebe.usuario_nivel != undefined
		   or recebe.usuario_apelido != undefined){
			return true;
		}
		else{
			return false;
		}
	}
	private function armazenarResultadosPesquisa():Void{
		numero_de_resultados_ultima_pesquisa = recebe.numDadosEncontrados;
		dado_pesquisado = recebe.dado_pesquisado;
		
		conta_pesquisa.identificacao = recebe.usuario_id;
		conta_pesquisa.login = recebe.usuario_login;
		conta_pesquisa.senha = recebe.usuario_senha;
		conta_pesquisa.diaAniversario = recebe.usuario_dia_aniversario;
		conta_pesquisa.mesAniversario = recebe.usuario_mes_aniversario;
		conta_pesquisa.anoAniversario = recebe.usuario_ano_aniversario;
		conta_pesquisa.nome = recebe.usuario_nome;
		conta_pesquisa.nomeMae = recebe.usuario_nome_mae;
		conta_pesquisa.email = recebe.usuario_email;
		conta_pesquisa.nivel = recebe.usuario_nivel;
		conta_pesquisa.apelido = recebe.usuario_apelido;
		
		conta_pesquisa.turmasProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_professor; i++){
			conta_pesquisa.turmasProfessor.push(recebe["turmasProfessor"+i]);
		}
		conta_pesquisa.turmasConvidadoProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_professor; i++){
			conta_pesquisa.turmasConvidadoProfessor.push(recebe["turmasConvidadoProfessor"+i]);
		}
		conta_pesquisa.turmasHabilitadoProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_professor; i++){
			conta_pesquisa.turmasHabilitadoProfessor.push(recebe["turmasHabilitadoProfessor"+i]);
		}
		
		conta_pesquisa.turmasMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_monitor; i++){
			conta_pesquisa.turmasMonitor.push(recebe["turmasMonitor"+i]);
		}
		conta_pesquisa.turmasConvidadoMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_monitor; i++){
			conta_pesquisa.turmasConvidadoMonitor.push(recebe["turmasConvidadoMonitor"+i]);
		}
		conta_pesquisa.turmasHabilitadoMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_monitor; i++){
			conta_pesquisa.turmasHabilitadoMonitor.push(recebe["turmasHabilitadoMonitor"+i]);
		}
		
		conta_pesquisa.turmasAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_aluno; i++){
			conta_pesquisa.turmasAluno.push(recebe["turmasAluno"+i]);
		}
		conta_pesquisa.turmasConvidadoAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_aluno; i++){
			conta_pesquisa.turmasConvidadoAluno.push(recebe["turmasConvidadoAluno"+i]);
		}
		conta_pesquisa.turmasHabilitadoAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_aluno; i++){
			conta_pesquisa.turmasHabilitadoAluno.push(recebe["turmasHabilitadoAluno"+i]);
		}
	}
	
	//---- Dados
	public function dados():ac_dados{
		var objDados:ac_dados = new ac_dados();
		objDados.conta = conta_pesquisa;
		return objDados;
	} 
	
	
	
	
	
	
}