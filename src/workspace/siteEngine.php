<?php
// Класс движка CMS
class Wspc__siteEngine extends ErrorsManager{
	const PATH_TO_DUMMIES="design/";
	const DUMMY_FILE="dummy.fbd.html";
	const SETTINGS_FILE="settings.fbd.php";
	const CONTENT_PATH="content/";
	const ENGINE_OFF_FILE="off.fbd.html";
	const ENGINE_OFF_HTML="<h1>Сайт закрыт на реконструкцию</h1>";
	const E404_FILE="404.html";
	const E404_HTML="<h1>Данная страница недоступна.</h1><p>Вернитесь на <a href='{DOMEN_NAME}'>главную</a></p>";
	
	// Свойство, которое будет использовано для хранения и передачи html-кода при формировании страницы
	private $output=array();
	
	// При создании объекта на основе класса осуществляется формирование полного html-кода запрашиваемой страницы (id)
	public function __construct($id){
		// Осуществляем получение настроек движка, используемых при формировании контента страниц CMS
		$sets=$this->initSets();
		
		// Проверяем наличие файла, в котором должен храниться основной контент запрашиваемой страницы(id); в случае его отсутствия выдаем, что страница не найдена
		if (file_exists(self::CONTENT_PATH .$id.".php")===false){
			// Проверяем наличие файла с оповещением о том, что страница не найдена(E404_FILE)
			if (file_exists(self::E404_FILE)===true){
				header("http/1.1 404 Not Found");
				echo file_get_contents(self::E404_FILE);
			}
			else {
				header("http/1.1 404 Not Found");
				echo str_replace("{DOMEN_NAME}","http://{$_SERVER['HTTP_HOST']}/",self::E404_HTML);
			}
			exit;
		}
		// Формируем заголовок последнего изменения страницы(Last-Modified) на основе даты и времени последнего измненения файла, в котором хранится основной контент запрашиваемой страницы(id)
		$modTime=filemtime(self::CONTENT_PATH .$id.".php");
		if ($modTime!==false){
			header("Last-Modified: ".gmdate("D, d M Y H:i:s",$modTime)." GMT");
		}
		
		// Этап формирования html-кода страницы на основе наиболее важных частей контента (навигации, основной заголовок, основной контент и т.п.)
		$this->genContent($id,$sets['mainEngineSearch']);
		// Этап формирования html-кода страницы на основе вариативных частей контента(подобные части формируются через админ. панель и предоставляются в виде кода для вставки)
		$this->genEmbientContent($id,$sets['templateSearch']);
		// Добавляем в html-код страницы все теги, которые необходимо было разместить внутри контейнера тега <head>(headers)
		$this->output['html']=str_replace("{_HEAD_STRING_}",implode("\r\n",$this->output['headers']),$this->output['html']);
	}
	
	// Метод получения основных настроек движка CMS
	private function initSets(){
		// Проверяем наличие файла с настройками(SETTINGS_FILE)
		if (file_exists(self::SETTINGS_FILE)===false)
			parent::hideErrorByMessage();
		// В файле настроек(SETTINGS_FILE) существуют 3 переменные:
		//	siteEngineSwitcher - определяет включен ли движок CMS
		//	selectedDesign - имя текущей темы оформления страниц сайта
		//	functional - сериализованный массив, хранящий список всех классов и методов, задействованных в формировании контента страниц сайта
		include self::SETTINGS_FILE;
		// В случае доступности всех 3-х переменных настроек движка осуществляем попытку обработки настроек
		if (isset($siteEngineSwitcher)===true && isset($selectedDesign)===true && isset($functional)===true){
			// Если движок CMS выключен(siteEngineSwitcher)
			if ($siteEngineSwitcher=="off"){
				// Выводим контент файла темы(selectedDesign), отвечающего за оповещение о том, что сайт выключен(ENGINE_OFF_FILE), в случае, если такой существует
				if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE)===true)
					echo file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE);
				else
					echo self::ENGINE_OFF_HTML;
				exit;
			}
			// Если нет файла с исходной разметкой темы, выбранной для оформления(selectedDesign)
			else if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::DUMMY_FILE)===false)
				parent::hideErrorByMessage();
			else {
				// Конвертируем сериализованную строку со списком классов и методов(functional) в массив(functionalArr)
				$functional=stripslashes($functional);
				$functionalArr=@unserialize($functional);
				if ($functionalArr===false)
					parent::hideErrorByMessage();
				
				// Добавляем в свойство хранящее html-код контент файла с исходной разметкой темы(DUMMY_FILE), выбранной для оформления(selectedDesign)
				//	['html'] - весь html-код страницы
				$this->output['html']=file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::DUMMY_FILE);
				//	['headers'] - теги, которые необходимо было разместить внутри контейнера тега <head>
				$this->output['headers']=array();
				// Формируем тег <base>
				$baseTag=new Wspc__baseTagGen();
				// Вставляем в исходной html-код на определённое место({_BASE_TAG_}) сформированный тег <base>
				$this->output['html']=str_replace("{_BASE_TAG_}",$baseTag->getHtmlPiece(),$this->output['html']);
				return $functionalArr;
			}
		}
		else
			parent::hideErrorByMessage();
	}
	
	// Метод формирования html-кода(content) запрашиваемой страницы(id), путем обхода части настроек CMS(sets)
	private function walkOutGenFunctional($id,$sets,$content){
		for ($a=0,$b=count($sets);$a<$b;$a++){
			// Создаём объект на основе имени класса, которое было указано в настройках(sets[$a]['className'])
			$mod=new $sets[$a]['className']($id);
			// Обходим и вызываем все указанные в настройках методы(['ramification'])
			for ($c=0,$d=count($sets[$a]['ramification']);$c<$d;$c++){
				if ($sets[$a]['ramification'][$c]['engineSwitcher']=="on"){
					$content=$mod->{$sets[$a]['ramification'][$c]['methodName']}($content);
				}
			}
		}
		return $content;
	}
	
	// Метод формирующий наиболее важные части контента (навигации, основной заголовок, основной контент и т.п.)
	private function genContent($id,$sets){
		// Подключаем файл, в котором хранится контент запрашиваемой страницы(id)
		include self::CONTENT_PATH .$id.".php";
		// В переменной содержится имя функциональной метки({_TITLE_} и т.п.) и имя переменной из файла контента страницы, значением которой нужно заменить метку
		$vars=array("{_TITLE_}"=>"pageTitle","{_DESCRIPTION_}"=>"pageDesc","{_KEYWORDS_}"=>"pageKWords","{_HEADER_1_}"=>"pageH1","{_CONTENT_}"=>"content");
		// Формируем html-код страницы(id) путем обхода определённой части настроек CMS(walkOutGenFunctional)
		$tmpVar=$this->walkOutGenFunctional($id,$sets,array('html'=>$content,'headers'=>$this->output['headers']));
		$content=$tmpVar['html'];
		$this->output['headers']=$tmpVar['headers'];
		unset($tmpVar);
		// Заменяем в html-коде функциональные метки на значения переменных из файла с контентом страницы
		foreach ($vars as $tmpVar=>$workVar){
			$this->output['html']=str_replace($tmpVar,$$workVar,$this->output['html']);
		}
	}
	
	// Метод формирующий html-код страницы(id) на основе вариативных частей контента
	private function genEmbientContent($id,$sets){
		$this->output=$this->walkOutGenFunctional($id,$sets,$this->output);
	}
	
	// Метод осуществляющий вывод в документ сформированного html-кода
	public function echoPage(){
		echo $this->output['html'];
	}
}
?>
