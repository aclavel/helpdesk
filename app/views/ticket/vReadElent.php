<form name="frm2Titre" id="frm2Titre" onSubmit="return false;">
	<div class="alert alert-info">Ticket : <?php echo $ticket->toString()?></div>
	<div class="form-group">
		<label for="titre"><h4>Type : </h4></label>
		<p id="titre" name="titre"><?=$ticket->getType()?></p>
		<label for="contenu"><h4>Categorie : </h4></label>
		<p id="contenu" name="contenu"><?=$ticket->getCategorie()?></p>
		<label for="dateCrea"><h4>Titre : </h4></label>
		<p id="dateCrea" name="dateCrea"><?=$ticket->getTitre()?></p>
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
echo $idTicket;



?>