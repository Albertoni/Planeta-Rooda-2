import mx.utils.Delegate;
import flash.external.ExternalInterface;

/*
* Classe que representa a interface do menu para acesso rápido a funcionalidades (sem necessidade de usar o personagem no terreno).
*/
class c_interface_acesso_rapido extends ac_interface_menu{
//dados
	/*
	* Menu de funcionalidades às quais esta interface oferece acesso rápido.
	*/
	private var menu_funcionalidades:c_select = undefined;
	
	/*
	* Relação entre nomes de funcionalidades (que aparecem para os usuários) e seus links.
	*/
	private var funcionalidades:Array;
	private static var INDICE_NOME_FUNCIONALIDADE:Number = 0;
	private static var INDICE_LINK_FUNCIONALIDADE:Number = 1;
	
	/*
	* Botão para acesso ao links selecionada.
	*/
	private var btAcessoLink:c_btGrande;

//métodos
	public function inicializar(){
		super.inicializacoes();
		
		if(c_objeto_acesso.LINK_BASE == ""){
			c_objeto_acesso.inicializar();
		}
		
		funcionalidades = new Array();
		funcionalidades.push(["Biblioteca",     c_objeto_acesso.LINK_CASA_BIBLIOTECA + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Blog",           c_objeto_acesso.LINK_CASA_BLOG       + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Forum",          c_objeto_acesso.LINK_CASA_FORUM      + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Portfolio",      c_objeto_acesso.LINK_CASA_PORTFOLIO  + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Aparencia",      c_objeto_acesso.LINK_CASA_APARENCIA  + _root.personagem_status.getIdentificacaoBancoDeDados() + '&' + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Arte",           c_objeto_acesso.LINK_CASA_ARTE       + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Pergunta",       c_objeto_acesso.LINK_CASA_PERGUNTA   + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Aulas",          c_objeto_acesso.LINK_CASA_AULAS      + getArgumentoPlanetaURL()]);
		funcionalidades.push(["Player",         c_objeto_acesso.LINK_CASA_PLAYER     + getArgumentoPlanetaURL()]);
		if(_root.personagem_status.getNome()=="Diogo Raphael Cravo"){
			funcionalidades.push(["Afeto", c_objeto_acesso.LINK_BASE+"funcionalidades/afeto/index.php"]);
		}
		
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_acessoRapido_dummy", getNextHighestDepth(), {_x:2.8, _y:65});
		menu_funcionalidades = this['menu_acessoRapido_dummy'];
		menu_funcionalidades.inicializar(10, new Array(), "Funcionalidades");
		menu_funcionalidades.setTipoInvisivel(false);
		menu_funcionalidades._visible = true;
		
		for(var indice_funcionalidade:Number = 0; indice_funcionalidade < funcionalidades.length; indice_funcionalidade++){
			menu_funcionalidades.inserirOpcao(funcionalidades[indice_funcionalidade][INDICE_NOME_FUNCIONALIDADE]);
		}
		
		//attachMovie(c_btGrande.LINK_BIBLIOTECA, "btAcessoLink_dummy", getNextHighestDepth(), {_x:menu_funcionalidades._x, _y:menu_funcionalidades._y + menu_funcionalidades._height + 50});
		//btAcessoLink = this['btAcessoLink_dummy'];
		//btAcessoLink.inicializar("Acessar Link");
		menu_funcionalidades.addEventListener("botaoPressionado", Delegate.create(this, irParaLinkSelecionado));
	}
	
	/*
	* Para um planeta do tipo turma, retorna o argumento necessário para a chamada de uma funcionalidade, especificando a turma.
	* Caso o planeta não seja de turma, retorna um string vazia.
	*/
	private function getArgumentoPlanetaURL():String{
		if(_root.planeta_status.tipo == c_planeta.TURMA){
			return "turma="+_root.turma_status.identificacao;
		} else {
			return new String();
		}
	}
	

	/* Acessa o link correspondente à funcionalidade selecionada. 
	* Executada toda vez que o botão de acesso ao link for pressionado.
	* Recebe um evento com os seguintes atributos.
	*	- nome O numero do botão pressionado.
	*/
	private function irParaLinkSelecionado(evento_botao_param:Object):Void{
		var indiceFuncionalidade:Number = menu_funcionalidades.getIndiceOpcaoSelecionada();
		var link:String;
		
		if(indiceFuncionalidade != undefined){
			 link = funcionalidades[indiceFuncionalidade][INDICE_LINK_FUNCIONALIDADE];
			 ExternalInterface.call("chamaLink", link);
			 c_banco_de_dados.informaAcessoFuncionalidade(link, _root.planeta.getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdentificacao());
			 _root.planeta.getTerrenoEmQuePersonagemEstah().esperarLinkFechar();
		}
	}


}
