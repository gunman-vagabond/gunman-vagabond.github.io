<?PHP

$score = $_POST["score"] . $_GET["score"];

$filename = "SlipGunman.score.txt";

file_put_contents($filename, $score."\n", FILE_APPEND | LOCK_EX);
?>