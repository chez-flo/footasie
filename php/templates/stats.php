<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
	<CENTER>
		<table cellspacing='20px'>
			<tr>
				<td align=center valign=top>
					<TABLE>
						<tr>
							<th>Classement de la meilleure Attaque</th>
						</tr>
					</table>
					<TABLE cellpadding=5 cellspacing=0 class="tabDetail">
						<TR>
							<TH WIDTH=15 ALIGN=center>Pos.</TH>
							<TH WIDTH=200 ALIGN=left>&Eacute;quipes</TH>
							<TH WIDTH=70 ALIGN=center>Buts / Match</TH>
						</TR>
						<?php
							$sSQLPour = "select eq_id, eq_nom, coalesce(sum(sco_bp),0) bp, ROUND(AVG(sco_bp),2) bpm " .
									" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
									" where sco_eq_id = eq_id " .
										" and eps_eq_id = eq_id " .
										" and sco_mat_id = mat_id " .
										" and mat_pou_id = pou_id " .
										" and eve_id = pou_eve_id " .
										" and sai_pou_id = pou_id " .
										" and eps_pou_id = pou_id " .
										" and mat_sai_annee = sai_annee " .
										" and eps_sai_annee = sai_annee " .
										" and sai_annee = '" . SAISON . "' " .
										" and eve_id = 2 " .
									" group by eq_nom " .
									" order by bpm desc, eq_nom " ;
							$resultPour = $mysqli->query($sSQLPour) ;
							//echo $sSQLClassement ;
							$i=1 ;
							$i_tmp=1 ;
							$bp_prec = 0 ;
							while ($rowPour = mysqli_fetch_array($resultPour)) {
								extract($rowPour) ;
								$color = COLOR ;
								if($bp_prec!=$bpm) { $i_tmp = $i ; }
								if($i_tmp==1) { $color = COLOR_CHAMPION ; }
								if($i_tmp>1 && $i_tmp<6) { $color = COLOR_MONTEE ; }
								echo "<TR>" ;
									echo "<TD WIDTH=15 ALIGN=center style='color: ".$color."; font-weight: bold;'>".$i_tmp."</TD>" ;
									echo "<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$color.";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" ;
									echo "<TD WIDTH=70 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bpm." (".$bp.")</TD>" ;
								echo "</TR>" ;
								$bp_prec = $bpm ;
								$i++;
							}
						echo "</TABLE>" ;
					?>
				</td>
				<td align=center valign=top>
					<TABLE>
						<tr>
							<th>Classement de la meilleure D&eacute;fense</th>
						</tr>
					</table>
					<TABLE cellpadding=5 cellspacing=0 class="tabDetail">
						<TR>
							<TH WIDTH=15 ALIGN=center>Pos.</TH>
							<TH WIDTH=200 ALIGN=left>&Eacute;quipes</TH>
							<TH WIDTH=70 ALIGN=center>Buts / Match</TH>
						</TR>
						<?php
							$sSQLContre = "select eq_id, eq_nom, coalesce(sum(sco_bc),0) bc, ROUND(AVG(sco_bc),2) bpm " .
									" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
									" where sco_eq_id = eq_id " .
										" and eps_eq_id = eq_id " .
										" and sco_mat_id = mat_id " .
										" and mat_pou_id = pou_id " .
										" and eve_id = pou_eve_id " .
										" and sai_pou_id = pou_id " .
										" and eps_pou_id = pou_id " .
										" and mat_sai_annee = sai_annee " .
										" and eps_sai_annee = sai_annee " .
										" and sai_annee = '" . SAISON . "' " .
										" and eve_id = 2 " .
									" group by eq_nom " .
									" order by bpm, eq_nom " ;
							$resultContre = $mysqli->query($sSQLContre) ;
							//echo $sSQLContre ;
							$i=1 ;
							$i_tmp=1 ;
							$bc_prec = 0 ;
							while ($rowContre = mysqli_fetch_array($resultContre)) {
								extract($rowContre) ;
								$color = COLOR ;
								if($bc_prec!=$bpm) { $i_tmp = $i ; }
								if($i_tmp==1) { $color = COLOR_CHAMPION ; }
								if($i_tmp>1 && $i_tmp<6) { $color = COLOR_MONTEE ; }
								echo "<TR>" ;
									echo "<TD WIDTH=15 ALIGN=center style='color: ".$color."; font-weight: bold;'>".$i_tmp."</TD>" ;
									echo "<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$color.";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" ;
									echo "<TD WIDTH=70 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bpm." (".$bc.")</TD>" ;
								echo "</TR>" ;
								$bc_prec = $bpm ;
								$i++;
							}
						echo "</TABLE>" ;
					?>
				</td>
				<td align=center valign=top>
					<TABLE>
						<tr>
							<th>Classement du meilleur Goal Average</th>
						</tr>
					</table>
					<TABLE cellpadding=5 cellspacing=0 class="tabDetail">
						<TR>
							<TH WIDTH=15 ALIGN=center>Pos.</TH>
							<TH WIDTH=200 ALIGN=left>&Eacute;quipes</TH>
							<TH WIDTH=70 ALIGN=center>Goal A. / Match</TH>
						</TR>
						<?php
							$sSQLGA = "select eq_id, eq_nom, coalesce(sum(sco_bp)-sum(sco_bc),0) diff, ROUND(AVG(sco_bp-sco_bc),2) gapm " .
									" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
									" where sco_eq_id = eq_id " .
										" and eps_eq_id = eq_id " .
										" and sco_mat_id = mat_id " .
										" and mat_pou_id = pou_id " .
										" and eve_id = pou_eve_id " .
										" and sai_pou_id = pou_id " .
										" and eps_pou_id = pou_id " .
										" and mat_sai_annee = sai_annee " .
										" and eps_sai_annee = sai_annee " .
										" and sai_annee = '" . SAISON . "' " .
										" and eve_id = 2 " .
									" group by eq_nom " .
									" order by gapm desc, eq_nom " ;
							$resultGA = $mysqli->query($sSQLGA) ;
							//echo $sSQLGA ;
							$i=1 ;
							$i_tmp=1 ;
							$diff_prec = 0 ;
							while ($rowGA = mysqli_fetch_array($resultGA)) {
								extract($rowGA) ;
								$color = COLOR ;
								if($diff_prec!=$gapm) { $i_tmp = $i ; }
								if($i_tmp==1) { $color = COLOR_CHAMPION ; }
								if($i_tmp>1 && $i_tmp<6) { $color = COLOR_MONTEE ; }
								echo "<TR>" ;
									echo "<TD WIDTH=15 ALIGN=center style='color: ".$color."; font-weight: bold;'>".$i_tmp."</TD>" ;
									echo "<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$color.";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" ;
									echo "<TD WIDTH=70 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$gapm." (".$diff.")</TD>" ;
								echo "</TR>" ;
								$diff_prec = $gapm ;
								$i++;
							}
						echo "</TABLE>" ;
					?>
				</td>
			</tr>
		</table>
	</CENTER>
</div>

<?php
	echo "<script>" .
		"$(document).ready(function(){ " ;
		for ($k=0;$k<sizeof($idPhase);$k++) {
			echo "$('#j".$idPhase[$k]." th').click(function(){ " .
				"$('#j".$idPhase[$k]."_ ').toggle('fast'); " .
				"$('img:visible', this).hide().siblings().show(); " .
			"}); " ;
		}
		echo "$('#closeAll').click(function(){ " ;
			for ($k=0;$k<sizeof($idPhase);$k++) {
				echo "$('#j".$idPhase[$k]."_ ').toggle('fast'); " ;
			}
		echo"}); " .
			"}); " .
	"</script> ";
?>