<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class ErrorsManager {
	const ERROR_HTML_DUMMY="<h2>Сбой в работе</h2>\r\n <p>Простите, произошёл сбой в работе административной части сайта! Возможно система была повреждена. Обратитесь к Хостинг-Провайдеру дл восстановления рабочего состояния сайта по Back'up.</p>";
	const ERROR_HTML_FILE="specials/html-dummyes/special_error.html";
	const ACCESS_TO_PAGE_ERROR="<h2>Недостаточно прав</h2>\r\n <p>Простите, но тип Вашей учетной записи не позволяет открыть запрашиваемую страницу.</p>";
	const ACCESS_TO_EXE_ERROR="Простите, но тип Вашей учетной записи не позволяет произвести запрашиваемые изменения.<span id='ajax_close_button'>Скрыть оповещение</span>";
	function __construct(){}
	public function hideErrorByMessage(){
		if (file_exists(self::ERROR_HTML_FILE)===true)
			echo file_get_contents(self::ERROR_HTML_FILE);
		else
			echo self::ERROR_HTML_DUMMY;
		exit;
	}
	public function hideAccessError($type){
		if ($type=="page")
			echo self::ACCESS_TO_PAGE_ERROR;
		else if ($type=="exe")
			echo self::ACCESS_TO_EXE_ERROR;
		exit;
	}
}
class Loader extends ErrorsManager{
	private $classPrefixes=array(
	"App"=>"functional_departments/{%FD_CLASS%}/augmentation/{%CLASS_NAME%}.php",//App_navigation_pathRewrite (example)
	"Wspc"=>"workspace/{%CLASS_NAME%}.php"//Wspc__gatekeeper (example)
	//"Shr"=>"subsidiary/php/{%CLASS_NAME%}.php" --> Shr__arrayPacker
	);
	private function loadClass($class){
		if (preg_match("/([a-zA-Z0-9]*)\_{2}([a-zA-Z0-9]*)/",$class,$matcher)==1){
			$type="workspace";
		}
		else if (preg_match("/([a-zA-Z0-9]*)\_{1}([a-zA-Z0-9]*)\_{1}([a-zA-Z0-9]*)/",$class,$matcher)==1) {
			$type="fd application";
		}
		else {
			$type=false;
		}
		if ($type!==false){
			if (array_key_exists($matcher[1],$this->classPrefixes)===false){
				parent::hideErrorByMessage();
			}
			else {
				if ($type=="workspace"){
					$path=str_replace('{%CLASS_NAME%}',$matcher[2],$this->classPrefixes[$matcher[1]]);
				}
				else if ($type=="fd application"){
					$path=str_replace('{%FD_CLASS%}',$matcher[2],$this->classPrefixes[$matcher[1]]);
					$path=str_replace('{%CLASS_NAME%}',$matcher[3],$path);
				}
				
				if (file_exists($path)===false){
					parent::hideErrorByMessage();
				}
				else {
					include_once($path);
				}	
			}
		}
	}
	public function insertCustomPath($prefix,$path){
		if (array_key_exists($prefix,$this->classPrefixes)===true){
		}
		else{
			$this->classPrefixes[$prefix]=$path;
		}
	}
	function __construct(){
		spl_autoload_register('self::loadClass');
	}
}
class manipulateGETInf {
	private $getArray;
	public function __construct(){
		if (isset($_GET)===true)
			$this->getArray=$_GET;
		else 
			$this->getArray=false;
	}
	public function checkGETInf(){
		if ($this->getArray===false)
			return false;
		else
			return true;
	}
	public function searchGETElem($key){
		if (self::checkGETInf()===false)
			return false;
		if (array_key_exists($key,$this->getArray)===true)
			return $this->getArray[$key];
		else
			return false;
	}
	public function implodeGETInf($exceptions=array(),$additions=array()){
		if (self::checkGETInf()===false)
			return false;
		$tempArray=array();
		foreach ($this->getArray as $key=>$value){
			if (array_search($key,$exceptions,TRUE)===false){
				$tempArray[]=$key."=".$value;
			}
		}
		if (empty($additions)===false){
			foreach ($additions as $key=>$value){
				$tempArray[]=$key."=".$value;
			}
		}
		return implode("&",$tempArray);
	}
}
class maskHTMLEntity {
	const SEARCH_PATTERN_1="/(\&)([a-zA-Z]*)(\;)/u";
	const SEARCH_PATTERN_2="/(\&)\[([a-zA-Z]*)\](\;)/u";
	const REPLACEMENT_PATTERN_1="$1[$2]$3";
	const REPLACEMENT_PATTERN_2="$1$2$3";
	private $type;
	public function __construct($typeIs){
		if ($typeIs!==false && $typeIs!==true)
			$this->type=false;
		else
			$this->type=$typeIs;
	}
	
	public function maskEngine($string){
		if ($this->type===false)
			return preg_replace(self::SEARCH_PATTERN_2,self::REPLACEMENT_PATTERN_2,$string);
		else
			return preg_replace(self::SEARCH_PATTERN_1,self::REPLACEMENT_PATTERN_1,$string);
	}
}
?>