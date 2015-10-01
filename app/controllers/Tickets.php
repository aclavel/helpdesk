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
class Tickets extends \_DefaultController {
	public function Tickets(){
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
		
		if (Auth::isAdmin() == false){
			
			
			//$selectclass = '<select disabled class="form-control" name="idStatut"> '.statutNow.'</select>';
			$this->loadView("ticket/vAdd",array("ticket"=>$ticket,"listCat"=>$listCat,"listType"=>$listType, "listStatut"=>$listStatut,));
			echo Jquery::execute("CKEDITOR.replace( 'description');");
			
			
			//statutupdate
			//$statutUpdate= 'coucou';
			/* '<div class="form-control" disabled name="idStatut"><br>
					<input type="hidden" name="idStatut" value=" echo $ticket->getStatut()->getId()"><br>$ticket->getStatut();<br></div>';
			 */
			
		}
			
		if (Auth::isAdmin()){
			
			$stat=$ticket->getStatut()->getId();
			$this->loadView("ticket/vAdd",array("ticket"=>$ticket,"listCat"=>$listCat,"listType"=>$listType, "statut"=>$statut, "listStatut"=>$listStatut));
			echo Jquery::execute("CKEDITOR.replace( 'description');"); 
			
			//updteticket
			//s$statutUpdate='coucou1';
			/* '<select class="form-control" class="idStatut" name="idStatut"><br>$listStatut<br></select>'; */
			
		}
		
	
		
			
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
	public function getInstance($id = NULL) {
		$obj=parent::getInstance($id);
		if(null==$obj->getType())
			$obj->setType("intervention");
		 if($obj->getStatut()===NULL){
			$statut=DAO::getOne("Statut", 1);
			$obj->setStatut($statut);
		
			}
			
			
		
		if($obj->getUser()===NULL){
			$obj->setUser(Auth::getUser());
		}
		if($obj->getDateCreation()===NULL){
			$obj->setdateCreation(date('Y-m-d H:i:s'));
		}
		return $obj;
	}


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