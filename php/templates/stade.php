<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<CENTER>
<TABLE ALIGN='center'>
<tr>
<?php
	$sSQLStades = "select ter_nom, ter_adresse, ter_url from " . TBL_TERRAIN . " order by ter_nom ;" ;
	$resultStades = $mysqli->query($sSQLStades) ;
	//echo $sSQL ;
	$mois_prec = "" ;
	$i=0;
	$j=1;
	while ($rowStades = mysqli_fetch_array($resultStades)) {
		extract($rowStades) ;
		if($i/3==$j) {
			echo "</tr></tr>" ;	
			$j++ ;
		}
		echo "<td>" ;
			echo "<table border=1 style='margin: 10px'>" ;
				echo "<tr>" ;
					echo "<td class='titreStade'>" ;
						echo lienMap($ter_nom, $ter_adresse) ;
					echo "</td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
					echo "<td>" ;
						if(isMobile()) {
							echo str_replace('width="400" height="300"','width="200" height="150"',$ter_url);
						} else {
							echo $ter_url ;
						}
					echo "</td>" ;
				echo "</tr>" ;
			echo "</table>" ;
		echo "</td>" ;
		$i++ ;
	}
?>
</tr>
</TABLE>
</CENTER>
</div>
