<?php 
require '../config/config.php';

// gespeicherte Newsletter aus der Datenbank ziehen
$letters = $db->query('SELECT id, header, last_edited, sent, content FROM newsletters ORDER BY last_edited DESC')->fetchAll();
?>


<!DOCTYPE html>
<html lang="de">
<head>
	<title>Newsletter-Liste</title>
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

  <!-- Header-Bereich -->
  <div style="padding-left:16px">
    <h2>Newsletter-Liste</h2>
    <p>Liste aller gespeicherten Newsletter.</p>
  </div>

  <!-- Suchleiste -->
  <div class="searchbar">
    <input type="text" id="myInput" onkeyup="filterFunction()" placeholder="Search for names.." title="Type in a name">
    <button id="sentFilterButton" class="btn info" onclick="sentFilter()">alle</button>
    <input type="checkbox" id="sentFilter" name="filterSent" style="display: none;">
  </div>


  <!-- Liste mit Newslettern -->
  <script src="../scripts/paginator.js"></script>
  <ul id="myUL">
    <?php foreach ($letters as $letter): ?>
    <li>
      <a data="<?= $letter['header'] ?>"><?php if ($letter['sent'] == 1) { echo " &#9989; ";}?><?= $letter['header'] ?></a>
      <a class="nomobile"><?= date('d.m.Y - H:i:s', strtotime($letter['last_edited'])) ?></a>
      <input type="hidden" name="sent" value="<?= $letter['sent'] ?>">
      <input type="hidden" name="content" value="<?= htmlspecialchars($letter['content'], ENT_QUOTES) ?>">
      <button class="btn warning" onclick="location.href='edit.php?letterid=<?= $letter['id'] ?>'">edit</button>
    </li>
    <?php endforeach; ?>
  </ul>
  <div id="list_index" class="box"></div>

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


  <!-- eigene JavaScript Erweiterungen -->
  <script>
    // Funktion filtert die Suchleiste
    function filterFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            b = li[i].getElementsByTagName("a")[1];
            c = li[i].getElementsByTagName("input")[1];
            txtValue = a.textContent || a.innerText;
            txtValueB = b.textContent || b.innerText;
            txtValueC = c.value;
            if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValueB.toUpperCase().indexOf(filter) > -1 || txtValueC.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    // Funktion filtert nach dem Button
    function sentFilter() {
      document.getElementById('sentFilterButton').classList.toggle('info');
      if (document.getElementById('sentFilter').checked) {
        status = 1;
        document.getElementById('sentFilter').checked = false;
        document.getElementById('sentFilterButton').innerText = 'alle';
        
      } else {
        status = 0;
        document.getElementById('sentFilter').checked = true;
        document.getElementById('sentFilterButton').innerText = 'gesendet';
      }
      var filter, ul, li, a, i, sentStatus;
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("input")[0];
            
            sentStatus = a.value;
            if (sentStatus == status) {
                li[i].style.display = "none";
            } else {
                li[i].style.display = "";
            }

            if (status == 1) {
              li[i].style.display = "";
            }
        }

    }
  </script>

  <!-- Skript Einbindung für Navigation -->
  <script src="../scripts/script.js"></script>

</body>
</html>
