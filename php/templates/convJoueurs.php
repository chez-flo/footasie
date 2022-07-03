<?php include('templates/bandeau.php') ; ?>
<div class="PagePrincipale">
<script>
	function Valid_Form(form) {
		var liste = document.getElementById("convoques[]");
		var nb=liste.options.length ;
		if(nb==0) {
			alert("Vous n'avez choisi aucun joueur à convoquer ...") ;
			return false ;
		} else {
			if(nb<7) {
				if(confirm("Vous n'avez choisi que " + nb + " joueur(s), êtes-vous sûr de vouloir continuer ?")) {
					for(i=0; i<liste.options.length; i++) {
						liste.options[i].disabled = false ;
						liste.options[i].selected = true ;
					}
					return true ;
				} else {
					return false ;
				}
			}
			else {
				for(i=0; i<liste.options.length; i++) {
					liste.options[i].disabled = false ;
					liste.options[i].selected = true ;
				}
				return true ;
			}
		}
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
	<form name="form" action="code.php" method="POST"/>
		<table align="center">
		<?php
			$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
				" date_format(cre_date, ' %d '), " .
				" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
				" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
				" cre_id, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) poule, mat_id, pou_id " .
				" FROM " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_POULE . ", " . TBL_SCORE . " sco1, " . TBL_SCORE . " sco2, " . TBL_EPS . ", " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
				" WHERE eve_id = pou_eve_id " .
					" and eps_eq_id = e1.eq_id " .
					" and eps_pou_id = pou_id " .
					" and sai_pou_id = pou_id " .
					" and mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and cre_mat_id = mat_id " .
					" and cre_ter_id = ter_id " .
					" and mat_id = sco1.sco_mat_id " .
					" and mat_id = sco2.sco_mat_id " .
					" and mat_eq_id_1 = sco1.sco_eq_id " .
					" and mat_eq_id_2 = sco2.sco_eq_id " .
					" and mat_pou_id = pou_id " .
					" and mat_sai_annee = sai_annee " .
					" and eps_sai_annee = sai_annee " .
					" and sai_annee = '" . SAISON . "' " .
					" and mat_id = '" . htmlentities($_GET['id']) . "' " .
				" ORDER BY cre_date, ter_nom, cre_id " ;
			$result = $mysqli->query($sSQL) ;
			//echo $sSQL ;
			$mois_prec = "" ;
			while ($row = mysqli_fetch_array($result)) {
				extract($row) ;
				echo "<input type='hidden' name='pou_id' id='pou_id' value='".$pou_id."'>" ;
				echo "<input type='hidden' name='mat_id' id='mat_id' value='".$mat_id."'>" ;
				echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
				echo "<input type='hidden' name='jour' id='jour' value='".$jour."'>" ;
				echo "<input type='hidden' name='ter_nom' id='cre_id' value='".$ter_nom."'>" ;
				echo "<input type='hidden' name='eqId1' id='eqId1' value='".$eqId1."'>" ;
				echo "<input type='hidden' name='eqId2' id='eqId2' value='".$eqId2."'>" ;
				echo "<input type='hidden' name='eq1' id='eq1' value='".$eq1."'>" ;
				echo "<input type='hidden' name='eq2' id='eq2' value='".$eq2."'>" ;
				echo "<tr><td colspan='3'>".$jour." &agrave; ".$ter_nom." : ".$eq1." contre ".$eq2." arbitr&eacute; par ".$arb." en ".$poule."</td></tr>" ;
			}
		?>
		</table>
		<table colspan="3" align="center">
			<tr>
				<td class="center col200">
					<select id="presents[]" name="presents[]" onChange='effacerGroup(this.name);' multiple size="20" class="col200 bgRefuse">
						<?php echo createOptionsConvoques(htmlentities($_GET['id']), '1') ; ?>
					</select>
				</td>
				<td class="center">
					<img src="<?php echo IMG ; ?>flecheD.png" name="inviter" value=">" onClick="copier('presents[]','convoques[]')" width="90px" class="main"/><br/><br/>
					<img src="<?php echo IMG ; ?>flecheG.png" name="retirer" value="<" onClick="copier('convoques[]','presents[]')" width="90px" class="main"/>
				</td>
				<td class="center col200">
					<select id="convoques[]" name="convoques[]" multiple size="20" class="col200 bgParticipe">
						<?php echo createOptionsConvoques(htmlentities($_GET['id']), '2') ; ?>
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
				<td colspan="2" align="center">
					<?php echo boutonRetour() . " " . boutonSubmit("convJoueurs", "Convoquer les joueurs", 'return Valid_Form(document.forms["form"]);') ; ?>
				</td>
			</tr>
		</table>
	</Form>
</div>