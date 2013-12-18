<?php	/*		temporiza��es e afins		*/
	//aumentar tempo m�ximo de execu��o para 600 segundos
	ini_set("max_execution_time", 600);

//error_reporting(E_ALL);

function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
?>
<?php	/*		inicializa��o arrays	*/
/*		apenas se n�o tiver sido inclu�do algum outro arquivo com os dados do banco		*/
//require_once("db.inc.php");
/*--------------------------------------------------------------------------------------*/
$sws = db_busca("select stopword from raf_stopwords");			//inicializa��o do array de stopwords
for($i=0;$i<count($sws);$i++){
	$sws[$i] = $sws[$i]['stopword'];
}

$abrev = db_busca("select * from raf_abrevia");			//inicializa��o do array de abreviacoes
for($i=0;$i<count($abrev);$i++){
	$t1 = $abrev[$i]['abrev'];
	$t2 = $abrev[$i]['palavra'];
	unset($abrev[$i]);
	$abrev[$t1] = $t2;
	
}

$words = db_busca("select * from raf_lexico");			//inicializa��o do array de words
$word = array();
for($i=0;$i<count($words);$i++){
	$rad = $words[$i]['radical'];
	$cod = $words[$i]['codigo'];
	$plv = $words[$i]['palavra'];
	$qdr = $words[$i]['quadrante'];
	$sqd = $words[$i]['subquad'];
	if(!key_exists($rad,$word)){
		$word[$rad] = array();
		$j = 0;
	}
	$word[$rad][$j] = array('cod'=>$cod,'plv'=>$plv,'qdr'=>$qdr,'sqd'=>$sqd);
	$j++;
}

$advs = db_busca("select * from raf_adverbios");			//inicializa��o do array de adverbios
for($i=0;$i<count($advs);$i++){
	static $adv = array();
	$t = $advs[$i]['adverbio'];
	$adv[$t] = array($advs[$i]['codigo'], $advs[$i]['tipo'], $advs[$i]['opera']);
}

$emots = db_busca("select * from raf_emoticons");			//inicializa��o do array de adverbios
$emot = array();
$links = array();
for($i=0;$i<count($emots);$i++){
	$t = $emots[$i]['emoticon'];
	$emot[$t] = array($emots[$i]['cod'], $emots[$i]['quadrante'],$emots[$i]['subquadr']);
	$links[$emots[$i]['cod']] = array(
					'emot' => $emots[$i]['emoticon'],
					'quad' => $emots[$i]['quadrante'],
					'subquad' => $emots[$i]['subquadr']
	);
}
unset($emots);

$plural = 	array(	array("ns",1,"m"),
						array("�es",3,"�o"),
						array("�es",1,"�o",array("m�es","mam�es")),
						array("ais",1,"al",array("cais","mais","pais","papais")),
						array("�is",2,"el"),
						array("eis",2,"el"),
						array("�is",2,"ol"),
						array("les",3,"l"),
						array("res",3,"r",array("pires")),
						array("ses",1,"s"),
						array("is",2,"il",array("l�pis","cais","mais","cr�cis","biqu�nis","pois","depois","dois","leis","pais","papais")),
						array("s",2,"",array("ali�s","pois","caos","pires","l�pis","cais","mais","mas","menos","f�rias","fezes","p�sames","cr�cis","g�s","atr�s","mois�s","atrav�s","conv�s","�s","pa�s","ap�s","ambas","ambos","messias")),
					);
					
$feminino = array(	array("ona",3,"�o",array("abandona","lona","iona","cortisona","mon�tona","maratona","acetona","detona","carona")),
						array("ora",3,"or"),
						array("na",4,"no",array("carona","abandona","lona","iona","cortisona","mon�tona","maratona","acetona","detona","guiana","campana","grana","caravana","banana","paisana")),
						array("inha",3,"inho",array("rainha","linha","minha")),
						array("esa",3,"�s",array("mesa","obesa","princesa","turquesa","ilesa","pesa","presa")),
						array("osa",3,"oso",array("mucosa","prosa")),
						array("�aca",3,"�aco"),
						array("ica",3,"ico",array("dica")),
						array("ada",2,"ado",array("pitada")),
						array("ida",3,"ido",array("vida")),
						array("�da",3,"ido",array("reca�da","sa�da","d�vida")),
						array("ima",3,"imo",array("v�tima")),
						array("iva",3,"ivo",array("saliva","oliva")),
						array("eira",3,"eiro",array("beira","cadeira","frigideira","bandeira","feira","capoeira","barreira","fronteira","besteira","poeira")),
						array("�ria",4,"�rio"),
						array("�",2,"�o",array("amanh�","arapu�","f�","div�"))
					);

$adverbio = array(	array("mente",4,"",array("experimente","argumente"))
					);

