<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Convertisseur Youtube</title>
    <link rel="stylesheet" type="text/css" href="ressources/semantic.min.css">
    <script src="ressources/jquery-3.1.1.min.js"></script>
    <script src="ressources/semantic.min.js"></script>
</head>
<body>

<div class="ui segment">
    <a class="ui header" href="index.php">Téléchargement de musiques depuis </a><a href="https://www.youtube.com"
                                                                                   target="_blank"
                                                                                   class="ui youtube button">
        YouTube
    </a>
</div>

<div class="ui segment">
    <?php
    if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
        echo "<div class=\"ui compact negative message id=\"errorMessage\"\">
  <div class=\"header\">
    Erreur !
  </div>
  <p>" . $_SESSION["error"] . "
</p></div>";
        $_SESSION["error"] = null;
    }
    ?>
    <div class="ui success compact message" style="display: none;" id="startMessage">
        <div class="header">Le téléchargement va commencer</div>
        <p>Veuillez patienter ...</p>
    </div>
    <form class="ui form" method="POST" action="download.php" id="form">
        <div class="field">
            <label>URL (séparez les URLs par des ; pour télécharger plusieurs musiques) :</label>
            <textarea name="url" id="urls" required></textarea>
        </div>
        <div class="field">
            <label>Format audio</label>
            <select class="ui fluid dropdown" name="format" id="format">
                <option value="mp3">MP3</option>
                <option value="wav">WAV</option>
            </select>
        </div>
        <button class="fluid ui green button" type="submit" id="downloadButton">Lancer le téléchargement</button>
    </form>
</div>


</body>
</html>

<script type="text/javascript">
    $("#downloadButton").on('click', function (e) {
        // $("#downloadButton, #form").addClass("loading");
        $("#errorMessage").hide();
        if (document.getElementById("urls").value) {
            $("#startMessage").show();
        }
    });
    $("#format").dropdown();
</script>
