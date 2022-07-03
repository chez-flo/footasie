function trim(s) {
	while (s.substring(0,1) == ' ') {
		s = s.substring(1,s.length);
	}
	while (s.substring(s.length-1,s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}
	return s;
}

function validEmail(email) {
	var reg=/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(email) == false) {
		return false;
	}
	else {
		return true ;
	}
}

function validTel(tel) {
	var reg=/^0[1-9][0-9]{8}$/;
	if(reg.test(tel) == false) {
		return false;
	}
	else {
		return true ;
	}
}

function validPassword(pass) {
	var reg=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
	if(reg.test(pass) == false) {
		return false;
	}
	else {
		return true ;
	}
}

function blink_show() {
	blink_tags  = document.getElementsByTagName('blink');
	blink_count = blink_tags.length;
	for ( i = 0; i < blink_count; i++ )
	{
		blink_tags[i].style.visibility = 'visible';
	}
	 
	window.setTimeout( 'blink_hide()', 700 );
}
 
function blink_hide() {
	blink_tags  = document.getElementsByTagName('blink');
	blink_count = blink_tags.length;
	for ( i = 0; i < blink_count; i++ )
	{
		blink_tags[i].style.visibility = 'hidden';
	}
	 
	window.setTimeout( 'blink_show()', 250 );
}