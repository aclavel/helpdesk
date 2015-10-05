<?php
use micro\controllers\BaseController;
use micro\utils\RequestUtils;
use micro\orm\DAO;


/**
 * Gestion de la connexion
 * @author jcheron
 * @version 1.1
 * @package helpdesk.controllers
 */
class Connexions extends BaseController {
	public function Connexions(){
		$this->title="Connexion";
		$this->title2="Mon compte";
	}
	
	protected $model;
	
	/* (non-PHPdoc)
	 * @see \micro\controllers\BaseController::index()
	 */
	public function index() {
		$this->header();
		$this->loadView("connexion/vConnexion");
		
	}
	
	public function compte($id = NULL) {
		$this->title="Mon compte";
		$this->header();
		$user = Auth::getUser();
		$this->loadView("connexion/vCompte", array("user"=>$user));
	}

	private function header() {
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader",array("infoUser"=>Auth::getInfoUser()));
			echo "<div class='container'>";
			echo "<h1>".$this->title."</h1>";
		}
	}
	

	public function testConnexion() {
		$login = $_POST["login"] ;
		//echo $login;
		$mdp = $_POST["mdp"];
		//echo $mdp;
		$resultat = DAO::getOne("user", "login='".$login."' AND password='".$mdp."'");
		if($resultat != null) {
			$_SESSION["user"]=$resultat;
			$_SESSION['KCFINDER'] = array(
					'disabled' => false
					
			);
		$this->header();
		$notif= sizeof(DAO::getAll("Ticket", "idStatut='1'"));
		$this->loadView("main/vDefault", array("notif"=>$notif));
		
		//$this->loadView("connexion/vConnexion", array("notif"=>$notif));
		}
		else {
			$this->header();
			echo "<span> Votre mot de passe ou login est incorrecte. </span>";
		}
		
		
		
	}
	
	public function modifCompte() {
		$newLogin = $_POST["login"];
		$newMdp = $_POST["mdp"];
		$newMail = $_POST["mail"];
		
		$user = Auth::getUser();
		array("user"=>$user);
		$login = $user->getLogin();
		
		$requete = "UPDATE user SET login='".$newLogin."', password='".$newMdp."', mail='".$newMail."' WHERE login='".$login."'";
		$statement=DAO::$db->prepareStatement($requete);
		$result= $statement->execute();
		
		$user->setLogin($newLogin);
		$user->setPassword($newMdp);
		$user->setMail($newMail);
		
		$this->header();
		$this->loadView("main/vDefault");
		
	}
	
	
	
	
	
	
	
	
}