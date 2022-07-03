// Requette AJAX
			function sansAccent(str) {
				var accent = [
					/[\300-\306]/g, /[\340-\346]/g, // A, a
					/[\310-\313]/g, /[\350-\353]/g, // E, e
					/[\314-\317]/g, /[\354-\357]/g, // I, i
					/[\322-\330]/g, /[\362-\370]/g, // O, o
					/[\331-\334]/g, /[\371-\374]/g, // U, u
					/[\321]/g, /[\361]/g, // N, n
					/[\307]/g, /[\347]/g // C, c
				];
				var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];
				for(var i = 0; i < accent.length; i++){
					str = str.replace(accent[i],noaccent[i]);
				}
				return str;
			}
			
			function makeRequest(url,id_val,id_ecrire){
				var http_request = false;
				//créer une instance (un objet) de la classe désirée fonctionnant sur plusieurs navigateurs
				if (window.XMLHttpRequest) { // Mozilla, Safari,...
					http_request = new XMLHttpRequest();
					if (http_request.overrideMimeType) {
						http_request.overrideMimeType('text/xml');//un appel de fonction supplémentaire pour écraser l'en-tête envoyé par le serveur, juste au cas où il ne s'agit pas de text/xml, pour certaines versions de navigateurs Mozilla
					}
				} else if (window.ActiveXObject) { // IE
					try {
						http_request = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try {
							http_request = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e) {}
					}
				}

				if (!http_request) {
					alert('Abandon :( Impossible de créer une instance XMLHTTP');
					return false;
				}
				http_request.onreadystatechange = function() { traitementReponse(http_request,id_ecrire); } //affectation fonction appelée qd on recevra la reponse
				// lancement de la requete
				http_request.open('POST', url, true);
				//changer le type MIME de la requête pour envoyer des données avec la méthode POST ,  !!!! cette ligne doit etre absolument apres http_request.open('POST'....
				http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				obj=document.getElementById(id_val);
				//alert(sansAccent(obj.value)) ;
				data="val_sel="+sansAccent(obj.value);
				http_request.send(data);
			}
			
			function makeRequestArray(url,id_val,id_ecrire){
				var http_request = false;
				//créer une instance (un objet) de la classe désirée fonctionnant sur plusieurs navigateurs
				if (window.XMLHttpRequest) { // Mozilla, Safari,...
					http_request = new XMLHttpRequest();
					if (http_request.overrideMimeType) {
						http_request.overrideMimeType('text/xml');//un appel de fonction supplémentaire pour écraser l'en-tête envoyé par le serveur, juste au cas où il ne s'agit pas de text/xml, pour certaines versions de navigateurs Mozilla
					}
				} else if (window.ActiveXObject) { // IE
					try {
						http_request = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try {
							http_request = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e) {}
					}
				}

				if (!http_request) {
					alert('Abandon :( Impossible de créer une instance XMLHTTP');
					return false;
				}
				http_request.onreadystatechange = function() { traitementReponse(http_request,id_ecrire); } //affectation fonction appelée qd on recevra la reponse
				// lancement de la requete
				http_request.open('POST', url, true);
				//changer le type MIME de la requête pour envoyer des données avec la méthode POST ,  !!!! cette ligne doit etre absolument apres http_request.open('POST'....
				http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				var data = "" ;
				for(i=0;i<id_val.length;i++) {
					obj=document.getElementById(id_val[i]);
					data+="val_sel"+i+"="+obj.value;
					if(i<id_val.length-1) {
						data+="&" ;
					}
				}
				http_request.send(data);
			}

			function traitementReponse(http_request,id_ecrire) {
				var affich="";
				if (http_request.readyState == 4) {
					//alert("Reponse de php: "+http_request.status);
					if (http_request.status == 200) {
						// cas avec reponse de PHP en mode texte:
						//chargement des elements reçus dans la liste
						var affich_list=http_request.responseText;
						// alert("Reponse de php: "+affich_list);
						obj = document.getElementById(id_ecrire); 
						obj.innerHTML = affich_list;
					} 
					else {
						alert('Un problème est survenu avec la requête.');
					}
				}
			}