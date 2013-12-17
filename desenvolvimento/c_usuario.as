class c_usuario {
//eD - funcionando a partir de 18/12/08
	public var personagem_id 					: Number = 0;
	public var personagem_avatar_1 				: Number = 0;
	public var personagem_cor_texto 			: Number = 0;
	public var ultima_atualizacao 				: Number = 0;
	public var usuario_nivel 					: Number = 0;
	public var usuario_grupo_base		 		: Number = 0;
	public var personagem_posicao_x 			: Number = 0;
	public var personagem_posicao_y 			: Number = 0;
	public var personagem_posicao_x_auxiliar	: Number = 0;
	public var personagem_posicao_y_auxiliar	: Number = 0;
	public var personagem_nome 					: String = "";
	public var personagem_login 				: String = "";
	public var personagem_aniver				: String = "";
	public var rotaMp							: String = ""; 			//Array  = new Array();					//Rota realizada pelo mp para ser enviada ao banco de dados e orientar o seu movimento em outros computadores, como op - guto - 18.02.09
	public var velocidade                   	: Boolean = false;  	//0 se personagem esta caminhando e 1 se o personagem esta correndo - Roger - 28.07.09
    public var personagem_linha_chat        	: Number = 0;    
    public var personagem_fala 					: String = "";
    public var private_chat                 	: String = "";
	public var pos_private_chat             	: Number = 0;
	public var lista_contatos               	: String = "";
	//public var grupo_chat                   	: String = "";
	//public var tamanho_grupo_chat           	: Number = 0;
    
}