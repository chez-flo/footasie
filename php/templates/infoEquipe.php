<?php 
	include('templates/bandeau.php') ;
	
	if(isCapitaine()) {
		if(isset($_POST['infoEquipe'])) {
			extract($_POST) ;
			$sSQLUpdate = "update " . TBL_EQUIPE . " set eq_couleur = '" . securite_bdd($couleur) . "', " .
							" eq_couleur_ext = '" . securite_bdd($couleur_ext) . "', " .
							" eq_coupe = '" . $coupe . "', " .
							" eq_ter_id = '" . $eq_ter_id . "', " .
							" eq_jour = '" . $jour . "', " .
							" eq_ami_id = '" . $eq_ami_id . "' " .
						" WHERE eq_id = '" . $_POST['eq_id'] . "' " ;
			$resultUpdate = $mysqli->query($sSQLUpdate) ;
			envoiMailInfosEquipe($_SESSION['utilisateur']) ;
			$messageOk = "Vos informations ont &eacute;t&eacute; mises &agrave; jour. Un mail de confirmation vient de vous &ecirc;tre envoy&eacute;. " ;
		}
	}
?>
	<script type="text/javascript">
		function Valid_Form(form) {
			if(form.couleur.value=="") {
				alert("Merci de renseigner la couleur du maillot principal.");
				return false;
			}
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="#" method="POST"/>
			<table colspan="2" align="center"/>
				<?php
					$sSQL = "SELECT eq_id, eq_nom, eq_jour, eq_ter_id, eq_ami_id, eq_coupe, eq_couleur, eq_couleur_ext " .
						" FROM " . TBL_JOUEUR . ", " . TBL_EQUIPE_CORRESP . ", " . TBL_EQUIPE . 
						" WHERE jou_id = ec_jou_id " .
							" AND eq_id = ec_eq_id " .
							" AND jou_id = '" . $_SESSION['id'] . "' " ;
					$result = $mysqli->query($sSQL) ;
					//echo $sSQL ;
					$mois_prec = "" ;
					while ($row = mysqli_fetch_array($result)) {
						extract($row) ;
						echo "<tr>" ;
						echo "<td colspan='2' align='center'><input type='hidden' name='eq_id' id='eq_id' value='".$eq_id."'/>".$eq_nom."</td>" ;
						echo "</tr>" ;
						if(isCapitaine()) {
							echo "<tr>" ;
							echo "<td align='right'>Couleur Maillot Principal* : </td><td><input type='text' name='couleur' id='couleur' value='".$eq_couleur."' size='40'/></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Couleur Maillot Secondaire : </td><td><input type='text' name='couleur_ext' id='couleur_ext' value='".$eq_couleur_ext."' size='40'/></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Jour &agrave; &eacute;viter : </td><td><select name='jour' id='jour'/>".selectJour($eq_jour)."</select></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Terrain &agrave; &eacute;viter : </td><td><select name='eq_ter_id' id='eq_ter_id'/>".selectTerrain($eq_ter_id)."</select></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>&Eacute;quipe amie : </td><td><select name='eq_ami_id' id='eq_ami_id'/>".selectAmi($eq_ami_id)."</select></td>" ;
							echo "</tr>" ;
							echo "<tr>" ;
							echo "<td align='right'>Coupe* : </td><td><select name='coupe' id='coupe'/>".selectOuiNon($eq_coupe)."</select></td>" ;
							echo "</tr>" ;
						}
					}
				?>
			</table>
			<table align="center">
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("infoEquipe", "Mettre &agrave; jour", 'return Valid_Form(document.forms["form"]);') ; ?>
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
	
		