$aumentativo = array(	array("d�ssimo",5,""),
						array("abil�ssimo",5,""),
						array("�ssimo",3,""),
						array("�simo",3,""),
						array("�rrimo",4,""),
						array("zinho",2,""),
						array("quinho",4,"c"),
						array("uinho",4,""),
						array("adinho",3,""),
						array("inho",3,"",array("caminho","cominho")),
						array("alh�o",4,""),
						array("u�a",4,""),
						array("a�o",4,"",array("antebra�o")),
						array("a�a",4,""),
						array("ad�o",4,""),
						array("id�o",4,""),
						array("�zio",3,"",array("top�zio")),
						array("arraz",4,""),
						array("zarr�o",3,""),
						array("arr�o",4),
						array("arra",3,""),
						array("z�o",2,"",array("coaliz�o")),
						array("�o",3,"",array("camar�o","chimarr�o","can��o","cora��o","embri�o","grot�o","glut�o",
									   "fic��o","fog�o","fei��o","furac�o","gam�o","lampi�o","le�o","macac�o","na��o",
									   "�rf�o","org�o","patr�o","port�o","quinh�o","rinc�o","tra��o",
									   "falc�o","espi�o","mam�o","foli�o","cord�o","aptid�o","campe�o",
									   "colch�o","lim�o","leil�o","mel�o","bar�o","milh�o","bilh�o","fus�o",
									   "crist�o","ilus�o","capit�o","esta��o","sen�o"))
					);

$pronome = array(
	array("encialista",4,""),
	array("alista",5,""),
	array("agem",3,"",array("coragem","chantagem","vantagem","carruagem")),
	array("iamento",4,""),
	array("amento",3,"",array("firmamento","fundamento","departamento")),
	array("imento",3,""),
	array("mento",6,"",array("firmamento","elemento","complemento","instrumento","departamento")),
	array("alizado",4,""),
	array("atizado",4,""),
	array("tizado",4,"",array("alfabetizado")),
	array("izado",5,"",array("organizado","pulverizado")),
	array("ativo",4,"",array("pejorativo","relativo")),
	array("tivo",4,"",array("relativo")),
	array("ivo",4,"",array("passivo","possessivo","pejorativo","positivo")),
	array("ado",2,"",array("grado")),
	array("ido",3,"",array("c�ndido","consolido","r�pido","decido","t�mido","duvido","marido")),
	array("ador",3,""),
	array("edor",3,""),
	array("idor",4,"",array("ouvidor")),
	array("dor",4,"",array("ouvidor")),
	array("sor",4,"",array("assessor")),
	array("atoria",5,""),
	array("tor",3,"",array("benfeitor","leitor","editor","pastor","produtor","promotor","consultor")),
	array("or",2,"",array("motor","melhor","redor","rigor","sensor","tambor","tumor","assessor","benfeitor","pastor","terior","favor","autor")),
	array("abilidade",5,""),
	array("icionista",4,""),
	array("cionista",5,""),
	array("ionista",5,""),
	array("ionar",5,""),
	array("ional",4,""),
	array("�ncia",3,""),
	array("�ncia",4,"",array("ambul�ncia")),
	array("edouro",3,""),
	array("queiro",3,"c"),
	array("adeiro",4,"",array("desfiladeiro")),
	array("eiro",3,"",array("desfiladeiro","pioneiro","mosteiro")),
	array("uoso",3,""),
	array("oso",3,"",array("precioso")),
	array("aliza�",5,""),
	array("atiza�",5,""),
	array("tiza�",5,""),
	array("iza�",5,"",array("organiza�")),
	array("a�",3,"",array("equa�","rela�")),
	array("i�",3,"",array("elei��o")),
	array("�rio",3,"",array("volunt�rio","sal�rio","anivers�rio","di�rio","lion�rio","arm�rio")),
	array("at�rio",3,""),
	array("rio",5,"",array("volunt�rio","sal�rio","anivers�rio","di�rio","compuls�rio","lion�rio","pr�prio","st�rio","arm�rio")),
	array("�rio",6,""),
	array("�s",4,""),
	array("eza",3,""),
	array("ez",4,""),
	array("esco",4,""),
	array("ante",2,"",array("gigante","elefante","adiante","possante","instante","restaurante")),
	array("�stico",4,"",array("eclesi�stico")),
	array("al�stico",3,""),
	array("�utico",4,""),
	array("�utico",4,""),
	array("tico",3,"",array("pol�tico","eclesi�stico","diagnostico","pr�tico","dom�stico","diagn�stico","id�ntico","alop�tico","art�stico","aut�ntico","ecl�tico","cr�tico","critico")),
	array("ico",4,"",array("tico","p�blico","explico")),
	array("ividade",5,""),
	array("idade",4,"",array("autoridade","comunidade")),
	array("oria",4,"",array("categoria")),
	array("encial",5,""),
	array("ista",4,""),
	array("auta",5,""),
	array("quice",4,"c"),
	array("ice",4,"",array("c�mplice")),
	array("�aco",3,""),
	array("ente",4,"",array("freq�ente","alimente","acrescente","permanente","oriente","aparente")),
	array("ense",5,""),
	array("inal",3,""),
	array("ano",4,""),
	array("�vel",2,"",array("af�vel","razo�vel","pot�vel","vulner�vel")),
	array("�vel",3,"",array("poss�vel")),
	array("vel",5,"",array("poss�vel","vulner�vel","sol�vel")),
	array("bil",3,"vel"),
	array("ura",4,"",array("imatura","acupuntura","costura")),
	array("ural",4,""),
	array("ual",3,"",array("bissexual","virtual","visual","pontual")),
	array("ial",3,""),
	array("al",4,"",array("afinal","animal","estatal","bissexual","desleal","fiscal","formal","pessoal","liberal","postal","virtual","visual","pontual","sideral","sucursal")),
	array("alismo",4,""),
	array("ivismo",4,""),
	array("ismo",3,"",array("cinismo")));

