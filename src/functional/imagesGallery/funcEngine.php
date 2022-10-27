<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования

class Pre__imagesGallery{

	const LIST_FILE="functional/imagesGallery/gallerysList.fbd.csv";
	const TEMPLATE_FILE="functional/imagesGallery/dummyes/imagesGalleryTpls.fbd.tpl";
	const CONTENT_FILE_PATH="functional/imagesGallery/content/";
	const LEVEL1_SEPARATOR="|--|";
	const GENERAL_TEMP="<a href='files/media/images_gallery/original/{ORIGINAL_IMG_PATH}' title='{HINT}' data-gallery='rebox_{NUM}' data-gallery-desc='{HINT}'>{PREVIEW_INSERT}</a>";
	const PREVIEW_ADDON="<img src='files/media/images_gallery/preview/{PREVIEW_IMG_PATH}' alt='{HINT}'>";
	const SEWN_HEADERS=<<<EOT
	<script type="text/javascript" src="functional/imagesGallery/rebox/jquery-litelighter.js"></script>
	<script type="text/javascript" src="functional/imagesGallery/rebox/jquery-rebox.js"></script>
	<link type="text/css" rel="stylesheet" href="functional/imagesGallery/rebox/jquery-rebox.css">
	<script type='text/javascript'>
		$(document).ready(function(){
{GALLERY_INIT}
		});
	</script>
EOT;
	const GALLERY_INIT_TEMPLATE=<<<EOT
			$("body").rebox({
				selector:"a[data-gallery^='rebox_{GALLERY_NUM}']",
				prev:"&lArr;",
				next:"&rArr;",
				close:"X",
				captionAttr:'data-gallery-desc'
			});	
EOT;
	
	private $constructError=false;
	const SEARCH_PAT_STRING="/\{\*IMG_GALLERY_INSERT\-([0-9]+)\*\}/";
	
	public function __construct(){
		if (file_exists(self::LIST_FILE)===false || file_exists(self::TEMPLATE_FILE)===false){
			$this->constructError=true;
			return false;
		}
	}
	
	public function getGalleries($html){
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
		$existsGalleries=array();
		list($headers,$separateTmp,$builtInTmp)=explode(self::LEVEL1_SEPARATOR,$htmlTmp);
		if (strlen(trim($headers))>0)
			$html['headers'][]=$headers;
		
		for ($a=0,$b=0,$c=count($items);$a<$c;$a++){
			$elem=explode(";;",rtrim($items[$a]));
			$key=array_search($elem[0],$matches[1],true);
			if ($b<$matcher && $key!==false){
				if ($elem[3]=="codeIns"){
					if (file_exists(self::CONTENT_FILE_PATH .$elem[0].".fbd.csv")===true)
						$elem[3]=file_get_contents(self::CONTENT_FILE_PATH .$elem[0].".fbd.csv");
					else {
						$html['html']=str_replace($matches[0][$key],"",$html['html']);
						$b++;
						continue;
					}
				}
				$existsGalleries[]=$a;
				$imgsGroup=explode(",,",$elem[3]);
				$string="";
				for ($d=0,$e=count($imgsGroup);$d<$e;$d++){
					list($original,$preview,$hint)=array_pad(explode("::",$imgsGroup[$d]),3,false);
					if ($preview===false || $original===false || $hint===false)
						continue;
					$string.=str_replace("{ORIGINAL_IMG_PATH}",$original,self::GENERAL_TEMP);
					$string=str_replace("{NUM}",$a,$string);
					if ($preview!="0"){
						$string=str_replace("{PREVIEW_INSERT}",self::PREVIEW_ADDON,$string);
						$string=str_replace("{PREVIEW_IMG_PATH}",$preview,$string);
					}
					else
						$string=str_replace("{PREVIEW_INSERT}","",$string);
					if ($hint!="0")
						$string=str_replace("{HINT}",$hint,$string);
					else
						$string=str_replace("{HINT}","",$string);
				}
				if ($elem[2]=="built-in"){
					$string=str_replace("{CONTAINER_CONTENT}",$string,$builtInTmp);
					$html['html']=str_replace($matches[0][$key],$string,$html['html']);
				}
				else {
					$string=str_replace("{CONTAINER_CONTENT}",$string,$separateTmp);
					$html['html']=preg_replace("/(<blockquote(.*)>(\s*))?((.*)<(div|p|h1|h2|h3|h4|h5|h6)(.*)>(.*))(".preg_quote($matches[0][$key]).")((.*)<\/(div|p|h1|h2|h3|h4|h5|h6)>(.*))((\s*)<\/blockquote>)?/u",$string,$html['html']);
				}
				
				$b++;
			}
		}
		
		$html['headers'][]=self::genGalleryHeaders($existsGalleries);
		
		return $html;
	}
	
	private function genGalleryHeaders($numsList){
		$headerStrings="";
		for($a=0,$b=count($numsList);$a<$b;$a++){
			$headerStrings.=str_replace("{GALLERY_NUM}",$numsList[$a],self::GALLERY_INIT_TEMPLATE)."\n";
		}
		$fullHeader=str_replace("{GALLERY_INIT}",$headerStrings,self::SEWN_HEADERS);
		
		return $fullHeader;
	}
}
?>