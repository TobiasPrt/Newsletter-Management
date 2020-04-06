<?php
// Dateien einbinden
require '../config/config.php';

$id = $_GET['id'];
// entsprechenden Newsletter laden
$newsletter = $db->query("SELECT content FROM newsletters WHERE id = ? LIMIT 1", $_GET['id'])->fetchArray();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Beispiel-Email</title>
</head>
<body>
    <!-- Roboto einbinden -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300" rel="stylesheet">
    <style type="text/css">
        * {
            font-family: Roboto, sans-serif;
            font-weight: 100;
            color: black;
        }
    </style>

    <!-- Table für Positionierung -->
    <table style="table-layout: fixed; width: 900px;">
        <tr>
            <td colspan="8" style="text-align: center;">
                <img style="border-radius: 5px;" src="../images/header.jpg" alt="Logo Header">
            </td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6" style="padding-left: 60px"><?= $newsletter['content']; ?></td>
            <td></td>
        </tr>
        <tr style="height: 40px">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <a href="https://www.instagram.com/officialmateapp/"><img style="width: 30px; margin-right: 10px" src="../images/instagram.png" alt="Instagram Logo"></a>
                <a href="https://twitter.com/officialmateapp"><img  style="width: 30px; margin-left: 10px;" src="../images/twitter.png"></a>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center;">
                <p style="font-size: 22px; color: black !important;">Erfahre das Studium der Zukunft</p>
            </td>
        </tr>
        <tr style="height: 10px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td style="background-color: #FF9933;" colspan="6"></td>
            <td></td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="text-align: center; color: #FF9933; font-size: 12px;" colspan="8">Tobias Pörtner &middot; Beispielstraße 1, 12345 Kiel &middot; <a style="text-decoration: none; color: #FF9933;" href="tel:+491708275715">+49 170 8275715</a> &middot; <a style="text-decoration: none; color: #FF9933;" href="mailto: legal@mate.app.de">legal@mate-app.de</a>&middot;
            <a style="text-decoration: none; color: #FF9933; font-size: 12px;" href="https://mate-app.de/datenschutz">Datenschutz</a></td>
        </tr>
        <tr style="height: 51px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="text-align: center; color: #FF9933; font-size: 12px;" colspan="8">
            <a style="text-decoration: none; color: #FF9933; font-size: 12px;" href="https://mate-app.de/abbestellen">abbestellen</a></td>
        </tr>
    </table>    
</body>
</html>