$verbo = array( 
	array("ar�amo",2),array("�ssemo",2),array("er�amo",2),
	array("�ssemo",2),array("ir�amo",3),array("�ssemo",3),
	array("�ramo",2),array("�rei",2),array("aremo",2),array("ariam",2),
	array("ar�ei",2),array("�ssei",2),array("assem",2),array("�vamo",2),
	array("�ramo",3),array("eremo",3),array("eriam",3),array("er�ei",3),
	array("�ssei",3),array("essem",3),array("�ramo",3),array("iremo",3),
	array("iriam",3),array("ir�ei",3),array("�ssei",3),array("issem",3),
	array("ando",2),array("endo",3),array("indo",3),array("ondo",3),
	array("aram",2),array("ar�o",2),array("arde",2),array("arei",2),array("arem",2),array("aria",2),
	array("armo",2),array("asse",2),array("aste",2),array("avam",2,"",array("agravam")),
	array("�vei",2),array("eram",3),array("er�o",3),array("erde",3),
	array("erei",3),array("�rei",3),array("erem",3),array("eria",3),
	array("ermo",3),array("esse",3),array("este",3,"",array("faroeste","agreste")),array("�amo",3),
	array("iram",3),array("�ram",3),array("ir�o",2),array("irde",2),
	array("irei",3,"",array("admirei")),array("irem",3,"",array("adquirem")),array("iria",3),array("irmo",3),
	array("isse",3),array("iste",4),array("iava",4,"",array("ampliava")),array("amo",2),array("iona",3),
	array("ara",2,"",array("arara","prepara")),array("ar�",2,"",array("alvar�")),array("are",2,"",array("prepare")),
	array("ava",2,"",array("agrava")),array("emo",2),array("era",3,"",array("acelera","espera")),array("er�",3),array("ere",3,"",array("espere")),
	array("iam",3,"",array("enfiam","ampliam","elogiam","ensaiam")),array("�ei",3),
	array("imo",3,"",array("reprimo","intimo","�ntimo","nimo","queimo","ximo")),
	array("ira",3,"",array("fronteira","s�tira")),array("�do",3),array("ir�"),
	array("tizar",4,"",array("alfabetizar")),array("izar",5,"",array("organizar")), array("itar",5,"",array("acreditar","explicitar","estreitar")),
	array("ire",3,"",array("adquire")),array("omo",3),array("ai",2),array("am",2),array("ear",4,"",array("alardear","nuclear")),
	array("ar",2,"",array("azar","bazaar","patamar")),array("uei",3),array("u�a",5,"u"),
	array("ei",3),array("guem",3,"g"),array("em",2,"",array("alem","virgem")),array("er",2,"",array("�ter","pier")),array("eu",3,"",array("chapeu")),
	array("ia",3,"",array("est�ria","fatia","acia","praia","elogia","mania","l�bia","aprecia","pol�cia","arredia","cheia","�sia")),
	array("ir",3,"",array("freir")),array("iu",3),array("eou",5),array("ou",3),array("i",3));

$vogal = array( 
	array("bil",2,"vel"),
	array("gue",2,"g",array("gangue","jegue")),
	array("�",3), 
	array("�",3,"",array("beb�")),
	array("a",3,"",array("�sia")),
	array("e",3),
	array("o",3,"",array("�o")));

$acentos = array(	"�" => "a", "�" => "a", "�" => "a", "�" => "e", "�" => "e", "�" => "i",
					"�" => "o",	"�" => "o", "�" => "o", "�" => "u", "�" => "u", "�" => "c");
