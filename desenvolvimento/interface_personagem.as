/*---------------------------------------------------
*					Arquivo principal	
*	
*	Considerações a respeito de variáveis, eventos e funções APÓS a inicialização:
*	Aqui são declaradas e inicializadas as variáveis, tratados os eventos do filme 
*	e chamadas as funções essenciais. 
*	Estas funções encontram-se em arquivos externos e foram dividas por 
*	funcionalidades. Novas variáveis, eventos e funções como setInterval devem constar neste 
*	arquivo.  
---------------------------------------------------*/


/*
	//Definições.
	
		Tudo é uma série.
		O colégio é uma série a qual pertencem todos os alunos.
		Usuário pode ter mais de uma turma. Neste caso, é professor.

		Hierarquia de planetas.

		Nível 0) O planeta raiz é o colégio.
		Nível 1)	Um planeta pai de uma turma é uma série.
		Nível 2)		Um planeta possui vários terrenos e representa uma turma.
							Um terreno pertence somente a um planeta e uma pessoa.
						Planetas irmãos representam diferentes turmas de um planeta.	
		Nível 3)			Planetas representam disciplinas.
		Nível 4)				Planetas pertencem a alunos.


		terreno_grupo_id é o id do planeta ao qual pertence.


		-falar com João: deve haver usuario_grupos default para todos os alunos?




		//-- fazer personagem deslizar sobre o objeto ao colidir com algum no meio da rota.
		//-- repetir todos os testes com mais de um personagem online. 1x
		//-- permitir visão de descrição no minimapa enquanto estiver no modo de edição?
		//-- É possível inserir objetos (inclusive casas) fora dos limites alcançáveis.
			 Isto deve ser retirado.
		//-- haverão objetos não editáveis?
			
				* Caracterização de planetas. - Nova interface com botões.
				
					e ou E 			 - Acesso ao modo de edição
					ESC	   			 - Saída do modo de edição
					DEL    			 - Deletar objeto selecionado
					p ou P 			 - Inserir árvores
					o ou O 			 - Inserir casas
					n ou N           - Inserir np_a
					CTRL+z ou CTRL+Z - Nada.
					
			*** Nota:
				- Após salvar, o registro no array objArrastavel de um objeto que foi inserido deve conter o ID retornado pelo BD.
				- As animações, no BD, precisam ser cercadas de aspas duplas. Ex.: default está errado, mas "default" está certo.
			
			*** Bugs:
				Ok - A posição salva de objetos alterados tem coordenadas pouco menores do que a posição desejada pelo usuário.
				Ok - A colisão com casas modificadas não é perfeita. O personagem aparenta "escorregar" para dentro da porta quando caminha em direção
					à casa. Há vezes em que o personagem fica acima ou abaixo da casa, mas não é capaz de atravessa-la totalmente.
					O registro da casa contém uma posição diferente de sua verdadeira posição.
				Ok - Ao sair do modo edição, os objetos inseridos movem-se junto com o personagem.
				Ok - Operações com o BD são feitas objeto a objeto. Deve-se criar filas de: inserção, deleção e modificação de objetos. Após,
					fazer todas as operações em uma única comunicação com o BD e retornar para o usuário o verdadeiro resultado.
					* São várias comunicações, mas há um controle do número comunicações que tiveram sucesso.
				Ok - O ID retornado pelo BD de um objeto recém inserido não é tratado corretamente ou não é correto. 
				  Na prática: ao modificar um objeto inserido e salvo, insere-se um novo objeto, obtendo-se dois objetos salvos no BD ao total.
				- As cores do personagem "piscam" levemente ao se movimentar. 
				  X) há muito tempo entre os comandos - Descartada por testes com mp._visible.
				         mp.personagem.gotoAndStop(3); e 
						 mp.personagem.mpEsq.cor_pele.gotoAndStop(usuario_status.corPele);
				     da função movMpTecladoMouse em funcMoveObj.as.
				  X) ao dar o comando - Descartada, o frame continua o mesmo.
				         mp.personagem.mpEsq.play();
				     na função movMpTecladoMouse de funcMoveObj.as, o personagem retorna para a cor 1.
				  X) conferir conteúdo dos frames das cores da animação de caminhar do personagem. - Conferido.
				  4) os efeitos de gotoAndStop só duram para a primeira execução de um mc quando o comando play() é dado.
				  	 Nas execuções seguintes, os efeitos são esquecidos e daí as cores do personagem "piscam".
					 
					 ~~ tentar fazer testes da aparência em onEnterFrame. Assim como é feito o ciclo fecharmenu = true/false.
					 
				  "Pistas": i) Apesar da construção dos cabelos e olhos ser análoga, estes não piscam.
				  		   ii) Cores de ops também piscam. Seus cabelos e olhos não.
						  iii) Quando o personagem pára, é necessário reconfigurar suas cores, mas não seus olhos e cabelos.
						   iv) Em muitas chamadas de configura_aparencia_personagem, o frame de cor ou não está definido ou é 1. 
						       Isto faz com que as cores pisquem.
							v) Undefined acontecerá quando mudar a direção do movimento.
						   vi) Não é suficiente substituir gotoAndStop por ColorTransform.
						  vii) Adicionar um único keyframe ao fim dos 18 frames de mpBaixo, desde que numa layer que não a do cabelo,
						  	   faz com que o cabelo também pisque.
				Ok - Às vezes, o browser não recerregará ao salvar.
				Ok - Inserir, salvar, deletar. O objeto não é deletado.
				Ok - Indicador do mapa na posição errada ao inserir objetos. O indicador é colocado na posição certa ao clicar novamente.
				Ok - Indicador do mapa sem nome ao inserir objetos_link.
				Ok - Personagem movimenta-se sem sair do lugar ao entrar no modo de edição.
				Ok - Personagem continuará sua rota anterior à entrada no modo de edição, ao sair deste.
				Ok - Atualizar aparência de ops no terreno.
				Ok - Ao fazer movimento circular no sentido horário com o botão esquerdo do mouse pressionado e terminando com o personagem
				  andando para baixo, a animação trancará no personagem parado. No entanto, ele estará se movimentando.
				- Às vezes, as portas das casas são abertas e não fecham quando o personagem se afasta.
				Ok - Não é possível enxergar tudo o que é digitado no comunicador.
				Parcial - Ao sair de alguma funcionalidade, entrar no mundo ou salvar alterações, o primeiro movimento do mp só é possível pelo mouse.
						  Na verdade, é apenas um caso: quando o programa inicia.
					O SWF só funcionará ao ser selecionado com o mouse. O primeiro movimento pode ser feito via teclado, mas é necessário
					que o SWF tenha sido selecionando anteriormente clicando com o mouse em qualquer parte desse (inclusive botões, etc).
				- Ao inserir, remover e modificar objetos, eventualmente o indicador do mp será duplicado.
				- Balões não funcionam para mais de um personagem online.


		30.08.11
		
			- somente os donos dos terrenos poderão editá-los
				O dono de um terreno pode ser determinado no momento da criação do terreno.
				Criar nova coluna para tabela terrenos dizendo seu dono.
			Ok - mudar a cor da casa que tem o link de criar personagem
			Ok - o avatar default deve corresponder ao sexo de seu dono
			Ok - screenshot do avatar
			- fixar o tamanho mínimo do balão, expandindo-o na horizontal e vertical.
			- telas da administração
				-- o flash possui somente formulários, que serão preenchidos e enviados para processamento no código em php.
				Ok - os botões estão quase prontos
				Ok - todos botões aparecem para todos usuários, alguns devem ser escondidos de determinados grupos de usuários
					para as crianças, apenas editar usuário e trocar de planeta. todos os outros usuários poderão ver todos botões.
				- estudar o php da administração
				- elaborar os formulários
				- comunicar resultado para o usuário
			- ao colidir com algum objeto, perde-se o controle do movimento e das cores do personagem, mas não de seu cabelo e olhos
			- distância mínima entre objetos para permitir inserção de novo objeto
			- inserir paleta de cores
			- escrever nome dos planetas
			- o balão é sobreposto por casinhas.
				Notar que o balão está dentro do personagem. Isto é necessário para manter o controle da posição do balão relativa ao personagem.
				Para que o balão não seja sobreposto por casinhas, é preciso que fique fora do movieclip do personagem, pois pode haver
				situações em que o personagem deve estar atrás da casinhas, mas o balão deve estar acima desta (ou não?).
				


		A fazer:	
			verificar configurarFuncionalidades em c_menu

*/


/*---------------------------------------------------
*	APIs - Deve ser chamado antes dos arquivos, pois podem vir a usá-las - Guto - 02.06.10
---------------------------------------------------*/
import flash.external.*;
import flash.geom.ColorTransform;
import flash.geom.Matrix;
import flash.display.BitmapData;
import flash.geom.*;

/*---------------------------------------------------
*	Arquivos com funções
---------------------------------------------------*/
#include "funcMoveObj.as"
#include "funcHitTest.as"
#include "funcAtualizaObj.as"
#include "funcFala.as"
#include "funcAdm.as"
#include "funcBD.as"
#include "funcEditarTerreno.as"
#include "funcAparenciaPersonagem.as"
#include "funcAvisos.as"
/*---------------------------------------------------
*   Declaração de constantes.
*   Assume-se que o valor destas variáveis NÃO será mudado.
*		No futuro: criar uma classe de dados não editáveis chamada constante.
----------------------------------------------------*/
var NUM_FRAMES_PAREDE:Number = 6;
var NUM_FRAMES_OBJETO_LINK:Number = 5;

