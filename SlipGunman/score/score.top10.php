<?PHP

  $score = $_POST["score"] . $_GET["score"];

  $filename = "SlipGunman.score.txt";

  if ( strcmp($score, "9999.999") != 0 && strcmp($score, "") != 0 ) {
    file_put_contents($filename, $score."\n", FILE_APPEND | LOCK_EX);
  }

  exec("sort $filename | head -n 10 > $filename".".tmp; cp $filename".".tmp $filename", $output, $errno);

  $score_top10 = @file_get_contents($filename);

  $out = fopen('php://output', 'w');
  fwrite($out, $score_top10);
  fclose($out);

?>