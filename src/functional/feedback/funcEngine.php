<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования

class Pre__feedback{

	const CONTENT_FILE="functional/feedback/content/fbContent.fbd.tpl";
	const HEADERS_FILE="functional/feedback/content/fbHeaders.fbd.tpl";
	const TEXTAREA_MASK="[textfield]";
	const FORM_INSERT_STR="/(<blockquote(.*)>(\s*))?((.*)<(div|p|h1|h2|h3|h4|h5|h6)(.*)>(.*))(\{\*FEEDBACK_INSERT\*\})((.*)<\/(div|p|h1|h2|h3|h4|h5|h6)>(.*))((\s*)<\/blockquote>)?/u";
	const EXTRA_FORM_INSERT_STR="{*FEEDBACK_INSERT*}";
	
	private $constructError=false;
	
	
	public function __construct(){
		if (file_exists(self::CONTENT_FILE)===false || file_exists(self::HEADERS_FILE)===false){
			$this->constructError=true;
			return false;
		}
	}
	
	public function getFeedbackForm($html){
		if ($this->constructError===true){
			$html['html']=str_replace(self::EXTRA_FORM_INSERT_STR,"",$html['html']);
			return $html;
		}
		$matcher=preg_match(self::FORM_INSERT_STR,$html['html'],$matches);
		if ($matcher===0 || $matcher===false){
			$html['html']=str_replace(self::EXTRA_FORM_INSERT_STR,"",$html['html']);
			return $html;
		}
		$content=file_get_contents(self::CONTENT_FILE);
		$headers=file_get_contents(self::HEADERS_FILE);
		$content=str_replace(self::TEXTAREA_MASK,"textarea",$content);
		$content=preg_replace("/\&\[([a-zA-Z0-9]+)\]\;/","&$1;",$content);
		$html['html']=preg_replace(self::FORM_INSERT_STR,$content,$html['html']);
		
		if (strlen(trim($headers))>0)
			$html['headers'][]=$headers;
		
		return $html;
	}
}
?>