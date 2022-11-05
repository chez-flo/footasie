<?php include('templates/bandeau.php') ; 

	if(isAdmin()) { 
		if(isset($_POST['envoiMail'])) {
			extract($_POST) ;
			if(isset($mail)) {
				$eq_id=array() ;
				for($i=0;$i<sizeof($mail);$i++) {
					$mail[$i] = substr($mail[$i],strrpos($mail[$i],"_")+1) ;
				}
				for($i=0;$i<sizeof($mail);$i++) {
					$flag=false ;
					for($j=0;$j<sizeof($eq_id);$j++) {
						if($mail[$i]==$eq_id[$j]) {
							$flag=true ;
						}
					}
					if(!$flag) {
						$eq_id[sizeof($eq_id)]=$mail[$i] ;
					}
				}
			}
			$dest = "" ;
			for($i=0;$i<sizeof($eq_id);$i++) {
				$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_jou_id = jou_id " .
					" and ec_eq_id = '". $eq_id[$i] ."' " ;
				//echo $sSQL . "<br/>" ;
				$result = $mysqli->query($sSQL) ;
				while ($row = mysqli_fetch_array($result)) {
					extract($row) ;
					$dest .= $jou_mail . ";" ;
				}
			}
			
			$sujet = "[Foot Asie] " . $sujet ;
			$texte = nl2br($corps) ;
			$adresse_exp = ADR_MAIL ;
			$frontiere = "---~".mt_rand()."~";
			$headers = "From: $adresse_exp\n";
			$headers.= "Reply-to: $adresse_exp\n";
			$headers.= "Cc: $adresse_exp\n";
			//$headers.= "Bcc: $dest\n";
			$headers.= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=\"utf8\"";
			//$dest = "test-xoq7mb1me@srv1.mail-tester.com" ;
			//echo $dest . "<br/>" ;
			//echo html_entity_decode($sujet) . "<br/>" ;
			//echo html_entity_decode($texte) . "<br/>" ;
			//echo $headers . "<br/>" ;
			$err = mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
			//echo "Retour envoi mail : " . $err ;
		}
	}



?>

