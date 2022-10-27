<?php
class App_startPage_generator extends ErrorsManager {
	const NAVIGATION_FILE_PATH="information_base/admin_navigation.fbd.dat";
	private $footerNavigationTempl=array(
	'rowConteiner'=>"<span class='num_rows_conteiner'>{REPLACEMENT_PART}</span>",
	'currentNumConteiner'=>"<span class='nums current_num'>{NUMERIC}</span>",
	'numConteiner'=>"<a class='nums' href='boggart/index.php?num={NUMERIC}'>{NUMERIC}</a>",
	);
	private $contentTempl=array(
	'iconsRow'=>"\n<div class='navigation_row'>{REPLACEMENT_PART}</div>\n",
	'icon'=>"\n<a class='menu{CLASSNUM}' href='boggart/index.php?dep={DEPART}&trunk={NODEFILE}' title='{DESC}'>{NAME}</a>\n"
);
	private $pageNumber;
	private $navigationArray;
	private $elemsQuantity=0;
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE_PATH)===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"File with Admin Navigation is not exists here:".self::NAVIGATION_FILE_PATH);
			parent::hideErrorByMessage();
		}
		$temporaryObj=new Shr__arrayPacker(self::NAVIGATION_FILE_PATH);
		$this->navigationArray=$temporaryObj->unpackArray();
		foreach ($this->navigationArray as $innerArray){
			$this->elemsQuantity += count($innerArray);
		}
		
		$GETDatas=new manipulateGETInf();
		$startpageNumber=$GETDatas->searchGETElem("num");
		if ($startpageNumber===false)
			$this->pageNumber=1;
		else 
			$this->pageNumber=$startpageNumber;
		
		
	}
	private function genHtmlString($strPat,$content,$pattern){
		$strPat="{".strtoupper($strPat)."}";
		return str_replace($strPat,$content,$pattern);
	}
	public function genStartpageMainContent(){
		$content="";
		$elements="";
		$cellsCounter=1;
		$startCounter=1+(16*($this->pageNumber - 1));
		$endCounter=16*$this->pageNumber;
		foreach ($this->navigationArray as $innerArray){
			foreach ($innerArray as $key=>$value){
				if ($cellsCounter<=$endCounter && $cellsCounter>=$startCounter){
					$classnum=$cellsCounter-$startCounter+1;
					$elements.=self::genHtmlString('classnum',$classnum,$this->contentTempl['icon']);
					$elements=self::genHtmlString('depart',$key,$elements);
					$elements=self::genHtmlString('nodefile',$value['nodeFile'],$elements);
					$elements=self::genHtmlString('desc',$value['desc'],$elements);
					$elements=self::genHtmlString('name',$value['name'],$elements);
					if ($cellsCounter%4==0){
						$content.=self::genHtmlString('replacement_part',$elements,$this->contentTempl['iconsRow']);
						$elements="";
					}
					else if ($cellsCounter%4!=0 && $cellsCounter==$this->elemsQuantity){
						$content.=self::genHtmlString('replacement_part',$elements,$this->contentTempl['iconsRow']);
						$elements="";
						break 2;
					}
					$cellsCounter++;
				}
				else if ($cellsCounter<$startCounter) {
					$cellsCounter++;
				}
				else {
					if (($cellsCounter-1)%4!=0){
						$content.=self::genHtmlString('replacement_part',$elements,$this->contentTempl['iconsRow']);
						$elements="";
					}
					break 2;
				}
			}
		}
		return $content;
	}
	public function genNumericNavMainContent(){
		if ($this->elemsQuantity<=16)
			return "";
		$content="";
		for ($a=1,$b=ceil($this->elemsQuantity / 16);$a<=$b;$a++){
			if ($a==$this->pageNumber)
				$string=$this->footerNavigationTempl['currentNumConteiner'];
			else
				$string=$this->footerNavigationTempl['numConteiner'];
			$content.=self::genHtmlString('numeric',$a,$string);
		}
		return self::genHtmlString('replacement_part',$content,$this->footerNavigationTempl['rowConteiner']);
	}
}

?>