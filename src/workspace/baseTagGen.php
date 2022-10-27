<?php
// Класс формирующий тег <base>
class Wspc__baseTagGen {
	private $baseTag=false;
	public function __construct(){
		$this->baseTag="\r\n<base href='http://{$_SERVER['HTTP_HOST']}/'>\r\n";
	}
	public function getHtmlPiece(){
		return $this->baseTag;
	}
}
?>