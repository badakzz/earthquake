 <!-- formulaire.php -->
 <?PHP require "recaptcha.php"; ?>
 <!DOCTYPE html>
 <html lang="fr">

 <head>
 	<meta charset="UTF-8">
 	<script src="https://www.google.com/recaptcha/api.js"></script>
 </head>

 <body>
 	<h1>Formulaire</h1>
 	<form id='myform' name='myform' action="traitement-exemple.php" method='post'>
 		<fieldset>
 			<label for='mytext'>Rentrer du texte
 				<input id='mytext' name='mytext' type='text'>
 			</label>
 			<button class="g-recaptcha" data-sitekey="<?PHP echo SITE_TUTO_EXEMPLE ?>" data-callback='onSubmit' data-action='submit'>Submit</button>
 		</fieldset>
 	</form>
 	<script>
 		function onSubmit() {
 			// ICI Validation JavaScript du formulaire  
 			document.getElementById("myform").submit();
 		}
 	</script>
 </body>

 </html>