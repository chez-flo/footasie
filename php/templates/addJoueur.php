<?php 
	include('templates/bandeau.php') ;
?>
	<script type="text/javascript">
		function changeType(e) {
			if(e.value==0) {
				document.getElementById("type1").style.display = "none";
				document.getElementById("type2").style.display = "none";
				document.getElementById("addJoueur").style.display = "none";
			}
			else if(e.value==1) {
				document.getElementById("type1").style.display = "";
				document.getElementById("type2").style.display = "none";
				document.getElementById("addJoueur").style.display = "";
				if(e.options[0].value==0) {
					e.removeChild(e.options[0]);
				}
			} else {
				document.getElementById("type1").style.display = "none";
				document.getElementById("type2").style.display = "";
				document.getElementById("addJoueur").style.display = "none";
				if(e.options[0].value==0) {
					e.removeChild(e.options[0]);
				}
			}
		}
		
		function changeJoueur(e) {
			if(e.value==0) {
				document.getElementById("addJoueur").style.display = "none";
			}
			else {
				if(e.value.includes("_")) {
					document.getElementById("addJoueur").style.display = "none";
				} else {
					document.getElementById("addJoueur").style.display = "";
				}
				if(e.options[0].value==0) {
					e.removeChild(e.options[0]);
				}
			}
		}
	
		function Valid_Form(form) {
			if(form.type.value==1) {
				var liste = document.getElementById("joueurs_ok_1[]");
				var nb=liste.options.length ;
				for(i=0; i<liste.options.length; i++) {
					liste.options[i].disabled = false ;
					liste.options[i].selected = true ;
				}
			}
			return true ;
		}
	
		function copier(src,dest) {
			var liste = document.getElementById(src);
			var listeInvites = document.getElementById(dest);
			var lsSelections = "";
			var i=0;
			while( i<liste.options.length) {
				if(liste.options[i].selected) {
					listeInvites.appendChild(liste.options[i]);
					i-- ;
				}
				i++ ;
			}
			listeInvites.selectedIndex = -1;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="code.php" method="POST"/>
			<table colspan="2" align="center"/>
				<tr>
					<td align='right'>Type de joueur* : </td>
					<td>
						<select name="type" id="type" onchange="changeType(this) ;">
							<option value='0'>--- Choisissez un type de joueur ---</option>
							<?php
								$sSQLType = "select typ_id, typ_nom " .
									" from " . TBL_TYPE . 
									" order by typ_id " ;
								$resultType = $mysqli->query($sSQLType) ;
								while ($rowType = mysqli_fetch_array($resultType)) {
									extract($rowType) ;
									echo "<option value='".$typ_id."'>".$typ_nom."</option>" ;
								}
							?>
						</select>
					</td>
				</tr>
			</table>
			<table align="center" id="type1" style="display: none;"/>
				<tr>
					<td class="center" colspan="3">
						<img src="<?php echo IMG ; ?>loupe.png" height="20px">&nbsp;<input type="text" class="col400" id="hlSearch" name="hlSearch" onKeyUp="makeRequest('reponse.php?choix=5','hlSearch','joueurs_1[]')"/>
					</td>
				<tr>
				<tr>
					<td class="center col200">
						<select id="joueurs_1[]" name="joueurs_1[]" onChange='effacerGroup(this.name);' multiple size="20" class="col200 bgRefuse">
							<?php echo createOptionsAjoutJoueurs() ; ?>
						</select>
					</td>
					<td class="center">
						<img src="<?php echo IMG ; ?>flecheD.png" name="inviter" value=">" onClick="copier('joueurs_1[]','joueurs_ok_1[]')" width="90px" class="main"/><br/><br/>
						<img src="<?php echo IMG ; ?>flecheG.png" name="retirer" value="<" onClick="copier('joueurs_ok_1[]','joueurs_1[]')" width="90px" class="main"/>
					</td>
					<td class="center col200">
						<select id="joueurs_ok_1[]" name="joueurs_ok_1[]" multiple size="20" class="col200 bgParticipe">
							<?php echo createOptionsJoueursEquipe() ; ?>
						</select>
					</td>
				</tr>
			</table>
			<table align="center" id="type2" style="display: none;"/>
				<tr>
					<td align='right'>Joueur* : </td>
					<td>
						<select name="jou_id_2" id="jou_id_2" onchange="changeJoueur(this) ;">
							<option value='0'>--- Choisissez un joueur ---</option>
							<?php
								$sSQLJoueur = "select jou_id, jou_nom, eq_id, eq_nom " .
									" from " . TBL_JOUEUR . ", " . 
										TBL_EJT . ", " . 
										TBL_EQUIPE .  
									" where ejt_jou_id = jou_id " .
										" and ejt_eq_id = eq_id " .
										" and ejt_sai_annee = '".SAISON."' " .
										" and ejt_typ_id = 1 " .
									" order by jou_nom " ;
								$resultJoueur = $mysqli->query($sSQLJoueur) ;
								while ($rowJoueur = mysqli_fetch_array($resultJoueur)) {
									extract($rowJoueur) ;
									$flagExist=false ;
									$sSQLExist = "select eq_nom eq_joker " .
										" from " . TBL_EJT . ", " . 
											TBL_EQUIPE .  
										" where ejt_eq_id = eq_id " .
											" and ejt_sai_annee = '".SAISON."' " .
											" and ejt_jou_id = '".$jou_id."' " .
											" and ejt_typ_id = 2 " ;
									$resultExist = $mysqli->query($sSQLExist) ;
									while ($rowExist = mysqli_fetch_array($resultExist)) {
										extract($rowExist) ;
										$flagExist=true ;
									}
									if($flagExist) {
										echo "<option value='".$jou_id."_' class='optionJoker' disabled>".$jou_nom." (".$eq_nom." - Joker pour ".$eq_joker.")</option>" ;
									} else {
										if($eq_id==$_SESSION["eq_id"]) {
											echo "<option value='".$jou_id."_' class='optionJoker' disabled>".$jou_nom." (".$eq_nom.")</option>" ;
										} else {
											echo "<option value='".$jou_id."' class='optionOk'>".$jou_nom." (".$eq_nom.")</option>" ;
										}
									}
								}
							?>
						</select>
					</td>
				</tr>
			</table>
			<table align="center" id="boutons">
				<tr/>
					<td>
						<?php echo boutonRetour() . " " . boutonSubmit("addJoueur", "Valider", 'return Valid_Form(document.forms["form"]);', "display: none;") ; ?>
					</td>
				</tr>
			</table>
		</Form>
	</div>
</BODY>
</HTML>
	
		