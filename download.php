<?php

session_start();

$formats = array(
    "mp3" => "audio/mpeg",
    "wav" => "audio/x-wav"
);


if (!isset($_POST["url"]) || !isset($_POST["format"]) || !array_key_exists($_POST["format"], $formats)) {
    header("Location: index.php");
}
$format = $_POST["format"];
$url = "Location: index.php";

$output = array();
$return_value = 0;
exec('youtube-dl --rm-cache', $output, $return_value);

if ($return_value) {
    $_SESSION["error"] = "Impossible de supprimer le cache";
    header($url);
}

$urls = explode(';', $_POST["url"]);
$filenames = array();

for ($i = 0; $i < sizeof($urls); $i++) {
    $commmand = 'youtube-dl --extract-audio --audio-format ' . $format . ' -o \'%(title)s.%(ext)s\' ' . $urls[$i] . ' --restrict-filenames';
//    echo $commmand; die;
    exec($commmand, $output, $return_value);
    if (!$return_value) {
        $filenames[$i]["name"] = substr($output[sizeof($output) - 2], 22);
    } else {
        $filenames[$i]["name"] = null;
    }
    $filenames[$i]["return_value"] = $return_value;
}

$archive_name = time() . '.zip';
$commmand = 'zip ' . $archive_name;
$valid_filenames = 0;
foreach ($filenames as $filename) {
    if ($filename["name"]) {
        $commmand = $commmand . ' ' . $filename["name"];
        $valid_filenames++;
    }
}
if (!$valid_filenames) {
    $_SESSION["error"] = "Aucun fichier valide à télécharger";
    header($url);
}
if ($valid_filenames > 1) {
    exec($commmand, $output, $return_value);
    if (file_exists($archive_name)) {
        header("Location: " . $archive_name);
    }
} else {
    $k = 0;
    while (!$filenames[$k]["name"]) {
        $k++;
    }
    $fn = $filenames[$k]["name"];
    if (file_exists($fn)) {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $formats[$format]);
        header('Content-Disposition: attachment; filename="' . basename($fn) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fn));
        readfile($fn);
    }
}
header($url);