?>
<?php	/*		fun��es diversas		*/
function montaanchor($nome,$valor){
	global $atual;
	global $args;
	$link = "";
	$link .= $atual."?";
	if(count($args>0)){
		foreach($args as $gg)
			$link .= $gg[0]."=".$gg[1]."&";
	}
	$link .= $nome."=".$valor;
	echo "<a href='$link'>$nome=$valor</a>\t<!br />\n"; 
}
function _round($v,$nc){
	return (int) (round($v*pow(10,$nc)))/pow(10,$nc);
}
function alert($str){
echo "<script>alert(\"".$str."\");</script>";
}
function lower($plv){
	return mb_strtolower($plv);
}
function upper($plv){
	return mb_strtoupper($plv);
}
function testam($plv){
	return ($plv === mb_strtoupper($plv))? 1:0;
}
function _reduce($l_word, $array){
	$lgt_word = strlen($l_word);
	$continua = true;			//flag -> recebe false no momento q a palavra for 'despluralizada'. acaba o processo "plural" na palavra
	$j = 0;		$max = count($array);
	while($j<$max && $continua){
		$cur = $array[$j];									//c�pia do array de regras
		$lgt_regra = strlen($cur[0]);
		$f_word = substr($l_word,-$lgt_regra);				//final da palavra -> fazer um comparativo de regra, pra ver se precisa 'tentar despluralizar' ou n�o
		$dif_lgt = $lgt_word - $lgt_regra;					//diferen�a de tamanho das strings

		if(($f_word == $cur[0]) && ($dif_lgt >= $cur[1])){
			$able = false;									//come�a n�o podendo fazer transforma��o; s� pode ser feita se n�o estiver na lista de exce��es, caso exista
			
			if(!is_array($cur[3])){	
				$able = true;
			}
			elseif(!in_array($l_word,$cur[3])){
				$able = true;
			}
			
			if($able){
				$l_word = substr($l_word,0,$dif_lgt).$cur[2];
				$continua = false;
			}
		}
		$j++;
	}
	return $l_word;
}
function _acentua($word){
	global $acentos;
	for($j=0;$j<strlen($word);$j++){
		$key = $word[$j];
		if(array_key_exists($key,$acentos)){
			$word[$j] = $acentos[$key];
		}
	}
	return $word;
}
function _desacentua($word){
	$enc = 'UTF-8';
	$acentos = array(
		'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
		'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
		'C' => '/&Ccedil;/',
		'c' => '/&ccedil;/',
		'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
		'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
		'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
		'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
		'N' => '/&Ntilde;/',
		'n' => '/&ntilde;/',
		'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
		'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
		'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
		'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
		'Y' => '/&Yacute;/',
		'y' => '/&yacute;|&yuml;/',
		'a.' => '/&ordf;/',
		'o.' => '/&ordm;/'
	);
    $word = preg_replace($acentos, array_keys($acentos), htmlentities($word,ENT_NOQUOTES, $enc));
	return $word;
}
function _minimiza($word){
	$word = strtolower(_desacentua($word));
	return $word;
}
function reduce($string){					
	global $plural, $feminino, $adverbio, $aumentativo, $pronome, $verbo, $vogal;


	$_plural	= array("s");
	$_feminino	= array("a","�");
	$usando = mb_strtolower($string);
/*	plural		*/
	$lgt = strlen($usando);
	if(in_array($usando[$lgt-1],$_plural)){
		$usando = _reduce($usando,$plural);
	}

/*	adv�rbio	*/
	$usando = _reduce($usando,$adverbio);

/*	feminino	*/
	$lgt = strlen($usando);
	if(in_array($usando[$lgt-1],$_feminino)){
		$usando = _reduce($usando,$feminino);
	}

/*	aumentativo	*/
	$usando = _reduce($usando,$aumentativo);
	
	$temp = $usando;	//variavel auxiliar para compara��o ap�s o pr�ximo passo
/*	nominal		*/
	$usando = _reduce($usando,$pronome);

	if($usando == $temp){
		$temp = $usando;	//variavel auxiliar para compara��o ap�s o pr�ximo passo
		$usando = _reduce($usando,$verbo);
		
		if($usando == $temp){
			$usando = _reduce($usando,$vogal);
		}
	}

	$usando = _acentua($usando);
	
	return $usando;
}
function cataword($radical,$words){
	global $word;
	$flag = false;	$i = 0;	$fim = count($word[$radical]);
	while(!$flag && ($i < $fim)){
		if($word[$radical][$i]['plv'] == $words){
			$flag = true;
		}
		else{
			$i++;
		}	
	}
	if(!$flag){
		$saida = array($word[$radical][0]['qdr'],$word[$radical][0]['sqd']);
	}
	else{
		$saida = array($word[$radical][$i]['qdr'],$word[$radical][$i]['qdr']);
	}
	return $saida;
}
function in_adv($str){
	global $adv;
	$defou = false;
	if(key_exists($str,$adv)){
		$defou = true;
	}
	return $defou;
}
function in_abrev($str){
	global $abrev;
	$string = lower($str);
	if(array_key_exists($string,$abrev)){
		return true;
	}	elseif(is_numeric(substr($str,strlen($str)-1))){
			return true;
		}
			else{
				return false;
			}

}
function chrt($str,$ini){
	return substr($str,$ini,1);
}
function ini_fim($str,$pos){
//marcadores de inicio e fim de mensagem
if(strlen($str) > 22){
	global $pp,$datas,$nomes;
	$chs = Array( "(", ")", "-", ":");
	//$num = Array( substr($str,-20,4), substr($str,-15,2), substr($str,-12,2),
	//			substr($str,-9,2), substr($str,-6,2), substr($str,-3,2));
	//$smb = Array( chrt($str,-22), chrt($str,-17), chrt($str,-14),
	//			chrt($str,-8), chrt($str,-5), chrt($str,-2));
	if(strpos($str,"(") && strpos($str,")")){
		$fs = true;	//flag symbol
		$fn = true;	//flag number
		$flag = true;
		$data = strrchr($str,"(");
			$data = substr($data,1,strpos($data,")")-1);
		$smb = Array(chrt($data,4), chrt($data,7), chrt($data,10),
						chrt($data,13), chrt($data,16));
		$def = Array("-","-"," ",":",":");
		$num = Array(substr($data,0,4),substr($data,5,2),substr($data,8,2),
						substr($data,11,2),substr($data,14,2),substr($data,17,2));
		
		for($i=0;$i<5;$i++){
			if($smb[$i] != $def[$i]){
				$fs = false;
			}
		}
		for($i=0;$i<6;$i++){
			if(!is_numeric($num[$i])){
				$fn = false;
			}
		}
	}
	
	if($fs && $fn){
		$nomes[] = nome($str);
		$datas[] = $data;
		$pp[] = $pos;
	}
}
}
function nome($strn){
	$lgt = strpos($strn,strrchr($strn,"(")) - 1;
	$subs = ucwords(strtolower(substr($strn,0,$lgt)));
	return $subs;
}
?>
<?php	/*			classes				*/
class Ppost{
	public $autor;			//autor
	public $data;			//data
	public $frases;			//array de frases relativas ao post
	public $msg;			//corpo da mensagem do post [imutavel]
	public $mudada;			//corpo da mensagem com mudancas
	public $def;			//valor inicial do peso do radical
	public $limite;			//numero de adverbios significativos antes do radical
	public $predomina;		//array de estados-> consta o predominante em cada frase/post
	public $soma_fr;		//contabiliza os quadrantes e subquadrantes[radicais e quantidades]
	public $periodo;		//periodo escolhido
	public $numero;			//cardinalidade
	
