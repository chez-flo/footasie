<?php
include("conf.php") ;
include("function.php") ;
//include("class/icalcreator/function.php") ;
//print_r($_SESSION) ;
$menuFlag = false ;
function accueil() {
	$titre = "Bienvenue" ;
	$menuFlag = true ;
	$retour = array('titre' => $titre, 'menuFlag' => $menuFlag) ;
	return $retour ;
}

function calendrier($serie="") {
	$titre = "Calendrier S&eacute;rie ".$serie ;
		
	$retour = array('titre' => $titre,
					'nomSerie' => $serie) ;
	return $retour ;
}

function calendrierG() {
	
	$titre = "Calendrier G&eacute;n&eacute;ral" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function equipe($idEquipe="") {
	$titre = "Fiche &Eacute;quipe" ;
		
	$retour = array('titre' => $titre,
					'idEquipe' => $idEquipe) ;
	return $retour ;
}

function ch_equipe() {
	$titre = "Choix d'&Eacute;quipe" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function reglement() {
	$titre = "R&eacute;glement" ;
		
	$retour = array('titre' => $titre) ;
	
	return $retour ;
}

function stade() {
	$titre = "Stades" ;
		
	$retour = array('titre' => $titre) ;
	
	return $retour ;
}

function coupe() {
	$titre = "Calendrier de la Phase de Poules de " . NOM_EVENEMENT ;
		
	$retour = array('titre' => $titre) ;
	
	return $retour ;
}

function coupeF() {
	$titre = "Calendrier des Phases Finales de " . NOM_EVENEMENT ;
		
	$retour = array('titre' => $titre) ;
	
	return $retour ;
}

function correspondant() {
	$titre = "Correspondants des &eacute;quipes" ;
		
	$retour = array('titre' => $titre) ;
	
	return $retour ;
}

function identification() {
	$titre = 'Identification' ;
	if(isset($_SESSION["validate"]) && $_SESSION["validate"] == "ko_erreur") {
		$message = "Erreur : Identification incorrecte" ;
	}
	$retour = array('titre' => $titre,
					'message' => isset($message) ? $message : '' ) ;
	return $retour ;
}

function majScore() {
	
	$titre = "Mise &agrave; jour des scores" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function envoiScore() {
	
	$titre = "Envoi du score" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function forfait() {
	
	$titre = "déclarer forfait" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function score() {
	
	$titre = "R&eacute;sultats" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function inscription() {
	
	$titre = "Inscription pour ".NOM_SAISON_INS ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function ajoutMatch() {
	
	$titre = "Ajout d'un nouveau match" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function demandeCreneau() {
	
	$titre = "Demande d'un cr&eacute;neau" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function report() {
	
	$titre = "Report de match" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function annule() {
	
	$titre = "Annulation de match" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function modifArb() {
	
	$titre = "Modification Arbitrage" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function information($suite="Personnelles") {
	
	$titre = "Informations " . $suite ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function envoi_mail() {
	
	$titre = "Envoi des Mails" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function stats() {
	
	$titre = "Statistiques globales" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function mdp() {
	
	$titre = "Mot de passe oubli&eacute;" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function test() {
	
	$titre = "Page de Test" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function relance() {
	
	$titre = "Page de Relance" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function listeMatchs() {
	
	$titre = "Liste des Matchs" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function dispo() {
	
	$titre = "Disponibilit&eacute; des joueurs" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function detailMatch() {
	
	$titre = "Match" ;
		
	$retour = array('titre' => $titre) ;
	return $retour ;
}

function ics($eq_id) {
	$retour = array('file' => createCalendar($eq_id)) ;
	return $retour ;
}

if (isset($_GET['op'])) {
	switch(htmlentities($_GET['op'])) {
		case 'cal' : // Page du calendrier des équipes
			if(isset($_GET['s'])) {
				extract(calendrier(strtoupper(htmlentities($_GET['s'])))) ;
				include("templates/calendrier.php") ;
			}
			else {
				extract(calendrierG()) ;
				include("templates/calendrierGeneral.php") ;
			}
			break ;
		case 'calG' : // Page du calendrier général
			extract(calendrierG()) ;
			include("templates/calendrierGeneral.php") ;
			break ;
		case 'eq' : // Page des équipes
			extract(equipe(isset($_GET['id']) ? htmlentities($_GET['id']) : '')) ;
			include("templates/equipe.php") ;
			break ;
		case 'ch_eq' : // Page des équipes
			extract(ch_equipe()) ;
			include("templates/ch_equipe.php") ;
			break ;
		case 'reg' : // Page du règlement
			extract(reglement()) ;
			include("templates/reglement.php") ;
			break ;
		case 'stade' : // Page des Stades
			extract(stade()) ;
			include("templates/stade.php") ;
			break ;
		case 'coupe' : // Page des phases de poule de la Coupe
			extract(coupe()) ;
			include("templates/coupe.php") ;
			break ;
		case 'coupeF' : // Page des phases finales de la Coupe
			extract(coupeF()) ;
			include("templates/coupeF.php") ;
			break ;
		/*case 'cor' : // Page des correspondant
			extract(correspondant()) ;
			include("templates/corresp.php") ;
			break ;*/
		case 'id' : // Identification
			extract(identification()) ;
			include("templates/identification.php") ;
			break ;
		case 'mdp' : // Identification
			extract(mdp()) ;
			include("templates/mdp.php") ;
			break ;
		case 'lm' : // Liste des matchs
			extract(listeMatchs()) ;
			include("templates/listeMatchs.php") ;
			break ;
		case 'id_cor' : // Identification 
			extract(identification()) ;
			include("templates/identification.php") ;
			break ;
		case 'stat' : // Statistiques 
			extract(stats()) ;
			include("templates/stats.php") ;
			break ;
		case 'logout' : // Déconnexion
			include("logout.php") ;
			break ;
		case 'dj' : // Déconnexion
			extract(dispo()) ;
			if(checkMatch(htmlentities($_GET['id']))) {
				include("templates/dispoJoueurs.php") ;
			} else {
				include("templates/accueil.php") ;
			}
			break ;
		case 'cj' : // Déconnexion
			extract(dispo()) ;
			if(checkMatch(htmlentities($_GET['id']))) {
				include("templates/convJoueurs.php") ;
			} else {
				include("templates/accueil.php") ;
			}
			break ;
		case 'dm' : // Déconnexion
			extract(detailMatch()) ;
			if(checkMatch(htmlentities($_GET['id']))) {
				include("templates/detailMatch.php") ;
			} else {
				include("templates/accueil.php") ;
			}
			break ;
		case 'sc' : // Déconnexion
			if(checkMatch(htmlentities($_GET['id']))) {
				include("templates/supprCom.php") ;
			} else {
				include("templates/accueil.php") ;
			}
			break ;
		case 'ms' : // Déconnexion
			if(checkMatch(htmlentities($_GET['id']))) {
				include("templates/modifStatut.php") ;
			} else {
				include("templates/accueil.php") ;
			}
			break ;
		case 'ics' : // Téléchargement du calendrier
			extract(ics(isset($_GET['eq_id']) ? htmlentities($_GET['eq_id']) : '')) ;
			redirect("envoi_ics.php?file=".$file) ;
			break ;
		case 'rel' : // Ajout d'un match
			if(isAdmin()) {
				if(isset($_GET['id'])) {
					extract(relance()) ;
					include("templates/admin/relance.php");
				}
				else {
					extract(accueil()) ;
					include("templates/accueil.php");
				}
			}
			break ;
		case 'majS' : // Mise à jour du score par le capitaine et l'admin
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(majScore()) ;
					include("templates/admin/majScore.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'envS' : // Envoi du score par les capitaines
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(envoiScore()) ;
					include("templates/admin/envScore.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'score' : // Mise à jour du score par le capitaine et l'admin
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(score()) ;
					include("templates/admin/majScore.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'addM' : // Ajout d'un match
			if(isAdmin()) {
				if(isset($_GET['id'])) {
					extract(ajoutMatch()) ;
					include("templates/admin/ajoutMatch.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'dem' : // Demande de créneau
			if(isset($_GET['id'])) {
				extract(demandeCreneau()) ;
				include("templates/demCreneau.php") ;
			}
			else {
				extract(calendrierG()) ;
				include("templates/calendrierGeneral.php") ;
			}
			break ;
		case 'rep' : // Report
			if(isAdmin()) {
				if(isset($_GET['id'])) {
					extract(report()) ;
					include("templates/admin/report.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'demRep' : // Demande de report
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(report()) ;
					include("templates/admin/demReport.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'forfait' : // Demande de report
			if(isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(forfait()) ;
					include("templates/demForfait.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'ann' : // Annuler Match amical
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					extract(annule()) ;
					include("templates/admin/annule.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'modArb' : // Modification d'arbitrage
			if(isAdmin()) {
				if(isset($_GET['id'])) {
					extract(modifArb()) ;
					include("templates/admin/modifArb.php") ;
				}
				else {
					extract(calendrierG()) ;
					include("templates/calendrierGeneral.php") ;
				}
			}
			break ;
		case 'repA' : // acceptation du report
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					include("templates/admin/repAccept.php") ;
				}
				else {
			extract(accueil()) ;
					include("templates/accueil.php") ;
				}
			}
			break ;
		case 'repRR' : // Refus de la date du report
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					$refus="R" ;
					include("templates/admin/repRefus.php") ;
				}
				else {
					extract(accueil()) ;
					include("templates/accueil.php") ;
				}
			}
			break ;
		case 'repRD' : // Refus de la date du report
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					$refus="D" ;
					include("templates/admin/repRefus.php") ;
				}
				else {
					extract(accueil()) ;
					include("templates/accueil.php") ;
				}
			}
			break ;
		case 'geq' : // Gestion des joueurs
			if(isAdmin() || isCapitaine()) {
				$titre="Gestion des joueurs" ;
				include("templates/gestionEquipe.php") ;
			}
			else {
				extract(accueil()) ;
				include("templates/accueil.php") ;
			}
			break ;
		case 'jou' : // Gestion des joueurs
			if(isAdmin() || isCapitaine()) {
				if(isset($_GET['id'])) {
					$titre="Fiche joueur" ;
					include("templates/infoJoueur.php") ;
				}
				else {
					extract(accueil()) ;
					include("templates/accueil.php") ;
				}
			}
			break ;
		case 'addJ' : // Gestion des joueurs
			if(isAdmin() || isCapitaine()) {
				$titre="Ajout joueur" ;
				include("templates/addJoueur.php") ;
			}
			else {
				extract(accueil()) ;
				include("templates/accueil.php") ;
			}
			break ;
		case 'infoE' : // Informations
			if(isCapitaine()) {
				extract(information("&Eacute;quipe")) ;
				include("templates/infoEquipe.php") ;
			}
			else {
				extract(accueil()) ;
				include("templates/accueil.php") ;
			}
			break ;
		case 'infoS' : // Informations
			if(isCapitaine()) {
				extract(information("Soci&eacute;t&eacute;")) ;
				include("templates/infoSociete.php") ;
			}
			else {
				extract(accueil()) ;
				include("templates/accueil.php") ;
			}
			break ;
		case 'info' : // Informations
			extract(information()) ;
			include("templates/info.php") ;
			break ;
		case 'ins' : // Inscription
			extract(inscription()) ;
			include("templates/inscription.php") ;
			break ;
		case 'vins' : // Validation Inscription
			extract(inscription()) ;
			include("templates/valid_inscription.php") ;
			break ;
		case 'em' : // Validation Inscription
			extract(envoi_mail()) ;
			include("templates/envoiMail.php") ;
			break ;
		case 'test' : // Inscription
			extract(test()) ;
			include("templates/test.php") ;
			break ;
		default :
			extract(accueil()) ;
			include("templates/accueil.php") ;
			break ;
	}
}
else { // Accueil
	extract(accueil()) ;
	include("templates/accueil.php");
}

?>