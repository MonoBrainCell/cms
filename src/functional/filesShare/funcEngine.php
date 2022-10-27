<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования

class Pre__filesShare{

	const LIST_FILE="functional/filesShare/filesList.fbd.csv";
	const TEMPLATE_FILE="functional/filesShare/dummyes/filesShareTpls.fbd.tpl";
	const CONTENT_FILE_PATH="functional/filesShare/content/";
	const LEVEL1_SEPARATOR="|--|";
	const FILE_DOWNLOAD_PREFIX="download.php?file=";
	
	private $constructError=false;
	const SEARCH_PAT_STRING="/\{\*FSHL_INSERT\-([0-9]+)\*\}/";
	
	public function __construct(){
		if (file_exists(self::LIST_FILE)===false || file_exists(self::TEMPLATE_FILE)===false){
			$this->constructError=true;
			return false;
		}
	}
	
	public function getFilesLists($html){
		$matcher=preg_match_all(self::SEARCH_PAT_STRING,$html['html'],$matches);
		if ($matcher===false || $matcher===0)
			return $html;
		if ($this->constructError===true){
			for ($a=0;$a<$matcher;$a++){
				$html['html']=str_replace($matches[0][$a],"",$html['html']);
			}
			return $html;
		}
		$items=file(self::LIST_FILE);
		if (empty($items)===true || strlen(trim($items[0]))<1){
			for ($a=0;$a<$matcher;$a++){
				$html['html']=str_replace($matches[0][$a],"",$html['html']);
			}
			return $html;
		}
		
		$htmlTmp=file_get_contents(self::TEMPLATE_FILE);
		list($headers,$wrapper,$anchor)=explode(self::LEVEL1_SEPARATOR,$htmlTmp);
		if (strlen(trim($headers))>0)
			$html['headers'][]=$headers;
		
		for ($a=0,$b=0,$c=count($items);$a<$c;$a++){
			$elem=explode(";;",rtrim($items[$a]));
			$key=array_search($elem[0],$matches[1],true);
			if ($b<$matcher && $key!==false){
				if ($elem[2]=="codeIns"){
					if (file_exists(self::CONTENT_FILE_PATH .$elem[0].".fbd.csv")===true)
						$elem[2]=file_get_contents(self::CONTENT_FILE_PATH .$elem[0].".fbd.csv");
					else {
						$html['html']=str_replace($matches[0][$key],"",$html['html']);
						$b++;
						continue;
					}
				}
				$flsGroup=explode(",,",$elem[2]);
				$string="";
				for ($d=0,$e=count($flsGroup);$d<$e;$d++){
					list($file,$hint)=array_pad(explode("::",$flsGroup[$d]),2,false);
					if ($file===false || $hint===false)
						continue;
					$string.=str_replace("{FILE_ANCHOR}",self::FILE_DOWNLOAD_PREFIX .$file,$anchor);
					$string=str_replace("{FILE_NAME}",$hint,$string);
				}
				$string=str_replace("{CONTAINER_CONTENT}",$string,$wrapper);
				
				$html['html']=preg_replace("/(<blockquote(.*)>(\s*))?((.*)<(div|p|h1|h2|h3|h4|h5|h6)(.*)>(.*))(".preg_quote($matches[0][$key]).")((.*)<\/(div|p|h1|h2|h3|h4|h5|h6)>(.*))((\s*)<\/blockquote>)?/u",$string,$html['html']);
				
				$b++;
			}
		}
		
		return $html;
	}
}
?>