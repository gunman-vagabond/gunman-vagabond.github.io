<?PHP

  $score = $_POST["score"] . $_GET["score"];

  $filename = "SlipGunman.score.today.txt";

  date_default_timezone_set('Asia/Tokyo');
  $timestamp = time();
  $nowdate = date("y.m.d", $timestamp);
  $filedate = date ("y.m.d", filemtime($filename));

  if ( strcmp ( $nowdate, $filedate ) != 0 ) {
    file_put_contents($filename, ""); //init score file (daily)
    for ( $i = 1; $i <= 10; $i++) {
      file_put_contents($filename, "9999.999\n", FILE_APPEND | LOCK_EX); //dummy 10 lines
    }
  }

  if ( strcmp($score, "9999.999") != 0 && strcmp($score, "") != 0 ) {
    file_put_contents($filename, $score."\n", FILE_APPEND | LOCK_EX);
    for ( $i = 1; $i <= 10; $i++) {
      file_put_contents($filename, "9999.999\n", FILE_APPEND | LOCK_EX); //dummy 10 lines
    }
  }

  exec("sort $filename | head -n 10 > $filename".".tmp; cp $filename".".tmp $filename; rm $filename".".tmp", $output, $errno);

  $score_top10_today = @file_get_contents($filename);

  $filename = "SlipGunman.score.highscore.txt";
  if ( strcmp($score, "9999.999") != 0 && strcmp($score, "") != 0 ) {
    file_put_contents($filename, $score."\n", FILE_APPEND | LOCK_EX);
    for ( $i = 1; $i <= 10; $i++) {
      file_put_contents($filename, "9999.999\n", FILE_APPEND | LOCK_EX); //dummy 10 lines
    }
  }

  exec("sort $filename | head -n 10 > $filename".".tmp; cp $filename".".tmp $filename; rm $filename".".tmp", $output, $errno);

  $score_top10_highscore = @file_get_contents($filename);

  header('Access-Control-Allow-Origin: *');
  $out = fopen('php://output', 'w');
  fwrite($out, $score_top10_today);
  fwrite($out, $score_top10_highscore);
  fwrite($out, $nowdate . $filedate);
  fclose($out);

?>