<?php 
	include('templates/bandeau.php') ;
?>
	<script type="text/javascript">
		function changeType(e) {
			if(e.value==0) {
				document.getElementById("type1").style.display = "none";
				document.getElementById("type2").style.display = "none";
				document.getElementById("equipe").style.display = "none";
				document.getElementById("addIns").style.display = "none";
				document.getElementById("addInsNew").style.display = "none";
				document.getElementById("eq_id").value = "";
				document.getElementById("soc_id_new").value = "";
			}
			else if(e.value==1) {
				document.getElementById("type1").style.display = "";
				document.getElementById("type2").style.display = "none";
				document.getElementById("equipe").style.display = "none";
				document.getElementById("addIns").style.display = "none";
				document.getElementById("addInsNew").style.display = "none";
				document.getElementById("eq_id").value = "";
				document.getElementById("soc_id_new").value = "";
			} else {
				document.getElementById("type1").style.display = "none";
				document.getElementById("type2").style.display = "";
				document.getElementById("equipe").style.display = "none";
				document.getElementById("addIns").style.display = "none";
				document.getElementById("addInsNew").style.display = "";
				document.getElementById("eq_id").value = "";
				document.getElementById("soc_id_new").value = "";
			}
		}
		
		function changeSoc(e) {
			if(e.value!=0) {
				document.getElementById("soc_tr").style.display = "none";
				document.getElementById("societe").value = "";
				/*makeRequest('reponse.php?choix=3','societe','msg_soc');*/
			} else {
				document.getElementById("soc_tr").style.display = "";
			}
		}
		
		function changeSocNew(e) {
			if(e.value!=0) {
				document.getElementById("soc_tr_new").style.display = "none";
				document.getElementById("societe_new").value = "";
				makeRequest('reponse.php?choix=3','societe_new','msg_soc_new');
			} else {
				document.getElementById("soc_tr_new").style.display = "";
			}
		}
		
		function changeEquipe(e) {
			if(e.value!=0) {
				document.getElementById("equipe").style.display = "";
				document.getElementById("addIns").style.display = "";
			} else {
				document.getElementById("equipe").style.display = "none";
				document.getElementById("addIns").style.display = "none";
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
				if(form.eq_id.value=="") {
					alert("Merci de choisir une équipe.");
					return false;
				}
				if(form.soc_id.value=="") {
					alert("Merci de choisir une société.");
					return false;
				}
				if(form.soc_id.value=="0" && form.societe.value=="") {
					alert("Merci de renseigner le nom d'une société.");
					return false;
				}
				if(form.couleur.value=="") {
					alert("Merci de renseigner la couleur du maillot principal.");
					return false;
				}
				if(form.mail_1.value!="" && !validEmail(form.mail_1.value)) {
					alert("L'adresse mail du premier correspondant supplémentaire n'est pas valide.");
					return false;
				}
				if(form.mail_2.value!="" && !validEmail(form.mail_2.value)) {
					alert("L'adresse mail du second correspondant supplémentaire n'est pas valide.");
					return false;
				}
			} else if(form.type.value==2) {
				if(form.soc_id_new.value=="") {
					alert("Merci de choisir une société.");
					return false;
				}
				if(form.soc_id_new.value=="0" && form.societe_new.value=="") {
					alert("Merci de renseigner le nom d'une société.");
					return false;
				}
				if(form.soc_id_new.value=="0" && form.societe_new.value!="" && form.msg_soc_new.value!="") {
					alert("Cette société existe déjà. Merci de la sélectionner dans la liste déroulante.");
					return false;
				}
				if(form.eq_nom_new.value=="") {
					alert("Merci de renseigner le nom d'une équipe.");
					return false;
				}
				if(form.eq_nom_new.value!="" && form.msg_eq_new.value!="") {
					alert("Cette équipe est déjà inscrite.");
					return false;
				}
				if(form.couleur_new.value=="") {
					alert("Merci de renseigner la couleur du maillot principal.");
					return false;
				}
				if(form.nom_1_new.value=="") {
					alert("Merci de renseigner le nom d'un responsable.");
					return false;
				}
				if(form.mail_1_new.value=="") {
					alert("Merci de renseigner le mail d'un responsable.");
					return false;
				}
				if(form.mail_1_new.value!="" && !validEmail(form.mail_1_new.value)) {
					alert("L'adresse mail du premier correspondant n'est pas valide.");
					return false;
				}
				if(form.mail_2_new.value!="" && !validEmail(form.mail_2_new.value)) {
					alert("L'adresse mail du second correspondant n'est pas valide.");
					return false;
				}
			} else {
				return false ;
			}
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="code.php" method="POST"/>
			<table width="70%" align="center" style='margin-bottom: 10px'>
				<tr>
					<td align="justify">
						<center><b>Veuillez lire attentivement les indications de ce paragraphe.</b></center>
						<p>Remplissez ce bulletin pour toutes les équipes de votre société.</p>
						<p><b>IMPORTANT</b> : vous trouverez une question demandant si vous souhaitez vous inscrire pour la coupe ELOCAR. Merci d'y répondre afin que nous sachions comment nous organiser. Bien entendu, la tenue ou non de cette coupe, ainsi que la formule qu'elle adoptera dépendra du nombre de participants et des créneaux que nous aurons réussi à obtenir.</p>
						<p>Je vous rappelle que l'inscription avait été fixée l'année dernière à 300€ pour le championnat et le tarif de la coupe était de l'ordre de 50€ les années précédentes (pas de coupe l'année dernière). Ces valeurs indicatives sont susceptibles d'évoluer en fonction des tarifs de location des terrains que nous ne connaissons pas encore tous.</p>
						<p><b>Pour les nouvelles équipes</b> : si vous souhaitez directement intégrer le championnat par une poule de niveau C, veuillez faire votre demande dans les commentaires, on verra ce qu'on pourra faire.</p>
						<p>Mon mail est <u><i><a href="mailto:foot@asiesophia.fr">foot@asiesophia.fr</a></i></u> ou <u><i><a href="mailto:florian.joyeux@gmail.com">florian.joyeux@gmail.com</a></i></u> si vous avez des questions à poser au préalable.</p>
					</td>
				</tr>
			</table>
			<table colspan="2" align="center"/>
				<tr>
					<td align='right'>Choix de l'inscription* : </td>
					<td>
						<select name="type" id="type" onchange="changeType(this) ;">
							<option value='0'>--- Veuillez choisir ---</option>
							<option value='1'>R&eacute;inscription &eacute;quipe <?php echo SAISON ; ?></option>
							<option value='2'>Nouvelle &eacute;quipe</option>
						</select>
					</td>
				</tr>
			</table>
			<table align="center" id="type1" style="display: none;"/>
				<tr>
					<td align='center' colspan="2">&Eacute;quipe* :
						<select name="eq_id" id="eq_id" onchange="changeEquipe(this);makeRequest('reponse.php?choix=2','eq_id','eq_table');">
							<option value="">--- Choisissez une &eacute;quipe ---</option>
							<?php
								$sSQLEquipe = "select eq_id, eq_nom " .
									" from " . TBL_EPS . ", " . 
										TBL_EQUIPE .  
									" where eps_eq_id = eq_id " .
										" and eps_sai_annee = '".SAISON."' " .
										" and eps_pou_id between 10 and 30 " .
										" and not exists (select eq_id, eq_nom " .
									" from " . TBL_EPS . 
									" where eps_eq_id = eq_id " .
										" and eps_sai_annee = '".SAISON_INS."') " .
									" order by eq_nom " ;
								
								$resultEquipe = $mysqli->query($sSQLEquipe) ;
								while ($rowEquipe = mysqli_fetch_array($resultEquipe)) {
									extract($rowEquipe) ;
									echo "<option value='".$eq_id."'>".$eq_nom."</option>" ;
								}
							?>
						</select>
					</td>
				</tr>
				<tr id="equipe" style='display: none; margin-top: 15px;'>
					<td id="eq_table">
					</td>
				</tr>
			</table>
			<table align="center" id="type2" style="display: none; margin-top: 15px;"/>
				<tr>
					<td>
						<table align="center">
							<tr>
								<td align='right' valign="top">Société* : </td>
								<td>
									<select name="soc_id_new" id="soc_id_new" onchange="changeSocNew(this) ;">
										<option value="">--- Veuillez choisir ---</option>
										<option value="0">Nouvelle soci&eacute;t&eacute;</option>
									<?php
										$sSQLSociete = "select soc_id, soc_nom " .
												" from " . TBL_SOCIETE . 
												" order by soc_nom " ;
										$resultSociete = $mysqli->query($sSQLSociete) ;
										while ($rowSociete = mysqli_fetch_array($resultSociete)) {
											extract($rowSociete) ;
											echo "<option value='".$soc_id."'>".$soc_nom."</option>" ;
										}
									?>
									</select>
								</td>
							</tr>
							<tr id="soc_tr_new" style="display: none;">
								<td align='right'>Nom de la soci&eacute;t&eacute;* : </td>
								<td>
									<input type='text' name='societe_new' id='societe_new' size='20' onkeyup="makeRequest('reponse.php?choix=3','societe_new','msg_soc_new');"/>
									<output id="msg_soc_new" name="msg_soc_new" class="error" value=""/>
								</td>
							</tr>
							<tr>
								<td align='right'>Nom de l'&eacutequipe* : </td>
								<td>
									<input type='text' name='eq_nom_new' id='eq_nom_new' size='20' onkeyup="makeRequest('reponse.php?choix=4','eq_nom_new','msg_eq_new');"/>
									<output id="msg_eq_new" name="msg_eq_new" class="error" value=""/>
								</td>
							</tr>
							<tr>
								<td align='right'>Terrain &agrave; &eacute;viter : </td>
								<td>
									<select name="ter_id_new" id="ter_id_new">
										<option value="0">Aucun</option>
									<?php
										$sSQLTerrain = "select ter_id, ter_nom " .
												" from " . TBL_TERRAIN . 
												" order by ter_nom " ;
										$resultTerrain = $mysqli->query($sSQLTerrain) ;
										while ($rowTerrain = mysqli_fetch_array($resultTerrain)) {
											extract($rowTerrain) ;
											echo "<option value='".$ter_id."'>".$ter_nom."</option>" ;
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td align='right'>Jour &agrave; &eacute;viter : </td>
								<td>
									<select name="jour_new" id="jour_new">
										<option value="aucun">Aucun</option>
										<option value="lundi">Lundi</option>
										<option value="mardi">Mardi</option>
										<option value="mercredi">Mercredi</option>
										<option value="jeudi">Jeudi</option>
										<option value="vendredi">Vendredi</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align='right'>&Eacute;quipe amie : </td>
								<td>
									<select name="ami_id_new" id="ami_id_new">
										<option value="0">Aucune</option>
									<?php
										$sSQLAmi = 	"select eq_id, eq_nom" .
													" from " . TBL_EPS . ", " . 
														TBL_EQUIPE . 
													" where eps_eq_id = eq_id " .
														" and eq_id not in (1,230)" .
														" and eps_sai_annee = '".SAISON."' " .
														" and eps_pou_id = 2 " .
													" order by eq_nom " ;
										$resultAmi = $mysqli->query($sSQLAmi) ;
										while ($rowAmi = mysqli_fetch_array($resultAmi)) {
											extract($rowAmi) ;
											echo "<option value='".$eq_id."'>".$eq_nom."</option>" ;
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td align='right'>Participation &agrave; la coupe ELOCAR* : </td>
								<td>
									<select name="coupe_new" id="coupe_new">
										<option value="1">oui</option>
										<option value="0">non</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align='right'>Couleur Maillot 1* : </td><td><input type='text' name='couleur_new' id='couleur_new' size='20'/></td>
							</tr>
							<tr>
								<td align='right'>Couleur Maillot 2 : </td><td><input type='text' name='couleur_ext_new' id='couleur_ext_new' size='20'/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align='center'>
						<table>
							<tr>
								<th colspan="4">Responsables</th>
							</tr>
							<tr>
								<td align='right'>Pr&eacute;nom Nom Resp. 1* : </td><td><input type='text' name='nom_1_new' id='nom_1_new' size='30'/></td>
								<td align='right'>Mail Resp. 1* : </td><td><input type='text' name='mail_1_new' id='mail_1_new' size='30'/></td>
							</tr>
							<tr>
								<td align='right'>Pr&eacute;nom Nom Resp. 2 : </td><td><input type='text' name='nom_2_new' id='nom_2_new' size='30'/></td>
								<td align='right'>Mail Resp. 2 : </td><td><input type='text' name='mail_2_new' id='mail_2_new' size='30'/></td>
							</tr>
						</table>
					<td>
				</tr>
				<tr>
					<td align="center">
						<table>
							<tr>
								<th>Commentaires</th>
							</tr>
							<tr>
								<td><input type='text'  name='commentaire_new' id='commentaire_new' size='100'/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table align="center" id="boutons">
				<tr>
					<td>
						<?php echo boutonRetour() . " " . boutonSubmit("addInsNew", "Valider l&apos;inscription pour ".SAISON_INS, 'return Valid_Form(document.forms["form"]);', "display: none;") . " " . boutonSubmit("addIns", "Valider la r&eacute;inscription pour ".SAISON_INS, 'return Valid_Form(document.forms["form"]);', "display: none;") ; ?>
					</td>
				</tr>
			</table>
		</Form>
	</div>
</BODY>
</HTML>
	
		