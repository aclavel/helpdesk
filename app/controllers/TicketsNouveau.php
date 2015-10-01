<?php

use micro\orm\DAO;
use micro\js\Jquery;
use micro\views\Gui;
/**
 * Gestion des tickets
 * @author jcheron
 * @version 1.1
 * @package helpdesk.controllers
 */
class TicketsNouveau extends \_DefaultController {
	public function TicketsNouveau(){
		parent::__construct();
		$this->title="Tickets";
		$this->model="Ticket";
	}





	public function messages($id){
		$ticket=DAO::getOne("Ticket", $id[0]);
	
		if($ticket!=NULL){
			echo "<h2>".$ticket."</h2>";
			$messages=DAO::getOneToMany($ticket, "messages");
			echo "<table class='table table-striped'>";
			echo "<thead><tr><th>Messages</th></tr></thead>";
			echo "<tbody>";
			foreach ($messages as $msg){
				echo "<tr>";
				echo "<td title='message' data-content='".htmlentities($msg->getContenu())."' data-container='body' data-toggle='popover' data-placement='bottom'>".$msg->toString()."</td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
			echo Jquery::execute("$(function () {
					  $('[data-toggle=\"popover\"]').popover({'trigger':'hover','html':true})
				})");
			
			
			
		
		}
	}
	
	
	
	
	
public function index($message=null){
		global $config;
		$notifT = 'SELECT COUNT(idStatut ="0") FROM ticket';
		$baseHref=get_class($this);
		if(isset($message)){
			if(is_string($message)){
				$message=new DisplayedMessage($message);
			}
			$message->setTimerInterval($this->messageTimerInterval);
			$this->_showDisplayedMessage($message);
		}
		$objects=DAO::getAll("Ticket","idStatut = '1'	");
	
		echo "<table class='table table-striped'>";
		
		echo "<thead><tr><th>".$this->model."</th></tr></thead>";
		echo "<tbody>";
		foreach ($objects as $object){
			echo "<tr>";
			echo "<td>".$object->toString()."</td>";
			echo "<td class='td-center'><a class='btn btn-primary btn-xs' href='".$baseHref."/frm/".$object->getId()."'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a></td>".
			"<td class='td-center'><a class='btn btn-warning btn-xs' href='".$baseHref."/delete/".$object->getId()."'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a></td>";
			echo "</tr>";
			
		}
		echo "</tbody>";
		echo "</table>";
		echo "<a class='btn btn-primary' href='".$config["siteUrl"].$baseHref."/frm'>Ajouter...</a>";
		//echo DAO::$db->query("SELECT COUNT(idStatut) FROM ticket where idStatut<1")->fetchColumn();
		$objects=DAO::getAll("SELECT COUNT(idStatut) FROM ticket");
		
		
		
		
		
	}
	
	
	
	public function frm($id=NULL){
		$ticket=$this->getInstance($id);
		$categories=DAO::getAll("Categorie");
		$statut=DAO::getAll("Statut");
		

		if($ticket->getCategorie()==null){
			$cat=-1;
			$stat=-1;
				
		}
		else{
				
			$cat=$ticket->getCategorie()->getId();
			$stat=$ticket->getStatut()->getId();
				
		}

	
		
		$listCat=Gui::select($categories,$cat,"Sélectionner une catégorie ...");
		$listStatut=Gui::select($statut, $stat, "Sélectionner un statut ...");
		$listType=Gui::select(array("demande","intervention"),$ticket->getType(),"Sélectionner un type ...");

			$stat=$ticket->getStatut()->getId();
			$this->loadView("ticket/vAdd",array("ticket"=>$ticket,"listCat"=>$listCat,"listType"=>$listType, "statut"=>$statut, "listStatut"=>$listStatut));
			echo Jquery::execute("CKEDITOR.replace( 'description');");
				
			//updteticket
			//s$statutUpdate='coucou1';
			/* '<select class="form-control" class="idStatut" name="idStatut"><br>$listStatut<br></select>'; */
				
		



			
	}

	/* (non-PHPdoc)
	 * @see _DefaultController::setValuesToObject()
	 */
	protected function setValuesToObject(&$object) {
		parent::setValuesToObject($object);
		$categorie=DAO::getOne("Categorie", $_POST["idCategorie"]);
		$object->setCategorie($categorie);
		$statut=DAO::getOne("Statut", $_POST["idStatut"]);
		$object->setStatut($statut);
		$user=DAO::getOne("User", $_POST["idUser"]);
		$object->setUser($user);

	}

	/* (non-PHPdoc)
	 * @see _DefaultController::getInstance()
	 */
	

	/* (non-PHPdoc)
	 * @see BaseController::isValid()
	 */
	public function isValid() {
		return Auth::isAuth();
	}

	/* (non-PHPdoc)
	 * @see BaseController::onInvalidControl()
	 */
	public function onInvalidControl() {
		$this->initialize();
		$this->messageDanger("<strong>Autorisation refusée</strong>,<br>Merci de vous connecter pour accéder à ce module.&nbsp;".Auth::getInfoUser("danger"));
		$this->finalize();
		exit;
	}



}