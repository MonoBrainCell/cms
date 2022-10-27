<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
$totalArray=array(
	'templateSearch'=>array(
		array(
			'name'=>'Навигация сайта',
			'className'=>'Pre__navigation',
			'ramification'=>array(
				array(
					'name'=>'Основная навигация',
					'methodName'=>'getMainNavigation',
					'engineSwitcher'=>"on",
					'switcherFixed'=>"yes"
				),
				array(
					'name'=>'Дополнительная навигация',
					'methodName'=>'getAdvNavigation',
					'engineSwitcher'=>"on"
				),
				array(
					'name'=>'Путь от главной',
					'methodName'=>'getPathFromMain',
					'engineSwitcher'=>"on"
				),
				array(
					'name'=>'Список стоящих рядом страниц',
					'methodName'=>'getRelatives',
					'engineSwitcher'=>"on"
				)
			)
		),
		array(
			'name'=>'Реклама на сайте',
			'className'=>'Pre__ad',
			'ramification'=>array(
				array(
					'name'=>'Реклама на отдельной странице',
					'methodName'=>'getLocalAdv',
					'engineSwitcher'=>'on'
				),
				array(
					'name'=>'Реклама на сайте',
					'methodName'=>'getGlobalAdv',
					'engineSwitcher'=>'on',
					'switcherFixed'=>"yes"
				)
			)
		),
		array(
			'name'=>'Виджеты',
			'className'=>'Pre__widgets',
			'ramification'=>array(
				array(
					'name'=>'Вывод виджетов на сайте',
					'methodName'=>'getWidgets',
					'engineSwitcher'=>'on'
				)
			)
		)
	),
	'mainEngineSearch'=>array(
		array(
			'name'=>'Галерея изображений',
			'className'=>'Pre__imagesGallery',
			'ramification'=>array(
				array(
					'name'=>'Отображение галерей изображений на сайте',
					'methodName'=>'getGalleries',
					'engineSwitcher'=>'on'
				)
			)
		),
		array(
			'name'=>'Вставка видео',
			'className'=>'Pre__videoGallery',
			'ramification'=>array(
				array(
					'name'=>'Отображение видео на сайте',
					'methodName'=>'getVideos',
					'engineSwitcher'=>'on'
				)
			)
		),
		array(
			'name'=>'Раздача файлов',
			'className'=>'Pre__filesShare',
			'ramification'=>array(
				array(
					'name'=>'Отображение списков файлов для скачивания',
					'methodName'=>'getFilesLists',
					'engineSwitcher'=>'on'
				)
			)
		),
		array(
			'name'=>'Обратная связь',
			'className'=>'Pre__feedback',
			'ramification'=>array(
				array(
					'name'=>'Вывод формы обратной связи',
					'methodName'=>'getFeedbackForm',
					'engineSwitcher'=>'on'
				)
			)
		),
		array(
			'name'=>'Калькулятор стоимости',
			'className'=>'Pre__costEngine',
			'ramification'=>array(
				array(
					'name'=>'Вывод калькулятора стоимости',
					'methodName'=>'getcostEngineForm',
					'engineSwitcher'=>'off',
					'switcherFixed'=>"off"
				)
			)
		)
	),
	'enclaveEngines'=>array(
		array(
			'name'=>'Новости',
			'engineName'=>'news.php',
			'engineSwitcher'=>'off'
		),
		array(
			'name'=>'Гостевая книга',
			'engineName'=>'guestbook.php',
			'engineSwitcher'=>'off'
		)
	)
);
file_put_contents("originalInform.fbd.dat",addslashes(serialize($totalArray)));
?>