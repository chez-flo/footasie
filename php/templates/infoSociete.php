<?php 
	include('templates/bandeau.php') ;
	
	if(isCapitaine()) {
		if(isset($_POST['infoSociete'])) {
			extract($_POST) ;
			$sSQLUpdate = "update " . TBL_SOCIETE . " set soc_nom = '" . securite_bdd($soc_nom) . "', " .
							" soc_adresse = '" . securite_bdd($soc_adresse) . "', " .
							" soc_cp = LPAD('" . securite_bdd($soc_cp) . "',5,'0'), " .
							" soc_ville = '" . securite_bdd($soc_ville) . "', " .
							" soc_org_nom = '" . securite_bdd($soc_org_nom) . "', " .
							" soc_org_adresse = '" . securite_bdd($soc_org_adresse) . "', " .
							" soc_org_cp = LPAD('" . securite_bdd($soc_org_cp) . "',5,'0'), " .
							" soc_org_ville = '" . securite_bdd($soc_org_ville) . "', " .
							" soc_contact_nom_1 = '" . securite_bdd($soc_contact_nom_1) . "', " .
							" soc_contact_mail_1 = '" . securite_bdd($soc_contact_mail_1) . "', " .
							" soc_contact_tel_1 = '" . securite_bdd($soc_contact_tel_1) . "', " .
							" soc_contact_nom_2 = '" . securite_bdd($soc_contact_nom_2) . "', " .
							" soc_contact_mail_2 = '" . securite_bdd($soc_contact_mail_2) . "', " .
							" soc_contact_tel_2 = '" . securite_bdd($soc_contact_tel_2) . "', " .
							" soc_contact_nom_3 = '" . securite_bdd($soc_contact_nom_3) . "', " .
							" soc_contact_mail_3 = '" . securite_bdd($soc_contact_mail_3) . "', " .
							" soc_contact_tel_3 = '" . securite_bdd($soc_contact_tel_3) . "' " .
							" WHERE soc_id = '" . $soc_id . "' " ;
			$resultUpdate = $mysqli->query($sSQLUpdate) ;
			envoiMailInfosSociete($_SESSION['utilisateur']) ;
			$messageOk = "Vos informations ont &eacute;t&eacute; mises &agrave; jour. Un mail de confirmation vient de vous &ecirc;tre envoy&eacute;. " ;
		}
	}
