<form action='Connexions/modifCompte' method='post'>
	<div class="form-group">
		<label for="login"><h4> Login : </h4></label>
		<input type="text" class="form-control" id="login" name="login" value="<?=$user->getLogin()?>">
		<label for="mdp"><h4> Mot de passe : </h4></label>
		<input type="text" class="form-control" id="mdp" name="mdp" value="<?=$user->getPassword()?>">
		<label for="mail"><h4> Mail : </h4></label>
		<input type="text" class="form-control" id="mail" name="mail" value="<?=$user->getMail()?>"><br>
		
		<button type="submit" class="btn btn-primary" id="btUpdateTitre">Modifier</button>
	</div>
</form>