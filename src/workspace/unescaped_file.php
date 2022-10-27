<?php

// Данный файл является обязательным дополнение для реализации движка CMS

/*
	* Класс используемый для вывода оповещения о недоступности сайта и прерывания работы текущего php скрипта
	* Осуществляется путём вызова статического метода hideErrorByMessage
*/
class ErrorsManager {
	const ERROR_HTML_DUMMY="<h1>Технические работы.</h1>\r\n <p>Простите, на сайте ведутся технические работы! <br>Обязательно зайдите позднее.</p>";
	function __construct(){}
	static public function hideErrorByMessage(){
		echo self::ERROR_HTML_DUMMY;
		exit;
	}
}

// Класс отвечающий за автоматическую подгрузку файлов классов
class Loader extends ErrorsManager{
	// Свойство, используемое для хранения префиксов имен классов и мест из которых должна быть осуществлена из подгрузка
	private $classPrefixes=array(
		"Pre"=>"functional/{%CLASS_NAME%}/funcEngine.php",
		"Wspc"=>"workspace/{%CLASS_NAME%}.php"
	);
	// Метод подгружающий файл класса (Реализуется через автоматическую подгрузку классов в конструкторе)
	private function loadClass($class){
		// Проверяем имя класса на соответствие возможным форматам
		
		// Ожидаемый примитив: a__b; Пример: Wspc__gatekeeper (префикс__имяКласса)
		if (preg_match("/^([a-zA-Z0-9]+)\_{2}([a-zA-Z0-9]+)$/",$class,$matcher)==1){
			$type="workspace";
		}
		// Ожидаемый примитив: a_b_c; Пример: Pre_navigation_pathRewrite (префикс_папкаВКоторойЛежитКласс_имяКласса)
		else if (preg_match("/^([a-zA-Z0-9]+)\_{1}([a-zA-Z0-9]+)\_{1}([a-zA-Z0-9]+)$/",$class,$matcher)==1) {
			$type="fd application";
		}
		else {
			$type=false;
		}
		
		// Если формат имени класса определён, проверяем его в списке доступных(classPrefixes) и формируем полноценный путь к файлу
		if ($type!==false){
			// Если префикс целевого класса отсутствует в списке доступных, прерываем работы скрипта(hideErrorByMessage)
			if (array_key_exists($matcher[1],$this->classPrefixes)===false){
				parent::hideErrorByMessage();
			}
			else {
				if ($type=="workspace"){
					//Пример класса и соответствующего пути:
					//	Класс: Pre__myClass
					//	Путь: functional/{%CLASS_NAME%}/funcEngine.php
					//	Результат: functional/myClass/funcEngine.php
					$path=str_replace('{%CLASS_NAME%}',$matcher[2],$this->classPrefixes[$matcher[1]]);
				}
				else if ($type=="fd application"){
					//Пример класса и соответствующего пути:
					//	Класс: Some_weatherApp_mainClass
					//	Путь: apps/{%FD_CLASS%}/{%CLASS_NAME%}.php
					//	Результат: apps/weatherApp/mainClass.php
					$path=str_replace('{%FD_CLASS%}',$matcher[2],$this->classPrefixes[$matcher[1]]);
					$path=str_replace('{%CLASS_NAME%}',$matcher[3],$path);
				}
				
				// В случае отсутствия файла по сформированному пути прерываем скрипт, в противном случае подгружаем требуемый файл
				if (file_exists($path)===false){
					parent::hideErrorByMessage();
				}
				else {
					include($path);
				}	
			}
		}
	}
	
	// Метод, добавляющий в список доступных для подгрузки классов новый префикс(prefix) и соответствующий ему путь(path)
	public function insertCustomPath($prefix,$path){
		if (array_key_exists($prefix,$this->classPrefixes)===true){
		}
		else{
			$this->classPrefixes[$prefix]=$path;
		}
	}
	function __construct(){
		spl_autoload_register(array($this,'loadClass'));
	}
}

// Класс отвечающий за работу с данными, переданными через адресную строку (суперглобальный массив _GET)
class manipulateGETInf {
	// Свойство, в котором будет содержаться копия суперглобального массива _GET
	private $getArray;
	
	// При создании объекта класса конструктор проверяет есть ли данные в _GET и копирует их в спец. свойство(getArray) в случае их наличия
	public function __construct(){
		if (empty($_GET)===false)
			$this->getArray=$_GET;
		else 
			$this->getArray=false;
	}
	
	// Метод проверяет скопированы ли данные из _GET
	public function checkGETInf(){
		if ($this->getArray===false)
			return false;
		else
			return true;
	}
	
	// Метод осуществляет поиск по скопированным из _GET данным по целевому ключу
	public function searchGETElem($key){
		// Проверяем на существование скопированных из _GET данных
		if ($this->checkGETInf()===false)
			return false;
		
		// Проверяем наличие элемента с целевым ключом в скопированных из _GET данных, если ключ присутствует - возвращаем соответствующее ему значение
		if (array_key_exists($key,$this->getArray)===true)
			return $this->getArray[$key];
		else
			return false;
	}
	
	// Метод формирует из массива скопированных из _GET данных строку, которую можно использовать в адресной строке, исключая значения, соответствующие определённым ключам(exceptions) и добавляя необходимые данные(additions)
	// $additions=array("ключВGETСтроке"=>"значение");
	public function implodeGETInf($exceptions=array(),$additions=array()){
	
		// Проверяем на существование скопированных из _GET данных
		if ($this->checkGETInf()===false)
			return false;
		
		// Формируем итоговый массив значений, которые будут преобразованы в строку, путём обхода массива данных скопированных из _GET
		// итоговый массив будет содержать строки типа: ключ=значение
		$tempArray=array();
		foreach ($this->getArray as $key=>$value){
			// Проверяем наличие имени текущего ключа в исключениях и в случае его отсуствия - добавляем в итоговой массива
			if (in_array($key,$exceptions,TRUE)===false){
				$tempArray[]=$key."=".$value;
			}
		}
		
		// Добавляем в итоговый массив дополнительные данные(additions), если это необходимо (массив с доп. данными не пустой)
		if (empty($additions)===false){
			foreach ($additions as $key=>$value){
				$tempArray[]=$key."=".$value;
			}
		}
		
		// Возвращаем строку, сформированную из итогового массива, с разделением в ней по символу "&", т.е.: ключ1=значение1&ключ2=значение2&...
		return implode("&",$tempArray);
	}
}
?>