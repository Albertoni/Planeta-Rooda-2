var mouseX = 0;
var mouseY = 0;
var mouse_andamento_arrasto = false;
var mouse_volume_arrasto = false;
var IE = document.all?true:false;
var ROODAplayer = {
	carga_total : 0,
	carga : 0,
	carga_inicial : 0,
	percent_andamento : 0,
	percent_carga : 0,
	percent_carga_inicial : 0,
	barra_total : 286,
	duracao : 0,
	andamento : 0,
	volume : 50,
	mudo : false,
	video : "null",
	estado : 5,
	rodando : false
};

document.onmouseup = getMouseXY;

		function getMouseXY(e){
			if(IE){
				mouseX = event.clientX + document.body.scrollLeft;
				mouseY = event.clientY + document.body.scrollTop;
			}else{
				mouseX = e.pageX;
				mouseY = e.pageY;
			}
			if(mouseX < 0){mouseX = 0;}
			if(mouseY < 0){mouseY = 0;}
			if(mouse_andamento_arrasto){arrastaVideo();}
			mouse_andamento_arrasto = false;
			mouse_volume_arrasto = false;
		}

	function seekTo(seconds) {
		if (ytplayer) {
			ytplayer.seekTo(seconds, true);
		}
	}

	function getDuration() {
		if (ytplayer) {
			return ytplayer.getDuration();
		}
	}
		
	function inicia_player(){
			document.getElementById("barra").onmousedown = function(eve){mouse_andamento_arrasto = true;};
			document.getElementById("barra").onmouseup = function(eve){
				setTimeout("arrastaVideo()",50);
			};
			document.getElementById("barra_carrega").onmousedown = function(eve){mouse_andamento_arrasto = true;};
			document.getElementById("barra_carrega").onmouseup = function(eve){
				setTimeout("arrastaVideo()",50);
			};
			document.getElementById("barra_andamento").onmousedown = function(eve){mouse_andamento_arrasto = true;};
			document.getElementById("barra_andamento").onmouseup = function(eve){
				setTimeout("arrastaVideo()",50);
			};
	}

	function arrastaVideo(){
		var andamento = 0;
		var barraX0 = document.getElementById("barra").offsetLeft + document.getElementById("geral").offsetLeft + document.getElementById("embede").offsetLeft + document.getElementById("conteudo").offsetLeft;
		var barraX1 = barraX0 + document.getElementById("barra").offsetWidth;
		
		if (mouseX < (barraX0 + 2)) andamento = 0;
		if (mouseX > (barraX1 - 2)) andamento = 296;
		
		andamento = mouseX - barraX0;
		//console.log("mouseX:"+mouseX +" barraX0:"+barraX0);
		multiplicador = andamento/296;
		
		tempo = multiplicador*getDuration();
		seekTo(tempo);
		
		ROODAplayer.percent_andamento = multiplicador;
		document.getElementById("bolinha").style.width = 15 + "px";
	}

	setInterval("atualizacoes()",100);

	function updatePlayerInfo() {
		if(ytplayer && ytplayer.getDuration) {
			ROODAplayer.duracao = ytplayer.getDuration();
			ROODAplayer.andamento = ytplayer.getCurrentTime();

			ROODAplayer.carga_total = ytplayer.getVideoBytesTotal();
			ROODAplayer.carga = ytplayer.getVideoBytesLoaded();
			ROODAplayer.carga_inicial = ytplayer.getVideoStartBytes();

			ROODAplayer.percent_carga = ROODAplayer.carga/ROODAplayer.carga_total;
			ROODAplayer.percent_carga_inicial = ROODAplayer.carga_inicial/ROODAplayer.carga_total;
			ROODAplayer.percent_andamento = ROODAplayer.andamento/ROODAplayer.duracao;
		}
	}
	function onPlayerStateChange(newState) {
		ROODAplayer.estado = newState;
		if(newState == 1)
		{ 
			document.getElementById("play").style.backgroundImage = "url(play1.png)";
			document.getElementById("pause").style.backgroundImage = "url(pause.png)";
		}
		if(newState == 0 || newState == 5)
		{
			document.getElementById("play").style.backgroundImage = "url(play.png)";
			document.getElementById("pause").style.backgroundImage = "url(pause.png)";
		}
		if(newState == 2)
		{
			document.getElementById("play").style.backgroundImage = "url(play.png)";
			document.getElementById("pause").style.backgroundImage = "url(pause1.png)";
		}
	}
	function atualizacoes(){
		document.getElementById("barra_carrega").style.width = (ROODAplayer.percent_carga * ROODAplayer.barra_total)+"px";
		document.getElementById("barra_andamento").style.width = (ROODAplayer.percent_andamento * ROODAplayer.barra_total)+"px";
		document.getElementById("barra_branco").style.width = (ROODAplayer.percent_carga_inicial * ROODAplayer.barra_total)+"px";
		document.getElementById("bolinha").style.left = (ROODAplayer.percent_andamento * ROODAplayer.barra_total - 4)+"px";
	}
	
	// Update a particular HTML element with a new value
	function updateHTML(elmId, value) {
		document.getElementById(elmId).innerHTML = value;
	}
	
	
	// This function is called when an error is thrown by the player
	function onPlayerError(errorCode) {
		alert("An error occured of type:" + errorCode);
	}
	
	function setVideoVolume(volume,id) {
		if(volume > 100) volume = 100;
		if(volume < 0) volume = 0;
		
		if (id < 0){
			id = Math.floor(volume/12.5);
		}
		
		for (i=0; i< 8; i++){
			if (document.getElementById("vol"+id)){
				if ((i <= id) && (volume > 0)){
					document.getElementById("vol"+i).style.backgroundColor = "purple";
				}else{
					document.getElementById("vol"+i).style.backgroundColor = "silver";
				}
			}
		}
		try{
			if(ytplayer) {ytplayer.setVolume(volume);}
		}catch(e){}
		
		ROODAplayer.volume = volume;
	}
	
		function playVideo() {
		document.getElementById("play").style.backgroundImage = "url(play1.png)";
		document.getElementById("pause").style.backgroundImage = "url(pause.png)";
		if (ytplayer) {
			ytplayer.setPlaybackQuality("medium");
			ytplayer.playVideo();
		}
	}
	
	function pauseVideo() {
		document.getElementById("pause").style.backgroundImage = "url(pause1.png)";
		document.getElementById("play").style.backgroundImage = "url(play.png)";
		if (ytplayer) {
			ytplayer.pauseVideo();
		}
	}
	
	function setSom(mudo)
	{
		if(!mudo)
		{
			muteVideo();
		}
		else unMuteVideo();
	}
	
	function muteVideo() {
		document.getElementById("mute").style.backgroundImage = "url(mute.png)";
		if(ytplayer) {
			ytplayer.mute();
		}
		ROODAplayer.mudo = true;
	}
	
	function unMuteVideo() {
		document.getElementById("mute").style.backgroundImage = "url(unmute.png)";
		if(ytplayer) {
			ytplayer.unMute();
		}
		ROODAplayer.mudo = false;
	}
	
	//arrumar essa parte de selecionar um video
	function selectVideo(id, turma)
	{
		var select = document.getElementById("videoSelection"+id);
		var videoURL = select.name;
		
		if(ytplayer){
			ytplayer.cueVideoByUrl(videoURL);
		}
		
		ROODAplayer.video = videoURL;
		
		document.getElementById("nomeVideo").innerHTML = select.innerHTML;
		document.getElementById("descricaoVideo").innerHTML = document.getElementById("desc"+id).innerHTML;
		document.getElementById("donoVideo").innerHTML = document.getElementById("nome"+id).document;
		$(innerHTML.getElementById("comentariosVideo").innerHTML = document.getElementById("numcom"+id)).append((new Comentarios(id)).link);
	}
	
	function addVideo(idUsu,idTur)
	{
		var link = "arquivo.php?codTurma="+idTur+"&codUsuario="+idUsu;
		window.open(link, "Adicionar arquivo", "width=550, height=400, top=200, left=800, scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no");
	}
	
	function deleteVideo(idVid, codTurma)
	{
		var a = newAjax();
		if(a){
			a.open('POST','delete.php',true);
			a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			a.send("codVideo="+idVid+"&codTurma="+codTurma);
			a.onreadystatechange = function(){
				if(a.readyState == 4){
					if(a.status == 200){
						if (a.responseText == "del-ok"){
							alert("Video deletado com sucesso.");
							document.getElementById("linha"+idVid).style.display="none";
						}else{
							alert('Erro com a solicitação! ('+a.responseText+')');
						}
					}else{
						alert('Erro com a solicitação! ('+a.responseText+')');
					}
				}
			}
		}
	}
	
	// This function is automatically called by the player once it loads
	function onYouTubePlayerReady(playerId) {
		ytplayer = document.getElementById("ytPlayer");
		// This causes the updatePlayerInfo function to be called every 250ms to
		// get fresh data from the player
		setInterval(updatePlayerInfo, 250);
		updatePlayerInfo();
		ytplayer.addEventListener("onStateChange", "onPlayerStateChange");
		ytplayer.addEventListener("onError", "onPlayerError");
		//Load an initial video into the player
		if(ROODAplayer.video!="null") {ytplayer.cueVideoByUrl(ROODAplayer.video);} //o valor da variavel não está sendo alterado
	}
	
	// The "main method" of this sample. Called when someone clicks "Run".
	function loadPlayer() {
		// Lets Flash from another domain call JavaScript
		var params = { allowScriptAccess: "always" };
		// The element id of the Flash embed
		var atts = { id: "ytPlayer" };
		// All of the magic handled by SWFObject (http://code.google.com/p/swfobject/)
		swfobject.embedSWF("http://www.youtube.com/apiplayer?version=3&enablejsapi=1&playerapiid=ytPlayer"/*ROODAplayer.video*/,
							"videoDiv", "580", "297", "8", null, null, params, atts);
		setVideoVolume(ROODAplayer.volume,-1);
	}
	
	function _run() {
		loadPlayer();
	}
	
	google.setOnLoadCallback(_run);




function newAjax() {
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		aux_ajax = new XMLHttpRequest();
		if (aux_ajax.overrideMimeType) {
			aux_ajax.overrideMimeType('text/xml');
			// See note below about this line
		}
	}
	else if (window.ActiveXObject) { // IE
		try {
			aux_ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				aux_ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}

	if (!aux_ajax) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	return aux_ajax;
}	

function carregaHTML(obj_id,script_url,pars) {
	var a = newAjax();
	var obj = document.getElementById(obj_id);
	if(a) {
		a.open('POST',script_url + '.php',true);
		a.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		a.send(pars);
		a.onreadystatechange = function() {
			if(a.readyState == 4) {
				if(a.status == 200) {
					obj.innerHTML = a.responseText;
				} else {
					alert('Erro com a solicitação! (AJAX)');
				}
			}
		}
	}
}