/*---------------------------------------------------
*	Declaração de variáveis 
* 	Tipos de variáveis separados por linha (Boolean, Number, Array, String, Object)
---------------------------------------------------*/
//---- DropDown
var dropDown:MovieClip;
var paginaDropDownSistemas:Number = 0;			//Referência para a página do dropdown que mostra os sistemas - Guto - 08.06.10
var primeiroDropDown:Number = 0;				//Referência para os níveis ou sistemas que aparecerão do dropDown da administração - Guto - 20.05.10
var sistemaDropDown:Boolean = false;
var limiteSuperiorScrollDropdown:Number = 0;
var limiteInferiorScrollDropdown:Number = 0;
var escalaScrollDropdown:Number = 0;			//Utilizada no controle da rolagem da inserção de usuários da administração - Guto - 20.05.10

var editarMundoBL:Boolean = false;				//Flag para editar a aparência do mundo, modificando a localização dos objetos.
var chatBoxDrag:Boolean = false;		//Arrastar chat_box_main
var velCtrl1Drag:Boolean = false;		//Arrastar botão de velocidade
var bloqLayout:Boolean = true;			//Auxiliar para o botão de bloquear o layout da página. Deve começar bloqueado.
var bloqMov:Boolean = false;				//Auxiliar para o botão "Start" - eD - 03/11/08
var chamouLink:Boolean = false;			//Indica se já foi realizada a solicitação para chamar link externo - Guto - 17.11.08
var colisao:Boolean = false;			//Indica se o mp colidirá com algum objeto caso se movimente como desejado - Guto - 17.11.08
var testaTiles:Boolean = false;			//Indica se o hitTest deve continuar testando os objetos mesmo que o mp não tenha trocado de tile - Guto - 16.06.09
var clickMouse:Boolean = false;         //Indica se o mouse foi clicado - Roger - 20.07.09
var mousePress:Boolean = false;         //Indica se o botao esquerdo do mouse esta sendo pressionado - Roger - 26.08.09
var selAba1:Boolean = false;			//Indica se o botÃ£o para a ferramenta de administraÃ§Ã£o foi pressionado - Guto - 12.01.10
//var scrollPress:Boolean = false;        		//Indica se o mouse esta arrastando o scroll do comunicador - Roger - 8.03.10
//var scrollPress2:Boolean = false;
var pressionado:Boolean = false;				//Controle do Easter Egg do Giovani - Guto - 30.04.10
var usaEspada:Boolean = false;
var fecharmenu:Boolean = false;					//Variáveis para o menu criadas pelo giovani - Giovani - xx.xx.xx
//var criarusuario:Boolean = false;			//Não será mais possível criar usuário. - Diogo - 02.09.11
var criarturma:Boolean = false;
var editarUsuario:Boolean = false;	
var editarTurmas:Boolean = false;			//Novas opções do menuMC. - Diogo - 02.09.11
var editarPlanetas:Boolean = false;
var editarContas:Boolean = false;
var traceLocal:Boolean = false;
var nivelDropDown:Boolean = false;
var scrollPressDropdown:Boolean = false;
var fullScreen:Boolean = true;					//Para controlar o modo fullScreen - Guto - 25.05.10
var btSistemaOn:Boolean = false;				//controles para ativações das abas do comunicador - Giovani - 02.06.10
var btContatoOn:Boolean = false;
var btChatLocalOn:Boolean = true;
var procuraUsuario:Boolean = false;				//Controla a exibição da lista de usuários na edição de usuários - Guto - 04.08.10
var nao_entrou:Boolean = true;	 				//Para desaparecer o nome do indicador(objeto) no mapa - Jean - 09.07.10
var inserirObjeto:Boolean = false;				//Flag para inserir objetos no modo de edição. - Diogo - 19.07.11
var haObjetoSelecionado:Boolean = false; 		//Indica se há objeto selecionado. - Diogo - 28.07.11
var exibirInstrucoesEdicao:Boolean = true;		//Determina se as instruções devem ser exibidas ao entrar no modo de edição. - Diogo - 15.08.11
var viuAvisoEdicaoTerreno:Boolean = false;		//Determina se o usuário viu o aviso de edição do terreno. - Diogo - 19.08.11


var speedMp:Number = 3; 						//Inicializo nas funções de movimentação
var speedMpDiag:Number = 6; 		
var speedOp:Number = 3;
//var multiplicador_velocidade_impacto:Number = (1/3); //quando ocorre impactos a speedMp será multiplicada por essa constante -> **o resultado deve ser um inteiro!! -> Não é mais utilizada. Essa linha tende a ser apagada - Guto - 02/12/08
//var tempo_repeticao:Number = 1000*segundos
var tempo_repeticao:Number = 1500;
var tempo_repeticao2:Number = 900;				//Inicialmente eram utilizados 12 quadros por segundo, cujo tempo mínimo de cada ação era de 84ms. Constante desnecessária - Guto - 08/12/08
var tempo_repeticao5:Number = 180;	
var dirMp:Number = 3;							//Registram a direção em que os personagens estão se movimentando - Guto - 15.01.09
var dirOp:Number = 3;
var ajustX:Number = 0;							//Registra o ajuste necessário para a posição de objetos na tela - Guto - 23.12.08     
var ajustY:Number = 0;
var depthBaseObj:Number = 0;					//Registra a altura base dos objetos no eixo Z - Guto - 19.12.08
var depth:Number = 0;							//Registra a altura atual dos objetos no eixo Z - Guto - 19.12.08
var n_matriz_parede:Number = 0;					//Registra o número de linhas das tabelas de objetos. Acho essas variáveis inúteis, visto que existe a propriedade length do tipo array. Podem ser subtituídas visando aliviar memória do projeto - Guto - 20.07.09
var n_matriz_objeto_link:Number = 0;
var n_matriz_np_a:Number = 0;
var n_matriz_op:Number = 0;
var id:Number = 0;								//Identificação dos personagens usada no banco de dados - Guto - 21.01.09
var margemCena:Number = 50;						//Margem estabelecida para a matriz referente ao cenário. A matriz é do tipo 1700x1300, com uma margem de 50, visto que as dimensões do cenário são 1600x1200 - Guto - 12.02.09 
var margemTilesX:Number = 1;					//Margem estabelecida para a matriz às tiles do cenário. A matriz é do tipo 34x66, com uma margem de 1 tile para cada lado em X e 3 tiles para cada lado em Y, visto que as dimensões da matriz de tiles do cenário são 32x60 - Guto - 16.06.09 
var margemTilesY:Number = 3;
var difOpX:Number = 0;							//Diferenças X e Y entre a posição atual do Op para a próxima posição contida no banco de dados - Guto - 13.02.09
var difOpY:Number = 0;
var passoOp:Number = 0;							//Variável que indica o passo nos arrays de rota dos OPs - Guto - 18.02.09
var usX:Number = 50;							//Medida da unidade de sombra utilizada no projeto - Guto - 16.06.09
var usY:Number = 20;
var terrDimX:Number = 1600;						//Dimensões do terreno padronizadas - Guto - 10.07.09
var terrDimY:Number = 1200;
var limiteSuperiorScroll:Number = 9.8;          //Limites superior e inferior do scroll do comunicador, ou seja, o qnto ele pode se mover no eixo y - Roger - 8.03.10
var limiteInferiorScroll:Number = 47.8;
var portaAcesso:Number = null;					//O valor indica o id do objeto de link que teve uma porta aberta. Se for 0, não há porta aberta - Guto - 25.03.10
var limiteSuperiorScrollMessenger:Number = 10;
var limiteInferiorScrollMessenger:Number = 179;
var numBotoesContatos:Number = 8;              //numero de botoes de contatos que o messenger exibira - Roger - 01.04.10
var primeiroContato:Number = 0;                //numero que indica qual sera o primeiro contato da lista que estara mostrando em determinado momento - Roger - 01.04.10
/*
var botaoTurmasHeight:Number = chat_box_main._y                //altura do botao de turmas do comunicador - Roger - 29.04.10
                             + chat_box_main.chatSubir._y
							 - turmas._y;
var botaoContatoHeight:Number = chat_box_main._y               //altura do botao de contatos do comunicador - Roger - 29.04.10
                              + chat_box_main.chatSubir._y
							  - contatos._y;
var botaoChatTerrenoHeight:Number = chat_box_main._y               //altura do botao de chat_terreno do comunicador - Roger - 29.04.10
                                  + chat_box_main.chatSubir._y
							      - chat_terreno._y;
								  */
var timeoutID:Number = -1;               //id do timeout para apagar o balao na cabeça do personagem	 
//var numeroAbasPrivate:Number = 0;
var posCamposOrgY:Number = 0;					//Variáveis para o menu criadas pelo giovani - Giovani - 10.05.10
var inilocalY:Number = 0;
var barraSelecaoY:Number = 0;
var dropDownY:Number = 0;
var numNiveis:Number = 0;						//Número de níveis para os quais o usuário tem permissão de adicionar outros usuários. - Guto - 14.05.10
var numSel:Number = 0;							//Número de níveis ou turmas, caso o usuário esteja selecionando os níveis ou as turmas. - Guto - 14.05.10
var nivelEscolhido:Number = 0;					//Nível e sistema selecionado para a adição de usuários - Guto - 18.05.10
var sistemaEscolhido:Number = 0;
var tipoMapa:Number = 0;						//Define o tipo do indicador no mapa - Jean - 07.07.10

