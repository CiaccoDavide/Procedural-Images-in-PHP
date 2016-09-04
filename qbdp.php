<?php

	$larghezza 	= isset($_GET['w']) ? $_GET['w'] : 600;	//width
	$altezza 		= isset($_GET['h']) ? $_GET['h'] : 400;	//height
	$stepmin 		= isset($_GET['n']) ? $_GET['n'] : 10;	//stepMin
	$stepmax 		= isset($_GET['x']) ? $_GET['x'] : 10;	//stepMax
	$sfondo 		= isset($_GET['b']) ? $_GET['b'] : '111111';	//backgrounColor
	$colore			= isset($_GET['c']) ? $_GET['c'] : 'ffffff';	//foregroundColor
	$randcols		= isset($_GET['r']) ? $_GET['r'] : 1;	//randomForegroundColor

	$colore = preg_replace(' /[^0-9A-Fa-f]/ ', '', $colore); // Gets a proper hex string

	if (strlen($colore) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
    $coloreVal = hexdec($colore);
    $coloreR = 0xFF & ($coloreVal >> 0x10);
    $coloreG = 0xFF & ($coloreVal >> 0x8);
    $coloreB = 0xFF & $coloreVal;
  } if (strlen($colore) == 3) { //if shorthand notation, need some string manipulations
    $coloreR = hexdec(str_repeat(substr($colore, 0, 1), 2));
    $coloreG = hexdec(str_repeat(substr($colore, 1, 1), 2));
    $coloreB = hexdec(str_repeat(substr($colore, 2, 1), 2));
  }

	$sfondo = preg_replace("/[^0-9A-Fa-f]/", '', $sfondo); // Gets a proper hex string

	if (strlen($sfondo) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$sfondoVal = hexdec($sfondo);
		$sfondoR = 0xFF & ($sfondoVal >> 0x10);
		$sfondoG = 0xFF & ($sfondoVal >> 0x8);
		$sfondoB = 0xFF & $sfondoVal;
	} if (strlen($sfondo) == 3) { //if shorthand notation, need some string manipulations
		$sfondoR = hexdec(str_repeat(substr($sfondo, 0, 1), 2));
		$sfondoG = hexdec(str_repeat(substr($sfondo, 1, 1), 2));
		$sfondoB = hexdec(str_repeat(substr($sfondo, 2, 1), 2));
	}

	mt_srand();	//random seed

	$img = imagecreatetruecolor($larghezza+1, $altezza+1);
	$sfondo0 = imagecolorallocate($img,$sfondoR,$sfondoG,$sfondoB);
	imagefilledrectangle($img, 0, 0, $larghezza, $altezza, $sfondo0);
	$colore0 = imagecolorallocate($img,$coloreR,$coloreG,$coloreB);

	$zerox = $larghezza/2;
	$zeroy = $altezzsa/2;
	while($zerox!=$larghezza&&$zeroy!=$altezza){
		$tmpx	= $zerox;
		$tmpy	= $zeroy;
		$rnd 	= intval(mt_rand(0,3));
		$step = intval(mt_rand($stepmin,$stepmax));
		if($rnd==0){
			$zeroy+=$step;
		}else if($rnd==1){
			$zerox+=$step;
		}else if($rnd==2&&$zeroy>$altezza/2){
			$zeroy-=$step;
		}else if($rnd==3&&$zerox>$larghezza/2){
			$zerox-=$step;
		}
		if($randcols==1){
			$r=intval(mt_rand(60,$coloreR));
			$g=intval(mt_rand(60,$coloreG));
			$b=intval(mt_rand(60,$coloreB));
			$colore0=imagecolorallocate($img,$r,$g,$b);
		}
		imageline($img,$tmpx,$tmpy,$zerox,$zeroy,$colore0);
		imageline($img,$larghezza/2-($tmpx-$larghezza/2),$altezza/2+($tmpy-$altezza/2),$larghezza/2-($zerox-$larghezza/2),$zeroy,$colore0);
		imageline($img,$larghezza/2+($tmpx-$larghezza/2),$altezza/2-($tmpy-$altezza/2),$zerox,$altezza/2-($zeroy-$altezza/2),$colore0);
		imageline($img,$larghezza/2-($tmpx-$larghezza/2),$altezza/2-($tmpy-$altezza/2),$larghezza/2-($zerox-$larghezza/2),$altezza/2-($zeroy-$altezza/2),$colore0);
	}

	header("Content-type: image/png");
	imagepng($img);
	imagedestroy($img);
?>
