<?php
require '../config/config.php';

// Formularverarbeitung
if (isset($_POST['submit'])) {
  $changedEntries = substr($_POST['changedEntries'], 0, -1);
  $changedEntries = explode(';', $changedEntries);
  for ($i=0; $i < sizeof($changedEntries); $i++) { 
    $splitted = explode(',', $changedEntries[$i]);
    if (isset($splitted[1])) {
      $db->query('UPDATE mail_list SET subscribed = ? WHERE mail_id = ?', $splitted[1], $splitted[0]);
    }
  }
}

// Angemeldete Adressen aus Datenbank laden
$addresses = $db->query('SELECT * FROM mail_list')->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<title>Empfänger-Liste</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>

  <!-- Navigation -->
  <div class="topnav" id="myTopnav">
  	  <a>Mate-Newsletter</a>
    	<a id="list" href="list.php">Empfänger-Liste</a>
    	<a id="letters" href="letters.php">Newsletter-Liste</a>
    	<a id="write" href="write.php">Newsletter schreiben</a>
    	<a class="icon" id="burgericon">
      	<svg height="20px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path fill="#fff" d="M4,10h24c1.104,0,2-0.896,2-2s-0.896-2-2-2H4C2.896,6,2,6.896,2,8S2.896,10,4,10z M28,14H4c-1.104,0-2,0.896-2,2  s0.896,2,2,2h24c1.104,0,2-0.896,2-2S29.104,14,28,14z M28,22H4c-1.104,0-2,0.896-2,2s0.896,2,2,2h24c1.104,0,2-0.896,2-2  S29.104,22,28,22z"/></svg>
    	</a>
  </div>

  <!-- Headerbereich -->
  <div style="padding-left:16px">
    <h2>Empfänger-Liste</h2>
    <p>Liste aller eingetragen e-Mail Adressen für den Mate Newsletter.</p>
  </div>

  <!-- Suchleiste -->
  <div class="searchbar">
  	<input style="width: 100%;" type="text" id="myInput" onkeyup="filterFunction()" placeholder="Search for names.." title="Type in a name">
  </div>

  <!-- Liste mit Addressen -->
  <script src="../scripts/paginator.js"></script>
  <ul id="myUL">
    <?php foreach ($addresses as $address): ?>
      <li>
        <a href="#"><?= $address['mail'] ?></a>
        <label class="switch">
          <input 
            type="checkbox"
            id="li_<?= $address['mail_id']?>"
            data="<?= $address['mail_id']?>"
            onclick="changeMade(this)"
            <?php if($address['subscribed'] == 1) {echo 'checked';} ?>
          >
          <span class="slider round"></span>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>
  <div id="list_index" class="box"></div>

  <!-- Formular zum absenden der Änderungen -->
  <form method="POST">

    <input id="changedEntries" type="hidden" name="changedEntries" value="" data_id="">
    <div class="savechanges">
      <button class="btn success" type="submit" name="submit"><span>Speichern</span></button>
    </div>
  </form>

  <!-- JavaScript für Navigation -->
  <script type="text/javascript" src="../scripts/script.js"></script>

  <!-- JavaScript für klickbare Liste -->
  <script type="text/javascript">
    paginator({
      get_rows: function () {
          return document.getElementById("myUL").getElementsByTagName("li");
      },
      box: document.getElementById("list_index"),
      rows_per_page: 10,
      box_mode: 'buttons',
      page_options: false
  });
  </script>

  <!-- eigene JavaScript-Erweiterungen -->
  <script>
    // Funktion filtert nach der Suchleiste
    function filterFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    // Funktion fragt passiv Änderungen ab und speichert diese in einem Inputfeld, um daten für PHP bereitzustellen
    function changeMade(entry) {
        change = entry.getAttribute('data')
        if (document.getElementById('li_'+change).checked) {
          status = 1;
        } else {
          status = 0;
        }
        change = change.concat(',', status);

        formField = document.getElementById('changedEntries');
        value = formField.getAttribute('value');

        formField.setAttribute('value', value.concat(change, ';'));
    }
  </script>
</body>
</html>
