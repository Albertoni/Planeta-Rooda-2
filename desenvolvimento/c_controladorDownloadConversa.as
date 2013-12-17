import flash.net.FileReference;

/*
* Classe que escuta e trata eventos do download de uma conversa de chat.
*/
class c_controladorDownloadConversa extends Object{
//dados



//métodos
	function c_controladorDownloadConversa(){
		super();
		this['onSelect'] = funcaoOnSelect;
		this['onCancel'] = funcaoOnCancel;
		this['onOpen'] = funcaoOnOpen;
		this['onProgress'] = funcaoOnProgress;
		this['onComplete'] = funcaoOnComplete;
		this['onIOError'] = funcaoOnIOError;
	}
	
	/*
	* Invoked when the user selects a file to upload or download from the file-browsing dialog box. 
	* (This dialog box is displayed when you call FileReference.browse(), FileReferenceList.browse(), or FileReference.download().) 
	* When the user selects a file and confirms the operation (for example, by clicking OK), the properties of the FileReference object are populated.
	* The onSelect listener works slightly differently depending on what method invokes it. 
	* When onSelect is invoked after a browse() call, Flash Player can read all of the FileReference object's properties, 
	* because the file selected by the user is on the local file system. When onSelect is invoked after a download() call, 
	* Flash Player can read only the name property, because the file hasn't yet been downloaded to the local file system at the moment onSelect is invoked. 
	* When the file has been downloaded and onComplete invoked, then Flash Player can read all other properties of the FileReference object.
	*/
	private function funcaoOnSelect(fileRef_param: FileReference):Void{
		//c_aviso_com_ok.mostrar("on select");
		fazNada();
	}
	/*
	* Invoked when the user dismisses the file-browsing dialog box. 
	* This dialog box is displayed when you call FileReference.browse(), FileReferenceList.browse(), or FileReference.download().
	*/
	private function funcaoOnCancel(fileRef_param: FileReference):Void{
		//c_aviso_com_ok.mostrar("on cancel");
		fazNada();
	}
	/*
	* Invoked when an upload or download operation starts.
	*/
	private function funcaoOnOpen(fileRef_param: FileReference):Void{
		//c_aviso_com_ok.mostrar("O download de seu arquivo foi iniciado.");
		fazNada();
	}
	/*
	* Invoked periodically during the file upload or download operation.
	* The onProgress listener is invoked while the Flash Player transmits bytes to a server, 
	* and it is periodically invoked during the transmission, even if the transmission is ultimately not successful. 
	* To determine if and when the file transmission is successful and complete, use onComplete.
	* In some cases, onProgress listeners are not invoked; for example, if the file being transmitted is very small, 
	* or if the upload or download happens very quickly.
	* File upload progress cannot be determined on Macintosh platforms earlier than OS X 10.3.
	* The onProgress event is called during the upload operation, but the value of the bytesLoaded parameter is -1, 
	* indicating that the progress cannot be determined.
	*/
	private function funcaoOnProgress(fileRef_param: FileReference, bytesLoaded_param: Number, bytesTotal_param: Number):Void{
		//c_aviso_com_ok.mostrar("on progress");
		fazNada();
	}
	/*
	* Invoked when the upload or download operation has successfully completed. 
	* Successful completion means that the entire file has been uploaded or downloaded.
	* For file download, this event listener is invoked when Flash Player has downloaded the entire file to disk. 
	* For file upload, this event listener is invoked after the Flash Player has received an HTTP status code of 200 
	* from the server receiving the transmission.
	*/
	private function funcaoOnComplete(fileRef_param: FileReference):Void{
		c_aviso_com_ok.mostrar("Chat salvo com sucesso!");
	}
	/*
	* Invoked when an input/output error occurs.
	* This listener is invoked when the upload or download fails for any of the following reasons:
	* An input/output error occurs while the player is reading, writing, or transmitting the file.
	* The SWF file tries to upload a file to a server that requires authentication, such as a user name and password. 
	*	During upload, Flash Player does not provide a means for users to enter passwords. 
	*	If a SWF file tries to upload a file to a server that requires authentication, the upload fails.
	* The SWF file tries to download a file from a server that requires authentication, in the stand-alone or external player.
	*	During download, the stand-alone and external players do not provide a means for users to enter passwords. If a SWF file 
	*	in these players tries to download a file from a server that requires authentication, the download fails. File download can 
	*	succeed only in the ActiveX control and browser plug-in players.
	* The value passed to the url parameter in upload() contains an invalid protocol. Valid protocols are HTTP and HTTPS.
	* Important: Only Flash applications that are running in a browser -- that is, using the browser plug-in or ActiveX control -- 
	*	can provide a dialog to prompt the user to enter a user name and password for authentication, and then only for downloads. 
	*	For uploads that use the plug-in or ActiveX control, or that upload and download using either the standalone or external players, 
	*	the file transfer fails.
	*/
	private function funcaoOnIOError(fileRef_param: FileReference):Void{
		c_aviso_com_ok.mostrar("Desculpe. Houve um erro ao tentar fazer o download.");
	}
	
	
	private function fazNada():Void{}
	
}