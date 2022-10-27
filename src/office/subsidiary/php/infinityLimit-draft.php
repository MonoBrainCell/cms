<?php
private function searchParams($id,$fileArray){
	$returnDatas=array(); $returnDatas['fileArray']=array(); $idArray=array();$stringsArray=array(); $maxIndex=0;
		for ($a=0,$b=count($fileArray);$a<$b;$a++){
			if ($id=="new" && $b==self::LIMITER){
				if ($a===0 && isset($marker)===false){
					$elem=explode(";;",$fileArray[$a]);
					$returnDatas['newId']=$elem[0];
					$returnDatas['stringNum']=self::LIMITER - 1;
					$marker=true;
				}
				else {
					$returnDatas['fileArray'][]=$fileArray[$a];
				}
			}
			else {
				$elem=explode(";;",$fileArray[$a]);
				if ($id=="new"){
					$idArray[]=$elem[0]*1;
					$stringsArray[$elem[0]]=$a;
					if ($maxIndex<$elem[0])
						$maxIndex=$elem[0];
				}
				else {
					if ($elem[0]==$id){
						$returnDatas['newId']=$elem[0];
						$returnDatas['stringNum']=$a;
					}
				}
				$returnDatas['fileArray'][]=$fileArray[$a];
			}
		}
		if ($maxIndex!==0){
			for ($a=0;$a<$maxIndex;$a++){
				if (array_search($a,$idArray,TRUE)===false){
					$returnDatas['newId']=$a;
					$successMarker=true;
					break;
				}
			}
			if (isset($successMarker)===false){
				$returnDatas['newId']=$maxIndex+1;
				$returnDatas['stringNum']=$b;
			}
			else
				$returnDatas['stringNum']=$stringsArray[$returnDatas['newId']];
		}
		return $returnDatas;
	}
?>