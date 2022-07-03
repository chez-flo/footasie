<?php include('templates/bandeau.php') ; ?>

<script>
	function valid(form) {
		if(document.getElementById("eq_id").value==0) {
			alert('Merci de choisir une équipe dans la liste');
			return false;
		}
		if(document.getElementById("nom").value=="") {
			alert('Merci de renseigner votre nom');
			return false;
		}
		if(document.getElementById("raison").value=="") {
			alert('Merci de renseigner la raison de la demande');
			return false;
		}
		return confirm('Voulez-vous vraiment faire la demande pour '+document.getElementById("eq_id").options[document.getElementById("eq_id").selectedIndex].text+' ?');
	}
</script>

<div class = "PagePrincipale">
	<form id="formDemCreneau" action="code.php" method="POST"/>
		<CENTER>
			<table cellpadding=1 cellspacing=1><tr>
			<?php
				$sSQL = "SELECT cre_date, MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
					" date_format(cre_date, ' %d '), " .
					" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
					" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
					" cre_id, ter_nom " .
					" FROM " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
					" WHERE cre_ter_id = ter_id " .
						" and cre_id = '" . htmlentities($_GET['id']) . "' " .
					" ORDER BY cre_date, ter_nom, cre_id " ;
				$result = $mysqli->query($sSQL) ;
				//echo $sSQL ;
				$mois_prec = "" ;
				while ($row = mysqli_fetch_array($result)) {
					extract($row) ;
					echo "<tr>" ;
					echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
					echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
					echo "<td colspan='2' align='center'>".$jour." &agrave; ".$ter_nom."</td>" ;
					echo "</tr>" ;
				}
				$sSql = "SELECT eq_id, eq_nom, pou_eve_id " .
						" FROM " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . 
						" WHERE eps_eq_id = eq_id " .
							" and eps_pou_id = pou_id " .
							" and eps_sai_annee = '" . SAISON . "' " .
							" and eps_pou_id = '2' " .
							" and eq_id <> '1' " .
							" ORDER BY eq_nom" ;
				//echo $sSQLPoule ;
				$resultSql = $mysqli->query($sSql) ;
				echo "<tr>" ;
				echo "<td align='right'>&Eacute;quipe demandeuse :&nbsp;</td>" ;
				echo "<td align='left'><select name='eq_id' id='eq_id'\"><option value='0'>-- S&eacute;lectionner l'&eacute;quipe demandeuse --</option>" ;
				while ($rowSql = mysqli_fetch_array($resultSql)) {
					extract($rowSql) ;
					echo "<option value='".$eq_id."'>".$eq_nom."</option>" ;
				}
					echo "</select></td>" ;
				echo "</tr>" ;
			?>
				<tr>
					<td align='right'>
						Nom du demandeur :&nbsp;
					</td>
					<td align='left'>
						<input type="text" name="nom" id="nom" value="" size='40'/>
					</td>
				</tr>
				<tr>
					<td align='right'>
						Adresse Mail :&nbsp;
					</td>
					<td align='left'>
						<input type="text" name="mail" id="mail" value="" size='40'/>
					</td>
				</tr>
				<tr>
					<td align='right' valign='top'>
						Raison de la demande :&nbsp;
					</td>
					<td align='left'>
						<textarea name="raison" id="raison" rows='5' cols='30'>Match d'Entra&icirc;nement</textarea>
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2'>
						<?php echo boutonRetour() . " " . boutonSubmit("demCreneau", "Valider", 'return valid("formDemCreneau");') ; ?>
					</td>
				</tr>
			</table>
		</CENTER>
	</form>
</div>
