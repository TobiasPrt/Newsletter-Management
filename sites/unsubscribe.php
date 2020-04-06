<?php 
if ( !(isset($_GET['mail']) && isset($_GET['token'])) ) {
	header('location: 404.php?error=mailortoken');
	exit;
}

// Dateien einbinden
require '../sources/db.php';

// Datenbank verbinden
$dbhost = 'mysql01.manitu.net';
$dbuser = 'u45062';
$dbpass = 'XnmrtfgJRFm9';
$dbname = 'db45062';

// Datenbankklasse instanzieren
$db = new db($dbhost, $dbuser, $dbpass, $dbname);


// Token zum Abgleich aus der Datenbank
$token = $db->query('SELECT token FROM mail_list WHERE mail = ?', $_GET['mail'])->fetchArray();
// Token miteinander abgleichen
if ($token['token'] == $_GET['token']) {
	// Subscribe-Status anpassen
	$db->query('UPDATE mail_list SET subscribed = ? WHERE mail = ?', 0, $_GET['mail']);

	// Änderung überprüfen
	$status = $db->query('SELECT subscribed FROM mail_list WHERE mail = ? LIMIT 1', $_GET['mail'])->fetchArray();

	// Erfolg ausgeben
	if ($status['subscribed'] == 0) {
		echo "Du hast dich erfolgreich abgemeldet.";
	}
} else {
	header('location: 404.php?error=verification');
	exit;
}
?>