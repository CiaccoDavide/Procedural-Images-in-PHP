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

// parse the colors and get RGB values
$color = preg_replace(' /[^0-9A-Fa-f]/ ', '', $color); // Gets a proper hex string
if (strlen($color) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
    $colorVal = hexdec($color);
    $colorR = 0xFF & ($colorVal >> 0x10);
    $colorG = 0xFF & ($colorVal >> 0x8);
    $colorB = 0xFF & $colorVal;
} if (strlen($color) == 3) { //if shorthand notation, need some string manipulations
    $colorR = hexdec(str_repeat(substr($color, 0, 1), 2));
    $colorG = hexdec(str_repeat(substr($color, 1, 1), 2));
    $colorB = hexdec(str_repeat(substr($color, 2, 1), 2));
}
$background = preg_replace("/[^0-9A-Fa-f]/", '', $background); // Gets a proper hex string
if (strlen($background) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
	$backgroundVal = hexdec($background);
	$backgroundR = 0xFF & ($backgroundVal >> 0x10);
	$backgroundG = 0xFF & ($backgroundVal >> 0x8);
	$backgroundB = 0xFF & $backgroundVal;
} if (strlen($background) == 3) { //if shorthand notation, need some string manipulations
	$backgroundR = hexdec(str_repeat(substr($background, 0, 1), 2));
	$backgroundG = hexdec(str_repeat(substr($background, 1, 1), 2));
	$backgroundB = hexdec(str_repeat(substr($background, 2, 1), 2));
}

// set a random seed for the default PNRG
mt_srand();

// create the "canvas"
$img = imagecreatetruecolor($width, $height);

// create different shades of colors
if($backgroundR<20){
	$backgroundR1=$backgroundR+20;
	$backgroundR2=$backgroundR+40;
}
if($backgroundR>235){
	$backgroundR1=$backgroundR-20;
	$backgroundR2=$backgroundR-40;
}
if($backgroundR>20 & $backgroundR<235){
	$backgroundR1=$backgroundR-20;
	$backgroundR2=$backgroundR+20;
}

if($backgroundG<20){
	$backgroundG1=$backgroundG+20;
	$backgroundG2=$backgroundG+40;
}
if($backgroundR>235){
	$backgroundG1=$backgroundG-20;
	$backgroundG2=$backgroundG-40;
}
if($backgroundG>20 & $backgroundG<235){
	$backgroundG1=$backgroundG-20;
	$backgroundG2=$backgroundG+20;
}

if($backgroundB<20){
	$backgroundB1=$backgroundB+20;
	$backgroundB2=$backgroundB+40;
}
if($backgroundB>235){
	$backgroundB1=$backgroundB-20;
	$backgroundB2=$backgroundB-40;
}
if($backgroundB>20 & $backgroundB<235){
	$backgroundB1=$backgroundB-20;
	$backgroundB2=$backgroundB+20;
}

// initialize the colors
$color0 = imagecolorallocate($img,$colorR,$colorG,$colorB);
$background0 = imagecolorallocate($img,$backgroundR,$backgroundG,$backgroundB);
$background1 = imagecolorallocate($img,$backgroundR1,$backgroundG1,$backgroundB1);
$background2 = imagecolorallocate($img,$backgroundR2,$backgroundG2,$backgroundB2);

// fill the background with the background color
imagefilledrectangle($img, 0, 0, $width, $height, $background0);

// every char of the string $s is a "pixel" of the image
$s='';
for($y = 0; $y < $width; $y++){
	for($x = 0; $x < $height; $x++){
		$s.='0';
	}
}

// symmetric image generation
$i=0;
$s[0]=mt_rand(0,1);
for($y = $ystart; $y < $height/$pixelsize; $y++){
	for($x = $xstart; $x < $width/($pixelsize*2); $x++){
		
		$f=$width/($pixelsize)-$x-1;
		
		$diff=intval(mt_rand(0,2));

		if($s[$i]=='1'){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $color0);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $color0);
		}
		if($s[$i]=='0'){
			if($diff==0){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $background0);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $background0);
			}
			if($diff==1){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $background1);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $background1);
			}
			if($diff==2){
				imagefilledrectangle($img, $pixelsize*($x), $pixelsize*($y), $pixelsize+$pixelsize*($x), $pixelsize-1+$pixelsize*($y), $background2);
				imagefilledrectangle($img, $pixelsize*($f), $pixelsize*($y), $pixelsize+$pixelsize*($f), $pixelsize-1+$pixelsize*($y), $background2);
			}
		}

		// randomly set if the next "pixel" is empty or full
		$r=mt_rand(1,100);
		if($s[$i]=='0' && $r<$prob_empty)
			$s[++$i]='1';
		if($s[$i]=='0' && $r>$prob_empty)
			$s[++$i]='0';
		if($s[$i]==1 && $r<$prob_full)
			$s[++$i]='1';
		if($s[$i]==1 && $r>$prob_full)
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
