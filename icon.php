<?php

// size of the image
$height = 80;
$width = 80;

// size of the "pixels"
$pixelsize = 8;

// vertical gap distance between "pixels"
$ygap = 0;

$ystart = 0;
$xstart = 0;

// hex code for the main color
$color = "1e1e1e";
// hex code for the background color
$background = "399bad";

// probability of finding an empty/full pixel
$prob_empty = 50;
$prob_full = 50;

// if you place multiple images on a single web page
// you'll need to set a different counter for each image
// to prevent the caching of the image (or else all images will be the same)
$counter = $_GET['counter'];
	
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
//VARIABILI 
/*
$altezza = 1280;
$larghezza = 256;
$pixelsize = 8; //deve essere un divisore sia dell'altezza che della larghezza
$ygap = 0;
$ystart = 0;
$xstart = 0;

$probvuoto = 50;
$probpieno = 60;
*/
//random seed

mt_srand();
//CREA "CANVAS"
$img = imagecreatetruecolor($larghezza, $altezza);
//crea sfondo
imagefilledrectangle($img, 0, 0, $larghezza, $altezza, $sfondo0);
//COLORS

if($sfondoR<20){
	$sfondoR1=$sfondoR+20;
	$sfondoR2=$sfondoR+40;
}
if($sfondoR>235){
	$sfondoR1=$sfondoR-20;
	$sfondoR2=$sfondoR-40;
}
if($sfondoR>20 & $sfondoR<235){
	$sfondoR1=$sfondoR-20;
	$sfondoR2=$sfondoR+20;
}

if($sfondoG<20){
	$sfondoG1=$sfondoG+20;
	$sfondoG2=$sfondoG+40;
}
if($sfondoR>235){
	$sfondoG1=$sfondoG-20;
	$sfondoG2=$sfondoG-40;
}
if($sfondoG>20 & $sfondoG<235){
	$sfondoG1=$sfondoG-20;
	$sfondoG2=$sfondoG+20;
}

if($sfondoB<20){
	$sfondoB1=$sfondoB+20;
	$sfondoB2=$sfondoB+40;
}
if($sfondoB>235){
	$sfondoB1=$sfondoB-20;
	$sfondoB2=$sfondoB-40;
}
if($sfondoB>20 & $sfondoB<235){
	$sfondoB1=$sfondoB-20;
	$sfondoB2=$sfondoB+20;
}
$colore0 = imagecolorallocate($img,$coloreR,$coloreG,$coloreB);
$sfondo0 = imagecolorallocate($img,$sfondoR,$sfondoG,$sfondoB);

$sfondo1 = imagecolorallocate($img,$sfondoR1,$sfondoG1,$sfondoB1);

$sfondo2 = imagecolorallocate($img,$sfondoR2,$sfondoG2,$sfondoB2);
//SFONDO

//COSTRUZIONE STRINGA 0
$i=0;
for($y = 0; $y < $larghezza; $y++){
	for($x = 0; $x < $altezza; $x++){
		$s.='0';
		$i++;
	}
}
//GENERAZIONE IMMAGINE SIMMETRICA
$i=0;
for($y = $ystart; $y < $altezza/$pixelsize; $y++){
	for($x = $xstart; $x < $larghezza/($pixelsize*2); $x++){
		
		$r=mt_rand(0,100);
		
		$f=$larghezza/($pixelsize)-$x-1;
		
		
		
		$diff=intval(mt_rand(0,2));
	
		
		if($s[$i]=='1'){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $colore0);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $colore0);
		}
		if($s[$i]=='0'){
			if($diff==0){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $sfondo0);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $sfondo0);
			}
			if($diff==1){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $sfondo1);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $sfondo1);
			}
			if($diff==2){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $sfondo2);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $sfondo2);
			}
		}
		if($s[$i]=='0' && $r<$probvuoto)
			$s[++$i]='1';
		if($s[$i]=='0' && $r>$probvuoto)
			$s[++$i]='0';
		if($s[$i]==1 && $r<$probpieno)
			$s[++$i]='1';
		if($s[$i]==1 && $r>$probpieno)
			$s[++$i]='0';
	}
	$y = $y+$ygap;
}

$counter=$counter;
// if you want to output a jpeg instead of a png
// add '?type=jpeg' to the url
$type = $_GET['type'];
if($type=="jpeg"){
	header("Content-type: image/jpeg");
	imagejpeg($img,NULL,75);
}else{
	header("Content-type: image/png");
	imagepng($img,NULL,9,PNG_ALL_FILTERS);
}
imagedestroy($img);
?>
