<?php 
	include('templates/bandeau.php') ;
?>
	<div class = "PagePrincipale"> 
		<TABLE class="center tabValidInscription" align="center">
			<TR>
				<TH>&Eacute;quipe</TH>
				<TH>Soci&eacute;t&eacute;</TH>
				<TH>Responsables</TH>
				<TH>Terrain &agrave; &eacute;viter</TH>
				<TH>Jour &agrave; &eacute;viter</TH>
				<TH>&Eacute;quipe amie</TH>
				<TH>Participation Coupe</TH>
				<TH>Couleur 1</TH>
				<TH>Couleur 2</TH>
				<TH>Commentaires</TH>
			</TR>
			<?php
				$sSQLEquipe = "select eq_id, eq_commentaire, eq_nom, soc_nom, eq_ter_id, eq_jour, eq_ami_id, eq_coupe, eq_couleur, eq_couleur_ext " .
					" from " . TBL_EPS . ", " . 
						TBL_EQUIPE . ", " .
						TBL_SOCIETE .  
					" where eps_eq_id = eq_id " .
						" and eq_soc_id = soc_id " .
						" and eps_sai_annee = '".SAISON_INS."' " .
					" order by eq_nom " ;
				
				$resultEquipe = $mysqli->query($sSQLEquipe) ;
				while ($rowEquipe = mysqli_fetch_array($resultEquipe)) {
					extract($rowEquipe) ;
					$sSQLCor = "select jou_nom " .
									" from " . TBL_EQUIPE_CORRESP . ", " .
									TBL_JOUEUR .
									" where ec_eq_id = '".$eq_id."' " .
										" and ec_jou_id = jou_id ;" ;
					$resultCor = $mysqli->query($sSQLCor) ;
					$flagCor = false ;
					$corresp = "" ;
					while ($rowCor = mysqli_fetch_array($resultCor)) {
						extract($rowCor) ;
						if($flagCor) { $corresp .= ", " ; }
						$corresp .= $jou_nom ;
						$flagCor = true ;
					}
					$sSQLTer = "select ter_nom " .
									" from " . TBL_TERRAIN . 
									" where ter_id = '".$eq_ter_id."' ;" ;
					$resultTer = $mysqli->query($sSQLTer) ;
					$ter_nom = "" ;
					while ($rowTer = mysqli_fetch_array($resultTer)) {
						extract($rowTer) ;
					}
					$sSQLAmi = "select eq_nom " .
									" from " . TBL_EQUIPE .
									" where eq_ami_id = '".$eq_ami_id."' ;" ;
					$resultAmi = $mysqli->query($sSQLAmi) ;
					$ami_nom = "" ;
					while ($rowAmi = mysqli_fetch_array($resultAmi)) {
						extract($rowAmi) ;
					}
					$eq_coupe==1 ? $coupe = "Oui" : $coupe = "non" ;
					echo "<TR>" ;
						echo "<TD>".$eq_nom."</TD>" ;
						echo "<TD>".$soc_nom."</TD>" ;
						echo "<TD>".$corresp."</TD>" ;
						echo "<TD>".$ter_nom."</TD>" ;
						echo "<TD>".$eq_jour."</TD>" ;
						echo "<TD>".$ami_nom."</TD>" ;
						echo "<TD>".$coupe."</TD>" ;
						echo "<TD>".$eq_couleur."</TD>" ;
						echo "<TD>".$eq_couleur_ext."</TD>" ;
						echo "<TD>".$eq_commentaire."</TD>" ;
					echo "</TR>" ;
				}
			echo "</TABLE>" ;
		?>
		<?php
			
		?>
				</tr>
			</table>
		</Form>
	</div>
</BODY>
</HTML>
	
		