<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class sendMessageClass extends ErrorsManager {
	const RECORDS_LIST_FILE="functional/guestbook/recordsList.fbd.csv";
	const LIMITER=100;
	const LIMIT_OF_ITEMS_IN_PAGE=10;
	const SITEMAP_FILE="sitemap.xml";
	//---
	const EMAIL_ADDRESS="sg.forever@yandex.ru";
	const ERROR_BUTTON="<span id='ajax_close_button'>Вернуться к странице</span>\r\n";
	const SUCCESS_BUTTON="<a class='buttons_from_script' href='guestbook.php'>Вернуться к странице</a>";
	const EMAIL_STYLE_FILE="functional/guestbook/css/emailSendStyle.css";
	const CONTENT_HEADER="Content-type: text/html; charset=utf-8\r\n";
	const MESSAGE_THEME="Добавление в гостевую книгу сайта {DOMEN_NAME}";
	const EMAIL_CONTENT="
{MESSAGE_STYLE}\r\n<h2>Добавление в гостевую книгу</h2>\r\n
<p>В гостевую книгу сайта была добавлена новая запись. Зайдите в административную часть сайта, чтобы ознакомиться с записью и информацией о лице оставившем её.</p>\r\n
";
	//---
	const SIMPLE_TEXT_PATTERN="/[^0-9а-яА-Яa-zA-Z\-\.\(\)\s]/u";
	const SIMPLE_EMAIL_PATTERN="/[^0-9a-zA-Z\-\.\_\@]/u";
	const STRING_EMAIL_PATTERN="/^[a-zA-Z0-9\.\-\_]+\@[a-zA-Z0-9\-]+\.[a-zA-Z]+/u";
	const SIMPLE_MESSAGE_PATTERN="/[^0-9а-яА-Яa-zA-Z\.\,\!\+\-\_\*\(\)\=\:\;\'\"\?\s]/u";
	
	private $dataCheckSettings;
	private $datasToRewrite=array();
	
	public function __construct() {
		$this->dataCheckSettings=array(
			'name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>60,
				'minLenght'=>3,
				'fieldName'=>'Ваше имя'
			),
			'email'=>array(
				'pattern'=>self::SIMPLE_EMAIL_PATTERN,
				'patternStr'=>self::STRING_EMAIL_PATTERN,
				'maxLenght'=>120,
				'fieldName'=>'Ваш E-mail'
			),
			'message'=>array(
				'pattern'=>self::SIMPLE_MESSAGE_PATTERN,
				'minLenght'=>5,
				'maxLenght'=>150,
				'fieldName'=>'Ваше сообщение'
			)
		);
	}
	
	public function saveEngine() {mb_regex_encoding("UTF-8");
		if (
		isset($_POST['send_datas'][0])===false
		||
		(isset($_POST['send_datas'][0])===true && mb_strlen(trim($_POST['send_datas'][0]))<1)
		){
			echo "<span class='error_class'>Произошла ошибка при отправке данных. Перезагрузите страницу и повторите попытку.</span>\r\n".self::ERROR_BUTTON;
			exit;
		}
		foreach ($this->dataCheckSettings as $field=>$properties){
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::ERROR_BUTTON;
				exit;
			}
			if (isset($properties['pattern'])===true && preg_match($properties['pattern'],$_POST[$field][0])===1 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::ERROR_BUTTON;
				exit;
			}
			if (isset($properties['patternStr'])===true && preg_match($properties['patternStr'],$_POST[$field][0])===0 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>Поле <q>{$properties['fieldName']}</q> было заполнено с ошибками (Не соответствует указанному шаблону заполнения).</span>\r\n".self::ERROR_BUTTON;
				exit;
			}
			if (isset($properties['minLenght'])===true && mb_strlen(trim($_POST[$field][0]))<$properties['minLenght']){
				echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::ERROR_BUTTON;
				exit;
			}
			if (isset($properties['maxLenght'])===true && mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght']){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::ERROR_BUTTON;
				exit;
			}
			$this->datasToRewrite[$field]=trim($_POST[$field][0]);
		}
		$this->datasToRewrite['message']=nl2br($this->datasToRewrite['message'],FALSE);
		$this->datasToRewrite['message']=str_replace("\r","",$this->datasToRewrite['message']);
		$this->datasToRewrite['message']=str_replace("\n","",$this->datasToRewrite['message']);
		$rewriteDatas=self::searchParams('new');
		self::rewriteRecordsFile($rewriteDatas);
		self::rewriteSitemap($rewriteDatas['newId'],$rewriteDatas['stringNum'],count($rewriteDatas['fileArray']));
		self::sendMessage();
		echo "<span class='success_class'>Ваша запись добавлена. Спасибо за Ваше мнение!</span>\r\n".self::SUCCESS_BUTTON;
		exit;
	}
	
	private function sendMessage(){
		$theme=str_replace("{DOMEN_NAME}",$_SERVER['HTTP_HOST'],self::MESSAGE_THEME);
		if (file_exists(self::EMAIL_STYLE_FILE)===true)
			$style="<style type='text/css'>\r\n".file_get_contents(self::EMAIL_STYLE_FILE)."</style>\r\n";
		else
			$style="";
		$content=str_replace("{MESSAGE_STYLE}",$style,self::EMAIL_CONTENT);
		@mail(self::EMAIL_ADDRESS,$theme,$content,self::CONTENT_HEADER);
	}
	
	private function searchParams($id){
		if (file_exists(self::RECORDS_LIST_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой в работе сайта. Попробуйте отсавить Ваше сообщение немного позднее.</span>\r\n".self::ERROR_BUTTON;
			exit;
		}
		$fileArray=file(self::RECORDS_LIST_FILE);	
		$returnDatas=array(); $returnDatas['fileArray']=array(); $idArray=array();$stringsArray=array(); $maxIndex=1;
		if (empty($fileArray)===true || count($fileArray)<1) {
			$returnDatas['newId']=1;
			$returnDatas['stringNum']=0;
			$returnDatas['fileArray']=array();
		}
		else {
			for ($a=0,$b=count($fileArray);$a<$b;$a++){
				if ($id=="new" && $b==self::LIMITER){
					if ($a===0 && isset($marker)===false){
						$elem=explode(";;",$fileArray[$a]);
						$returnDatas['newId']=$elem[0];
						$returnDatas['stringNum']=self::LIMITER - 1;
						$marker=true;
					}
					else {
						$returnDatas['fileArray'][]=$fileArray[$a];
					}
				}
				else {
					$elem=explode(";;",$fileArray[$a]);
					if ($id=="new"){
						$idArray[]=$elem[0]*1;
						$stringsArray[$elem[0]]=$a;
						if ($maxIndex<$elem[0])
							$maxIndex=$elem[0];
					}
					else {
						if ($elem[0]==$id){
							$returnDatas['newId']=$elem[0];
							$returnDatas['stringNum']=$a;
						}
					}
					$returnDatas['fileArray'][]=$fileArray[$a];
				}
			}
			if ($maxIndex!==1){
				for ($a=1;$a<=$maxIndex;$a++){
					if (array_search($a,$idArray,TRUE)===false){
						$returnDatas['newId']=$a;
						$successMarker=true;
						break;
					}
				}
			}
			if (isset($successMarker)===false){
				if ($maxIndex!==1 || empty($idArray)===false)
					$returnDatas['newId']=$maxIndex+1;
				else
					$returnDatas['newId']=$maxIndex;
				$returnDatas['stringNum']=$b;
			}
			else{
				if ($id=="new")
					$returnDatas['stringNum']=$b;
				else
					$returnDatas['stringNum']=$stringsArray[$returnDatas['newId']];
			}
		}
		return $returnDatas;
	}
	
	private function rewriteRecordsFile($fileArray){
		$date=date('d-m-Y');
		$stringReplace="{$fileArray['newId']};;{$this->datasToRewrite['name']};;{$date};;{$this->datasToRewrite['email']};;{$this->datasToRewrite['message']}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		if (@file_put_contents(self::RECORDS_LIST_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при сохранении Вашей записи. Обновите страницу и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function rewriteSitemap($id,$strId,$strQuantity){
		if (file_exists(self::SITEMAP_FILE)===false)
			return false;
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/guestbook.php?gId=";
		$lastmodDate=date("c");
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$sitemap->load(self::SITEMAP_FILE);
		$itemPosition=ceil( ($strId+1) / self::LIMIT_OF_ITEMS_IN_PAGE);

		$urlsetList=$sitemap->getElementsByTagName("urlset");
		$urlset=$urlsetList->item(0);
		$urlsList=$sitemap->getElementsByTagName("url");
		
		if (
		(($strId+1)<self::LIMITER && $strId%self::LIMIT_OF_ITEMS_IN_PAGE !==0)
		||
		(($strId+1)==self::LIMITER && $strQuantity<self::LIMITER)
		){
			foreach ($urlsList as $simpleTag){
				$urlTag=$simpleTag->getElementsByTagName("loc");
				$loc=$urlTag->item(0);
				if ($loc->nodeValue==$domainName_group.$itemPosition){
					$timeTag=$simpleTag->getElementsByTagName("lastmod");
					$tTag=$timeTag->item(0);
					$tTag->nodeValue=$lastmodDate;
					unset($tTag);unset($timeTag);
					break;
				}
			}
		}
		else if ($strQuantity!=self::LIMITER) {
			$url=$sitemap->createElement('url');
			$locTag=$sitemap->createElement('loc',$domainName_group.$itemPosition);
			$TimeTag=$sitemap->createElement('lastmod',$lastmodDate);
			$urlset->appendChild($url);
			$url->appendChild($locTag);
			$url->appendChild($TimeTag);
		}
		else {
			foreach ($urlsList as $simpleTag){
				$urlTag=$simpleTag->getElementsByTagName("loc");
				$loc=$urlTag->item(0);
				for ($a=1,$b=ceil(self::LIMITER / self::LIMIT_OF_ITEMS_IN_PAGE);$a<=$b;$a++){
					if ($loc->nodeValue==$domainName_group.$a){
						$timeTag=$simpleTag->getElementsByTagName("lastmod");
						$tTag=$timeTag->item(0);
						$tTag->nodeValue=$lastmodDate;
						unset($tTag);unset($timeTag);
						break;
					}
				}
			}
		}
		
		@$sitemap->save(self::SITEMAP_FILE);
	}
	
}
$varObj=new sendMessageClass();
$varObj->saveEngine();
?>