?>
	<script type="text/javascript">
		function Valid_Form(form) {
			if(form.soc_nom.value=="") {
				alert("Le nom de la société doit être renseigné.");
				return false;
			}
			if(form.soc_adresse.value=="") {
				alert("L'adresse de la société doit être renseignée.");
				return false;
			}
			if(form.soc_cp.value=="") {
				alert("Le code postal de la société doit être renseigné.");
				return false;
			}
			if(form.soc_cp.value.length!="5") {
				alert("Le code postal de la société est incorrect.");
				return false;
			}
			if(form.soc_ville.value=="") {
				alert("La ville de la société doit être renseignée.");
				return false;
			}
			if(form.soc_org_nom.value=="") {
				alert("Le nom de l'organisme doit être renseigné.");
				return false;
			}
			if(form.soc_org_adresse.value=="") {
				alert("L'adresse de l'organisme doit être renseignée.");
				return false;
			}
			if(form.soc_org_cp.value=="") {
				alert("Le code postal de l'organisme doit être renseigné.");
				return false;
			}
			if(form.soc_org_cp.value.length!="5") {
				alert("Le code postal de l'organisme est incorrect.");
				return false;
			}
			if(form.soc_org_ville.value=="") {
				alert("La ville de l'organisme doit être renseigné.");
				return false;
			}
			if(form.soc_contact_mail_1.value=="") {
				alert("Le mail du contact 1 doit être renseigné.");
				return false;
			}
			if(!validEmail(form.soc_contact_mail_1.value)) {
				alert("Le mail du contact 1 est incorrect.");
				return false;
			}
			if(form.soc_contact_tel_1.value=="") {
				alert("Le téléphone du contact 1 doit être renseigné.");
				return false;
			}
			if(!validTel(form.soc_contact_tel_1.value)) {
				alert("Le téléphone du contact 1 est incorrect.");
				return false;
			}
			if(form.soc_contact_mail_2.value!="" && !validEmail(form.soc_contact_mail_2.value)) {
				alert("Le mail du contact 2 est incorrect.");
				return false;
			}
			if(form.soc_contact_tel_2.value!="" && !validTel(form.soc_contact_tel_2.value)) {
				alert("Le téléphone du contact 2 est incorrect.");
				return false;
			}
			if(form.soc_contact_mail_3.value!="" && !validEmail(form.soc_contact_mail_3.value)) {
				alert("Le mail du contact 3 est incorrect.");
				return false;
			}
			if(form.soc_contact_tel_3.value!="" && !validTel(form.soc_contact_tel_3.value)) {
				alert("Le téléphone du contact 3 est incorrect.");
				return false;
			}
			return true ;
		}
	</script>
	<div class = "PagePrincipale"> 
		<Form name="form" action="#" method="POST"/>
			<table colspan="2" align="center"/>
				<?php
					$sSQL = "SELECT eq_id, eq_nom, soc_id, soc_nom, soc_adresse, soc_cp, soc_ville, soc_org_nom, soc_org_adresse, soc_org_cp, soc_org_ville, " .
						" soc_contact_nom_1, soc_contact_mail_1, soc_contact_tel_1, soc_contact_nom_2, soc_contact_mail_2, soc_contact_tel_2, soc_contact_nom_3, soc_contact_mail_3, soc_contact_tel_3 " .
						" FROM " . TBL_JOUEUR . ", " . TBL_EQUIPE_CORRESP . ", " . TBL_EQUIPE . ", " . TBL_SOCIETE . 
						" WHERE jou_id = ec_jou_id " .
							" AND eq_id = ec_eq_id " .
							" AND soc_id = eq_soc_id " .
							" AND jou_id = '" . $_SESSION['id'] . "' " ;
					$result = $mysqli->query($sSQL) ;
					//echo $sSQL ;
					$mois_prec = "" ;
					while ($row = mysqli_fetch_array($result)) {
						extract($row) ;
						if(isCapitaine()) {
							echo "<tr>" .
								"<td align='center'>" .
									"<table>" .
										"<tr>" .
											"<th colspan='2'><input type='hidden' name='soc_id' id='soc_id' value='".$soc_id."'/>Coordonn&eacute;es Soci&eacute;t&eacute;</th>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Nom Soci&eacute;t&eacute;* : </td><td><input type='text' name='soc_nom' id='soc_nom' value='".$soc_nom."' size='40' maxlength='100'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Adresse Soci&eacute;t&eacute;* : </td><td><input type='text' name='soc_adresse' id='soc_adresse' value='".$soc_adresse."' size='40' maxlength='100'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Code Postal Soci&eacute;t&eacute;* : </td><td><input type='text' name='soc_cp' id='soc_cp' value='".str_pad($soc_cp, 5, "0", STR_PAD_LEFT)."' size='40' maxlength='5'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Ville Soci&eacute;t&eacute;* : </td><td><input type='text' name='soc_ville' id='soc_ville' value='".$soc_ville."' size='40' maxlength='50'/></td>" .
										"</tr>" .
										"<tr>" .
											"<th colspan='2'>Coordonn&eacute;es Organisme</th>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Nom Organisme* : </td><td><input type='text' name='soc_org_nom' id='soc_org_nom' value='".$soc_org_nom."' size='40' maxlength='100'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Adresse Organisme* : </td><td><input type='text' name='soc_org_adresse' id='soc_org_adresse' value='".$soc_org_adresse."' size='40' maxlength='100'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Code Postal Organisme* : </td><td><input type='text' name='soc_org_cp' id='soc_org_cp' value='".str_pad($soc_org_cp, 5, "0", STR_PAD_LEFT)."' size='40' maxlength='5'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td align='right'>Ville Organisme* : </td><td><input type='text' name='soc_org_ville' id='soc_org_ville' value='".$soc_org_ville."' size='40' maxlength='50'/></td>" .
										"</tr>" .
									"</table>" .
								"</td>" .
							"</tr>" .
							"<tr>" .
								"<td align='center'>" .
									"<table>" .
										"<tr>" .
											"<th colspan='4'>Contacts Facturation</th>" .
										"</tr>" .
										"<tr>" .
											"<th></th>" .
											"<th>Pr&eacute;nom Nom</th>" .
											"<th>Mail</th>" .
											"<th>T&eacute;l&eacute;phone</th>" .
										"</tr>" .
										"<tr>" .
											"<td>Contact 1*</td>" .
											"<td><input type='text' name='soc_contact_nom_1' id='soc_contact_nom_1' value='".$soc_contact_nom_1."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_mail_1' id='soc_contact_mail_1' value='".$soc_contact_mail_1."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_tel_1' id='soc_contact_tel_1' value='".lpad($soc_contact_tel_1, 10, "0")."' size='7' maxlength='10'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td>Contact 2</td>" .
											"<td><input type='text' name='soc_contact_nom_2' id='soc_contact_nom_2' value='".$soc_contact_nom_2."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_mail_2' id='soc_contact_mail_2' value='".$soc_contact_mail_2."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_tel_2' id='soc_contact_tel_2' value='".lpad($soc_contact_tel_2, 10, "0")."' size='7' maxlength='10'/></td>" .
										"</tr>" .
										"<tr>" .
											"<td>Contact 3</td>" .
											"<td><input type='text' name='soc_contact_nom_3' id='soc_contact_nom_3' value='".$soc_contact_nom_3."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_mail_3' id='soc_contact_mail_3' value='".$soc_contact_mail_3."' size='30' maxlength='100'/></td>" .
											"<td><input type='text' name='soc_contact_tel_3' id='soc_contact_tel_3' value='".lpad($soc_contact_tel_3, 10, "0")."' size='7' maxlength='10'/></td>" .
										"</tr>" .
									"</table>" .
								"<td>" .
							"</tr>" ;
						}
					}
				?>
			</table>
			<table align="center">
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("infoSociete", "Mettre &agrave; jour", 'return Valid_Form(document.forms["form"]);') ; ?>
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
	
		