var posNovoObjetoX:Number = 0;					//Contém a coordenada X do novo objeto, no momento em que é inserido na cena. - Diogo - 20.07.11
var posNovoObjetoY:Number = 0;					//Contém a coordenada Y do novo objeto, no momento em que é inserido na cena. - Diogo - 20.07.11
var ultimoIdParede:Number = 0;					//Último id registrado de parede. Utilizado para criação do objeto. - Diogo - 20.07.11
var ultimoIdNpA:Number = 0;						//Último id registrado de np_a. Utilizado para criação do objeto. - Diogo - 20.07.11
var ultimoIdObjetoLink:Number = 0;				//Último id registrado de objeto_link. Utilizado para criação do objeto. - Diogo - 20.07.11
var dados_gravados:Number = 0;					//Número de dados que já foram gravados no BD. - Diogo - 28.07.11
var total_dados_gravacao:Number = 0;			//Número total de dados que devem ser gravados no BD. - Diogo - 28.07.11


var matriz_op:Array = new Array();				//Controle dos OPs - Guto - 13.01.09
var matriz_op_temp:Array = new Array();
var cena:Array = new Array();					//Matrizes para o sistema de hitTest  - Guto - 04.02.09
var cenaTiles:Array = new Array();
var nuclLimTerrX:Array = new Array();			//Núcleo dos limites do terreno Leste e Oeste - Guto - 16.06.09
var nuclLimTerrY:Array = new Array();			//Núcleo dos limites do terreno Norte e Sul - Guto - 16.06.09
var nuclParede:Array = new Array();
var nuclNpA:Array = new Array();
var nuclObjetoLink:Array = new Array();         //Objeto Link eh um objeto que possui um outro objeto com um link de acesso - Roger - 14.08.09
var nuclObjLinkAcesso:Array = new Array();      //Se refere ao núcleo do objeto que possui um link de acesso - Roger - 14.08.09
var nuclOp:Array = new Array();
var nuclLimOp:Array = new Array();
var nuclLimMp:Array = new Array();
var limMpTiles:Array = new Array();
var limMpTilesBuff:Array = new Array();
var resposta:Array = new Array();				//Tiles que o mp está invadindo no teste de colisão - Guto - 19.06.09
var objReg:Array = new Array();					//Registro de todos os objetos que compoem o cenário - Guto - 10.07.09   ObjReg[i][0] eh o frame referente ao obj, ObjReg[i][1] e ObjReg[i][2] sao as posições x e y do obj respectivamente e ObjReg[i][3] é o tipo do obj - Roger - 14.08.09
var objArrastavel:Array = new Array();			//Registro dos objetos que podem ter suas posições configuradas pelo usuário.
var listaAbas: Array = new Array();
var online: Array;                              //Array que guarda os ids de quem esta online - Roger - 10.02.10
var todosContatos:Array = new Array();          //Array que guarda todos os contatos do usuario - Roger - 26.03.10
var nivelSel:Array = new Array();				//Identificação dos niveis para os quais o usuário tem permissão de adicionar outros usuários. - Guto - 14.05.10
var rota:Array = new Array();
var infoObjArrastado:Array = new Array();		//Contém os dados do objeto sendo arrastado, quando no modo de edição, copiados do registro de objetos. - Diogo - 18.07.11
var objetosApagados:Array = new Array();		//Fila de objetos para serem apagados do BD. - Diogo - 26.07.11
var objetosModificados:Array = new Array();		//Fila de objetos para serem alterados no BD. - Diogo - 26.07.11
//Não implementada. var acoesEdicao:Array = new Array(); //É a pilha de todas as ações feitas no modo de edição. - Diogo - 19.08.11

var direcaoMovMp:String = new String();
var abaAtiva:String = new String();//obsoleto
var baseLink:String = new String();				//Link da base, indicando o endereço do projeto no servidor - Guto - 04.06.10
var dropDownScrollUp:String = new String();		//Indica se os botões do scroll da escolha de níveis e sistemas dos usuários foram pressionados - Guto - 07.06.10
var dropDownScrollDown:String = new String();
var nomeMapa:String = new String();					//Nome do objeto do indicador no mapa - Jean - 07.07.10
var nomeObjeto:String = new String();				//Nome do objeto_link do indicador no mapa - Jean - 08.07.10

var operacaoEdicao:String = new String();			//Contém a operação que está sendo realizada: ""-nenhuma, insercao, modificacao ou delecao. - Diogo - 29.07.11

switch(terreno_status.terreno_solo){
	case c_terreno.TIPO_VERDE: attachMovie("terreno_grama_mc", "outroTerreno", 1); //terá que sair
		break;
	case c_terreno.TIPO_GRAMA: attachMovie("terreno_grama_mc", "outroTerreno", 1);
		break;
	case c_terreno.TIPO_LAVA: attachMovie("terreno_lava_mc", "outroTerreno", 1);
		break;
	case c_terreno.TIPO_NEVE: attachMovie("terreno_grama_mc", "outroTerreno", 1); //ainda não implementado
		break;
	case c_terreno.TIPO_URBANO: attachMovie("terreno_urbano_mc", "outroTerreno", 1); 
		break;
	default: attachMovie("terreno_grama_mc", "outroTerreno", 1); //erro?
		break;
}

mp._x = Stage.width/2;
mp._y = Stage.height/2;
this['outroTerreno']._x = Stage.width/2 - usuario_status.personagem_posicao_x;
this['outroTerreno']._y = Stage.height/2 - usuario_status.personagem_posicao_y;

if(!this['outroTerreno'].estaNaAreaUtil(usuario_status.personagem_posicao_x, usuario_status.personagem_posicao_y)){
	usuario_status.personagem_posicao_x = this['outroTerreno'].getLimiteOeste(usuario_status.personagem_posicao_y);
	usuario_status.personagem_posicao_y = this['outroTerreno'].getLimiteNorte(usuario_status.personagem_posicao_x);
	this['outroTerreno']._x = Stage.width/2 - usuario_status.personagem_posicao_x;
	this['outroTerreno']._y = Stage.height/2 - usuario_status.personagem_posicao_y;
}


this['outroTerreno'].onMouseDown = mouseClicadoTerreno;

function mouseClicadoTerreno(){
	/*---------------------------------------------------
	*	Deve-se verificar aqui TODOS OS BOTÕES, pois o avatar só pode se movimentar quando o clique do mouse não for realizado
	*	em cima de um botão ou objeto selecionável. - Guto - 21.01.10
	*	Qndo se clica no terreno o  botaoMousePress vira true. Enquanto ela for true o mp segue o mouse. Só se torna false qndo o botao do mouse é solto - Roger - 21.08.09										
	---------------------------------------------------*/
	if(!(editarMundoBL
		or velCtrl1.hitTest(_xmouse, _ymouse)		
		or bloqlayTerra.hitTest(_xmouse, _ymouse)
		or ChamaPopupSair.hitTest(_xmouse, _ymouse)
		or TelaSaida.hitTest(_xmouse, _ymouse)
		//or chat_box_main.chat_box_movimentar.hitTest(_xmouse, _ymouse)
		or velCtrl1.move.hitTest(_xmouse, _ymouse)
		//or chat_box_main.enviar.hitTest(_xmouse, _ymouse)
		or btAba1.btVolta.hitTest(_xmouse, _ymouse)
		or btAba1.btUsuario.hitTest(_xmouse, _ymouse)
		or btAba1.btTurma.hitTest(_xmouse, _ymouse)
		or btAba1.hitTest(_xmouse, _ymouse)
		or btStart.hitTest(_xmouse, _ymouse)
		or imprime.hitTest(_xmouse, _ymouse)
		//or chat_box_main.hitTest(_xmouse, _ymouse)
		or (!menuMC.btMenu.hitTest and menuMC._currentframe == 1)
		//or contatos.hitTest(_xmouse, _ymouse)
		//or turmas.hitTest(_xmouse, _ymouse)
		or mapa.hitTest(_xmouse, _ymouse)
		or zoomIn.hitTest(_xmouse, _ymouse)
		or zoomOut.hitTest(_xmouse, _ymouse)
		//or contatos.hitTest(_xmouse, _ymouse)
		or chat_terreno.hitTest(_xmouse, _ymouse)
		or btAdmAlerta.hitTest(_xmouse, _ymouse)	
		or comunicador.hitTest(_xmouse, _ymouse)
		or btSalvarEdicao.hitTest(_xmouse, _ymouse)
		or btCancelarEditarMundo.hitTest(_xmouse, _ymouse)
		or btEditarMundo.hitTest(_xmouse, _ymouse)
		or btInstrucoesEdicao.hitTest(_xmouse, _ymouse)
		or telaAguardarBD.hitTest(_xmouse, _ymouse)
	)) {
		mousePress = true;		
	}
}

attachMovie("btAdmAlerta", "outroAviso", _root.getNextHighestDepth());
this['outroAviso'].inicializar();
this['outroAviso']._x = Stage.width / 2;
this['outroAviso']._y = Stage.height / 2;
this['outroAviso'].esconder();

var keyer:Object = new Object();
Key.addListener(keyer);
keyer.onKeyDown = controleTeclado;

