<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_loadFiles_developer {
	const FORM_TEMPLATE_PATH="functional_departments/loadFiles/dummyes/loadFiles_block.fbd.tpl";
	const OPTGROUP_TEMP="<optgroup label='{GROUP_NAME}'>{OPTIONS}</optgroup>";
	const MAX_FILES_QUANTITY=10;
	private $htmlContent;
	private function initFunctionTargets(){
		$foldersArray=array(
			array(
				'folderName'=>'Общие изображения',
				'folders'=>array(
						'../files/media/images/'=>'Изображения'
					)
			),
			array(
				'folderName'=>'Реклама на сайте',
				'folders'=>array(
						'../files/media/banners/images/'=>'Статические баннеры (Изображения)',
						'../files/media/banners/flashes/'=>'Динамические баннеры (Анимация)',
						
					)
			),
			array(
				'folderName'=>'Галереи изображений',
				'folders'=>array(
						'../files/media/images_gallery/original/'=>'Исходные изображения (будут отображаться при изображению-обложке)',
						'../files/media/images_gallery/preview/'=>'Preview-изображения (изображения обложки)',
						'../functional/imagesGallery/css/'=>'css-файлы для адекватного отображения галереи изображений',
						'../functional/imagesGallery/javascript/'=>'javascript-файлы для адекватного отображения галереи изображений'
						
					)
			),
			array(
				'folderName'=>'Файлы для скачивания',
				'folders'=>array(
						'../files/share/'=>'Файлы, которые можно будет в дальнейшем скачать',
						'../functional/filesShare/css/'=>'css-файлы для адекватного отображения списка файлов для скачивания',
						'../functional/filesShare/javascript/'=>'javascript-файлы для адекватного отображения списка файлов для скачивания'
						
					)
			),
			array(
				'folderName'=>'Виджеты',
				'folders'=>array(
						'../functional/widgets/css/'=>'css-файлы для адекватного отображения виджетов',
						'../functional/widgets/javascript/'=>'javascript-файлы для адекватного отображения виджетов'
						
					)
			),
			array(
				'folderName'=>'Видео-вставки',
				'folders'=>array(
						'../functional/videoGallery/css/'=>'css-файлы для адекватного отображения видео-вставок',
						'../functional/videoGallery/javascript/'=>'javascript-файлы для адекватного отображения видео-вставок'
						
					)
			),
			array(
				'folderName'=>'Обратная связь',
				'folders'=>array(
						'../functional/feedback/css/'=>'css-файлы для адекватного отображения и работы формы обратной связи',
						'../functional/feedback/javascript/'=>'javascript-файлы для адекватного отображения и работы формы обратной связи',
						'../functional/feedback/php/'=>'php-файлы для адекватной обработки данных полученных из формы обратной связи'
						
					)
			),
			array(
				'folderName'=>'Калькулятор стоимости',
				'folders'=>array(
						'../functional/costEngine/css/'=>'css-файлы для адекватного отображения и работы он-лайн калькулятора стоимости',
						'../functional/costEngine/javascript/'=>'javascript-файлы для адекватного отображения и работы он-лайн калькулятора стоимости',
						'../functional/costEngine/php/'=>'php-файлы для адекватной обработки данных полученных из калькулятора стоимости'
						
					)
			),
			array(
				'folderName'=>'Новости',
				'folders'=>array(
						'../functional/news/css/'=>'css-файлы для адекватного отображения новостей на сайте',
						'../functional/news/javascript/'=>'javascript-файлы для адекватного отображения новостей на сайте'
						
					)
			),
			array(
				'folderName'=>'Гостевая книга',
				'folders'=>array(
						'../functional/guestbook/css/'=>'css-файлы для адекватного отображения гостевой книги сайта',
						'../functional/guestbook/javascript/'=>'javascript-файлы для адекватного отображения гостевой книги сайта',
						'../functional/guestbook/php/'=>'php-файлы для адекватной обработки данных добавленных в гостевую книгу'
					)
			)
		);
		return $foldersArray;
	}
	private function generateOptions($arr){
		$str="";
		for ($a=0,$b=count($arr);$a<$b;$a++){
			$str.=self::OPTGROUP_TEMP;
			$str=str_replace("{GROUP_NAME}",$arr[$a]['folderName'],$str);
			$opts="";
			foreach ($arr[$a]['folders'] as $value=>$content){
				$opts.="<option value='{$value}'>{$content}</option>\r\n";
			}
			$str=str_replace("{OPTIONS}",$opts,$str);
		}
		return $str;
	}
	public function __construct(){
		if (file_exists(self::FORM_TEMPLATE_PATH)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if (isset($_POST['controlElem'])===true){
			$varObj=new App_loadFiles_filesManager();
			exit;
		}
		$GETInf=new manipulateGETInf();
		$msg=$GETInf->searchGETElem("msg");
		switch ($msg){
			case "params_failed":// не соответствует число параметров (опред. директории и файла)
				$errMsg="<p><span class='pay_attention'>Произошёл сбой загрузки данных: были переданы не все данные или данные были переданы с ошибкой</span>. Обновите страницу в браузере и повторите попытку</p>";
			break;
			
			case "failed_some_files":// не все файлы загрузились
				$errMsg="<p>Были загружены не все файлы";
				if (isset($_SESSION['failedFiles'])===true){
					$ffl=array();
					for ($a=0,$b=count($_SESSION['failedFiles']);$a<$b;$a++){
						$ffl[]=$_SESSION['failedFiles'][$a];
					}
					$fflStr=implode(", ",$ffl);
					$errMsg.=" :<span class='pay_attention'>{$fflStr}</span></p>";
					unset($_SESSION['failedFiles']);
				}
				else 
					$errMsg.="</p>";
			break;
			case "success":// загрузка прошла успешно
				$errMsg="<p><span class='pay_attention'>Все файлы были загружены успешно</span></p>";
			break;
			default:
				$errMsg="";
		}
		
		$this->htmlContent=file_get_contents(self::FORM_TEMPLATE_PATH);
		$this->htmlContent=str_replace("{ERRORS_MESSAGE}",$errMsg,$this->htmlContent);
		$this->htmlContent=str_replace("{MAX_FILES_QUANTITY}",self::MAX_FILES_QUANTITY,$this->htmlContent);
		$this->htmlContent=str_replace("{MAX_FILESIZE}",ini_get("upload_max_filesize"),$this->htmlContent);
		$this->htmlContent=str_replace("{DIRECTORIES}",self::generateOptions(self::initFunctionTargets()),$this->htmlContent);
	}
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>