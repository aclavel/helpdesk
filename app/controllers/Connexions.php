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
	
	
	
	/* (non-PHPdoc)
	 * @see \micro\controllers\BaseController::index()
	 */
	public function index() {
		$this->header();
		$this->loadView("connexion/vConnexion");
	}
	
	public function compte() {
		$this->header2();
		$this->loadView("connexion/vCompte");
	}

	private function header() {
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader",array("infoUser"=>Auth::getInfoUser()));
			echo "<div class='container'>";
			echo "<h1>".$this->title."</h1>";
		}
	}
	
	private function header2() {
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader",array("infoUser"=>Auth::getInfoUser()));
			echo "<div class='container'>";
			echo "<h1>".$this->title2."</h1>";
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
		$this->loadView("main/vDefault");
		}
		else {
			$this->header();
			echo "<span> Votre mot de passe ou login est incorrecte. </span>";
		}
	}
	
	
	
}