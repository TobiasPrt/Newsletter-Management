<?php
// Skript das Login zeigt
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Newsletter Verwaltung</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body class="loginbody">
	<div class="login-page">
	  <div class="form">
		<h2 style="margin-top: 0;">Newsletter-Verwaltung</h2>
	    <form class="login-form" action="sources/login.php" method="POST">
	      <input type="text" placeholder="username" name="username" value="admin" required autofocus>
	      <input type="password" placeholder="password" name="passwort" password="admin" required>
	      <button type="submit" name="submit" value="login">login</button>
	    </form>
	  </div>
	</div>
</body>
</html>










