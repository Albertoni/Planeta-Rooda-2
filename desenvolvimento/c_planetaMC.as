import mx.utils.Delegate;
import flash.geom.Point;
import flash.external.*;
import mx.data.types.Obj;
import mx.events.EventDispatcher;

class c_planetaMC extends MovieClip{ 
//dados
	/**
	* Terreno principal, acessível por todos da turma e editável somente por professores.
	*/
	private var terrenoPrincipal:c_terreno;

	/**
	* Pátio, acessível por todos da turma e editável por todos.
	*/
	private var terrenoPatio:c_terreno;
	
	/**
	* O terreno em que o personagem do usuário está.
	*/
	private var terrenoEmQuePersonagemEstah:c_terreno;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

//métodos
	/**
	* @param c_personagem_bd		dados_personagem_param		Representa o personagem do usuário.
	* @param c_terreno_bd			imagem_bd_principal_param	Imagem do terreno principal.
	* @param c_terreno_bd			imagem_bd_patio_param		Imagem do terreno principal.
	*/
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_principal_param:c_terreno_bd, imagem_bd_patio_param:c_terreno_bd){
		mx.events.EventDispatcher.initialize(this);
		
		_x = 0;
		_y = 0;
		
		var link_terrenoPrincipal:String = "terreno_grama_principal_mc";
		var link_terrenoPatio:String = "terreno_grama_patio_mc";
		
		c_aviso_com_ok.mostrar(imagem_bd_principal_param.paraString()+"\n"+imagem_bd_patio_param.paraString());
		
		switch(_root.planeta_status.getAparencia()){
			case c_terreno_bd.TIPO_VERDE:
			case c_terreno_bd.TIPO_GRAMA:
				link_terrenoPrincipal = "terreno_grama_principal_mc";
				link_terrenoPatio = "terreno_grama_patio_mc";
				break;
			case c_terreno_bd.TIPO_LAVA:
				link_terrenoPrincipal = "terreno_lava_principal_mc";
				link_terrenoPatio = "terreno_lava_patio_mc";
				break;
			case c_terreno_bd.TIPO_GELO:
				link_terrenoPrincipal = "terreno_principal_gelo_mc";
				link_terrenoPatio = "terreno_patio_gelo_mc";
				break;
			case c_terreno_bd.TIPO_URBANO:
				link_terrenoPrincipal = "terreno_urbano_principal_mc";
				link_terrenoPatio = "terreno_urbano_patio_mc";
				break;
		}
		
		var posx:Number = this['terrenoPrincipalMC']._x;
		var posy:Number = this['terrenoPrincipalMC']._y;
		var profundidade:Number = this['terrenoPrincipalMC'].getDepth();
		this['terrenoPrincipalMC'].removeMovieClip();
		attachMovie(link_terrenoPrincipal, "terrenoPrincipal", profundidade);
		this['terrenoPrincipal']._x = posx;
		this['terrenoPrincipal']._y = posy;
		
		posx = this['terrenoPatioMC']._x;
		posy = this['terrenoPatioMC']._y;
		profundidade = this['terrenoPatioMC'].getDepth();
		this['terrenoPatioMC'].removeMovieClip();
		attachMovie(link_terrenoPatio, "terrenoPatio", profundidade);
		this['terrenoPatio']._x = posx;
		this['terrenoPatio']._y = posy;
		
		this.terrenoEmQuePersonagemEstah = this.terrenoPrincipal;
		this.terrenoPrincipal.inicializar(dados_personagem_param, imagem_bd_principal_param);
		this.terrenoPrincipal.estahAtivo(false);
		this.terrenoPatio.inicializar(dados_personagem_param, imagem_bd_patio_param);
		this.terrenoPatio.estahAtivo(false);
		
		this['terrenoPrincipal'].addEventListener("trocarTerreno", Delegate.create(this, trocarTerreno));	
		this['terrenoPatio'].addEventListener("trocarTerreno", Delegate.create(this, trocarTerreno));	
		
		var castTerrenoPrincipal:MovieClip = this.terrenoPrincipal;
		this.terrenoPrincipal.estahAtivo(true);
		this.terrenoPrincipal.centralizarTelaEm(new Point(castTerrenoPrincipal.mp._x, castTerrenoPrincipal.mp._y));
	}
	
	/**
	* Atende evento de troca de terreno.
	*/
	private function trocarTerreno(){
		if(getTerrenoEmQuePersonagemEstah() == getTerrenoPatio()){
			trocarTerrenoPersonagem(getTerrenoPrincipal());
		} else {
			trocarTerrenoPersonagem(getTerrenoPatio());
		}
	}
	
	/**
	* Troca o terreno em que está o mp para o terreno fornecido.
	* @param c_terreno		terrenoDestino_param		Terreno para onde irá o personagem.
	*/
	private function trocarTerrenoPersonagem(terrenoDestino_param:c_terreno){
		this.terrenoPrincipal.estahAtivo(false);
		this.terrenoPatio.estahAtivo(false);
		
		if(this.terrenoEmQuePersonagemEstah != undefined){
			terrenoDestino_param.swapDepths(this.terrenoEmQuePersonagemEstah);
			this.terrenoEmQuePersonagemEstah.estahAtivo(false);
		}
		
		var mpEstahMaisAoNorte:Boolean = false;
		var yMeioPontes:Number;
		var mcTerrenoDestino:MovieClip = terrenoDestino_param;
		var mcTerrenoAtual:MovieClip = this.terrenoEmQuePersonagemEstah;
		
		var deslocamentoHorizontal:Number=0;
		if(terrenoDestino_param._name == getTerrenoPrincipal()._name){
			yMeioPontes = mcTerrenoDestino.acesso_nordeste_novo._y+
				(mcTerrenoDestino.acesso_sudeste_novo._y-mcTerrenoDestino.acesso_nordeste_novo._y)/2;
			mpEstahMaisAoNorte = ((mcTerrenoAtual.mp._y - yMeioPontes) < 0);
			if(mpEstahMaisAoNorte){
				mcTerrenoDestino.mp._x = mcTerrenoDestino.acesso_nordeste_novo._x + mcTerrenoDestino.acesso_nordeste_novo._width;
				mcTerrenoDestino.mp._y = mcTerrenoAtual.mp._y;
			} else {
				mcTerrenoDestino.mp._x = mcTerrenoDestino.acesso_sudeste_novo._x + mcTerrenoDestino.acesso_sudeste_novo._width;
				mcTerrenoDestino.mp._y = mcTerrenoAtual.mp._y;
			}
			if(terrenoDestino_param.atingiuLimiteAreaMovimentacao(0,0)){
				terrenoDestino_param.moverTerreno(50, 0);
			}
		} else {
			yMeioPontes = mcTerrenoDestino.acesso_noroeste_novo._y+
				(mcTerrenoDestino.acesso_sudoeste_novo._y-mcTerrenoDestino.acesso_noroeste_novo._y)/2;
			mpEstahMaisAoNorte = ((mcTerrenoAtual.mp._y - yMeioPontes) < 0);
			if(mpEstahMaisAoNorte){
				mcTerrenoDestino.mp._x = mcTerrenoDestino.acesso_noroeste_novo._x;
				mcTerrenoDestino.mp._y = mcTerrenoAtual.mp._y;
			} else {
				mcTerrenoDestino.mp._x = mcTerrenoDestino.acesso_sudoeste_novo._x;
				mcTerrenoDestino.mp._y = mcTerrenoAtual.mp._y;
			}
			if(terrenoDestino_param.atingiuLimiteAreaMovimentacao(0,0)){
				terrenoDestino_param.moverTerreno(-50, 0);
			}
		}
		mcTerrenoDestino.mp.olharPara(mcTerrenoAtual.mp.direcaoQueOlha());
		
		this.terrenoEmQuePersonagemEstah = terrenoDestino_param;
		terrenoDestino_param.estahAtivo(true);
		terrenoDestino_param.habilitarPontes(false);
	}

	/**
	* @return c_terreno		O terreno principal.
	*/
	public function getTerrenoPrincipal():c_terreno{
		return this.terrenoPrincipal;
	}

	/**
	* @return c_terreno		O terreno do pátio.
	*/
	public function getTerrenoPatio():c_terreno{
		return this.terrenoPatio;
	}

	/**
	* @return c_terreno		O terreno em que o personagem do usuário está!
	*/
	public function getTerrenoEmQuePersonagemEstah():c_terreno{
		return this.terrenoEmQuePersonagemEstah;
	}

	/**
	* @param String		aparecencia_param		Aparência do planeta do qual deseja-se o link na biblioteca.
	*											Definido em c_terreno_bd.
	* @return String		Link na biblioteca do planeta com a aparência dada.
	*/
	public static function getLinkBiblioteca(aparencia_param:String):String{
		var linkBiblioteca:String="";
		switch(aparencia_param){
			case c_terreno_bd.TIPO_VERDE:
			case c_terreno_bd.TIPO_GRAMA:
				linkBiblioteca = "planetaGrama";
				break;
			case c_terreno_bd.TIPO_LAVA:
				linkBiblioteca = "planetaLava";
				break;
			case c_terreno_bd.TIPO_GELO:
				linkBiblioteca = "planetaGelo";
				break;
			case c_terreno_bd.TIPO_URBANO:
				linkBiblioteca = "planetaUrbano";
				break;
		}
		return linkBiblioteca;
	}



















}