	//funcao de inicializacao da classe
	public function inicia($autor,$data,$msg,$indices){		//indices: array (periodo, numero)
		if(strpos($data,":")!==0){
			$vs = array(4,7,10,13,16);
				$sep = "";
				for($_i=0;$_i<strlen($data);$_i++)	if(!in_array($_i,$vs)){		$sep .=	substr($data,$_i,1);	}
		}
		else	$sep = $data;
		
		$this->data = $sep;					//hora- YYYYMMDDHHmmSS
		$this->periodo = $indices[0];
		$this->numero = $indices[1];
		$this->autor = $autor;
		$this->msg = $msg;
		$this->mudada = array();
		$this->def = 3;
		$this->limite = 3;
		$this->frases = array();
		$this->predomina = array();
		$this->soma_fr = array(	0=>0, 
								1 => array(0=>0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 'qtd'=>0),
								2 => array(0=>0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 'qtd'=>0),
								3 => array(0=>0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 'qtd'=>0),
								4 => array(0=>0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 'qtd'=>0),
								'qtd' => 0);
		$this->separa();
		return 0;
	}

	//retira 'quebras de linha' da mensagem original;	faz backup com esse dado;	array 'frases' recebe as frases do post, ja separadas
	public function separa(){
		global $emot;
		$temp = str_replace(array("...","\n","\t","\r","<br>","<br/>","<br />","  ")," ",$this->msg);
		
		$temp = explode(" ",$temp);
		for($i=0;$i<count($temp);$i++){
			if(!array_key_exists($temp[$i],$emot)){
				$this->mudada[$i] = $this->testa($temp[$i]);
			}
			else{
				$this->mudada[$i] = "&|".$emot[$temp[$i]][0]."|&";
			}
		}
		
		$this->mudada = implode(" ",$this->mudada);
		$this->limpa();
		$this->calcula();
		return 0;
	}
	
	//funcao para tirar as pontuacoes do texto e "arrumar" as abreviacoes -> palavra a palavra
	public function testa($str){
		global $abrev,$emot;
		$buf = "";
		$pont = array(".","!","?");
		$pause = array("/","*","-","+",",",":","[","]","{","}","#","|",";","(",")","\"","'");
		$final = array_merge($pont,$pause);
		//limpa inicio da string
		while(in_array(substr($str,0,1),$pause) && !in_array($str,$emot)){
			$str = substr($str,1);
		}
		//verifica pontuacoes no final da string
		while(in_array(substr($str,strlen($str)-1,1),$final) && !in_array($str,$emot)){
			$b = substr($str,strlen($str)-1,1);
			$buf = in_array($b,$pause)? $buf:$b.$buf;
			$str = substr($str,0,strlen($str)-1);
		}

		switch(strlen($buf)){
			case 0:		$str = "$str ";
						break;
			case 1: 	if($buf == "."){
							if(in_abrev($str)){
								$ind = lower($str);
								$str = key_exists($ind,$abrev)? $abrev[$ind]." ":$str." ";
							}	else{	
									$str = "$str<br />";
								}
						}	else{
								$str = "$str<br />";
							}
						break;
			default:	if(in_abrev($str)){
								$ind = lower($str);
								$str = key_exists($ind,$abrev)? $abrev[$ind]."<br />":"$str<br />";
							}	else{
									$str = "$str<br />";
								}
							
						break;
		}
		return $str;
	}

	//tira as stopwords[palavras sem significado relevante] e indexa cada palavra por posicao dela na frase
	public function limpa(){
		global $sws,$word, $adv, $emot, $links;
		$temp2 = explode("<br />",$this->mudada);
		$temp = array();
		for($i=0;$i<count($temp2);$i++){
			if($temp2[$i]!="  " && $temp2[$i]!=" " && $temp2[$i]!="" && $temp2[$i]!="\n" && $temp2[$i]!="\r" && $temp2[$i]!="\n\r" && $temp2[$i]!="\r\n")
				$temp[] = $temp2[$i];
		}
		unset($temp2);
		
		for($i=0;$i<count($temp);$i++){
			//--------	limpa os espacos em branco na frase	------------------
			$t33 = explode(" ",$temp[$i]);		$t2 = array();
			for($k=0;$k<count($t33);$k++){
				if($t33[$k] != "")
					$t2[] = $t33[$k];
			}
			//--------------------------
				$ar = array();
					$ar[0] = $temp[$i];

			for($j=0;$j<count($t2);$j++){
				$word_tmp = lower($t2[$j]);
				if(!in_array($word_tmp,$sws) && !is_numeric($word_tmp)){		// "NULL" se sws
					if(strpos($word_tmp,"-")!==false){
						$word_tmp = substr($word_tmp,0,strpos($word_tmp,"-"));
						$t2[$j] = substr($t2[$j],0,strlen($word_tmp));
					}
					
					$flag = 0;
					if(in_adv($word_tmp)){
						$flag = 1;
					}
					elseif(key_exists(reduce($word_tmp),$word)){
						$rad = reduce($word_tmp);
						$flag = 2;
					}
					elseif($this->flag($word_tmp) != false){
						$word_tmp = $links[$this->unflag($word_tmp)]['emot'];
						$flag = 3;
					}

					switch($flag){
						case 1:		$ar[1][$j] = array('word'=>$word_tmp,'opera'=>$adv[$word_tmp][2],'maiusc'=>testam($t2[$j]));
									break;
						case 2:		$ar[2][$j] = array('word'=>$word_tmp,'conf'=>cataword($rad,$word_tmp),'maiusc'=>testam($t2[$j]));
									break;
						case 3:		$ar[3][$j] = array('word'=>$word_tmp,'conf'=>array(0=>$emot[$word_tmp][1], 1=>$emot[$word_tmp][2]));
									break;
						default:	break;
					}
				}
			}
			$this->frases[] = $ar;
			unset($ar);
		}
	}

