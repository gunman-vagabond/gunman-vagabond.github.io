<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
<META HTTP-EQUIV='refresh' CONTENT='60;URL=GunClock.php?clockSize=<?php print $_POST["clockSize"] . $_GET["clockSize"]?>'> 
<title>GunClock (PHP)</title>
</head>

<body>

<h1>GunClock (PHP)</h1>
<hr>

<form method="POST" action="GunClock.php">
clock size : 
<input type="text" 
 name="clockSize"
 value="<?php print $_POST["clockSize"] . $_GET["clockSize"]?>"
 size="4">
<input type="submit" value="display">
</form>


<center>
<pre>

<?php

class Cast {
	var $image = 123;

	function Cast($image) {
		$this->image = $image;
	}

	function display($x, $y) {

		global $gunClock;

		/////////////////////////////
		// compute (x,y) for display
		/////////////////////////////
		$_x = ($x*2) -(strlen($this->image[0]) /2) ;
		$_y = $y     -(sizeof($this->image)    /2);


		$tmp = "          ";

		for($i=0; $i<sizeof($this->image); $i++) {
//			print $this->image[$i] . "\n" ;
//			substr($tmp, 2, 4) = $this->image[$i];
			for ($j=0; $j<strlen($this->image[$i]); $j++) {
				$c = substr($this->image[$i], $j, 1);
				if ( $c != '*' ) {
					$gunClock[$_y + $i][$_x + $j] = $c;
				}
			}
		}

	}

}

	$strGunman = 
		array(
			"*  __ *", 
			" _|__|_",
			"b (@@) ",
			" V|~~|>",
			"  //T| "
 		);

	$strInu = 
		array(
			"__AA  * ",
			"| 6 |__P",
			"~~|    l",
			" /_/~l_l"
		);

	$strLongHand = 
		array(
			"##" 
		);

	$strShortHand = 
		array(
			"::"
		);

	$str3  = array( "3" );
	$str6  = array( "6" );
	$str9  = array( "9" );
	$str12  = array( "12" );

	$strWaku  = array( "+" );

	$PI = 3.141592653;

	$clockSize = $_POST["clockSize"] . $_GET["clockSize"];

	/////////////////////
	// make CANVAS
	/////////////////////
	for ($i=0; $i<$clockSize; $i++) {
		for ($j=0; $j<$clockSize*2; $j++) {
			$gunClock[$i][$j] = " ";
		}
	}

	////////////////////
	// create CASTs
	////////////////////
	$gunman    = new Cast( $strGunman );
	$inu       = new Cast( $strInu );
	$longHand  = new Cast( $strLongHand  );
	$shortHand = new Cast( $strShortHand );

	$num3      = new Cast( $str3  );
	$num6      = new Cast( $str6  );
	$num9      = new Cast( $str9  );
	$num12     = new Cast( $str12 );
	$waku      = new Cast( $strWaku );

        /////////////
        // get time
        /////////////
	$today = getdate();
	$hour   = $today[hours];
	$minute = $today[minutes];
	$second = $today[second];

        ////////////////////////////////
        // compute character location
        ////////////////////////////////
	$centerX = $clockSize/2;
	$centerY = $clockSize/2;

	$gunmanX = $centerX + cos(hourToRadian($hour,$minute)) * ($clockSize*2/3/2);
	$gunmanY = $centerY - sin(hourToRadian($hour,$minute)) * ($clockSize*2/3/2);

	$inuX = $centerX + cos(minuteToRadian($minute,$second)) * ($clockSize*4/5/2);
	$inuY = $centerY - sin(minuteToRadian($minute,$second)) * ($clockSize*4/5/2);


	//////////////////////
	// display characters
	//////////////////////


	//// waku ////

	for($i=0; $i< 360; $i+=30) {
		if ( $i % 90 == 0 ) continue; 

		$radian = ($i * 2 * $PI) / 360;
		$wakuXdiff = $clockSize/2 * cos($radian);
		$wakuYdiff = $clockSize/2 * sin($radian);

		if ( $wakuXdiff >=0 ) {
			$wakuX = $centerX + floor($wakuXdiff);
		} else {
			$wakuX = $centerX + ceil($wakuXdiff);
		}

		if ( $wakuYdiff >=0 ) {
			$wakuY = $centerY + floor($wakuYdiff);
		} else {
			$wakuY = $centerY + ceil($wakuYdiff);
		}

		$waku->display($wakuX, $wakuY);
	}


        //// num ////
	$num3->display ($clockSize -1 , $centerY      );
	$num6->display ($centerX      , $clockSize -1 );
	$num9->display (0            , $centerY      );
	$num12->display($centerX      , 0            );

	//// longHand ////
	for($i=0; $i<$clockSize*2/3/2; $i++){
		$longHandX = $centerX + ( (($inuX - $centerX) * $i) / ($clockSize*2/3/2) );
		$longHandY = $centerY + ( (($inuY - $centerY) * $i) / ($clockSize*2/3/2) );

		$longHand->display($longHandX, $longHandY);
	}


	//// shortHand ////
	for($i=0; $i<$clockSize*5/6/2; $i++){
		$shortHandX = $centerX + ( (($gunmanX - $centerX) * $i) / ($clockSize*5/6/2) );
		$shortHandY = $centerY + ( (($gunmanY - $centerY) * $i) / ($clockSize*5/6/2) );

		$shortHand->display($shortHandX, $shortHandY);
        }


	//// inu ////
	$inu->display($inuX, $inuY );

	//// gunman ////
	$gunman->display($gunmanX, $gunmanY );


        ////////////////////////
        // display digital time 
        ////////////////////////

	$strBufTime = sprintf("%02d:%02d", $hour, $minute);
	$strDigital[0] = "_________";
	$strDigital[1] = "| " . $strBufTime . " |";
	$strDigital[2] = "~~~~~~~~~";

	$digital = new Cast($strDigital);

	$digitalRadian = digitalRadian($hour,$minute,$second);

	$digital->display($centerX + (cos($digitalRadian) * $clockSize/2 *1/2)
			, $centerY - (sin($digitalRadian) * $clockSize/2 *1/2)
	);




	/////////////////////
	// display CANVAS
	/////////////////////
	for ($i=0; $i<$clockSize; $i++) {
		for ($j=0; $j<$clockSize*2; $j++) {
			print $gunClock[$i][$j];
		}
		print "\n";
	}
		


	// tool : digitalRadian ///////////////////////////////////////////////////////
	function digitalRadian($h, $m, $s) {

		global $PI;

		$hRadian = hourToRadian($h,$m);
		$mRadian = minuteToRadian($m,$s);

		$aveRadian = ($hRadian + $mRadian) / 2;

	        if ( (($hRadian > $mRadian) && ($hRadian - $mRadian < $PI))
		  || (($mRadian > $hRadian) && ($mRadian - $hRadian < $PI))
		) {
			return $aveRadian + $PI; 
		} else {
			return $aveRadian;
		}
	}


	// tool : hourToRadian ///////////////////////////////////////////////////////
	function hourToRadian($h, $m) {

		global $PI;
		return $PI * (90.0 - (($h%12) + $m/60.0) * 30.0) / 180.0;

	}

	// tool : minuteToRadian ///////////////////////////////////////////////////////
	function minuteToRadian($m, $s) {

		global $PI;
		return $PI * (90.0 - ($m + $s/60.0) * 6.0) / 180.0;

	}


?>

</pre>
</center>
<hr>


</body>
</html>
