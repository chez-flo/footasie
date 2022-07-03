<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
	<CENTER>
		<table><tr><td><a href="<?php echo PATH ; ?>index.php?op=addJ">
			<img src="<?php echo IMG ; ?>add_joueur.png" style="height:20px;" alt="Ajout joueur"/>
			Ajouter un joueur
			<img src="<?php echo IMG ; ?>add_joueur.png" style="height:20px;" alt="Ajout joueur"/>
		</a></td></tr></table>
		<?php 
			$i=1 ;
			if(isAdmin() || isCapitaine()) { 
				$sSQLJoueur = "select jou_id, jou_nom, jou_mail, typ_id, typ_nom " .
					" from " . TBL_JOUEUR . ", " . 
						TBL_EJT . ", " . 
						TBL_TYPE . 
					" where ejt_jou_id = jou_id " .
						" and ejt_typ_id = typ_id " .
						" and ejt_eq_id = '".$_SESSION['eq_id']."' " .
						" and ejt_sai_annee = '".SAISON."' " .
					" order by typ_id, jou_nom " ;
				$resultJoueur = $mysqli->query($sSQLJoueur) ;
				echo "<table class='tabGestionEquipe'>" ;
					echo "<tr>" ;
						echo "<th>#</th>" ;
						echo "<th>Nom</th>" ;
						echo "<th>Email</th>" ;
						echo "<th>Type</th>" ;
						echo "<th>Autre &Eacute;quipe</th>" ;
					echo "</tr>" ;
						while ($rowJoueur = mysqli_fetch_array($resultJoueur)) {
							extract($rowJoueur) ;
								$s=array('id' => $jou_id) ;
								echo "<tr onclick='".createRedirect('jou', $s)."' class='".$typ_nom."'>" ;
								echo "<td>".$i++."</td>" ;
								echo "<td>".$jou_nom."</td>" ;
								echo "<td>".$jou_mail."</td>" ;
								echo "<td>".$typ_nom."</td>" ;
								echo "<td>" ;
								$sSQLEq = "select eq_nom " .
									" from " . TBL_EQUIPE . ", " . 
										TBL_EJT .
									" where ejt_eq_id = eq_id " .
										" and ejt_typ_id <> '".$typ_id."' " .
										" and ejt_sai_annee = '".SAISON."' " .
										" and ejt_jou_id = '".$jou_id."' " ;
								$resultEq = $mysqli->query($sSQLEq) ;
								while ($rowEq = mysqli_fetch_array($resultEq)) {
									extract($rowEq) ;
									echo $eq_nom ;
								}
								echo "</td>" ;
						}
				echo "</table>" ;
			}
		?>
	</CENTER>
</div>
