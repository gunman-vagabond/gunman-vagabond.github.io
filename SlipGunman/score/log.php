<?PHP
  date_default_timezone_set('Asia/Tokyo');
  $timestamp = time();
  $mydate = date("Y/m/d H:i:s", $timestamp);

  $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
  $REMOTE_PORT = $_SERVER['REMOTE_PORT'];

  $log = $mydate . ',' . $REMOTE_ADDR . ',' . $REMOTE_PORT . ',"' . $HTTP_USER_AGENT . '"' ;

  $filename = "SlipGunman.log.txt";

  file_put_contents($filename, $log."\n", FILE_APPEND | LOCK_EX);

?>