var casas:Array = new Array();
var arvores:Array = new Array();

mapaInfo.nomePlaneta.text = terreno_status.mensagemLocalizacao;
if(terreno_status.terreno_nome != ""){
	mapaInfo.nomeTerreno.text = terreno_status.terreno_nome;
} else {
	mapaInfo.nomeTerreno.text = "N.I.";
}

var menu:c_menu;
menu = _root.menuMC;
menu.inicializar();

switch(usuario_status.getPermissao()){
	case c_conta.getNivelVisitante(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA ); 
	break;
	
	case c_conta.getNivelAluno(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;
	
	case c_conta.getNivelMonitor(): menu.configurarFuncionalidades( menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;
	
	case c_conta.getNivelProfessor(): menu.configurarFuncionalidades( menu.EDITAR_TURMA | menu.EDITAR_CONTA | menu.EDITAR_PLANETA 
							                                     | menu.EDITAR_USUARIO | menu.TROCAR_DE_PLANETA );
	break;	

	case c_conta.getNivelCoordenador(): menu.configurarFuncionalidades( menu.EDITAR_TURMA | menu.EDITAR_CONTA | menu.EDITAR_PLANETA 
							                                       | menu.EDITAR_USUARIO | menu.CRIAR_TURMA | menu.TROCAR_DE_PLANETA );
	break;
	
	case c_conta.getNivelAdministrador(): menu.configurarFuncionalidades( menu.TODAS );
	break;
	
	default: menu.configurarFuncionalidades( menu.NENHUMA );
	break;
}
menu._visible = true;


//attachMovie("menu_selecao", menu_selecao_mc_teste, _root.getNextHighestDepth());
//menu_selecao_mc_teste.inicializar(300,300);


/*
comunicador.onPress = function() {
    mp.fala.text = "aaaai";	
}*/
//var comunicador:c_comunicador = new c_comunicador("_root.comunicador");
//comunicador.nova_caixa_texto("conversa");
//comunicador.nova_caixa_texto("contato15");
//chat_terreno.swapDepths(getNextHighestDepth());

/*---------------------------------------------------
*	Chamadas de funções iniciais 
---------------------------------------------------*/
setInterval(carregar_bd_posicoes, tempo_repeticao); 	//carregando posições dos ops
//setInterval(mover_np_a, tempo_repeticao5);
var tempoParaAvisoOps = setTimeout(avisoEdicaoTerrenoParaOps, 10000);

//setInterval(checar_chat, tempo_repeticao);          	//checa o bd por novos dados do comunicador
inicializaObj();										//Inicializa objetos no terreno - Guto - 23.12.8
carregaObj();											//Posiciona objetos no terreno - Guto - 10.07.09
//contatos.barra_rolagem.barra_scroll.scrollPress = false;
//chat_box_main.barra_rolagem.barra_scroll.scrollPress = false;
//esconder_botoes_contatos();
//criarContatos(usuario_status.lista_contatos);
//contatos.barra_rolagem.barra_scroll.unidade = ((limiteInferiorScrollMessenger - limiteSuperiorScrollMessenger) / (todosContatos.length - (numBotoesContatos - 1)));
//contatos.barra_rolagem.barra_scroll.unidade += contatos.barra_rolagem.barra_scroll.unidade / (todosContatos.length - (numBotoesContatos))+1;
//contatos.barra_rolagem.barra_scroll.numContato = 0;


//var botoesContatos:c_botoes_comunicador = new c_botoes_comunicador("contatos", "btContatos","contatos");
//var botoesTurmas:c_botoes_comunicador = new c_botoes_comunicador("turmas", "btTurmas", "grupos");


/*
var yep: Function = yepi();

function yepi(Void):Number {
    return 2;	
}
mp.fala.text = yep();*/
/*
var bla:Array = new Array();
bla.push("aaa","bbb","ccc","ddd","eee","fff");
//var teste:c_barra_rolagem = new c_barra_rolagem("barraRolagem");
contatos.attachMovie("barraRolagem", "barra", contatos.getNextHighestDepth());
contatos.barra._y = -300;
contatos.barra._x = 90;
contatos.barra.init_barra_rolagem(bla, 3);
*/
//mp.fala.text = barra._target;


//contatos.botao_contato1._visible = false;

//_root.attachMovie("botaoContato", "botao1", _root.getNextHighestDepth(), { _x:50, _y:450, visible:true } );
/*
duplicateMovieClip(_root.botaoContato0, "botaoContato1", _root.getNextHighestDepth());
botaoContato1._x = 50;
botaoContato1._y = 450;
botaoContato1._visible = true;
*/
/*---------------------------------------------------
*	Debug de texto em tela
---------------------------------------------------*/
//imprime._x = 0;
//imprime._y = 0;

/*
function imprime(estringue:String):Void {
	debug._x = 0;
	debug._y = 0;	
	
	debug.text += estringue;
	
	var textStd:TextFormat = new TextFormat();

	textStd.align = "left";
	debug.setTextFormat(textStd);
}*/


/*---------------------------------------------------
*	Funções para mudar o ponteiro do mouse qndo estiver sobre a caixa de texto na parte 
*	inferior esquerda da tela - DESATIVADO - QUANDO ATIVAR, MOVER PARA O LOCAL DE TRATAMENTO DO EVENTO onMove
---------------------------------------------------*/
/*
chat_box_main.onRollOver = function (){	
	ponteiro.gotoAndStop(2);  //ponteiro de selecao de texto
}

chat_box_main.onRollOut = function (){
	ponteiro.gotoAndStop(1);  //ponteiro default do planeta
}
*/

/*---------------------------------------------------
*	Comentar isso daqui...
---------------------------------------------------*/

usuario_status.personagem_posicao_x_auxiliar = usuario_status.personagem_posicao_x;
usuario_status.personagem_posicao_y_auxiliar = usuario_status.personagem_posicao_y;

if (funcionalidade == "true") {
	baseLink = "http://www.nuted.ufrgs.br/planeta2/";// <<--- LINK CERTO
	//baseLink = "http://sideshowbob/planeta2_diogo/";// ************************* <<---- ERRO DE LINK AQUI! MODIFICAR ANTES DE SALVAR NO ORIGINAL!
	ExternalInterface.call("chamaLink", baseLink+linkColorBox);
	
} else if (linkExterno == "true") {
	ExternalInterface.call("chamaLink", linkColorBox);
}

/*---------------------------------------------------
*	Centralização do evento que controla o movimento do mouse no planeta - Guto - 22.10.04
---------------------------------------------------*/
this.onMouseMove = function() {	
	
	if(editarMundoBL){
		if (btInstrucoesEdicao.btAdmOk.hitTest(_xmouse, _ymouse)) {						
			btInstrucoesEdicao.btAdmOk.gotoAndStop(2);		
		} else {
			btInstrucoesEdicao.btAdmOk.gotoAndStop(1);
		}
		
	}
	else{
		/*-----------------------------------------------
		*	Controle gráfico do menu de administração - Giovani - 22.04.10
		------------------------------------------------*/
		if ((sistemaDropDown == true) or (nivelDropDown == true)) {
			if(nivelDropDown == true){
				numSel = numNiveis;
			} else if(sistemaDropDown == true){
				numSel = numSist;
			} else {
				numSel = 0;
			}
			for (var i = 0; i < numSel; i++) {
				if (eval("dropDown.btSelAdm" + i).sistemaAbre.hitTest(_xmouse, _ymouse)) {
					eval("dropDown.btSelAdm" + i).sistemaAbre.gotoAndStop(2);
					eval("dropDown.btSelAdm" + i).sistemaFecha.gotoAndStop(1);
				} else if (eval("dropDown.btSelAdm" + i).sistemaFecha.hitTest(_xmouse, _ymouse)) {
					eval("dropDown.btSelAdm" + i).sistemaAbre.gotoAndStop(1);
					eval("dropDown.btSelAdm" + i).sistemaFecha.gotoAndStop(2);
				} else {				
					eval("dropDown.btSelAdm" + i).sistemaAbre.gotoAndStop(1);
					eval("dropDown.btSelAdm" + i).sistemaFecha.gotoAndStop(1);
					if (eval("dropDown.btSelAdm" + i).fundoSelect.hitTest(_xmouse, _ymouse)) {		//Botões de seleção dos níveis e sistemas - Guto - 12.05.10
						eval("dropDown.btSelAdm" + i).fundoSelect.gotoAndStop(2);
					} else {
						eval("dropDown.btSelAdm" + i).fundoSelect.gotoAndStop(1);
					}
				}
			}	
		}
	
		/*---------------------------------------------------
		*	É necessário verificar, antes de cada funcionalidade, se o pressionamento do botão de início ocorreu - Guto - 22.04.10
		---------------------------------------------------*/
		if (!bloqMov){
			/*---------------------------------------------------
			*	Mudança dos botoes de velocidade - Giovani - 12.04.10
			---------------------------------------------------*/	
			if(usuario_status.velocidade == 0){
				if(velCtrl1.speed1.hitTest(_xmouse,_ymouse)){
					velCtrl1.speed1.gotoAndStop(3);
				}
				else{
					velCtrl1.speed1.gotoAndStop(1);
				}
			}	
			if(usuario_status.velocidade == 1){
				if(velCtrl1.speed2.hitTest(_xmouse,_ymouse)){
					velCtrl1.speed2.gotoAndStop(3);
				}
				else{
					velCtrl1.speed2.gotoAndStop(1);
				}
			}
					
			/*-----------------------------------------------
			*	Controle gráfico do comunicador - Giovani - 22.04.10
			------------------------------------------------*/	
			/*
			if(chat_box_main.enviar.hitTest(_xmouse,_ymouse)){			//mudança de cor no botao enviar
				chat_box_main.enviar.gotoAndStop(2);
			}
			else{
				chat_box_main.enviar.gotoAndStop(1);
			}
			
			if (contatos.btContatos.hitTest(_xmouse, _ymouse)){			//mudanças de cor no botão contatos e turmas. 
				contatos.btContatos.gotoAndStop(2);
			}
			else{
				if (contatos._currentframe > 2){
					contatos.btContatos.gotoAndStop(3);
				}
				else{
					contatos.btContatos.gotoAndStop(1);
				}
			}
			if (turmas.btTurmas.hitTest(_xmouse, _ymouse)){
				turmas.btTurmas.gotoAndStop(2);
			}
			else{
				if (turmas._currentframe > 2){
					turmas.btTurmas.gotoAndStop(3);
				}
				else{
					turmas.btTurmas.gotoAndStop(1);
				}
			}
			if (chat_terreno.hitTest(_xmouse, _ymouse)){//mouse.onOver do botao chat_terreno
				chat_terreno.btChat_terreno.gotoAndStop(2);			
			}
			else{		
				chat_terreno.btChat_terreno.gotoAndStop(1);						
			}
			if (contatos.botao_contato1.hitTest(_xmouse, _ymouse)) {//mouse.onOver dos botoes em contatos
		    	contatos.botao_contato1.gotoAndStop(2);	
			}	
			else {
		    	contatos.botao_contato1.gotoAndStop(1);
			}	
		
			if(chat_box_main.chatSubir.hitTest(_xmouse,_ymouse)){		//fases do botao que aumenta e diminui o chat
				if (chat_box_main._currentframe == 1){
					chat_box_main.chatSubir.gotoAndStop(2);
				}
				else if (chat_box_main._currentframe > 1){
					chat_box_main.chatSubir.gotoAndStop(4);				
				}
			}
			else if (chat_box_main._currentframe == 1){
				chat_box_main.chatSubir.gotoAndStop(1);
			}
			else if (chat_box_main._currentframe > 1){
				chat_box_main.chatSubir.gotoAndStop(3);
			}
			*/
			/*---------------------------------------------------
			*	Scroll do botao messenger do comunicador - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(contatos.barra_rolagem.barra_scroll.hitTest(_xmouse,_ymouse)){		
				if (scrollPress2 == true){// and ( chat_box_main.barra_rolagem.barra_scroll.posy != chat_box_main.barra_rolagem.barra_scroll._y )){		
					var unidade:Number;
					var ultimoPrimeiroContato:Number;
					unidade = ((limiteInferiorScrollMessenger - limiteSuperiorScrollMessenger) / (todosContatos.length - (numBotoesContatos - 1)));
					ultimoPrimeiroContato = primeiroContato;
					primeiroContato = Math.ceil( ((contatos.barra_rolagem.barra_scroll._y - limiteSuperiorScrollMessenger) / unidade) - 1 );
					if (primeiroContato < 0 ) { primeiroContato = 0;}
					setBotoesContatos(ultimoPrimeiroContato, primeiroContato, todosContatos);
				}
			}		
			*/
			/*---------------------------------------------------
			*  Scroll da caixa de texto do comunicador - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(chat_box_main.barra_rolagem.barra_scroll.hitTest(_xmouse,_ymouse)){		
				if (scrollPress == true){// and ( chat_box_main.barra_rolagem.barra_scroll.posy != chat_box_main.barra_rolagem.barra_scroll._y )){		
					var unidade:Number;
					unidade = (limiteInferiorScroll-limiteSuperiorScroll) / eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).maxscroll;		
					eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll = ( (chat_box_main.barra_rolagem.barra_scroll._y - limiteSuperiorScroll) / unidade ) + 1  ;
					if (chat_box_main.barra_rolagem.barra_scroll._y == limiteInferiorScroll) {
						eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll = eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).maxscroll;
					}
				}
			}
			*/
			
			/*---------------------------------------------------
			*  Movimento do mouse no mapa - Giovani - 05.05.10
			---------------------------------------------------*/
		
			if(mapa.hitTest(_xmouse,_ymouse)){	   			//para mostrar o nome do objeto(indicador) no mapa ao passar o mouse no seu ponto
				if (mapa.indicadorMp.hitTest(_xmouse, _ymouse)) {
					nao_entrou =  false;					
					traceLocal = true;						
					tipoMapa = 1;												//indica que o tipo de objeto que o mouse está posicionado em cima , no caso botao correr
				
				}
				else {
					for(var j=0; j<n_matriz_op; j++) 
					{	
						id = matriz_op[j][0];
						if(eval("mapa.indicadorOP"+id).hitTest(_xmouse, _ymouse))
						{
							id = matriz_op[j][0];
							nao_entrou = false;
							traceLocal = true;
							tipoMapa = 2;
							nomeObjeto =eval("op"+id).nome.text;
							nomeMapa = "mapa.indicadorOP"+id;
						}
					}
					for(var i=0; i<ultimoIdObjetoLink; i++) //n_matriz_objeto_link
					{	
						id = objArrastavel[i][3] - (Math.floor(objArrastavel[i][3]/0x100)*0x100);//objReg
						if(eval("mapa.indicadorLink"+id).hitTest(_xmouse, _ymouse))
						{
							nao_entrou = false;
							traceLocal = true;
							tipoMapa = 3;													//indica qual o tipo de objeto que o mouse está posicionado em cima 
							nomeObjeto = "objeto_link"+id;
							nomeMapa = "mapa.indicadorLink"+id;
						}
					}
				}									
			}	
			if(velCtrl1.speed1.hitTest(_xmouse, _ymouse)){
				nao_entrou = false;
				traceLocal = true;
				tipoMapa = 4;												//indica qual o tipo de objeto que o mouse está posicionado em cima , no caso botao correr
			}
			if(velCtrl1.speed2.hitTest(_xmouse, _ymouse)){
				nao_entrou = false;
				traceLocal = true;
				tipoMapa = 5;											//indica qual o tipo de objeto que o mouse está posicionado em cima , no caso botao caminhar
			}
			if(nao_entrou)									//booleano para sumir o nome do indicador quando o mouse não estiver em cima do indicador
				traceLocal = false;
				
			nao_entrou = true;
		
		
		}
	}
}



/*---------------------------------------------------
*	Centralização do evento que controla o pressionamento do botão esquerdo do mouse no ambiente - Guto - 21.01.10
---------------------------------------------------*/
this.onMouseDown = function() {
	
	if(editarMundoBL)
	{
		desfazerSelecaoObjeto();	//Se houver objeto selecionado, terá sua seleção desfeita.
		verificarSelecaoObjeto();   //Procura por um objeto que o usuário tenha selecionado.
		operacaoEdicao = "";
	}else{

	
	/*---------------------------------------------------
	*  Scroll do dropDown para seleção de níveis ou sistemas an inserção de usuários da administração - Guto - 19.05.10
	---------------------------------------------------*/
		if (dropDown.barraRolagem.hitTest(_xmouse, _ymouse)) {	
			if ((_ymouse > (dropDown._y + dropDown.barraRolagem._y + limiteSuperiorScrollDropdown + dropDown.barraRolagem.barra_scroll._height/2)) and (_ymouse < (dropDown._y + dropDown.barraRolagem._y + limiteInferiorScrollDropdown + dropDown.barraRolagem.barra_scroll._height/2))) {
				dropDown.barraRolagem.barra_scroll._y = _ymouse - dropDown._y - dropDown.barraRolagem._y - dropDown.barraRolagem.barra_scroll._height/2;
			}
		}
		if (dropDown.barraRolagem.barra_scroll.hitTest(_xmouse, _ymouse)) {				
			if (nivelDropDown == true) {
				if (nivelSel.length > 7) {
					startDrag(dropDown.barraRolagem.barra_scroll, false, dropDown.barraRolagem.barra_scroll._x , limiteSuperiorScrollDropdown , dropDown.barraRolagem.barra_scroll._x , limiteInferiorScrollDropdown);
					scrollPressDropdown = true;
				}
			} else if (sistemaDropDown == true) {
				if (sistSel[paginaDropDownSistemas].length > 7) {
					startDrag(dropDown.barraRolagem.barra_scroll, false, dropDown.barraRolagem.barra_scroll._x , limiteSuperiorScrollDropdown , dropDown.barraRolagem.barra_scroll._x , limiteInferiorScrollDropdown);
					scrollPressDropdown = true;
				}
			}		
		}
		if (dropDown.barraRolagem.botao_scroll_up.hitTest(_xmouse, _ymouse)) {	
			dropDownScrollUp = "pressionado";
		} else if (dropDown.barraRolagem.botao_scroll_down.hitTest(_xmouse, _ymouse)) {	
			dropDownScrollDown = "pressionado";
		}
	
		if (!bloqMov){  		
		/*---------------------------------------------------
		*  Scroll do botao messenger do comunicador - Roger - 12.04.10
		---------------------------------------------------*/
		/*
		if(contatos.barra_rolagem.botao_scroll_down.hitTest(_xmouse, _ymouse)){	
			if (primeiroContato+numBotoesContatos < todosContatos.length){	
				var ultimoPrimeiroContato:Number;	
				ultimoPrimeiroContato = primeiroContato;	
				primeiroContato++;	
				setBotoesContatos(ultimoPrimeiroContato, primeiroContato , todosContatos);
				//contatos.barra_rolagem.barra_scroll._y += contatos.barra_rolagem.barra_scroll.unidade;
				contatos.barra_rolagem.barra_scroll.numContato++;
				contatos.barra_rolagem.barra_scroll._y = contatos.barra_rolagem.barra_scroll.numContato * contatos.barra_rolagem.barra_scroll.unidade;
				if (contatos.barra_rolagem.barra_scroll._y > limiteInferiorScrollMessenger ) {
					contatos.barra_rolagem.barra_scroll._y = limiteInferiorScrollMessenger;
				}				
			}
		}		
		if(contatos.barra_rolagem.botao_scroll_up.hitTest(_xmouse, _ymouse)){	
			if (primeiroContato > 0) {
				var ultimoPrimeiroContato:Number;	
				ultimoPrimeiroContato = primeiroContato;
				primeiroContato--;
				setBotoesContatos(ultimoPrimeiroContato, primeiroContato , todosContatos);
				//contatos.barra_rolagem.barra_scroll._y -= contatos.barra_rolagem.barra_scroll.unidade;
				contatos.barra_rolagem.barra_scroll.numContato--;
				contatos.barra_rolagem.barra_scroll._y = contatos.barra_rolagem.barra_scroll.numContato * contatos.barra_rolagem.barra_scroll.unidade;
				if (contatos.barra_rolagem.barra_scroll._y < limiteSuperiorScrollMessenger ) {
					contatos.barra_rolagem.barra_scroll._y = limiteSuperiorScrollMessenger;
				}				
			}
		}
		if(contatos.barra_rolagem.barra_scroll.hitTest(_xmouse, _ymouse)){	
			startDrag(contatos.barra_rolagem.barra_scroll, false, contatos.barra_rolagem.barra_scroll._x , limiteSuperiorScrollMessenger , contatos.barra_rolagem.barra_scroll._x , limiteInferiorScrollMessenger);
			scrollPress2 = true;	
		}
		*/
		/*---------------------------------------------------
		*  Scroll da caixa de texto do comunicador - Roger - 12.04.10
		---------------------------------------------------*/
		/*
		if(chat_box_main.barra_rolagem.barra_scroll.hitTest(_xmouse, _ymouse)){	
			//chat_box_main.barra_rolagem.barra_scroll.startDrag(false,false,false,false,false);
			startDrag(chat_box_main.barra_rolagem.barra_scroll, false, chat_box_main.barra_rolagem.barra_scroll._x , limiteSuperiorScroll , chat_box_main.barra_rolagem.barra_scroll._x , limiteInferiorScroll);
			scrollPress = true;	
		}*/
		//if (chat_terreno.hitTest(_xmouse, _ymouse)) {
		//    comunicador.set_caixa_texto_ativa("conversa");
		//}
		}
	}
	
}

//Retorna o link que corresponde a um determinado frame na timeline de um objeto_link. - Diogo - 25.07.11
function linkFrame(frame:Number):String{
	switch(frame){
		case 1:
			return "funcionalidades/biblioteca/biblioteca.php";
		break;
		case 2:
			return "funcionalidades/blog/blog_inicio.php";
		break;
		case 3:
			return "funcionalidades/forum/forum.php";
		break;
		case 4:
			return "funcionalidades/portfolio/portfolio.php";
		break;
		case 5:
			return "funcionalidades/criar_personagem/criar_personagem.php";
		break;
		default:
			return "";
		break;
	}
}

/*---------------------------------------------------
*	Centralização do evento que controla o soltar do botão esquerdo do mouse no ambiente - Guto - 22.01.10
---------------------------------------------------*/
this.onMouseUp = function() {
	/*---------------------------------------------------
	*	Controla funcionalidades do botão que dá foco ao filme
	---------------------------------------------------*/
	if(btStart.hitTest(_xmouse, _ymouse)) {
		bloqMov = false;
		gMask.gotoAndPlay(2);
		unloadMovie(btStart);	
	}	
	
	/*---------------------------------------------------
	*	Controla funcionalidades do botão de sair/voltar
	---------------------------------------------------*/
	if(TelaSaida.logoffBt.hitTest(_xmouse, _ymouse)) {
		getURL("../index.php?action=log0001","_self");
	}
	if(TelaSaida.voltarBt.hitTest(_xmouse, _ymouse)) {
		bloqMov = false;
		TelaSaida._y = -679.0;
		gMask.gotoAndPlay(2);
	}		
	
	//Entrar no modo de edição.
	if(btEditarMundo.hitTest(_xmouse, _ymouse)){
		if(usuario_status.possuiPermissaoDe(c_conta.getNivelProfessor()) || //Professor ou superior
		   (usuario_status.possuiPermissaoDe(c_conta.getNivelAluno()) && terreno_status.getPermissaoAlunosEdicao())){ //Aluno ou superior
			iniciarModoEdicao();
		} else {
			this['outroAviso'].chamar("Desculpe. Você não possui permissão para editar este terreno.");
		}
	}
	
	if(editarMundoBL and !btEditarMundo.hitTest(_xmouse, _ymouse)){
		//Botão de ok para gravação dos dados no BD.
		if (btAdmAlerta.btAdmOk.hitTest(_xmouse, _ymouse)) {							
			btAdmAlerta._x = 770;
			btAdmAlerta._y = -1000;
		}
		
		//Botão de ok para instruções do modo de edição.
		if (btInstrucoesEdicao.btAdmOk.hitTest(_xmouse, _ymouse)) {							
			btInstrucoesEdicao._x = 770;
			btInstrucoesEdicao._y = -1000;
			
			if(btInstrucoesEdicao.checkExibirInstrucoesEdicao.campoCheck.selected){
				exibirInstrucoesEdicao = false;
			}
		}
		
		//Sair do modo de edição.
		if(btCancelarEditarMundo.hitTest(_xmouse, _ymouse)){
			terminarModoEdicao();
		}
		
		/*---------------------------------------------------
		*	Controla funcionalidades da popup de logoff
		---------------------------------------------------*/
		if(ChamaPopupSair.hitTest(_xmouse, _ymouse)) {
			bloqMov = true;
			TelaSaida._x = Stage.width/2 - TelaSaida._width/2;
			TelaSaida._y = Stage.height/2 - TelaSaida._height/2; 		
			gMask.gotoAndPlay(8);	
		}
		
		//Mantém o objeto selecionado, mas permite o movimento independente do mouse.
		if(obj_selecionado.estahSelecionado()){
			obj_selecionado.pararArrastarMC();
		}
		
		//Salvar modificações.
		if(btSalvarEdicao.hitTest(_xmouse, _ymouse)) {
			salvarTerreno();
		}
	}
	else{
		if (!dropDown.hitTest(_xmouse, _ymouse)) {
			if (camposCriarUsuario.btInsereUsu.hitTest(_xmouse, _ymouse)) {				//Botão que cadastra o usuário no banco de dados - Guto - 06.05.10
				admCriaUsuario();			
			}
		}
		if(nivelDropDown == true){
			numSel = numNiveis;
		} else if(sistemaDropDown == true){
			numSel = numSist;
		} else {
			numSel = 0;
		}	
		if (scrollPressDropdown == true) {					//Scroll do dropDown para seleção de níveis ou sistemas na inserção de usuários da administração - Guto - 19.05.10
			dropDown.barraRolagem.barra_scroll.stopDrag();
			scrollPressDropdown = false;
		}	
		
		if ((sistemaDropDown == true) or (nivelDropDown == true)) {
			for (var i:Number = 0; i < numSel; i++){
				if (eval("dropDown.btSelAdm" + i).fundoSelect.hitTest(_xmouse, _ymouse)) {		//Botões de seleção dos níveis e sistemas - Guto - 12.05.10
					if (nivelDropDown == true) {				
						camposCriarUsuario.btSelNivel.conteudo.text = eval("dropDown.btSelAdm" + i).conteudo.text;
						nivelEscolhido = nivelSel[i + primeiroDropDown][0];
						nivelDropDown = false;
					} else if (sistemaDropDown == true) {
						camposCriarUsuario.btSelSistema.conteudo.text = eval("dropDown.btSelAdm" + i).conteudo.text;
						sistemaEscolhido = sistSel[paginaDropDownSistemas][i + primeiroDropDown][0];
						sistemaDropDown = false;
					}
				} else if (eval("dropDown.btSelAdm" + i).sistemaAbre.hitTest(_xmouse, _ymouse) and (eval("dropDown.btSelAdm" + i).sistemaAbre._visible == true)) {
					buscaDropDown(3,sistSel[paginaDropDownSistemas][i + primeiroDropDown][0]);
				} else if (eval("dropDown.btSelAdm" + i).sistemaFecha.hitTest(_xmouse, _ymouse) and (eval("dropDown.btSelAdm" + i).sistemaFecha._visible == true)) {
					buscaDropDown(2);
				}
			
			}
		} 
	
		if (dropDownScrollUp == "pressionado") {
			dropDownScrollUp = "liberado";
		} else if (dropDownScrollDown == "pressionado") {
			dropDownScrollDown = "liberado";
		}
		
		if (btAdmAlerta.btAdmOk.hitTest(_xmouse, _ymouse)) {							//Botão de OK para informações referentes a administração - Guto - 06.05.10
			btAdmAlerta._x = 770;
			btAdmAlerta._y = -1000;
		}

		/*---------------------------------------------------
		*	É necessário verificar, antes de cada funcionalidade, se o pressionamento do botão de início ocorreu - Guto - 03.02.10
		---------------------------------------------------*/
		if (!bloqMov) {		
			/*---------------------------------------------------
			*	Quando se clica no terreno, botaoMousePress vira true. Enquanto ela for true o mp segue o mouse. Só se torna false qndo o botao do mouse é solto - Roger - 21.08.09										
			---------------------------------------------------*/
			//if(terreno.hitTest(_xmouse, _ymouse)) {	
				mousePress = false;
			//}
		
			/*---------------------------------------------------
			*	Controla funcionalidades da popup de logoff
			---------------------------------------------------*/
			if(ChamaPopupSair.hitTest(_xmouse, _ymouse)) {
				bloqMov = true;
				TelaSaida._x = Stage.width/2 - TelaSaida._width/2;
				TelaSaida._y = Stage.height/2 - TelaSaida._height/2; 		
				gMask.gotoAndPlay(8);	
			}
		
			/*---------------------------------------------------
			*	Controla funcionalidades do botão de velocidade do avatar
			---------------------------------------------------*/
			if (velCtrl1.speed1.hitTest(_xmouse, _ymouse)) {
				velCtrl1.speed1.gotoAndStop(2);
				usuario_status.velocidade = 1;
				carregar_bd_posicoes();			//Tem que gravar no servidor a velocidade do mp, para que o mesmo possa se locomover corretamente quando visualizado por outro usuário - Guto - 16.01.09
			}
			if (velCtrl1.speed2.hitTest(_xmouse, _ymouse)) {
				velCtrl1.speed2.gotoAndStop(2);
				usuario_status.velocidade = 0;		
				carregar_bd_posicoes();			//Tem que gravar no servidor a velocidade do mp, para que o mesmo possa se locomover corretamente quando visualizado por outro usuário - Guto - 16.01.09				
			}
		
			/*---------------------------------------------------
			*	Animação para abrir as abas contatos e turmas - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(contatos.btContatos.hitTest(_xmouse, _ymouse)){
				contatos.play();
			}
			if(turmas.btTurmas.hitTest(_xmouse, _ymouse)){
				turmas.play();
			}
			*/
			/*---------------------------------------------------
			*	Controle dos botões do comunicador - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(chat_box_main.enviar.hitTest(_xmouse, _ymouse)) {		
				comunicador.addTexto(chat_box_main.fala.text, comunicador.get_caixa_texto_ativa());
				chat_box_main.fala.text = "";
			}		
			if(contatos.btContatos.hitTest(_xmouse, _ymouse)) {
				contatos.btContatos.gotoAndStop(1);
				contatos.play();
			}
			*/
			/*---------------------------------------------------
			*	Botões dos contatos do comunicador - Roger - 12.04.10
			* 	Quando se clica em um dos botões se não existe uma caixa de texto (textfield) para se comunicar com o 
			*	contato específico cria uma, se ela já existe deixa ela ativa - Roger - 15.04.10
			---------------------------------------------------*/
			/*
			if(contatos.btContatos.hitTest(_xmouse, _ymouse)){
				contatos.btContatos.gotoAndStop(3);
			}*/
			/*
			for (var i = 1; i <= 8; i++) {		//Esse trecho de código verifica os oito botões contidos na aba de contatos - Guto - 30.04.10
				if ((_xmouse >= (contatos._x + eval("contatos.botao_contato" + i + "._x"))) and (_xmouse <= (contatos._x + eval("contatos.botao_contato" + i + "._x") + eval("contatos.botao_contato" + i + "._width"))) and 
					(_ymouse >= (contatos._y + eval("contatos.botao_contato" + i + "._y"))) and (_ymouse <= (contatos._y + eval("contatos.botao_contato" + i + "._y") + eval("contatos.botao_contato" + i + "._height")))){
					if (comunicador.existe_caixa_texto("contato"+todosContatos[Number(primeiroContato) + Number(i) - 1][0])==false){
						comunicador.nova_caixa_texto("contato" + todosContatos[Number(primeiroContato) + Number(i) - 1][0]); 					
					}
					else {					
						comunicador.set_caixa_texto_ativa("contato"+todosContatos[Number(primeiroContato) + Number(i) -1][0]);					
					}
				}
			}
			*/
			/*-----------------------------------------------
			*	Animações para aumentar e diminuir o comunicador - Giovani - 05.05.10
			------------------------------------------------*/
			/*
			if(chat_box_main.chatSubir.hitTest(_xmouse, _ymouse)) {
				if(chat_box_main._currentframe == 1){
					chat_box_main.gotoAndPlay(2);
				}
				else{
					chat_box_main.gotoAndPlay(11);
				}
			}
			*/
			/*---------------------------------------------------
			*  Scroll da caixa de texto do comunicador - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(chat_box_main.barra_rolagem.botao_scroll_down.hitTest(_xmouse, _ymouse)) {
				if(eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll != eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).maxscroll){
					eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll++;
					var unidade:Number;
					unidade = (limiteInferiorScroll-limiteSuperiorScroll) / eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).maxscroll;
					chat_box_main.barra_rolagem.barra_scroll._y = (eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll -1) * unidade + limiteSuperiorScroll;		
				}
			}
			if(chat_box_main.barra_rolagem.botao_scroll_up.hitTest(_xmouse, _ymouse)) {
				if (eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll != 1 ) {
					eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll--;
					var unidade:Number;
					unidade = (limiteInferiorScroll - limiteSuperiorScroll) / eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).maxscroll;
					chat_box_main.barra_rolagem.barra_scroll._y = (eval("chat_box_main."+comunicador.get_caixa_texto_ativa()).scroll -1) * unidade + limiteSuperiorScroll ; 	    		
				}
			}
			if(scrollPress == true){	
				chat_box_main.barra_rolagem.barra_scroll.stopDrag();
				scrollPress = false;
			}
			*/
			/*---------------------------------------------------
			*  Scroll do botao messenger do comunicador - Roger - 12.04.10
			---------------------------------------------------*/
			/*
			if(scrollPress2 == true){
				contatos.barra_rolagem.barra_scroll.stopDrag();
				scrollPress2 = false;
			}
			*/
			/*---------------------------------------------------
			*  Botão para nevar - Giovani - 05.05.10
			---------------------------------------------------*/
			if(nevar.hitTest(_xmouse, _ymouse)) { //só pra avacalhação...deletar depois
				if (clima._currentframe == 3) {clima.gotoAndStop(1);}
				else {clima.gotoAndStop(3);}
			}
		
			/*---------------------------------------------------
			*	Debug de texto em tela. DEIXAR POR ÚLTIMOS, VISTO QUE É UM DEBUG - Guto - 21.01.10
			---------------------------------------------------*/
			if(imprime.hitTest(_xmouse, _ymouse)) {
				debug._x = 0;
				debug._y = 0;
				debug.text = "Matriz de tiles da tela \n";
				//debug.text += cenaTiles + " cenaTiles \n";
				for (var i = 0; i < cenaTiles.length; i++) {
					for(var j = 0; j < cenaTiles[0].length; j++){
						if (Math.round(cenaTiles[i][j]/1000) > 0){
							debug.text += cenaTiles[i][j];
						} else if (Math.round(cenaTiles[i][j]/100) > 0){
							debug.text += cenaTiles[i][j] + " ";
						} else if (Math.round(cenaTiles[i][j]/10) > 0){
							debug.text += cenaTiles[i][j] + "  ";
						} else {
							debug.text += cenaTiles[i][j] + "   ";
						}			
					}
					debug.text += "\n";
				}			
				var textStd:TextFormat = new TextFormat();
				textStd.align = "left";
				debug.setTextFormat(textStd);
			}
		}	
	}
}

//dimensiona a caixa de texto utilizada no chat no momento para o tamanho certo (se o chat estah maximizado ou nao) - Roger - 29.04.10
/*
function dimensionar_caixa_texto(caixa_texto:String):Void { //usar como parametro o caminho para a caixa de texto ex:"chat_box_main.chat_geral" - Roger - 27.04.10
    eval(caixa_texto)._y = chat_box_main.chatSubir._y + chat_box_main.chatSubir._height;
	eval(caixa_texto)._height = chat_box_main.fala._y - eval(caixa_texto)._y;
}
*/
/*---------------------------------------------------
*	Ações relacionadas ao evento de troca de frame. Similar a função main() do projeto - Guto - 30.04.10
---------------------------------------------------*/
this.onEnterFrame = function() {

	if(editarMundoBL){
		
	}
	else{
		/*	if (fullScreen == true) {						//Ativar o modo fullScreen - Guto - 25.05.10
			Stage["displayState"] = "fullScreen";
		} else {
			Stage["displayState"] = "normal";
		}
		*/	
		
		if (chamouLink == false) {			//testa se o mp está preso em algum objeto. Se foi chamado link, não é necessário. - Guto - 09.07.09
			preso();
		}	
		if (btAba1._currentframe != 1){		//Se a aba de administração está em uso - Guto - 26.01.10
			btAba1.btTurma._y = btAba1.btUsuario._y + btAba1.btUsuario._height + 20; 
		}
		if (selAba1) {		
			portalCentro._y = btAba1._y + btAba1._height + 20;
		}	
		
		menu.atualizacoesEnterFrame();
	
		msgBotaoVel();  //carrega a mensagem que serah mostrada no botao da velocidade dependendo da velocidade do personagem gravada no BD - Roger - 03.08.09

		//função a seguir chamada toda vez que um novo frame é desenhado. tentar otimizar. - diogo
		moveOp();		//Movimenta os ops pelo cenário 
		
		if (!bloqMov) {		//testa se o botão de início foi acionado - eD - 03/11/08	
			if(usuario_status.velocidade) {
				 speedMp = 15;
			} else {
				 speedMp = 5;
			}
		
			if (mousePress==true){			
		        xmouse = _xmouse - mp._x - mp.sombra._x - (mp.sombra._width)/2; //Qndo o mouse é clicado no terreno salva a posicao em xmouse e ymouse - Roger - 23.07.09
	    	    ymouse = _ymouse - mp._y - mp.sombra._y - (mp.sombra._height)/2;	
	        	clickMouse = true;
			}
		
			speedMpDiag = Math.round(Math.pow((speedMp*speedMp)/2,(1/2))); //Componente x e y do movimento em diagonal z = (2*x^2)^(1/2), onde z é speedMp. Deve ser inteiro para evitar grande acúmulo de dígitos após a vírgula - Guto - 04/03/09
			
			direcaoMovMp=dirMovMp();
		
			if (direcaoMovMp != "0")       
				movMpTecladoMouse(direcaoMovMp); // funcao de movimento do mp pelo mouse e pelo teclado - Roger - 15/07/2009	         
		}
		
		/*---------------------------------------------------
		*	Posiciona e mostra uma caixa com o nome do objeto quando o mouse 
		*   está em cima de algum indicador - jean - 09.07.10
		---------------------------------------------------*/
		if (traceLocal == true){														
		
			switch(tipoMapa){
			case 1:
				localizacao._x = mapa._x + mapa.indicadorMp._x + 5;
				localizacao._y = mapa._y + mapa.indicadorMp._y - 30;
				localizacao.nome.text = usuario_status.personagem_nome; 				//atribui o nome do personagem(mp) no minimapa
			break;
		
			case 2:																		//posiociona e atribui o nome do op no mapa
				localizacao._x = mapa._x + eval(nomeMapa)._x ;
				localizacao._y = mapa._y + eval(nomeMapa)._y - 30;
				localizacao.nome.text = nomeObjeto;
			break;
			
			case 3:
				localizacao._x = mapa._x + eval(nomeMapa)._x + ((eval(nomeMapa)._width)/2) + 5;
				localizacao._y = mapa._y + eval(nomeMapa)._y + ((eval(nomeMapa)._height)/2) - 30;	
				switch (eval(nomeObjeto)._currentframe)									//atribui o nome do objeto_link no minimapa em relação ao frame que se encontra
					{
						case 1:
							 localizacao.nome.text  = "BIBLIOTECA";						//atribui o nome biblioteca no minimapa
						break;
						 
						case 2:
							localizacao.nome.text  = "BLOG";							//atribui o nome blog no minimapa
						break;
						
						case 3:
							localizacao.nome.text  = "FORUM";					//atribui o nome link externo no minimapa
						break;
					
						case 4:
							localizacao.nome.text  = "PORTFOLIO";						//atribui o nome portfolio no minimapa
						break;
					
						case 5:
							localizacao.nome.text  = "LINK EXTERNO";					//atribui o nome link externo no minimapa
						break;
					
						default:
					
						break;
					}			
			break;
		
			case 4:
				localizacao._x = velCtrl1._x + velCtrl1.speed1._x + 30;				//posiciona a caixa de texto em relação a poição do mouse
				localizacao._y = velCtrl1._y + velCtrl1.speed1._y - 30;
				//localizacao._xscale  =100;
				localizacao.nome.text = "CORRER";									//preenche a caixa de texto depois de posicionada com o nome do objeto 
			break;
		
			case 5:
				localizacao._x = velCtrl1._x + velCtrl1.speed2._x + 30;				//posiciona a caixa de texto em relação a poição do mouse
				localizacao._y = velCtrl1._y + velCtrl1.speed2._y - 30;
				localizacao.nome.text = "CAMINHAR";									//preenche a caixa de texto depois de posicionada com o nome do objeto 
				//localizacao._xscale  = -100;
			break;
			default:
		
			break;
			}
		}
		else if (traceLocal == false){
			localizacao._y = inilocalY ;
		}
		/*---------------------------------------------------
		*	Controle da rolagem da inserção de usuários da ferramenta de administração - Guto - 20.05.10
		* 	Deve considerar uma relação entre a capacidade de excursão da rolagem 
		*   e a quantidade de opções além do limite de 5 botões. A quantidade é diminuida 
		* 	de 4 pois se tiver 6 botões, meia barra deve mostrar do 1 ao 5 a outra do 2 ao 6.	
		---------------------------------------------------*/
		if (nivelDropDown == true) {
			primeiroDropDown = 0;
			if (nivelSel.length > 7) {
				escalaScrollDropdown = (limiteInferiorScrollDropdown - limiteSuperiorScrollDropdown) / (nivelSel.length - 6);
				for (var i:Number = 0; i < nivelSel.length - 6; i++) {
					if ((dropDown.barraRolagem.barra_scroll._y > (limiteSuperiorScrollDropdown + (escalaScrollDropdown * i))) and (dropDown.barraRolagem.barra_scroll._y < (limiteSuperiorScrollDropdown + (escalaScrollDropdown * (i + 1))) + 10)) {		//É colocado o +10 para uma folga no limite, pois quando se arrasta a barra de rolagem é possível colocá-la alguns pixels fora do limite estabelecido. - Guto - 20.05.10
						for (var n:Number = 0; n < 7; n++) {
							eval("dropDown.btSelAdm" + n).conteudo.text = nivelSel[n + i][1];
						}
						primeiroDropDown = i;
					}	
				}
			}
		} 
	
		if (dropDownScrollUp == "pressionado") {
			if (((dropDown.barraRolagem.barra_scroll._y - 2) > limiteSuperiorScrollDropdown) and ((dropDown.barraRolagem.barra_scroll._y - 2) < limiteInferiorScrollDropdown)) {		
				dropDown.barraRolagem.barra_scroll._y -= 2;
			}		
		} else if (dropDownScrollDown == "pressionado") {
			if (((dropDown.barraRolagem.barra_scroll._y + 2) > limiteSuperiorScrollDropdown) and ((dropDown.barraRolagem.barra_scroll._y + 2) < limiteInferiorScrollDropdown)) {		
				dropDown.barraRolagem.barra_scroll._y += 2;
			}		
		}
	
	
		/*---------------------------------------------------
		*	Posicionamento do comunicador - Giovani - 05.05.10
		---------------------------------------------------*/
		/*
		contatos._y = 591 + chat_box_main.chatSubir._y;
		turmas._y = 591 + chat_box_main.chatSubir._y;
		if(chat_box_main._currentframe == 10){
			chat_box_main.chat_geral._height = 400;
			chat_box_main.chat_geral._y = -300;
		}
		else{
			chat_box_main.chat_geral._height = 71;
			chat_box_main.chat_geral._y = 30.9;
		}
		*/
		/*
		contatos._y = chat_box_main._y + chat_box_main.chatSubir._y - botaoContatoHeight;
		turmas._y = chat_box_main._y + chat_box_main.chatSubir._y - botaoTurmasHeight;
		chat_terreno._y = chat_box_main._y + chat_box_main.chatSubir._y - botaoChatTerrenoHeight;
		dimensionar_caixa_texto("chat_box_main."+comunicador.get_caixa_texto_ativa());
		*/	
	
		/*-----------------------------------------------
	    *	Mapa do terreno - Giovani - 05.05.10
		------------------------------------------------*/
		mapa.indicadorMp._x = mapa.mapaEscala._x + usuario_status.personagem_posicao_x / this['outroTerreno'].COMPRIMENTO_AREA_UTIL * (mapa.mapaEscala._width - mapa.indicadorMp._width);
		mapa.indicadorMp._y  = mapa.mapaEscala._y + usuario_status.personagem_posicao_y / this['outroTerreno'].LARGURA_AREA_UTIL * (mapa.mapaEscala._height - mapa.indicadorMp._height);
	
		if (n_matriz_op > 0 ) 									//Coloca os Op's no mapa - Jean - 14.07.10
		{			
			for( var k=0; k< n_matriz_op; k++)
			{
				id = matriz_op[k][0];
				eval("mapa.indicadorOP"+id)._x = mapa.mapaEscala._x + matriz_op[k][1]/ this['outroTerreno'].COMPRIMENTO_AREA_UTIL * (mapa.mapaEscala._width - eval("mapa.indicadorOP"+id)._width);		//Posiciona o indicador no mapa - Jean - 14.07.10
				eval("mapa.indicadorOP"+id)._y  = mapa.mapaEscala._y + matriz_op[k][2]/ this['outroTerreno'].LARGURA_AREA_UTIL * (mapa.mapaEscala._height - eval("mapa.indicadorOP"+id)._height);		
			}
		}
	}
}