<?php
// Dateien einbinden
require '../config/config.php';
require '../sources/sendmail.php';

// Flush vorbereiten, damit aktueller Fortschritt beim Senden von Mails angezeigt wird
ob_implicit_flush(true);

// Aktualisiert Datenbank bei speichern des Newsletters
if (isset($_POST['save'])) {
  $header = $_POST['betreff'];
  $content = $_POST['content'];
  $id = $_POST['save'];
  $db->query('UPDATE newsletters SET header = ?, content = ? WHERE id = ? LIMIT 1', $header, $content, $id);
  $_GET['letterid'] = $id;
}

// Löscht Eintrag aus Datenbank + leitet auf Übersicht aller Newsletter zurück
if (isset($_POST['delete'])) {
  $id = $_POST['delete'];
  $db->query('DELETE FROM newsletters WHERE id = ?', $id);
  $_GET['letterid'] = $id;
  header('location: letters.php');
}

// ruft Funktion zum Senden von Newslettern auf
if (isset($_POST['send'])) {
  // Flush Level festlegen
  if (ob_get_level() == 0) ob_start();

  // Aktuell zu sendenden Newsletter aus der Datenbank laden
  $id = $_POST['send'];
  $newslettertosend = $db->query('SELECT header, content FROM newsletters WHERE id = ? LIMIT 1', $_GET['letterid'])->fetchArray();
  $headertosend = $newslettertosend['header'];
  $contenttosend = $newslettertosend['content'];

  // Liste der Empfänger aus der Datenbank laden
  $addresses = $db->query('SELECT mail, token FROM mail_list WHERE subscribed = 1')->fetchAll();

  // Mehrmals die Funktion bei Fehlern aufrufen, um sicherzustellen, dass die E-Mail versendet wird
  $amount = 0; // Anzahl der erfolgreichen Mails speichern
  // for ($i=0; $i < sizeof($addresses); $i++) {
  //   $result = sendNewsletter($addresses[$i]['mail'], $contenttosend, $headertosend, $addresses[$i]['token']);
  //   if ($result !== '') {
  //     $result = sendNewsletter($addresses[$i]['mail'], $contenttosend, $headertosend, $addresses[$i]['token']);
  //     if ($result !== '') {
  //       $result = sendNewsletter($addresses[$i]['mail'], $contenttosend, $headertosend, $addresses[$i]['token']);
  //       if ($result !== '') {
  //         $result = sendNewsletter($addresses[$i]['mail'], $contenttosend, $headertosend, $addresses[$i]['token']);
  //       }
  //     }
  //   }
    // Result ist leer bei erfolgreichem Versand und enthält den entsprechenden Empfänger bei Fehlschlag
    if ($result == '') {
      $amount++;
    }

    // Flush aktivieren
    ob_flush();
    flush();

    // Ausgabe des aktuellen Status
    echo ($i+1).'/'.sizeof($addresses).'&ensp;';
  }

  // Nach Schleife, den Versand in Datenbank markieren
  $db->query('UPDATE newsletters SET sent_on = ?, sent = ?, sent_to = ? WHERE id = ?', date('Y-m-d H:i:s'), 1, $amount, $id);
  
  // Flush beenden
  ob_end_flush();
}

if (isset($_POST['test'])) {
  echo "<script type='text/javascript'>window.open('../sources/testmail.php?id=".$_POST['test']."')</script>";
}


// entsprechenden Newsletter aus der Datenbank ziehen
$letter = $db->query('SELECT * FROM newsletters WHERE id = ?', $_GET['letterid'])->fetchArray();
?>


<!DOCTYPE html>
<html lang="de">
<head>
	<title>Newsletter-Liste</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../node_modules/quill/dist/quill.snow.css">
  <script type="text/javascript" src="../node_modules/quill/dist/quill.js"></script>
  <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>

