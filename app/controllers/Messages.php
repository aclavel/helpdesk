<?php
/**
 * Gestion des messages
 * @author jcheron
 * @version 1.1
 * @package helpdesk.controllers
 */
class Messages extends \_DefaultController {
	public function Messages(){
		parent::__construct();
		$this->title="Messages";
		$this->model="Message";
	}
	
	
	public function nouveauMess() {
		$contenu = $_POST['contenu'];
		$iduser = Auth::getUser()->getId();
		$idTicket = $_POST['ticket'];
		echo $contenu;
		echo $iduser;
		
		
		/*$requete = "INSERT INTO message VALUES('',)";
		$statement=DAO::$db->prepareStatement($requete);
		$result= $statement->execute();*/
	}
}