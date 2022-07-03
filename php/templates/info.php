<?php 
	include('templates/bandeau.php') ;
	
	if(isset($_POST['info'])) {
		$flag = false ;
		$mailOld = $_SESSION['utilisateur'] ;
		$mail = securite_bdd($_POST['mail']) ;
		$passOld = md5($_POST['passOld']) ;
		$tel = $_POST['tel'] ;
		if($_POST['passNew']!="") {
			$passNew = md5($_POST['passNew']) ;
		} else {
			$passNew = "" ;
		}
		$sSQLTest = "SELECT jou_id FROM " . TBL_JOUEUR . " WHERE jou_mail = '$mailOld' AND jou_password = '$passOld'" ;
		$resultTest = $mysqli->query($sSQLTest) ;
		if($rowTest = mysqli_fetch_row($resultTest)) {
			if($rowTest[0]==$_SESSION['id']) {
				$flag = true ;
			}
		}
		if($flag) {
			if($passNew!="") {
				$sSQLUpdate = "update " . TBL_JOUEUR . " set jou_password = '".$passNew."' WHERE jou_id = '" . $_SESSION['id'] . "' " ;
				$resultUpdate = $mysqli->query($sSQLUpdate) ;
			} 
			if($mail!=$mailOld) {
				$sSQLUpdate = "update " . TBL_JOUEUR . " set jou_mail = '" . securite_bdd($mail) . "' WHERE jou_id = '" . $_SESSION['id'] . "' " ;
				$resultUpdate = $mysqli->query($sSQLUpdate) ;
			}
			if($tel!="") {
				$sSQLUpdate = "update " . TBL_JOUEUR . " set jou_tel = '" . securite_bdd(formatTel($tel)) . "' WHERE jou_id = '" . $_SESSION['id'] . "' " ;
				$resultUpdate = $mysqli->query($sSQLUpdate) ;
			}
			$_SESSION['utilisateur'] = $mail ;
			envoiMailInfos($mail) ;
			$messageOk = "Vos informations ont &eacute;t&eacute; mises &agrave; jour. Un mail de confirmation vient de vous &ecirc;tre envoy&eacute;. " ;
		} else {
			$message = "L'ancien mot de passe est incorrect ..." ;
		}
	}
?>
	<script type="text/javascript">
		function Valid_Form(form) {
			if(form.passOld.value=="") {
				alert("Le mot de passe (Ancien) est obligatoire pour modifier les informations.");
				return false;
			}
			if(form.mail.value=="") {
				alert("L'adresse email doit être renseignée.");
				return false;
			}
			if(!validEmail(form.mail.value)) {
				alert("L'adresse email est incorrecte.");
				return false;
			}
			if(!validTel(form.tel.value)) {
				alert("Le numéro de téléphone est incorrect.");
				return false;
			}
			if(form.passNew.value!="" && form.pass.value!="") {
				if(form.passNew.value!=form.pass.value) {
					alert("Les mot de passe sont différents.");
					return false;
				} else {
					if (!validPassword(form.passNew.value)) {
						alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.");
						return false ;
					}
				}
			}
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="#" method="POST"/>
			<table colspan="2" align="center"/>
				<?php
					$sSQL = "SELECT jou_nom, jou_mail, jou_tel " .
						" FROM " . TBL_JOUEUR .
						" WHERE jou_id = '" . $_SESSION['id'] . "' " ;
					$result = $mysqli->query($sSQL) ;
					//echo $sSQL ;
					$mois_prec = "" ;
					while ($row = mysqli_fetch_array($result)) {
						extract($row) ;
						echo "<tr>" ;
						echo "<td colspan='2' align='center'>".$jou_nom."</td>" ;
						echo "</tr>" ;
						echo "<tr>" ;
						echo "<td align='right'>Mail : </td><td><input type='text' name='mail' id='mail' value='".$jou_mail."' size='40'/></td>" ;
						echo "</tr>" ;
						echo "<tr>" ;
						echo "<td align='right'>Tel : </td><td><input type='text' name='tel' id='tel' maxlength='10' size='7' value='".str_replace(' ','',$jou_tel)."' size='50'/></td>" ;
						echo "</tr>" ;
					}
				?>
			</table>
			<table align="center">
				<tr/>
					<td colspan="3" align="center">Mot de Passe</td>
				</tr>
				<tr>
					<td align="right">Ancien : </td><td><input type='password' name='passOld' id='passOld' value=''/></td>
				</tr>
				<tr>
					<td align="right">Nouveau : </td><td><input type='password' name='passNew' id='passNew' value=''/></td>
				</tr>
				<tr>
					<td align="right">Confirmer : </td><td><input type='password' name='pass' id='pass' value=''/></td>
				</tr>
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("info", "Valider", 'return Valid_Form(document.forms["form"]);') ; ?>
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
	
		