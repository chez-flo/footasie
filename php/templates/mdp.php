<?php 
	include('templates/bandeau.php') ;
	
	if(isset($_POST['gen_mdp'])) {
		$flag=false ;
		$mail = securite_bdd($_POST['mail']) ;
		$mail = str_replace("\\", "", $mail) ;
		$jou_id = "" ;
		$sSQLVerif= "SELECT jou_id FROM " . TBL_JOUEUR . " WHERE jou_mail = '$mail' " ;
		$resultVerif = $mysqli->query($sSQLVerif) ;
		if($rowVerif = mysqli_fetch_row($resultVerif)) {
			$jou_id = $rowVerif[0] ;
			$flag = true ;
		}
		if($flag) {
			$pass = chaine_aleatoire(12) ;
			while(!validMdp($pass)) {
				$pass = chaine_aleatoire(12) ;
			}
			$sSQLUpdate = "update " . TBL_JOUEUR . " set jou_password = md5('" . $pass . "') WHERE jou_id = '" . $jou_id . "' and jou_mail = '" . $mail . "' " ;
			$resultUpdate = $mysqli->query($sSQLUpdate) ;
			envoiMailMdp($mail, $pass) ;
			$messageOk = "Votre mot de passe a &eacute;t&eacute; reg&eacuten&eacuter&eacute et vous a &eacute;t&eacute; envoy&eacute; par mail." ;
		} else {
			$message = "Cette adresse mail n'existe pas (" . $mail . ")" ;
		}
	}
?>
	<script type="text/javascript">
		function Valid_Form(form) {
			if(form.mail.value=="") {
				alert("L'adresse email doit être renseignée.");
				return false;
			}
			if(!validEmail(form.mail.value)) {
				alert("L'adresse email est incorrecte.");
				return false;
			}
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="#" method="POST"/>
			<br><br><br><br><br><br>
			<table colspan="2" align="center"/>
				<tr>
					<td/>Adresse mail :</td><td><input type="Text" name="mail" id="mail" size="50"/></td>
				</tr>
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("gen_mdp", "G&eacute;n&eacute;rer un nouveau mot de passe", 'return Valid_Form(document.forms["form"]);') ; ?>
					</td>
				</tr>
			</table>
		</Form>
		<?php if(isset($message) && $message != '') { ?> 
			<font color="red"/><center/> <?php echo $message ; ?> </center></font>
		<?php } 
			if(isset($messageOk) && $messageOk != '') { ?> 
			<font color="green"/><center/> <?php echo $messageOk ; ?> </center></font>
		<?php } ?> 
		</div>
	</BODY>
</HTML>
	
		