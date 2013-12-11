import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_pesquisa_turma extends ac_interface_pesquisa {
//dados
	//---- Pesquisa
	private var turma_pesquisa:c_turma;

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		turma_pesquisa = new c_turma();

		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_turmas.php";
	}
	
	//---- Interface
	private function restringirCamposDeTexto():Void{
		this['barraPesquisa'].multiline = false;
	}
	private function comunicarResultadosDaPesquisa():Void{
		var caixa_texto_resultado:MovieClip = this['textoResultado'];
		mostrarResultado();
		
		caixa_texto_resultado.text = "";
		
		caixa_texto_resultado.text += "Turma Pesquisada: ";
		if(informado(this.dado_pesquisado)){ caixa_texto_resultado.text += this.dado_pesquisado + "\n"; }
		else{                                caixa_texto_resultado.text += "Erro no envio dos dados." + "\n";}
		caixa_texto_resultado.text += "Exibindo resultado "+this.pos_tupla_resultado_pesquisa+" de ";
		if(informado(""+this.numero_de_resultados_ultima_pesquisa)){ caixa_texto_resultado.text += this.numero_de_resultados_ultima_pesquisa + " turmas encontradas.\n\n\n"; }
		else{                                                        caixa_texto_resultado.text += "Erro no envio dos dados." + "\n\n\n";}
		
		caixa_texto_resultado.text += "Nome da Turma: ";
		if(informado(this.turma_pesquisa.nome)){ caixa_texto_resultado.text += this.turma_pesquisa.nome + "\n"; }
		else{                                    caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Nome do Professor: ";
		if(informado(this.turma_pesquisa.professor)){ caixa_texto_resultado.text += this.turma_pesquisa.professor + "\n"; }
		else{                                         caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Mãe do Professor: ";
		if(informado(this.turma_pesquisa.nomeMaeProfessor)){ caixa_texto_resultado.text += this.turma_pesquisa.nomeMaeProfessor + "\n"; }
		else{                                                caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Descrição da Turma: ";
		if(informado(this.turma_pesquisa.descricao)){ caixa_texto_resultado.text += this.turma_pesquisa.descricao + "\n"; }
		else{                                         caixa_texto_resultado.text += "Não informado." + "\n";}
		caixa_texto_resultado.text += "Ano da Turma: ";
		if(informado(this.turma_pesquisa.ano)){ caixa_texto_resultado.text += this.turma_pesquisa.ano + "\n"; }
		else{                                   caixa_texto_resultado.text += "Não informado." + "\n";}
	}
	
	//---- Pesquisa
	private function dadosRecebidos():Boolean{
		if(recebe.identificacao != undefined 
		   or recebe.nome != undefined 
	       or recebe.professor != undefined 
		   or recebe.nomeMaeProfessor != undefined 
		   or recebe.descricao != undefined
		   or recebe.ano != undefined 
		   ){
			return true;
		}
		else{
			return false;
		}
	}
	private function armazenarResultadosPesquisa():Void{
		var professores:Array = new Array();
		var monitores:Array = new Array();
		var alunos:Array = new Array();
		turma_pesquisa.identificacao = recebe.identificacao;
		turma_pesquisa.nome = recebe.nome;
		turma_pesquisa.professor = recebe.professor;
		turma_pesquisa.nomeMaeProfessor = recebe.nomeMaeProfessor;
		turma_pesquisa.descricao = recebe.descricao;
		turma_pesquisa.ano = recebe.ano;
		
		for(var i:Number=0; i<recebe.numeroProfessores; i++){
			professores.push(recebe['professor'+i]);
		}
		for(var i:Number=0; i<recebe.numeroMonitores; i++){
			monitores.push(recebe['monitor'+i]);
		}
		for(var i:Number=0; i<recebe.numeroAlunos; i++){
			alunos.push(recebe['aluno'+i]);
		}
		turma_pesquisa.definirProfessores(professores);
		turma_pesquisa.definirMonitores(monitores);
		turma_pesquisa.definirAlunos(alunos);
	}
	
	//---- Dados
	public function dados():ac_dados{
		var objDados:ac_dados = new ac_dados();
		objDados.turma = turma_pesquisa;
		return objDados;
	} 
	
	
}