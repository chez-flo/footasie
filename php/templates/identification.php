<?php 
	include('templates/bandeau.php') ;
?>
	<div class = "PagePrincipale"> 
		<Form action="<?php echo PATH ; ?>login.php" method="POST"/>
			<br><br><br><br><br><br>
			<table colspan="2" align="center"/>
				<tr>
					<td align="right"/>Login :</td><td><input type="Text" name="login" size="30"/></td>
				</tr>
				<tr>
					<td align="right"/>Mot de passe :</td><td><input type="Password" name="pass" size="30"/></td>
				</tr>
				<tr/>
					<td colspan="2" align="center">
						<?php echo boutonRetour() . " " . boutonSubmit("Valider", "Connexion") ; ?>
					</td>
				</tr>
				<tr/>
					<td colspan="2" align="center">
						<a href="javascript:document.location.href='<?php echo PATH."index.php?op=mdp"?>'" style="font-style: italic;">Mot de passe oubli&eacute;<a/>
					</td>
				</tr>
			</table>
		</Form>
		<?php if(isset($message) && $message != '') { ?> 
			<font color="red"/><center/> <?php echo $message ; ?> </center></font>
			<?php session_destroy();
		} ?> 
		</div>
	</BODY>
</HTML>
	
		