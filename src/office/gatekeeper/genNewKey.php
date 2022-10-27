<?php
	$symbs=array(0,1,2,3,4,5,6,7,8,9,"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$heigth=array("l","h");
	$string="";
	$amount=48;
	for ($a=0;$a<$amount;$a++){
		$randKeySymbol=array_rand($symbs);
		$randKeyHeight=array_rand($heigth);
		$symbol=$symbs[$randKeySymbol];
		if ($heigth[$randKeyHeight]=="h")
			$symbol=strtoupper($symbol);
		$string.=$symbol;
	}
	file_put_contents("abcqwerty.fbd.dat",$string);
?>