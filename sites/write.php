<?php 
require '../config/config.php';

// Speicherung abfragen
if (isset($_POST['submit'])) {
  $header = $_POST['betreff'];
  $content = $_POST['content'];
  $db->query('INSERT INTO newsletters (header, content) VALUES (?, ?)', $header, $content);
  header('location: letters.php');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<title>Newsletter schreiben</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../node_modules/quill/dist/quill.snow.css">
  <script type="text/javascript" src="../node_modules/quill/dist/quill.min.js"></script>
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


  <!-- Formular -->
  <form method="POST">
    <!-- Headerbereich -->
    <div style="padding-left:16px">
      <h2>Newsletter schreiben</h2>
    </div>
    <div class="editor">
      <label for="betreff">Schreibe einen neuen Newsletter und speicher ihn ab.</label><br><br>

      <!-- Input für Überschrift -->
      <input type="text" id="betreff" placeholder="Newsletter Betreff" name="betreff" required autofocus>
      <br>

      <!-- Quill Editor kommt hier rein: -->
      <div id="editor" spell-check="false"></div>
    </div>

    <!-- Hidden Input, wo Daten aus dem Editor eingefügt werden, um sie für PHP bereitzustellen -->
    <input type="hidden" name="content" value="">

    <!-- Button zum speichern des Newsletters -->
    <div class="savechanges">
      <button class="btn success" type="submit" name="submit">
        <span>Speichern</span>
      </button>
    </div>
  </form>

  <!-- Skript für Navigation -->
  <script type="text/javascript" src="../scripts/script.js"></script>

  <!-- eigenes JavaScript -->
  <script type="text/javascript">
    // Optionen für die Toolbar vom Editor
    var toolbarOptions = [
      [{ 'header': [1, 2, 3, false] }],
      [{ 'font': [] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'color': [] }], 
      ['blockquote', 'code-block'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'align': [] }],
      [{ 'script': 'sub'}, { 'script': 'super' }],
      [{ 'indent': '-1'}, { 'indent': '+1' }],
    ]

    // Editor instanzieren
    var quill = new Quill('#editor', {
      modules: {
        toolbar: toolbarOptions,
        history: {
          maxStack: 500 // die letzten 500 Aktionen können rückgängig gemacht werden. 1 Aktion ist immer 100ms lang.
        }
      },
      theme: 'snow'
    });

    // Eingaben vom Editor in input speichern, um sie für PHP bereitzustellen
    var form = document.querySelector('form');
    form.onsubmit = function() {
      var content = document.querySelector('input[name=content]');
      content.value = quill.root.innerHTML;
    };
  </script>
</body>
</html>
