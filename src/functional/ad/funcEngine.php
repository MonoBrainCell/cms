<?php
// Основной класс функционала отображения рекламы
class Pre__ad{
	// Путь к файлу, в котором хранится список всех баннеров
	const LIST_FILE="functional/ad/bannersList.fbd.csv";
	// Путь к файлу, в котором хранится html-шаблон отображения баннеров
	const TEMPLATE_FILE="functional/ad/dummyes/bannersTpls.fbd.tpl";
	// Путь к папке, в которой хранятся html-коды отдельных баннеров
	const CONTENT_FILE_PATH="functional/ad/content/";
	// Разделитель для частей html-шаблона баннеров
	const LEVEL1_SEPARATOR="|--|";
	// Значение, указывающее на то, что баннер должен отображаться на всех страницах
	const THROUGH_MARKER="through";
	// Значение, указывающее на то, что в качестве баннера добавляется html-код 
	const CODE_INS_MARKER="codeIns";
	
	private $pageId;// id текущей страницы
	private $tmpArr=array();// массив для элементов html-шаблона
	private $constructError=false;// флаг ошибки
	private $allReadyGened=false;// флаг, указывающий, что баннеры уже сформированы
	
	public function __construct($id){
		// Проверяем наличие файла со списком баннеров и файла с html-шаблоном, если один из них отсутствует - меняем флаг ошибки на true
		if (file_exists(self::LIST_FILE)===false || file_exists(self::TEMPLATE_FILE)===false)
			$this->constructError=true;
		// Добавляем в объект информацию о текущей странице(->pageId) фрагменты html-шаблона отображения блока баннеров(->tmpArr)
		else {
			$this->pageId=$id;
			$strTmp=file_get_contents(self::TEMPLATE_FILE);
			list($this->tmpArr['block'],$this->tmpArr['wrapper'],$this->tmpArr['imgTmp'],$this->tmpArr['txtTmp'])=explode(self::LEVEL1_SEPARATOR,$strTmp);
		}
	}
	
	// Метод формирующий список баннеров, отображаемых только на текущей странице
	public function getLocalAdv($html){
		// Добавляем в html-код страницы блок баннеров (->genBannersContent)
		$html['html']=$this->genBannersContent($html['html'],true);
		return $html;
	}
	
	// Метод формирующий список баннеров, отображаемых на всех страницах
	public function getGlobalAdv($html){
		// Добавляем в html-код страницы блок баннеров (->genBannersContent)
		$html['html']=$this->genBannersContent($html['html']);
		return $html;
	}
		// P.s. наличие двух методов, обращающихся к одному общему(->genBannersContent), связано с тем, что для каждая отдельная часть функционала (getLocalAdv И getGlobalAdv) может быть отключена от выполнения в настройках CMS
	