	public function calcula(){
		global $emot;
		for($i=0;$i<count($this->frases);$i++){
			$atemp = $this->frases[$i][1];
			$rtemp = $this->frases[$i][2];
			$etemp = $this->frases[$i][3];

			if(count($etemp)>0){
				foreach($etemp as $j=>$eetemp){
					$tquad = $eetemp['conf'][0];			//temporario que guarda o quadrante do respectivo emoticon
					$tsqd = $eetemp['conf'][1];
					$this->soma_fr[$tquad][$tsqd] += $this->def;
					$this->soma_fr[$tquad][0]+= $this->def;
					$this->soma_fr[0]+= $this->def;
					$this->soma_fr['qtd']++;
					$this->soma_fr[$tquad]['qtd']++;
				}
			}
			
			if(count($rtemp)>0){
				$ar_r = array();		foreach($rtemp as $j=>$r){		$ar_r[] = $j;	}	//indices de radicais
				if(count($atemp)>0){
				$ar_a = array();		foreach($atemp as $j=>$a){		$ar_a[] = $j;	}	//indices de adverbios
				}
				for($j=0;$j<count($ar_r);$j++){
					$tquad = $rtemp[$ar_r[$j]]['conf'][0];
					$tsqd = $rtemp[$ar_r[$j]]['conf'][1];
					$tword = $rtemp[$ar_r[$j]]['word'];
					$tmais = $rtemp[$ar_r[$j]]['maiusc'];

					
					$ini = $ar_r[$j]-3;
					$fim = $ar_r[$j];
					if(isset($ar_r[$j-1])){
						if(($ar_r[$j]-$ar_r[$j-1])<3){
							$ini = $ar_r[$j-1]+1;
						}
					}

					$oop = "";								//processo de leitura das operacoes com cada adverbio
					for($l=$ini;$l<$fim;$l++){
						if(count($ar_a)>0)
							if(in_array($l,$ar_a))
								$oop .= $atemp[$l]['opera'];
					}
					if($oop==""){	$oop = "0";	}
					/*	simplificacao de operacoes		*/
					$oop = str_replace("11","",$oop);	$oop = str_replace("22","2",$oop);
					$oop = str_replace("33","3",$oop);	$oop = str_replace("32","2",$oop);
					$oop = str_replace("23","3",$oop);	$oop = str_replace("12","3",$oop);
					$oop = str_replace("13","2",$oop);	$oop = str_replace("11","",$oop);
					$oop = str_replace("22","2",$oop);	$oop = str_replace("33","3",$oop);
					$oop = str_replace("32","2",$oop);	$oop = str_replace("23","3",$oop);
					$oop = str_replace("12","3",$oop);	$oop = str_replace("13","2",$oop);
					/*----------------------------------*/
					//0-nada; 1-inverte quadrante; 2-intensidade soma 2; 3-intensidade subtrai 2
					
					$soma = $this->def + $tmais;
					switch($oop){
						case 1:		if($tquad<3)	$tquad = 3 - $tquad;
										else			$tquad = 7 - $tquad;
									break;
						case 2:		$soma += 2;
									break;
						case 3:		$soma -= 2;
									break;
						case 21:	$soma += 2;
									if($tquad<3)	$tquad = 3 - $tquad;
									else			$tquad = 7 - $tquad;
									break;
						case 31:	$soma -= 2;
									if($tquad<3)	$tquad = 3 - $tquad;
									else			$tquad = 7 - $tquad;
									break;
						default:	break;
					}
					$soma = ($soma>5)?	5:$soma;
					$soma = ($soma<1)?	1:$soma;
					$this->soma_fr[$tquad][$tsqd] += $soma;
					$this->soma_fr[$tquad][0] += $soma;
					$this->soma_fr[0] += $soma;
					$this->soma_fr['qtd']++;
					$this->soma_fr[$tquad]['qtd']++;
				}
				
			}
		
		if(isset($this->frases[$i][3]))
				$this->frases[$i][0] = $this->unemot($this->frases[$i][0]);
		}
		unset($rtemp);				unset($atemp);
		$this->calculo_demais();
	}

