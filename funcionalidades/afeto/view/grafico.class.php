<?php

require_once("subjetividade.class.php");
require_once("afetividade.class.php");

class grafico{
//dados
	
	private $nome;
	private $dataInicio;
	private $dataFim;
	
	private $bgcolor;
	
	/*
	* Deve ser um objeto da classe afetividade.
	*/
	private $afetividade_param;

	/*
	* Define se o gráfico exibe em meses ou semanas. Contém grafico::DIVISAO_MESES ou grafico::DIVISAO_SEMANAS
	*/
	const DIVISAO_MESES = 1;
	const DIVISAO_SEMANAS = 2;
	private $divisaoPeriodo;

	/*
	* Literalmente 'Mês' ou 'Ano', dependendo do que for especificado em divisaoPeriodo.
	*/
	private $periodo;
	
//métodos
	public function grafico(){
		$this->nome = 'nome';
		$this->dataInicio = 'dataInicio';
		$this->dataFim = 'dataFim';
		$this->periodo = 'periodo';
		$this->bgcolor = 0x000000;
	}
	
	/*
	* Define se o gráfico exibe em meses ou semanas.
	* @param divisao_param grafico::DIVISAO_MESES ou grafico::DIVISAO_SEMANAS.
	*/
	public function setDivisaoPeriodo($divisao_param){
		$this->divisaoPeriodo = $divisao_param;
		if($divisao_param == grafico::DIVISAO_MESES){
			$this->periodo = "Mês";
		} else if($divisao_param == grafico::DIVISAO_SEMANAS){
			$this->periodo = "Semana";
		}
	}
	
	/*
	* @param afetividade_param Um objeto da classe afetividade.
	*/
	public function setAfetividade($afetividade_param){
		$this->afetividade = $afetividade_param;
	}
	
