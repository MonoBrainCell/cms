<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования

class Pre__videoGallery{
	
	const LIST_FILE="functional/videoGallery/videosList.fbd.csv";
	const TEMPLATE_FILE="functional/videoGallery/dummyes/videoGalleryTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	private $constructError=false;
	const SEARCH_PAT_STRING="/\{\*VIDEO_INSERT\-([0-9]+)\*\}/";
	public function __construct(){
		if (file_exists(self::LIST_FILE)===false || file_exists(self::TEMPLATE_FILE)===false){
			$this->constructError=true;
			return false;
		}
	}
	
	public function getVideos($html){
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
		list($headers,$tmp)=explode(self::LEVEL1_SEPARATOR,$htmlTmp);
		if (strlen(trim($headers))>0)
			$html['headers'][]=$headers;
		for ($a=0,$b=0,$c=count($items);$a<$c;$a++){
			$elem=explode(";;",rtrim($items[$a]));
			$key=array_search($elem[0],$matches[1],true);
			if ($b<$matcher && $key!==false){
				$string=str_replace("{VIDEO_NAME}",$elem[1],$tmp);
				$string=str_replace("{VIDEO_WIDTH}",$elem[2],$string);
				$string=str_replace("{VIDEO_HEIGHT}",$elem[3],$string);
				$string=str_replace("{VIDEO_SOURCE}",$elem[4],$string);
				$html['html']=str_replace($matches[0][$key],$string,$html['html']);
				$b++;
			}
		}
		return $html;
	}
}
?>