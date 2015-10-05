<?php use micro\orm\DAO; ?>

<form name="frm2Titre" id="frm2Titre" onSubmit="return false;">
	<div class="alert alert-info">Ticket : <?php echo $ticket->toString()?></div>
	<div class="form-group">
		<label for="type"><h4>Type : </h4></label>
		<p id="type" name="type"><?=$ticket->getType()?></p>
		<label for="categorie"><h4>Categorie : </h4></label>
		<p id="categorie" name="categorie"><?=$ticket->getCategorie()?></p>
		<label for="titre"><h4>Titre : </h4></label>
		<p id="titre" name="titre"><?=$ticket->getTitre()?></p>
		<label for="categorie"><h4>Description : </h4></label>
		<p id="categorie" name="categorie"><?=$ticket->getDescription()?></p>
		<label for="categorie"><h4>Statut : </h4></label>
		<p id="categorie" name="categorie"><?=$ticket->getStatut()?></p>
		<label for="categorie"><h4>Emetteur : </h4></label>
		<p id="categorie" name="categorie"><?=$ticket->getUser()?></p>
		<label for="categorie"><h4>Date de creation : </h4></label>
		<p id="categorie" name="categorie"><?=$ticket->getDateCreation()?></p>
	</div>
	<a href="tickets" class="btn btn-primary" id="btReadElent">Retour</a>
</form>
<?php 
$idTicket = $ticket->getId();
$msg=DAO::getAll("message", "idTicket='".$idTicket."'");

foreach ($msg as $oklm){ ?>

	<div class="form-group" style="background-color:#ddd; padding:20px; margin-top:10px; display:block; overflow:hidden; border:solid #aaa 2px; border-radius:5px;">
		<p id="contMess" name="contMess"><?= $oklm->getContenu();?></p>
		<br>
		<p id="userMess" name="userMess" style="float:right; display:block; padding:10px;"><?= $oklm->getUser();?></p>
	</div>	
	
<?php } ?>

<form class="form-group" action='Messages/nouveauMess' method='post'>
	<input type='hidden' name="ticket" value="<?php echo $ticket->getId();?>">
	<label for="contenu">Contenu de votre message:</label> <br>
	<textarea name="contenu" style="width:100%; height:100px; display:block; margin-bottom:10px; border:2px #aaa solid; border-radius:5px;"></textarea>
	<button class="btn btn-primary" style="margin-bottom:100px; display:block;">Ajouter</button>
</form>
