// Aktive Seite in Navigation markieren
(function() {
	if (window.location.pathname == '/newsletter.mate-app.de/sites/list.php') {
		document.getElementById('list').classList.add('active');
	}
	if (window.location.pathname == '/newsletter.mate-app.de/sites/letters.php') {
		document.getElementById('letters').classList.add('active');
	}
	if (window.location.pathname == '/newsletter.mate-app.de/sites/write.php') {
		document.getElementById('write').classList.add('active');
	}
})();


// Eventlistener: ruft mobiles Navigationsmenu auf, indem Klassen zugeschaltet werden
document.getElementById("burgericon").addEventListener("click", navFunction);
function navFunction() {
	var x = document.getElementById("myTopnav");
  	if (x.className === "topnav") {
    	x.className += " responsive";
  	} else {
    	x.className = "topnav";
  	}
}