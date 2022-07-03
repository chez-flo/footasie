<?php include('templates/bandeau.php') ; ?>


<div class = "PagePrincipale">
	<form name="form" action="code.php" method="POST"/>
		<CENTER>
			<table>
				<tr>
					<td align="center" class='chEquipe'>
						<?php 
							$eq="" ;
							for($i=0; $i<$_SESSION["nb_eq"]; $i++) {
								$eq .= $_SESSION["tab_eq_id"][$i] . "," ;
							}
							$eq = substr($eq,0,strlen($eq)-1) ;
							echo "<select name='eq_id' id='eq_id'>" ;
							$query = "SELECT eq_id, eq_nom FROM " . TBL_EQUIPE . " WHERE eq_id in (".$eq.") ;" ;
							$result = $mysqli->query($query) ;
							while($row = mysqli_fetch_array($result)) {
								extract($row) ;
								if($eq_id==$_SESSION['eq_id']) {
									echo "<option value='".$eq_id."' selected>".$eq_nom."</option>" ;
								} else {
									echo "<option value='".$eq_id."'>".$eq_nom."</option>" ;
								}
							} 
							echo "</select>" ;
						?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("chEquipe", "Valider le choix") ; ?>
					</td>
				</tr>
			</table>
		</CENTER>
	</form>
</div>
