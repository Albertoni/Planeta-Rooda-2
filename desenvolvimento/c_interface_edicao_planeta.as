import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_edicao_planeta extends ac_interface_edicao {
//dados	
	//---- Edição
	private var planeta_pesquisa:c_planeta;
	private var planeta_edicao:c_planeta;

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	private static var POSX_PRIMEIRO_BOTAO_INTERFACE:Number = 420;
	private static var POSY_PRIMEIRO_BOTAO_INTERFACE:Number = 325;
	private static var POSX_SEGUNDO_BOTAO_INTERFACE:Number = 600;
	private static var POSY_SEGUNDO_BOTAO_INTERFACE:Number = 325;
	
	private var btEditarPlaneta:c_btEditarPlaneta;
	private static var POSX_BOTAO_EDITAR_PLANETA:Number = POSX_PRIMEIRO_BOTAO_INTERFACE;
	private static var POSY_BOTAO_EDITAR_PLANETA:Number = POSY_PRIMEIRO_BOTAO_INTERFACE;
	
	private var btEditarTerrenos:c_btEditarTerrenos;
	private static var POSX_BOTAO_EDITAR_TERRENOS:Number = POSX_PRIMEIRO_BOTAO_INTERFACE;
	private static var POSY_BOTAO_EDITAR_TERRENOS:Number = POSY_PRIMEIRO_BOTAO_INTERFACE;
	
	private var btEditarPermissoes:c_btEditarPermissoes;
	private static var POSX_BOTAO_EDITAR_PERMISSOES:Number = POSX_SEGUNDO_BOTAO_INTERFACE;
	private static var POSY_BOTAO_EDITAR_PERMISSOES:Number = POSY_SEGUNDO_BOTAO_INTERFACE;
	
	//---- Interfaces
	private var interfaceAtiva:MovieClip;
	
	private var camposEdicaoDados:c_camposEditarDadosPlaneta;
	private static var POSX_EDICAO_DADOS:Number = 0;
	private static var POSY_EDICAO_DADOS:Number = 0;
	
	private var camposEdicaoTerrenos:c_camposEdicaoTerrenosPlaneta;
	private static var POSX_EDICAO_TERRENOS:Number = 0;
	private static var POSY_EDICAO_TERRENOS:Number = 0;
	
	private var camposEdicaoPermissoes:c_camposEditarPermissoesPlaneta;
	private static var POSX_EDICAO_PERMISSOES:Number = 0;
	private static var POSY_EDICAO_PERMISSOES:Number = 0;
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		attachMovie("btEditarPlaneta", "btEditarPlaneta", getNextHighestDepth());
		btEditarPlaneta.inicializar();
		btEditarPlaneta._x = POSX_BOTAO_EDITAR_PLANETA;
		btEditarPlaneta._y = POSY_BOTAO_EDITAR_PLANETA;
		btEditarPlaneta.addEventListener("btEditarPlanetaPress", Delegate.create(this, editarPlaneta));	
		btEditarPlaneta._visible = false;
		
		attachMovie("btEditarTerrenos", "btEditarTerrenos", getNextHighestDepth());
		btEditarTerrenos.inicializar();
		btEditarTerrenos._x = POSX_BOTAO_EDITAR_TERRENOS;
		btEditarTerrenos._y = POSY_BOTAO_EDITAR_TERRENOS;
		btEditarTerrenos.addEventListener("btEditarTerrenosPress", Delegate.create(this, editarTerrenos));	
		btEditarTerrenos._visible = true;

		attachMovie("btEditarPermissoes", "btEditarPermissoes", getNextHighestDepth());
		btEditarPermissoes.inicializar();
		btEditarPermissoes._x = POSX_BOTAO_EDITAR_PERMISSOES;
		btEditarPermissoes._y = POSY_BOTAO_EDITAR_PERMISSOES;
		btEditarPermissoes.addEventListener("btEditarPermissoesPress", Delegate.create(this, editarPermissoes));	
		btEditarPermissoes._visible = true;
		
		attachMovie("camposEditarDadosPlaneta", "camposEdicaoDados", getNextHighestDepth());
		camposEdicaoDados.inicializar();
		camposEdicaoDados._x = POSX_EDICAO_DADOS;
		camposEdicaoDados._y = POSY_EDICAO_DADOS;
		camposEdicaoDados.abrirInterface();
		
		attachMovie("camposEdicaoTerrenosPlaneta", "camposEdicaoTerrenos", getNextHighestDepth());
		camposEdicaoTerrenos.inicializar();
		camposEdicaoTerrenos._x = POSX_EDICAO_TERRENOS;
		camposEdicaoTerrenos._y = POSY_EDICAO_TERRENOS;
		camposEdicaoTerrenos.fecharInterface();
		
		attachMovie("camposEditarPermissoesPlaneta", "camposEdicaoPermissoes", getNextHighestDepth());
		camposEdicaoPermissoes.inicializar();
		camposEdicaoPermissoes._x = POSX_EDICAO_PERMISSOES;
		camposEdicaoPermissoes._y = POSY_EDICAO_PERMISSOES;
		camposEdicaoPermissoes.fecharInterface();
		
		planeta_pesquisa = new c_planeta();
		planeta_edicao = new c_planeta();

		endereco_arquivo_gravacao_php = "phps_do_menu/edicao_planetas.php";
		endereco_arquivo_delecao_php = "phps_do_menu/deletar_planeta.php";
	}
	
	//---- TextFields
	private function inicializarTextFields(){
		this['nome'].multiline = false;
	}
	
	//---- Interface
	private function editarPlaneta():Void{
		btEditarTerrenos._x = POSX_PRIMEIRO_BOTAO_INTERFACE;
		btEditarTerrenos._y = POSY_PRIMEIRO_BOTAO_INTERFACE;
		btEditarPermissoes._x = POSX_SEGUNDO_BOTAO_INTERFACE;
		btEditarPermissoes._y = POSY_SEGUNDO_BOTAO_INTERFACE;
		
		btEditarPlaneta._visible = false;
		btEditarTerrenos._visible = true;
		btEditarPermissoes._visible = true;
		
		interfaceAtiva = camposEdicaoDados;
		camposEdicaoDados.abrirInterface();
		camposEdicaoTerrenos.fecharInterface();
		camposEdicaoPermissoes.fecharInterface();
	}
	private function editarTerrenos():Void{
		btEditarPlaneta._x = POSX_PRIMEIRO_BOTAO_INTERFACE;
		btEditarPlaneta._y = POSY_PRIMEIRO_BOTAO_INTERFACE;
		btEditarPermissoes._x = POSX_SEGUNDO_BOTAO_INTERFACE;
		btEditarPermissoes._y = POSY_SEGUNDO_BOTAO_INTERFACE;
		
		btEditarPlaneta._visible = true;
		btEditarTerrenos._visible = false;
		btEditarPermissoes._visible = true;
		
		interfaceAtiva = camposEdicaoTerrenos;
		camposEdicaoDados.fecharInterface();
		camposEdicaoTerrenos.abrirInterface();
		camposEdicaoPermissoes.fecharInterface();
	}
	private function editarPermissoes():Void{
		btEditarPlaneta._x = POSX_PRIMEIRO_BOTAO_INTERFACE;
		btEditarPlaneta._y = POSY_PRIMEIRO_BOTAO_INTERFACE;
		btEditarTerrenos._x = POSX_SEGUNDO_BOTAO_INTERFACE;
		btEditarTerrenos._y = POSY_SEGUNDO_BOTAO_INTERFACE;
		
		btEditarPlaneta._visible = true;
		btEditarTerrenos._visible = true;
		btEditarPermissoes._visible = false;
		
		interfaceAtiva = camposEdicaoPermissoes;
		camposEdicaoDados.fecharInterface();
		camposEdicaoTerrenos.fecharInterface();
		camposEdicaoPermissoes.abrirInterface();
	}
	private function armazenarDadosEditados():Void{
		planeta_edicao.identificacao = planeta_pesquisa.identificacao;
		
		planeta_edicao.nome = camposEdicaoDados.getNome();
		planeta_edicao.tipo = camposEdicaoDados.getTipo();
		planeta_edicao.setAparencia(camposEdicaoDados.getAparencia());
		planeta_edicao.dono = camposEdicaoPermissoes.getDono();
		planeta_edicao.setTerrenos(camposEdicaoTerrenos.getTerrenos());
		planeta_edicao.niveisAcessoPermitido = camposEdicaoPermissoes.getPermissaoAcesso();
		planeta_edicao.niveisEdicaoPermitida = camposEdicaoPermissoes.getPermissaoEdicao();
		
		if(!informado(planeta_edicao.nome)){
			planeta_edicao.nome = "";
		}
		//tipo sempre está informado, pois é botão radio
		if(!informado(planeta_edicao.dono)){
			planeta_edicao.dono = "";
		}
	}
	private function preencherCampos(dados_param:ac_dados):Void{
		planeta_pesquisa = dados_param.planeta;
		
		planeta_pesquisa.converterTipoParaNumber();
		
		camposEdicaoDados.setNome(planeta_pesquisa.nome);
		camposEdicaoDados.setTipo(planeta_pesquisa.tipo);
		camposEdicaoDados.setAparencia(planeta_pesquisa.getAparencia());
		
		camposEdicaoTerrenos.setTerrenos(planeta_pesquisa.getTerrenos());
		
		if(planeta_pesquisa.tipo == c_planeta.TURMA){
			camposEdicaoPermissoes.setAcessoConfiguravel(true);
			camposEdicaoPermissoes.setEdicaoConfiguravel(true);
		} else {
			camposEdicaoPermissoes.setAcessoConfiguravel(false);
			camposEdicaoPermissoes.setEdicaoConfiguravel(false);
		}
		camposEdicaoPermissoes.setDono(planeta_pesquisa.dono);
		camposEdicaoPermissoes.setPermissaoAcesso(planeta_pesquisa.niveisAcessoPermitido);
		camposEdicaoPermissoes.setPermissaoEdicao(planeta_pesquisa.niveisEdicaoPermitida);
	}
	public function mostrar():Void{
		interfaceAtiva._visible = true;
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
		camposEdicaoTerrenos.fecharInterface();
	}
	
	//---- Servidor
	private function criarEnvia():Void{
		/*_root.debug.text+=
		"identificacao("+planeta_edicao.identificacao+")\n"+
		"nome("+planeta_edicao.nome+")\n"+
		"tipo("+planeta_edicao.tipo+")\n"+
		"dono("+planeta_edicao.dono+")\n"+
		"getIdsTerrenos("+planeta_edicao.getIdsTerrenos().toString()+")\n"+
		"getNomesTerrenos("+planeta_edicao.getNomesTerrenos().toString()+")\n"+
		"getTiposTerrenos("+planeta_edicao.getTiposTerrenos().toString()+")\n"+
		"niveisAcessoPermitido("+planeta_edicao.niveisAcessoPermitido+")\n"+
		"niveisEdicaoPermitida("+planeta_edicao.niveisEdicaoPermitida+")\n";*/
		envia.identificacao = planeta_edicao.identificacao;
		envia.nome          = planeta_edicao.nome;
		envia.tipo 		    = planeta_edicao.tipo;
		envia.dono          = planeta_edicao.dono;
		envia.aparencia		= planeta_edicao.getAparencia();
		envia.idsTerrenos   = planeta_edicao.getIdsTerrenos().toString();
		envia.nomesTerrenos = planeta_edicao.getNomesTerrenos().toString();
		envia.acesso        = planeta_edicao.niveisAcessoPermitido;
		envia.edicao        = planeta_edicao.niveisEdicaoPermitida;
	}
	
	//---- Dados
	public function validarDados():Boolean{
		return planeta_edicao.validar();
	}
	public function getErroValidacao():String{
		return planeta_edicao.getMensagemErro();
	}
	
	
}