<body>

  <div class="topnav" id="myTopnav">
  	<a>Mate-Newsletter</a>
    	<a id="list" href="list.php">Empfänger-Liste</a>
    	<a id="letters" href="letters.php">Newsletter-Liste</a>
    	<a id="write" href="write.php">Newsletter schreiben</a>
    	<a class="icon" id="burgericon">
      	<svg height="20px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path fill="#fff" d="M4,10h24c1.104,0,2-0.896,2-2s-0.896-2-2-2H4C2.896,6,2,6.896,2,8S2.896,10,4,10z M28,14H4c-1.104,0-2,0.896-2,2  s0.896,2,2,2h24c1.104,0,2-0.896,2-2S29.104,14,28,14z M28,22H4c-1.104,0-2,0.896-2,2s0.896,2,2,2h24c1.104,0,2-0.896,2-2  S29.104,22,28,22z"/></svg>
    	</a>
  </div>

  <div style="padding-left:16px">
    <h2>Newsletter bearbeiten</h2>
  </div>


  <form method="POST">
    <div class="editor">
     
      <label for="betreff">Bearbeite diesen Newsletter und vergiss nicht deine Änderungen zu speichern bevor du versendest oder die Seite verlässt.</label><br><br>
      <div>zuletzt bearbeitet: <?= $letter['last_edited'] ?></div>
      <div>gesendet: <span style="color: green;"><?= $letter['sent'] ? $letter['sent_on'].'</span>' : '</span>-' ?></div>
      <br>
      <input type="text" id="betreff" placeholder="Newsletter Betreff" name="betreff" value="<?= $letter['header'] ?>" required autofocus>
      <br>

      <!-- Texteditor -->
      <div id="editor" style="height: auto !important;">
        <?= $letter['content'];  ?>
      </div>
    </div>

    <!-- Verstecktes Inputfeld in das mit JS der Content aus dem Editor geschrieben wird -->
    <input type="hidden" name="content">

    <!-- Buttons -->
    <div class="savechanges">
      <!-- Speichern -->
      <button class="btn success" type="submit" name="save" value="<?= $letter['id'];  ?>"><span>Speichern</span></button>
      
      <!-- Löschen -->
      <button 
        class="btn warning"
        name="delete"
        type="button"
        onclick="deleteConfirm(<?= $letter['id'];  ?>)"
        >
          <span>Löschen</span>
      </button>

      <button 
        class="btn info"
        name="test"
        type="submit"
        value="<?= $letter['id'] ?>" 
        >
          <span>Test</span>
      </button>
      
      <!-- Versenden -->
      <button 
        class="savebutton" 
        name="send"
        type="button"
        onclick="sendConfirm(<?= $letter['id'];  ?>)"
        >
          <span <?php if ($letter['sent']) {echo "disabled style='cursor: not-allowed'";}?>>verschicken</span>
      </button>
    </div>
  </form>


  <!-- Einbinden des Quill-Editor-Skriptes -->
  <script type="text/javascript" src="../scripts/script.js"></script>

  <!-- Eigene JavaScript-Ergänzungen -->
  <script type="text/javascript">
    // Überprüft das Löschen und öffnet Hinweisfenster zum Bestätigen
    function deleteConfirm(id) {
      if(confirm('Achtung! Möchtest du den Newsletter wirklich löschen? Dies kann nicht rückgängig gemacht werden')) {
        const form = document.createElement('form');
        form.method = "post";
        input = document.createElement("input");
        input.setAttribute("name", "delete");
        input.setAttribute("value", id);
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      } else {
        return false
      }
    }

    // Überprüft das Versenden und öffnet Hinweisfenster zum Bestätigen
    function sendConfirm(id) {
      if(confirm('Achtung! Möchtest du den Newsletter wirklich versenden? Dann erhalten alle Empfänger von der Liste diesen Newsletter unaufhaltsam per Mail.')) {
        const form = document.createElement('form');
        form.method = "post";
        input = document.createElement("input");
        input.setAttribute("name", "send");
        input.setAttribute("value", id);
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      } else {
        return false;
      }
    }

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

    // Instanzieren des Editors
    var quill = new Quill('#editor', {
      modules: {
        toolbar: toolbarOptions,
        history: {
          maxStack: 500 // die letzten 500 Aktionen können rückgängig gemacht werden. 1 Aktion ist immer 100ms lang.
        }
      },
      theme: 'snow'
    });


    // Eingaben aus dem Quill-Editor in Inputfeld[type=hidden] speichern und damit für PHP bereitstellen
    var form = document.querySelector('form');
    form.onsubmit = function() {
      // Populate hidden form on submit
      var content = document.querySelector('input[name=content]');
      content.value = quill.root.innerHTML;
    };
  </script>

</body>
</html>
