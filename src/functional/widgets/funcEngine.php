<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования

class Pre__widgets{
	const LIST_FILE="functional/widgets/widgetsList.fbd.csv";
	const TEMPLATE_FILE="functional/widgets/dummyes/widgetsTpls.fbd.tpl";
	const CONTENT_FILE_PATH="functional/widgets/content/wsContent/";
	const HEADERS_FILE_PATH="functional/widgets/content/wsHeaders/";
	
	private $pageId;
	private $constructError=false;
	const SEARCH_PAT_STRING="/\{\*WIDGET_INSERT\-([0-9]+)\*\}/";
	
	public function __construct($id){
		if (file_exists(self::LIST_FILE)===false || file_exists(self::TEMPLATE_FILE)===false){
			$this->constructError=true;
			return false;
		}
		$this->pageId=$id;
	}
	
	public function getWidgets($html){
		$matcher=preg_match_all(self::SEARCH_PAT_STRING,$html['html'],$matches);
		if ($matcher===false || $matcher===0)
			return $html;
		if ($this->constructError===true){
			for ($a=0;$a<$matcher;$a++){
				$html['html']=str_replace($matches[0][$a],"",$html['html']);
			}
			return $html;
		}
		$htmlTmp=file_get_contents(self::TEMPLATE_FILE);
		for ($a=0;$a<$matcher;$a++){
			$headers=self::checkFileAndGetContent($matches[1][$a],self::HEADERS_FILE_PATH);
			$content=self::checkFileAndGetContent($matches[1][$a],self::CONTENT_FILE_PATH);
			if (mb_strlen(trim($headers))<1 && mb_strlen(trim($content))<1){
				$html['html']=str_replace($matches[0][$a],"",$html['html']);
				continue;
			}
			if (mb_strlen(trim($headers))>0)
				$html['headers'][]=$headers;
			if (mb_strlen(trim($content))>0){
				$content=str_replace("{CONTAINER_CONTENT}",$content,$htmlTmp);
				$html['html']=str_replace($matches[0][$a],$content,$html['html']);
			}
			else
				$html['html']=str_replace($matches[0][$a],"",$html['html']);
		}
		
		return $html;
	}
	private function checkFileAndGetContent($fileId,$pathTmp){
		if (file_exists($pathTmp.$fileId.".fbd.tpl")===false)
			return "";
		return file_get_contents($pathTmp.$fileId.".fbd.tpl");
	}
}
?>