<?php 
	include('templates/bandeau.php') ;
?>
	<script type="text/javascript">
		function Valid_Form(form) {
			/*if(form.prenom.value=="") {
				alert("Le prénom doit être renseigné.");
				return false;
			}*/
			if(form.nom.value=="") {
				alert("Le nom doit être renseigné.");
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
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="code.php" method="POST"/>
			<input type='hidden' name='jou_id' id='jou_id' value='<?php echo $_GET['id'] ; ?>'/>
			<table colspan="2" align="center"/>
				<?php
					$sSQL = "SELECT jou_nom, jou_mail, ejt_typ_id " .
						" FROM " . TBL_JOUEUR . ", " . TBL_EJT .  
						" WHERE jou_id = ejt_jou_id " .
							" AND ejt_eq_id = '" . $_SESSION['eq_id'] . "' " .
							" AND ejt_sai_annee = '" . SAISON . "' " .
							" AND jou_id = '" . $_GET['id'] . "' " ;
					$result = $mysqli->query($sSQL) ;
					//echo $sSQL ;
					$mois_prec = "" ;
					while ($row = mysqli_fetch_array($result)) {
						extract($row) ;
						/*echo "<tr>" ;
						echo "<td align='right'>Prénom* : </td><td><input type='text' name='prenom' id='prenom' value='' size='40'/></td>" ;
						echo "</tr>" ;*/
						if($ejt_typ_id==1) {
							echo "<tr>" ;
							echo "<td align='right'>Nom* : </td><td><input type='text' name='nom' id='nom' value='".$jou_nom."' size='40'/></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Mail* : </td><td><input type='text' name='mail' id='mail' value='".$jou_mail."' size='40'/></td>" ;
							echo "</tr>" ;
						} else {
							echo "<tr>" ;
							echo "<td align='right'>Nom : </td><td>".$jou_nom."</td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Mail : </td><td>".$jou_mail."</td>" ;
							echo "</tr>" ;
						}
					}
				?>
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . bouton("supprimerJoueur", "Supprimer", 'submit', 'boutonSupprimer', 'return confirm("Voulez-vous vraiment supprimer le joueur de votre effectif ?")') . " " . boutonSubmit("majJoueur", "Valider", 'return Valid_Form(document.forms["form"]);') ; ?>
					</td>
				</tr>
			</table>
		</Form>
	</div>
</BODY>
</HTML>
	
		