	public function calculo_demais(){
		$arr = array(	$this->soma_fr[1][0],		//$arr tem os valores das somas totais de todos
						$this->soma_fr[2][0],		//	os quadrantes
						$this->soma_fr[3][0],
						$this->soma_fr[4][0],
					);
		$max = max($arr);
		$ctr = array(0);		//[0]->conta quantos registros == $max tem; [1](...[4])->quadrantes(ordem decrescente)
		$ret = array();			//[0]->intensidade;[1]->quadrante;[2]->subquadrante;[3]->qtd. termos significativos
		for($i=count($arr)-1;$i>=0;$i--){
			if($arr[$i] == $max){
				$ctr[0]++;
				$ctr[] = $i+1;
			}
		}
		switch($ctr[0]){//teste para quantidades de "registros de maximo"
			case 1:		//apenas um resultado. eh o que fica
						$ret[0] = $max;
						$ret[1] = $ctr[1];
						break;
			case 2:		//1 2 - alto controle; 3 4 - baixo controle; se os 2 alto ou baixo 
						//	controle, inconclusivo; senao, prevalece o de alto controle
						if($ctr[1]!=2 || $ctr[2]!=3){
							$ret[0] = $max;
							$ret[1] = $ctr[2];
						}
						else{
							$ret[0] = 0;
							$ret[1] = 0;
						}
						break;
			case 3:		$ret[0] = $max;
						switch($ctr[2]){
							case 2:		$ret[1] = $ctr[1];
										break;
							case 3:		$ret[1] = $ctr[3];
										break;
							default:	$ret[0] = 0;	$ret[1] = 0;
										break;
						}
						break;
			case 4:		
			default:	$ret[0] =  0;		$ret[1] =  0;		//soh eh conclusivo com 1-3 argumentos
						break;
		}
		switch($ret[1]){//define as quantidade de radicais significativos
			case 1:		
			case 2:
			case 3:
			case 4:		$ret[2] = -1;		//valor aleatorio para arrumar a variavel depois
						$ret[3] = $this->soma_fr[$ret[1]]['qtd'];
						$ret[4] = $this->soma_fr['qtd'];
						break;
			default:	$ret[2] = 0;		$ret[3] = 0;		$ret[4] = 0;
						break;
		}
		if($ret[2]<0){
			$ini = -1;
			for($i=4;$i>0;$i--){
				if($this->soma_fr[$ret[1]][$i] > $ini){
					$ini = $this->soma_fr[$ret[1]][$i];
					$ret[2] = $i;
				}
			}
		}
		
		$this->predomina['total_int'] = $ret[0];		//somatorio total de intensidades
		$this->predomina['quad'] = $ret[1];				//indicador de quadrante predominante
		$this->predomina['subquad'] = $ret[2];			//indicador de subquadrante predominante
		$this->predomina['sig_quad'] = $ret[3];			//quantidade rad. significativos no quad predominante
		$this->predomina['sig_total'] = $ret[4];		//quantidade rad. significativos do post
	}

	public function flag($term){
		$flagini = "&|";	$lgini = strlen($flagini);
		$flagfim = "|&";	$lgfim = strlen($flagfim);
		$v1 = (substr($term,-$lgfim) == $flagfim);
		$v2 = (substr($term,0,$lgini) == $flagini);
		if($v1&&$v2){
			$term = substr($term,$lgini,strlen($term)-($lgfim+$lgini));
			$v3 = is_numeric($term);
		}
		return $v3? $term:false;
	}
	
	public function unflag($term){
		$flagini = "&|";	$lgini = strlen($flagini);
		$flagfim = "|&";	$lgfim = strlen($flagfim);
		$term = substr($term,$lgini,strlen($term)-($lgfim+$lgini));
		return $term;
	}
	
	public function unemot($term){
		global $links;
		while(strpos($term,"&|")!==false){
			$in = strpos($term,"&|");
			$fim = strpos($term,"|&") + 2;
			$cod = $this->unflag(substr($term,$in,$fim-$in));
			$part = $links[$cod]['emot'];
			$term = str_replace("&|$cod|&",$part,$term);
		}
		return $term;
	}
}

class FForum{
	public $codMensagem;
	public $codUsuario;
	public $codTopico;
	public $mensagem;
	public $hora;
	public $citou;
	public $ncitadas;
	public $profCitacao;

	function inicia($cmsg,$cuser,$ctpc,$msg,$hr,$cit,$ncit,$pcit){
		$this->codMensagem = $cmsg;
		$this->codUsuario = $cuser;
		$this->codTopico = $ctpc;
		$this->mensagem = $msg;
		$this->hora = $hr;
		$this->citou = $cit;
		$this->ncitadas = $ncit;
		$this->profCitacao = $pcit;
	}

}

class Pontos{
	public $c;
	public $extra;
	public $tam;
	public $dados = array();
	public $extam = 7;	//tamanho extra lateral em cada 'quadradinho'
	public $periodo;
	
	function dothehand($tamanho, $componentes, $periodo){
		$this->periodo = $periodo;
		$this->tam = $tamanho;
		$this->extra = $tamanho/10;
		$this->c['x'] = $this->tam/2 + $this->extra + $this->extam/2;
		$this->c['y'] = $this->tam/2 + $this->extra + $this->extam/2;

		for($i=count($componentes);$i>0;$i--)
			$this->dados[$i] = $componentes[$i-1];
		$this->dados = $this->agrupa($this->dados);

		for($q=1;$q<=4;$q++){
			for($s=1;$s<=4;$s++){
				for($int=.5;$int<=5;$int+=.5){	//intensidade, n�o inteiro
					$this->div($this->tam/4*$q,$this->tam/4*$s,$this->dados[$s][$q]['msg']);
				}
			}
		}
	}

	function div($px, $py, $msg){
		$posx = $px - $this->extam;
		$posy = $py - $this->extam;
		echo	"<div style='width:{$this->extam}px; height:{$this->extam}px; ".
				"border:solid 2px red; position:absolute; top:".($posx)."px; left:".($posy)."px;' onmouseover='alert(\"{$msg}\");'></div>";
	}

