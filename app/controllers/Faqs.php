<?php
use micro\orm\DAO;
use micro\views\Gui;
use micro\js\Jquery;
use micro\utils\RequestUtils;
/**
 * Gestion des articles de la Faq
 * @author jcheron
 * @version 1.1
 * @package helpdesk.controllers
 */
class Faqs extends \_DefaultController {
	public function Faqs(){
		parent::__construct();
		$this->title="Foire aux questions";
		$this->model="Faq";
	}

	/* (non-PHPdoc)
	 * @see _DefaultController::setValuesToObject()
	 */
	protected function setValuesToObject(&$object) {
		parent::setValuesToObject($object);
		$object->setUser(Auth::getUser());
		$categorie=DAO::getOne("Categorie", $_POST["idCategorie"]);
		$object->setCategorie($categorie);
	}

	public function test(){
		$faqs=DAO::getAll("Faq","1=1 order by dateCreation limit 1,1");
		foreach ($faqs as $faq){
			echo $faq."<br>";
		}
		echo DAO::$db->query("SELECT max(id) FROM Faq")->fetchColumn();
		$ArticleMax=DAO::getOne("Faq","id=(SELECT max(id) FROM Faq)");
		echo $ArticleMax;
	}
	


	// **************************************************************************************** //
	



	public function index($param=null){

	global $config;
		$orderBy="1=1 order by idCategorie";
		if(!is_array($param) && $param!=null){
			$param=array($param);
		}
		$baseHref=get_class($this);
		if(isset($param) && $param[0] != "mess"){
			if(is_string($param[0])){
				$message=new DisplayedMessage($param[0]);
			}else
				$message=$param[0];
			if(isset($message)){
				$message->setTimerInterval($this->messageTimerInterval);
				$this->_showDisplayedMessage($message);
			}
		}
		
		
		$objects=DAO::getAll($this->model,$orderBy);
		
		if(Auth::isAdmin()){
			echo "<a class='btn btn-primary' href='".$config["siteUrl"].$baseHref."/mesArticles'>Mes articles</a>";
			echo "<a class='btn btn-info' href='".$config["siteUrl"].$baseHref."'>Tous les articles</a>";
		}
		echo "<table class='table table-striped'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th colspan='5' style='padding-left:90%'>";
				echo "<div class='btn-group'>";
					echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>";
						echo "Trier par... <span class='caret'></span>";
					echo "</button>";
					echo "<ul class='dropdown-menu' role='menu'>";
						echo "<li><a href='Faqs/index'>Sans filtre</a></li>";
						echo "<li><a href='Faqs/index/mess/idCategorie'>categorie</a></li>";
						echo "<li><a href='Faqs/index/mess/dateCreation'>date de creation</a></li>";
					echo "</ul>";
				echo "</div>";
				echo "</th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$currentOrder="";
		$func="getRien";
		if(count($param)>1){
			switch ($param[1]){
				case "idCategorie":
					$func="getCategorie";
					break;
				case "dateCreation":
					$func="getDateCreation";
					break;
			}
			
		}
		if (Auth::isAdmin()){
			foreach ($objects as $object){
				
				if($currentOrder!=$object->$func().""){
					echo "<tr><td colspan='2'><h2>".$object->$func()."</h2></td></tr>";
					$currentOrder=$object->$func()."";
				}
				
				echo "<tr>";
				echo "<td class='titre-faq' style='width:80%'><a href='".$baseHref."/frm2/".$object->getId()."' style='color:#253939'><b>".$object->getTitre()."</b> - ".$object->getUser()."</a></td>";
				echo "<td class='td-center'><a class='btn btn-success btn-xs' href='".$baseHref."/frm2/".$object->getId()."'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a></td>";
					
				if (Auth::getUser()==$object->getUser()){
					echo "<td class='td-center'><a class='btn btn-primary btn-xs' href='".$baseHref."/frm/".$object->getId()."'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a></td>";
					if ($object->getDisable()=="0"){
						echo "<td class='td-center'><a class='btn btn-warning btn-xs' href='".$baseHref."/disable/".$object->getId()."'><span class='glyphicon glyphicon-pause' aria-hidden='true'></span></a></td>";
					}
					else {
						echo "<td class='td-center'><a class='btn btn-info btn-xs' href='".$baseHref."/activate/".$object->getId()."'><span class='glyphicon glyphicon-play' aria-hidden='true'></span></a></td>";
					}
					echo "<td class='td-center'><a class='btn btn-danger btn-xs' href='".$baseHref."/delete/".$object->getId()."'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a></td>";
				}
			}
			echo "</tr>";
		}
		else {
			foreach ($objects as $object){
				if ($object->getDisable()=="0"){
					
					echo "<tr>";
					if($currentOrder!=$object->$func().""){
						echo "<tr><td colspan='3'><h2>".$object->$func()."</h2></td></tr>";
						$currentOrder=$object->$func()."";
					}
					echo "<td class='titre-faq'><a href='".$baseHref."/frm2/".$object->getId()."' style='color:#253939'><b>".$object->getTitre()."</b> - ".$object->getUser()."</a></td>";
					echo "<td class='td-center'><a class='btn btn-success btn-xs' href='".$baseHref."/frm2/".$object->getId()."'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a></td>";
				}
			}
			echo "</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";
		
		if (Auth::isAdmin()){
			echo "<a class='btn btn-primary' href='".$config["siteUrl"].$baseHref."/frm'>Ajouter...</a>";
		}
	}
	
	// **************************************************************************************** //
	
	/* (non-PHPdoc)
	 * @see _DefaultController::frm()
	 */
	public function frm($id = NULL) {
		
		if (Auth::isAdmin()){
			$faq = $this->getInstance($id);
			
			$categories=DAO::getAll("Categorie");
			
			if($faq->getCategorie()==null){
				$cat=-1;
			}
		
			else {
				$cat=$faq->getCategorie()->getId();
			}
		
			$listCat=Gui::select($categories,$cat,"Sélectionner une catégorie ...");
		
			if (isset($id)){
				$ajou_modif = "Modifier";
				$this->loadView("faq/vUpdateTitre",array("faq"=>$faq, "ajou_modif"=>$ajou_modif, "idCategorie"=>$cat, "listCat"=>$listCat));
			}
			else {
				$ajou_modif = "Ajouter";
				$this->loadView("faq/vUpdateTitre",array("faq"=>$faq, "ajou_modif"=>$ajou_modif, "idCategorie"=>$cat, "listCat"=>$listCat));
			}
		}
		else {
			echo "Vous devez vous connecter en tant qu'administrateur pour accéder à ce module";
		}
		echo Jquery::execute("CKEDITOR.replace( 'contenu');");
	}

	
	public function frm2($id = NULL) {
		$faq = $this->getInstance($id);
		$this->loadView("faq/vReadElent", array("faq"=>$faq));
	}
	
	public function disable($id = NULL){
		try{
			$object=DAO::getOne($this->model, $id[0]);
			if($object!==NULL){
				$object->setDisable("1");
				DAO::update($object);
				$msg=new DisplayedMessage("Article désactivé");
			}else{
				$msg=new DisplayedMessage($this->model." introuvable","warning");
			}
		}catch(Exception $e){
			$msg=new DisplayedMessage("Impossible de désactiver l'instance de ".$this->model,"danger");
		}
		$this->forward(get_class($this),"index",$msg);
	}
	
	public function activate($id = NULL){
		try{
			$object=DAO::getOne($this->model, $id[0]);
			if($object!==NULL){
				$object->setDisable("0");
				DAO::update($object);
				$msg=new DisplayedMessage("Article activé");
			}else{
				$msg=new DisplayedMessage($this->model." introuvable","warning");
			}
		}catch(Exception $e){
			$msg=new DisplayedMessage("Impossible d'activer l'instance de ".$this->model,"danger");
		}
		$this->forward(get_class($this),"index",$msg);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function mesArticles($param = NULL) {
		
	global $config;
		$orderBy="1=1 order by idCategorie";
		$baseHref=get_class($this);		
		
		$objects=DAO::getAll($this->model,$orderBy);
		
		echo "<a class='btn btn-primary' href='".$config["siteUrl"].$baseHref."/mesArticles'>Mes articles</a>";
		echo "<a class='btn btn-info' href='".$config["siteUrl"].$baseHref."'>Tous les articles</a>";
				echo "<br><br><table class='table table-striped'><tbody>";
			foreach ($objects as $object){
				if (Auth::getUser()==$object->getUser()){
					
					echo "<tr>";
					echo "<td class='titre-faq' style='width:80%'><a href='".$baseHref."/frm2/".$object->getId()."' style='color:#253939'><b>".$object->getTitre()."</b></a></td>";
					echo "<td class='td-center'><a class='btn btn-success btn-xs' href='".$baseHref."/frm2/".$object->getId()."'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a></td>";
						
					echo "<td class='td-center'><a class='btn btn-primary btn-xs' href='".$baseHref."/frm/".$object->getId()."'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a></td>";
					if ($object->getDisable()=="0"){
						echo "<td class='td-center'><a class='btn btn-warning btn-xs' href='".$baseHref."/disable/".$object->getId()."'><span class='glyphicon glyphicon-pause' aria-hidden='true'></span></a></td>";
					}
					else {
						echo "<td class='td-center'><a class='btn btn-info btn-xs' href='".$baseHref."/activate/".$object->getId()."'><span class='glyphicon glyphicon-play' aria-hidden='true'></span></a></td>";
					}
					echo "<td class='td-center'><a class='btn btn-danger btn-xs' href='".$baseHref."/delete/".$object->getId()."'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a></td>";
				}
			}
			echo "</tr>";
		
		echo "</tbody>";
		echo "</table>";
		
		if (Auth::isAdmin()){
			echo "<a class='btn btn-primary' href='".$config["siteUrl"].$baseHref."/frm'>Ajouter...</a>";
		}
	}

}