	// Метод добавляющий html-код баннеров в html-код страницы
	//	html - текущий html-код страницы
	//	fullList - флаг, указывающий на необходимость сверить id текущей страницы и список страниц, к которым привязан баннер (если имеет true)
	private function genBannersContent($html,$fullList=false){
		// Если все баннеры сформированы, возвращаем переданный html-код
		if ($this->allReadyGened===true)
			return $html;
		// Если возникла ошибка заменяем функц. метку блока баннеров({_ADVERTISEMENT_}) на пустую строку
		if ($this->constructError===true){
			$html=str_replace("{_ADVERTISEMENT_}","",$html);
			return $html;
		}
		// Пытаемся построчно считать контент файла со списком баннеров в массив
		$origArr=file(self::LIST_FILE);
		// Если контент получить не удалось или файл оказался пустой, то заменяем функц. метку блока баннеров({_ADVERTISEMENT_}) на пустую строку
		if ($origArr===false || empty($origArr)===true || strlen(trim($origArr[0]))<1){
			$html=str_replace("{_ADVERTISEMENT_}","",$html);
			$this->allReadyGened=true;
			return $html;
		}
		$string="";// html-код всего блока баннеров(будет формироваться по ходу цикла, реализованного ниже)
		$insert=false;// Флаг, указывающий нужно ли добавлять текущий баннер
		// Обходим циклом список всех баннеров(origArr)
		for ($a=0,$b=count($origArr);$a<$b;$a++){
			// разбиваем строку с информацией о баннере на части
			//	[0] - номер
			//	[1] - название
			//	[2] - данные баннера(на их основе формируется html-код баннера ИЛИ codeIns - вставка существуюшего кода)
			//	[3] - список страниц, к которым привязан баннер ИЛИ through(для отображения на всех страницах)
			$elem=explode(";;",rtrim($origArr[$a]));
			// Если баннер должен отображаться на всех страницах
			if ($elem[3]==self::THROUGH_MARKER)
				$insert=true;
			// Если нужно проверить привязан ли баннер к текущей странице
			else if ($fullList!==false && $elem[3]!=self::THROUGH_MARKER){
				// Страницы в списке разделены ",,". Разбиваем строку на массив и проверяем наличие имени текущей страницы в списке
				$list=explode(",,",$elem[3]);
				if (array_search($this->pageId,$list,true)!==false)
					$insert=true;
				else
					$insert=false;
			}
			else
				$insert=false;
			// Если флаг на добавление баннера установлен в true
			if ($insert===true){
				// Добавляем к уже существующему html-коду баннеров html-код шаблона оболочки одиночного баннера(['wrapper'])
				$string.=$this->tmpArr['wrapper'];
				// Определяем как нужно формировать html-код баннера
				// Если код баннера должен быть взят из файла
				if ($elem[2]==self::CODE_INS_MARKER){
					// Проверяем наличие целевого файла и получаем его контент
					if (file_exists(self::CONTENT_FILE_PATH .$elem[0].".fbd.tpl")===true)
						$content=file_get_contents(self::CONTENT_FILE_PATH .$elem[0].".fbd.tpl");
					else
						$content="";
				}
				// Если код должен быть сформирован из имеющихся данных
				else {
					// Проверяем на соответствие рег. выражению
					//	Пример ожидаемой строки: a,,b,,c,,d ИЛИ ,,b,,,, и т.п.
					// Если совпадение найдено, то формируем html-код баннера(->genBannerCode)
					if(preg_match("/.*\,{2}.*\,{2}.*\,{2}.*/",$elem[2])===1)
						$content=$this->genBannerCode(explode(",,",$elem[2]));
					else
						$content="";
				}
				// Заменяем в html-коде блока баннеров функ. метку контента баннера({CONTAINER_CONTENT}) на сформированный html-код
				$string=str_replace("{CONTAINER_CONTENT}",$content,$string);
			}
		}
		// Заменяем в html-шаблоне блока баннеров функ. метку контента блока({CONTAINER_CONTENT}) на сформированный html-код баннеров
		$string=str_replace("{CONTAINER_CONTENT}",$string,$this->tmpArr['block']);
		// Заменяем в html-коде страницы функ. метку блока баннеров({_ADVERTISEMENT_}) на полученный html-код
		$html=str_replace("{_ADVERTISEMENT_}",$string,$html);
		$this->allReadyGened=true;
		
		return $html;
	}
	
	// Метод формирующий html-код баннера на основе его данных(arr)
	private function genBannerCode($arr){
		// Определяем какой тип html-шаблона баннера необходимо использовать
		// Если путь к изображению баннера не указан, то используем шаблон текста
		if (strlen(trim($arr[2]))<1)
			$string=$this->tmpArr['txtTmp'];
		// Если путь к изображению баннера указан, то используем шаблон изображения
		else {
			$string=$this->tmpArr['imgTmp'];
			$marker=true;// Указывает на то, что был использован шаблон изображения
		}
		// Формируем html-код баннера, заменяя функ. метки в шаблоне на значения, полученные из данных
		//	{TITLE_HINT} - контент всплывающей подсказки(атрибут title)
		//	{HINT} - текст ссылки(для тексового шаблона) / значение атрибута alt(для шаблона изображения)
		//	{TARGET_HREF} - адрес, на который должен вести баннер
		//	{IMAGE_ADDR} - (только для шаблона изображения) адрес изображения
		$string=str_replace("{TITLE_HINT}",$arr[0],$string);
		$string=str_replace("{HINT}",$arr[1],$string);
		$string=str_replace("{TARGET_HREF}",$arr[3],$string);
		if (isset($marker)===true)
			$string=str_replace("{IMAGE_ADDR}",$arr[2],$string);
		// Заменяем в html-шаблоне оболочи одиночного баннера функ. метку контента({CONTAINER_CONTENT}) на сформированный html-код
		$string=str_replace("{CONTAINER_CONTENT}",$string,$this->tmpArr['wrapper']);
		
		return $string;
	}
}
?>