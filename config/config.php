<?php
///////////////////////////////////////////////
//	Speichername	:	config.php 		     //
//	Beschreibung	:	Konfigurationsdatei  //
///////////////////////////////////////////////

// alle Fehlermeldungen sollen gezeigt werden
error_reporting(0);

// Session starten
session_start();

// Verifizierung prüfen und ggf automatisch ausloggen
if (!isset($_SESSION['username'])) {
	header('Location: ../index.php');
	exit;
}

// Session automatisch löschen und Nutzer ausloggen nach 5min 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    session_unset();
    session_destroy();
    header('location: ../index.php');
}
$_SESSION['LAST_ACTIVITY'] = time();

// Dateien einbinden
require '../sources/db.php';

// Datenbank verbinden
$dbhost = '******';
$dbuser = '******';
$dbpass = '******';
$dbname = '******';

// Datenbankklasse instanzieren
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
?>