	/*
	* Mostra o gráfico. Dá um echo no HTML do gráfico, portanto deve ser executada antes do carregamento completo da página.
	*/
	public function imprimir(){
		echo "<script type=\"text/javascript\" language=\"javascript\">
			$(document).ready(function(){
				$('.botao').click(function(){
					$('.botao').removeClass('botao_ativo');
					$(this).addClass('botao_ativo');
					$('.conteudos').css('display','none');
					$('#conteudo' +this.id.substr(5)).css('display','block');
				});
			})
			</script>";
		echo 	"<div style=\"width:670px; margin-left:-5px; position:relative; z-index:1000\">
					<div style='border:0px solid; min-height:520px;text-align:left;background-color:#666; padding-top:20px; padding-bottom:20px; float:left'>
						<center>
							<div id=\"infografo\"><div id=\"fecha_info\" align=\"right\">fechar ajuda</div><img src=\"imagens/infografo.png\" /></div>
							<div id=\"geral\">
								<div id=\"logo\"><img src=\"imagens/logo_afeto.png\" /></div>
								
								<div id=\"nome\" class=\"bg0 largura\"><center>".ucwords(mb_strtolower($this->nome))." - Gráfico de afetividade</center></div>
								<div id=\"periodo\" class=\"bg1 largura\"><center>Período: ".$this->dataInicio." a ".$this->dataFim."	<br />	Divisão de período: ".$this->periodo."</center></div>
								
								<div id=\"botoes\" class=\"largura\">
									<div id=\"botao1\" class=\"botao botao_ativo\" onClick=\"function(){ $('.botao').removeClass('botao_ativo'); $(this).addClass('botao_ativo'); $('.conteudos').css('display','none'); $('#conteudo' +this.id.substr(5)).css('display','block');}\"><p>Subjetividade<br />em texto</p><img src=\"imagens/botao_grafico3.png\" /></div>
									<div id=\"botao2\" class=\"botao\"><p>Gráfico de fatores<br />motivacionais</p><img src=\"imagens/botao_grafico2.png\"  onClick=\"function(){ $('.botao').removeClass('botao_ativo'); $(this).addClass('botao_ativo'); $('.conteudos').css('display','none'); $('#conteudo' +this.id.substr(5)).css('display','block'); }\"/></div>
									<div id=\"botao3\" class=\"botao\"><p>Gráfico geral<br />de afetividade</p><img src=\"imagens/botao_grafico1.png\"  onClick=\"function(){ $('.botao').removeClass('botao_ativo'); $(this).addClass('botao_ativo'); $('.conteudos').css('display','none'); $('#conteudo' +this.id.substr(5)).css('display','block'); }\"/></div>
								</div>
								".$this->getGraficoSubjetividade()."
								".$this->getGraficoFatoresMotivacionais()."
								".$this->getGraficoGeral()."
							</div>
						</center>
					</div>
				</div>";
	}
	private function getGraficoSubjetividade(){
		$grafico = "<div class=\"conteudos\" id=\"conteudo1\">
									<table><tbody>
										<tr>
											<td class=\"grafico\" colspan=\"2\" style=\"bgcolor:".$this->bgcolor."\">
												<div id=\"ajudaafeto\" class=\"largura\"><center><img id=\"abre_ajuda\" src=\"imagens/botao_ajuda.png\" /></center></div>
											</td>
										</tr>
										<tr>
											<td class=\"grafico\">
												<div id='capsula'>
													<img src=\"roodaAfeto/aaaaux.php?pts=0,0,0,0,0,0,4,3,4,0,0,0,0,0,0,3,1,2,3.5,1,1,0,0,0,3,1,4,0,0,0,4,1,3,0,0,0,0,0,0,0,0,0,3,1,4,0,0,0,0,0,0,3.5,4,4,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,\" style=\"z-index:0\" id=\"dophp\">
												</div>
											</td>
											<td class=\"resultado\">
												<ul class=\"resultado_ul\">
												<li style='background-color:".$this->bgcolor.";font-size:11px;line-height:15px;'>";
		$qualSemanaOuMes = 1;
		$emocao = $this->afetividade->getSubjetividade()->getEmocaoPeriodo($this->divisaoPeriodo, $qualSemanaOuMes);
		while($emocao != null){
			$grafico.= "<img style=\"width:10px;height:10px;float:left;margin-right:10px;border: solid 1px #aaa\" src=\"quadradinho.php?q=3&amp;s=".$emocao."\">
							".$this->periodo." ".$qualSemanaOuMes.", intens. ".$emocao."
						<br>";
			$qualSemanaOuMes++;
			$emocao = $this->afetividade->getSubjetividade()->getEmocaoPeriodo($this->divisaoPeriodo, $qualSemanaOuMes);
		}
		$grafico.=									"</li>
													<li style=\"float:none; background-color:#444444\">
														<a href=\"download.php?p=Semana&amp;pts=0,0,0,0,0,0,4,3,4,0,0,0,0,0,0,3,1,2,3.5,1,1,0,0,0,3,1,4,0,0,0,4,1,3,0,0,0,0,0,0,0,0,0,3,1,4,0,0,0,0,0,0,3.5,4,4,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,&amp;cur=45963&amp;ctr=11419\" 
																target=\"_blank\" style=\"color:#ffcb11\">Download(versão TXT)</a>
													</li>
												</ul>
											</td>
										</tr>
									</tbody></table>
								</div>";
		return $grafico;
	}
	private function getGraficoFatoresMotivacionais(){
		return "<div class=\"conteudos\" id=\"conteudo2\" style=\"display: none; \">
									<ul style=\"margin:0\">
										<li class=\"tabela\">
											<div id=\"fatores_total\">
											</div>
											<table style=\"width:100%\"><tbody>
												<tr>
													<td class=\"grafico\" colspan=\"2\" style=\"bgcolor:#444444\">
														<div id=\"ajudaafeto2\" class=\"largura\"><center><img id=\"abre_ajuda2\" src=\"imagens/botao_ajuda.png\"></center></div>
													</td>
												</tr>
												<tr>
													<td class=\"grafico\">
														<img src=\"roodaAfeto/fatores/grafico_barras.php?confianca=0.631578947368&amp;esforco=0.65&amp;independencia=1&amp;size=422\">
													</td>
													<td class=\"resultado\">
														<ul class=\"resultado_ul\">
															<li>
																<p style=\"margin:7px 0 7px 0; float:left\"><img style=\"width:10px;height:10px;margin:5px; float:left; clear:both\" src=\"roodaAfeto/fatores/quadradinho_2.php?crc=confianca\"><span class=\"tipo\">Confiança: <br>63.16%</span></p><br>
																<p style=\"margin:7px 0 7px 0; float:left\"><img style=\"width:10px;height:10px;margin:5px; float:left; clear:both\" src=\"fatores/quadradinho_2.php?crc=esforco\"><span class=\"tipo\">Esforço: <br>65%</span></p><br>
																<p style=\"margin:7px 0 7px 0; float:left\"><img style=\"width:10px;height:10px;margin:5px; float:left; clear:both\" src=\"fatores/quadradinho_2.php?crc=independencia\"><span class=\"tipo\">Independência: <br>100%</span></p><br>
															</li>
														</ul>
													</td>
												</tr>
											</tbody></table>
											<script>
												function descreve(elemento){
													var descricao = new Array();
														descricao[\"for_na\"] =	\"<b>NA (Número de acessos)</b><br />definido pelo ato de abrir ou entrar na funcionalidade.\";
														descricao[\"for_nv\"] =	\"<b>NV (Número de vistas ao tópico)</b><br />definido pela quantidade de vezes em que um usuário visitou um tópico do fórum.\";
														descricao[\"for_fp\"] =	\"<b>FP (Frequência de participação)</b><br />índice probabilístico que relaciona o número de vezes em que o aluno participou, no Fórum, em relação à turma.\";
														descricao[\"for_mp_f\"] =	\"<b>MP (Modo de participação)</b><br />verificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum formador.\";
														descricao[\"for_mp_c\"] =	\"<b>MP (Modo de participação)</b><br />verificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum colega.\";
														descricao[\"for_to_t\"] =	\"<b>TO (Geração de tópicos)</b><br />indica a criação de novos tópicos para a funcionalidade Fórum.\";
														descricao[\"for_to_m\"] =	\"<b>TO (Geração de mensagens)</b><br />indica a criação de novas mensagens em um tópico para a funcionalidade Fórum.\";
														descricao[\"btp_fp\"] =	\"<b>FP (Frequência de participação)</b><br />índice probabilístico que relaciona o número de vezes em que o aluno participou, no Bate-Papo, em relação à turma.\";
														descricao[\"ddb_fp\"] =	\"<b>FP (Frequência de participação)</b><br />índice probabilístico que relaciona o número de vezes em que o aluno participou, no Diàrio de Bordo, em relação à turma.\";
														descricao[\"tp_tp\"] =	\"<b>TP (Tempo de permanência na sessão)</b><br />representa a média de tempo despendido em uma sessão.\";
													document.getElementById(\"explicacao\").innerHTML = descricao[elemento];
												}
											</script>
											<style>
												.titulo_fatores{
													
												}
												#tabela_fatores{
													width: 100%;
												}
												a.nolink{
													color:blue;
												}
												.col_1{
													font-size:80%;
													border: 1px dotted black;
													width: 20%;
													text-align: center;
												}
												.col_2{
													font-size:80%;
													border: 1px dotted black;
													width: 80%;
													text-align: center;
												}
											</style>
											<center><br>
											<a name=\"anch\"></a><br>
											<table id=\"tabela_fatores\">
												<tbody><tr>
													<th colspan=\"2\" class=\"titulo_fatores\">
														Dados baseados em: FÓRUM
													</th>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_nv&quot;)\">Número de vistas ao tópico</a>
													</td>
													<td class=\"col_2\">
														números de acessos é igual ou superior à média <br>(acessos do aluno: 243 ; média de acessos da turma: 184)
													</td>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_mp_f&quot;)\">Modo de participação</a>
													</td>
													<td class=\"col_2\">
														respondeu ao formador
													</td>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_mp_c&quot;)\">Modo de participação</a>
													</td>
													<td class=\"col_2\">
														respondeu ao colega
													</td>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_to_t&quot;)\">Geração de tópicos</a>
													</td>
													<td class=\"col_2\">
														não criou nenhum tópico
													</td>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_to_m&quot;)\">Geração de mensagens</a>
													</td>
													<td class=\"col_2\">
														criou alguma mensagem
													</td>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;for_fp&quot;)\">Frequência de participação</a>
													</td>
													<td class=\"col_2\">
														alto (entre 50% e 75%,inclusive)
													</td>
												</tr>
												<tr><th></th></tr>
												<tr><th><br></th></tr>
												<tr>
													<th colspan=\"2\" class=\"titulo_fatores\">
														Dados baseados em: DIÀRIO DE BORDO
													</th>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;ddb_fp&quot;)\">Frequência de participação</a>
													</td>
													<td class=\"col_2\">
														muito alto (entre 75% e 100%,inclusive)
													</td>
												</tr>
												<tr><th></th></tr>
												<tr><th><br></th></tr>
												<tr>
													<th colspan=\"2\" class=\"titulo_fatores\">
														Dados baseados em: TEMPO
													</th>
												</tr>
												<tr>
													<td class=\"col_1\">
														<a class=\"nolink\" href=\"#anch\" onclick=\"javascript:descreve(&quot;tp_tp&quot;)\">Tempo de permanência na sessão</a>
													</td>
													<td class=\"col_2\">
														tempo médio de sessão é superior à média da turma <br>(tempo médio do aluno: 3240 s; tempo médio da turma: 2842 s)
													</td>
												</tr>
												<tr><th></th></tr>
												<tr><th><br></th></tr>
												<tr>
													<td colspan=\"2\">(*) Para descrição mais detalhada do elemento, clique no link</td>
												</tr>
												<tr>
													<td colspan=\"2\">
														<br><br>
														<div id=\"explicacao\" height=\"50px\"></div></td>
												</tr>
											</tbody></table>
											</center>
										</li>
									</ul>
								</div>";
	}
	private function getGraficoGeral(){
		return "<div class=\"conteudos\" id=\"conteudo3\" style=\"display: none; \">
									<ul style=\"margin:0\">
										<li class=\"tabela\">
											<table style=\"width:100%\"><tbody>
												<tr>
													<td class=\"grafico\" colspan=\"2\" style=\"bgcolor:#444444\">
														<div id=\"ajudaafeto3\" class=\"largura\"><center><img id=\"abre_ajuda3\" src=\"imagens/botao_ajuda.png\"></center></div>
													</td>
												</tr>
												<tr>
													<td>
														<div id=\"graficogeral\" class=\"grafico\">
															<img src=\"roodaAfeto/fatores/grafico.geral.php?0=0.180312317838&amp;1=0.294978271288&amp;2=0.0692213924125&amp;3=0.083588850075&amp;4=0.371899168388\">
														</div>
													</td>
													<td class=\"resultado\" style=\"vertical-align:top\">
														<ul id=\"legendageral\" class=\"resultado resultado_ul\" style=\"list-style: none\">
															<li>
																<img src=\"quadradinho.php?awyeah=satisfeito\" style=\"float:left;height:10px;margin-right:10px;width: 10px;\">
																satisfeito:29.5%
															</li>
															<li>
																<img src=\"quadradinho.php?awyeah=insatisfeito\" style=\"float:left;height:10px;margin-right:10px;width: 10px;\">
																insatisfeito:6.92%
															</li>
															<li>
																<img src=\"quadradinho.php?awyeah=animado\" style=\"float:left;height:10px;margin-right:10px;width: 10px;\">
																animado:37.19%
															</li>
															<li>
																<img src=\"quadradinho.php?awyeah=desanimado\" style=\"float:left;height:10px;margin-right:10px;width: 10px;\">
																desanimado:8.36%
															</li>
															<li>
																<img src=\"quadradinho.php?awyeah=indefinido\" style=\"float:left;height:10px;margin-right:10px;width: 10px;\">
																indefinido:18.03%
															</li>
														</ul>
													</td>
												</tr></tbody>
											</table>
										</li>
									</ul>
								</div>";
	}
	
	
}


?>