	function agrupa($dados){
		$ret = array();
		foreach($dados as $key=>$data){
			if(	isset(	$ret[$data['quad']][$data['subquad']]	))
				$ret[$data['quad']][$data['subquad']][$data['int']] = array();
			
			$ret[$data['quad']][$data['subquad']][$data['int']][] = $key;
		}

		for($q=1;$q<=4;$q++){
			for($s=1;$s<=4;$s++){
				foreach($ret[$q][$s] as $int=>$pers){
					$frase = "Quad. $q, Subquad. $s, Intensidade $int : {$this->periodo} {$pers[0]}";
					for($i=1;$i<count($pers);$i++)
						$frase .= " , {$this->periodo} {$pers[$i]}";
					$ret[$q][$s][$int]['msg'] = $frase;
				}
			}
		}

		return $ret;
	}

	function sqq($i,$j){
		if($i%2==0)
			return 20-4*$i-(5-$j);
		else
			return 20-4*$i-$j;
	}
	
}
?>
<?php	/*		fun��es diversas(2)		*/
function round4($a){
	$casas = 2;
	$int = floor($a);
	$dec = $a-$int;
	if($dec>=0.25 && $dec<0.75)
			$dec = 0.5;
		elseif($dec<0.25)
				$dec = 0;
			else{
					$dec = 0;
					$int++;
				}
	return ($int+$dec);
}

function week($data){
	if(strlen($data)==14)
		return date("W",mktime(		substr($data,8,2),
									substr($data,10,2),
									substr($data,12,2),
									substr($data,4,2),
									substr($data,6,2),
									substr($data,0,4)
								)
				);
	elseif(strlen($data)==19)
		return date("W",mktime(		substr($data,11,2),
									substr($data,14,2),
									substr($data,17,2),
									substr($data,5,2),
									substr($data,8,2),
									substr($data,0,4)
								)
				);
	else
		return 0;
}

function com_aula($data,$dataini,$datafim){				//	gambiarras
	$data = 	substr($data,0,4).substr($data,5,2).substr($data,8,2).
				substr($data,11,2).substr($data,14,2).substr($data,17,2);

	return /*(($data>$dataini) && ($data<$datafim))*/true;
}

function filtrar($horario,$periodo){
	$dias = array(substr($horario,8,2), substr($horario,5,2), substr($horario,0,4));
	$horas = array(substr($horario,11,2),substr($horario,14,2),substr($horario,17,2));
	switch($periodo){
		case "semana":
						$ret = array(	week($horario),
										$dias[2]);
						break;
		case "mes":
						$ret = array(	$dias[1],
										$dias[2]);	
						break;
		case "semestre":
							$sem = ($dias[1]>7)?	2:1;
						$ret = array(	$sem,
										$dias[2]);
						break;
		default:		$ret = $periodo;
						break;
	}
	return $ret;
}

function resume($ar){
	$arr = array(1=>0, 2=>0, 3=>0, 4=>0);
	$qtd = array(1=>array(0,0,0,0,0), 2=>array(0,0,0,0,0), 3=>array(0,0,0,0,0), 4=>array(0,0,0,0,0));
	foreach($ar as $s){		// inicializador de quantidades
		$arr[$s['quad']]				+=	$s['total_int'];
		$qtd[$s['quad']][0]				+=	$s['sig_quad'];
		$qtd[$s['quad']][$s['subquad']]	+=	$s['sig_quad'];
	}

	$max = max($arr);
	$ctr = array_keys($arr,$max);

	switch(count($ctr)){	//	switch de quadrante
		case 1:
					$quad = $ctr[0];
					break;
		case 2:
					if($ctr[0]!=2 || $ctr[1]!=3){
						$quad = $ctr[1];
					}
					else{
						$max = 0;	$quad = 0;	$qtd[0][0] = 1;
					}
					break;
		case 3:		
					switch($ctr[1]){
						case 2:		$quad = $ctr[0];
									break;
						case 3:		$quad = $ctr[2];
									break;
						default:	$max = 0;	$quad = 0;	$qtd[0][0] = 1;
									break;
					}
					break;
		case 4:		
		default:	$max =  0;		$quad =  0;	$qtd[0][0] = 1;
					break;
	}
	if($quad!==0){
		$vini = -1;
		for($i=4;$i>=1;$i--){
			if($qtd[$quad][$i]>$vini){
				$subquad = $i;
				$vini = $qtd[$quad][$i];
			}
		}
	}
	else{		$subquad =	0;		}
	return array('max'=>$max,'qtd'=>$qtd[$quad][0],'quad'=>$quad,'subquad'=>$subquad);
}

function gera_geral($array,$ini,$fim,$periodo){
	$separado = array();
	$alldata = array();
	$link = "aaaaux.php?pts=";
	foreach($array as $elem){
		$separado[($elem->numero)][] = $elem->predomina;
	}

	for($i=$ini;$i<=$fim;$i++){			$i = round($i);
		$ret = resume($separado[$i]);
			$alldata[] = array(	'int'=>round4(($ret['max']/$ret['qtd'])),
								'quad'=>$ret['quad'],
								'subquad'=>$ret['subquad']);
		$link .= round4(($ret['max']/$ret['qtd'])).",".$ret['quad'].",".$ret['subquad'].",";
	}
	return array('link'=>$link,'alldata'=>$alldata);
}
?>