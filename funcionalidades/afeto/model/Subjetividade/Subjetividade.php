<?php

//Util
require_once("ppost.class.php");
require_once(dirname(__FILE__)."/../Util/Data.php");
require_once(dirname(__FILE__)."/../../../../usuarios.class.php");
require_once(dirname(__FILE__)."/../../../../turma.class.php");
//Model
require_once("Emocao.php");
require_once(dirname(__FILE__)."/../Funcionalidades/AparenciaTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/ArteTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/AulasTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/BibliotecaTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/BlogTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/ForumTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/PerguntasTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/PlayerTurma.php");
require_once(dirname(__FILE__)."/../Funcionalidades/PortfolioTurma.php");

class Subjetividade{
//dados
	/*
	* Mapeamento de intervalos de tempo em seus estados emocionais com as intensidades.
	* Um array de emo��es.
	*/
	private $emocoes;
	
//m�todos
	/*
	* Procura os dados de subjetividade do usu�rio dado no banco de dados no per�odo dado.
	* @param Data		$data_inicio		 Data mais antiga permitida para posts entrarem na busca.
	* @param Data		$data_fim			 Data mais recente permitida para posts entrarem na busca.
	* @param Usuario 	$usuario 			 Usu�rio cujos posts ser�o avaliados.
	* @param Turma		$turma				 A turma em que o usu�rio ser� testado.
	* @param String 	$divisaoTempo		 Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	*/
	public function __construct($data_inicio, $data_fim, $usuario, $turma, $divisaoTempo){
		$this->emocoes = array();

		$pPostsUsuario = $this->getPpostsUsuarioPeriodo($data_inicio, $data_fim, $usuario, $divisaoTempo);
		for($i=0; $i<count($pPostsUsuario); $i++){
			$this->emocoes[$i] = new Emocao($pPostsUsuario[$i]->predomina['subquad'], Emocao::INTENSIDADE_MAXIMA);
											/*$pPostsUsuario[$i]->predomina['total_int'],
											$pPostsUsuario[$i]->predomina['quad'],
											$pPostsUsuario[$i]->predomina['subquad'],
											$pPostsUsuario[$i]->predomina['sig_quad'],
											$pPostsUsuario[$i]->predomina['sig_total']);*/
		}
		//$this->predomina['total_int'] = $ret[0];		//somatorio total de intensidades
		//$this->predomina['quad'] = $ret[1];			//indicador de quadrante predominante
		//$this->predomina['subquad'] = $ret[2];		//indicador de subquadrante predominante
		//$this->predomina['sig_quad'] = $ret[3];		//quantidade rad. significativos no quad predominante
		//$this->predomina['sig_total'] = $ret[4];		//quantidade rad. significativos do post
		
	}
	
	/*
	* @param int	$qualPeriodo	 Um n�mero que representa uma semana ou um mes.
	* @return Retorna a emo��o correspondente � semana fornecida. Caso n�o haja mais semanas, retornar� null.
	*/
	public function getEmocao($qualPeriodo){
		$emocao = null;
		if($qualPeriodo <= count($this->emocoes)){
			$emocao = $this->emocoes[$qualPeriodo];
		}
		return $emocao;
	}
	
	/*
	* @param Data		$data_inicio		 Data mais antiga permitida para posts entrarem na busca.
	* @param Data		$data_fim			 Data mais recente permitida para posts entrarem na busca.
	* @param Usuario 	$usuario 			 Usu�rio cujos posts ser�o avaliados.
	* @param String 	$divisaoTempo		 Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Todos os textos que devem ser avaliados quanto � subjetividade.
	*		  Os textos s�o retornados em um array de objetos Ppost.
	*/
	private function getPpostsUsuarioPeriodo($data_inicio, $data_fim, $usuario, $divisaoTempo){
		$pPosts = array();
		$data = "";
		$texto = "";
		$periodo = "";
		
		//$textosUsuario = new TextosUsuario($usuario_param->getId());
		//$textosForum = $textosUsuario->getTextosForum();
		
		if($divisaoTempo == Data::SEMANA){
			$periodo = 'semana';
		} else if($divisaoTempo == Data::MES){
			$periodo = 'mes';
		}
		
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($data_inicio, $data_fim, $divisaoTempo);
		for($ordem=0; $ordem<$numeroIntervalosTempo; $ordem++){
			$pPosts[$ordem] = new Ppost();

error_reporting(~E_NOTICE);
			// $texto = "Hoje eu estou muito alegre.";
			$texto = "Hoje eu estou alegre.";
			$data = "2012-10-01 00:00:00";
			$pPosts[$ordem]->inicia($data, $texto, $periodo);
			echo "total_int=".$pPosts[$ordem]->predomina['total_int']."<br>";
			echo "quad=".$pPosts[$ordem]->predomina['quad']."<br>";
			echo "subquad=".$pPosts[$ordem]->predomina['subquad']."<br>";
			echo "sig_quad=".$pPosts[$ordem]->predomina['sig_quad']."<br>";
			echo "sig_total=".$pPosts[$ordem]->predomina['sig_total']."<br>"; //intensidade = total_int/sig_total
			BREAK;
		}
		
		return $pPosts;
	}
	
	
}





?>