<script>
	function Valid_Form(form) {
		var liste = document.getElementById("mail[]");
		var nb=liste.options.length ;
		if(nb==0) {
			alert("Vous n'avez choisi aucune équipe") ;
			return false ;
		}
		if(document.getElementById("sujet").value=="") {
			alert("Vous n'avez pas mis de sujet") ;
			return false ;
		}
		if(document.getElementById("corps").value=="") {
			alert("Vous n'avez pas dans le corps du mail") ;
			return false ;
		}
		for(i=0; i<liste.options.length; i++) {
			liste.options[i].selected = true ;
		}
		return true ;
	}
	
	function selectAllOptionsGroup(src, optGroup) {
		var liste = document.getElementById(src);
		var lsSelections = "";
		for(var i=0; i<liste.options.length; i++) {
			if(liste.options[i].value==optGroup) {
				liste.options[i].selected = false;
			}
			if(liste.options[i].value.substr(0,optGroup.length+1)==optGroup+"_") {
				liste.options[i].selected = true;
			}
		}
	}
	
	function selectAllOptions(src) {
		var liste = document.getElementById(src);
		var lsSelections = "";
		for(var i=0; i<liste.options.length; i++) {
			if(liste.options[i].value.indexOf("_") != -1) {
				liste.options[i].selected = true;
			}
			else {
				liste.options[i].selected = false;
			}
		}
	}
	
	function effacerGroup(src) {
		var liste = document.getElementById(src);
		var lsSelections = "";
		for(var i=0; i<liste.options.length; i++) {
			if(liste.options[i].value.indexOf("_") == -1) {
				liste.options[i].selected = false;
			}
		}
	}
	
	$(function() {
		$( "#datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true
		});
		$( "#datepicker" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
		$( "#datepickerdeb" ).datepicker({
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true,
			altField: "#datepickerdeb",
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			weekHeader: 'Sem.',
			firstDay: 1 
		});
		$( "#datepickerdeb" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
		$( "#datepickerfin" ).datepicker({
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true,
			altField: "#datepickerfin",
			closeText: 'Fermer',
			prevText: 'Précédent',
			nextText: 'Suivant',
			currentText: 'Aujourd\'hui',
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			weekHeader: 'Sem.',
			firstDay: 1 
		});
		$( "#datepickerfin" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
	});

	function changeDate(e) {
		jour = e.value.substr(0,2) ;
		mois = e.value.substr(3,2) ;
		annee = e.value.substr(6,4) 
		if(jour.substr(0,1) == "0") {
			jour = jour.substr(1,1) ;
		}
		if(mois.substr(0,1) == "0") {
			mois = mois.substr(1,1) ;
		};
	}

	function changeDateInput(e,f) {
		document.getElementById(e).value=document.getElementById(f).value ;
		if(e=="d_deb") {
			jour = document.getElementById(e).value.substr(0,2) ;
			mois = document.getElementById(e).value.substr(3,2) ;
			annee = document.getElementById(e).value.substr(6,4) ;
			jourFin = document.getElementById('d_fin').value.substr(0,2) ;
			moisFin = document.getElementById('d_fin').value.substr(3,2) ;
			anneeFin = document.getElementById('d_fin').value.substr(6,4) ;
			if(anneeFin+moisFin+jourFin<annee+mois+jour) {
				document.getElementById('d_fin').value=document.getElementById(e).value ;
			}
		}
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
	
	function afficheFin(src) {
		if(src.value=="0") {
			document.getElementById("tdFin").style.visibility="hidden" ;
		}		
		else {
			document.getElementById("tdFin").style.visibility="visible" ;
		}
	}
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
		document.getElementById("apercu").style.visibility = "visible" ;
	}
</script>
<div class = "PagePrincipale">
	<form id="formDemCreneau" action="#" method="POST"/>
		<CENTER>
			<table colspan="3" align="center">
				<tr>
					<td class="center" colspan="3">
						<img src="<?php echo IMG ; ?>loupe.png" height="20px">&nbsp;<input type="text" class="col400" id="hlSearch" name="hlSearch" onKeyUp="makeRequest('reponse.php?choix=1','hlSearch','equipes[]')"/>
					</td>
				<tr>
				<tr>
					<td class="center col200">
						<select id="equipes[]" name="equipes[]" onChange='effacerGroup(this.name);' multiple size="20" class="col200 bgRefuse">
							<?php echo createOptionsEquipes() ; ?>
						</select>
					</td>
					<td class="center">
						<img src="<?php echo IMG ; ?>flecheD.png" name="inviter" value=">" onClick="copier('equipes[]','mail[]')" width="90px" class="main"/><br/><br/>
						<img src="<?php echo IMG ; ?>flecheG.png" name="retirer" value="<" onClick="copier('mail[]','equipes[]')" width="90px" class="main"/>
					</td>
					<td class="center col200">
						<select id="mail[]" name="mail[]" multiple size="20" class="col200 bgParticipe">
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="center">
						<b><i>** : Cliquer sur le nom des listes afin de s&eacute;lectionner le groupe</i></b>
					</td>
				</tr>
			</table>
			<table align="center">
				<tr>
					<td colspan="3" class="center">
						Sujet : <input type="text" id="sujet" name="sujet" value="" size="75"/>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="center">
						<textarea name="corps" id="corps" cols="150" rows="10"></textarea>
					</td>
				</tr>
			</table>
			<table align="center">
				<tr>
					<td align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("envoiMail", "Envoyer le mail", 'return Valid_Form(document.forms["form"]);') ; ?>
					</td>
				</tr>
			</table>
		</CENTER>
	</form>
</div>
