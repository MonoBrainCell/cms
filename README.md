<h1>Система управления контентом сайта или просто CMS</h1>
<ul>
    <li><a href="#languages_in_cms_info">Что использовано в CMS?</a></li>
    <li><a href="#few_words_from_developer">Пара слов о CMS от её разработчика</a></li>
    <li><a href="#files_structure_info">Структура CMS</a></li>
</ul>
<h2 id="languages_in_cms_info">Что использовано в CMS?</h2>
<ol>
    <li>PHP>=7.0</li>
    <li>javascript(jQuery>=3.0)</li>
</ol>
<h2 id="few_words_from_developer">Пара слов о CMS от её разработчика (т.е. от меня)</h2>
<ol>
    <li>Я разрабатывал данную CMS, около 7-8 лет назад</li>
    <li>Я хотел создать систему управления контентом не связанную с БД</li>
    <li>Что следует из п.1 - все данные CMS хранятся в отдельных файлах</li>
    <li>На данный момент я не занимаюсь улучшением этой CMS</li>
    <li>С позиции моих текущих опыта и знаний: улучшение этой CMS в рамках той идеи, которая указана в п.2, до оптимального состояния привела бы к изменению порядка 90% всего исходного кода</li>
    <li>Зачем же я её выложил? Ответ прост: чтобы подтвердить тот факт, что я разработал свою собственную CMS с 0</li>
</ol>
<h2 id="files_structure_info">Структура CMS <small>(см. директорию <a href="https://github.com/MonoBrainCell/cms/tree/main/src" target="_blank">src</a>)</small></h2>
<ul>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/.htaccess" target="_blank">.htaccess</a> - настройка сервера для текущего сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/ajaxRqst.php" target="_blank">ajaxRqst.php</a> - php-файл, работающий как обработчик всех ajax-запросов внутри сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/download.php" target="_blank">download.php</a> - php-файл, отвечающий за реализацию функционала выгрузки файлов для посетителей страницы
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/guestbook.php" target="_blank">guestbook.php</a> - php-файл, отвечающий за реализацию функционала страницы отзывов
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/index.php" target="_blank">index.php</a> - php-файл, отвечающий за генерацию основных страниц сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/navigation.fbd.xml" target="_blank">navigation.fbd.xml</a> - файл, содержащий информацию о всех существующих страницах сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/news.php" target="_blank">news.php</a> - php-файл, отвечающий за реализацию функционала новостных страниц
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/settings.fbd.php" target="_blank">settings.fbd.php</a> - php-файл, хранящий информацию основную информацию, используемую в процессе генерирования страниц сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/sitemap.xml" target="_blank">sitemap.xml</a> - карта сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/content" target="_blank">content/</a> - папка, в которой находятся файлы, содержащие контент отдельных страниц сайта
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/design" target="_blank">design/</a> - папка, в которой находятся файлы тем оформления сайта
        <ul>
            <li><strong>имя_темы/</strong> - папки в которых хранятся файлы, используемые для оформления страниц, в рамках целевой темы
                <ul>
                    <li><strong>dummy.fbd.html</strong> - файл с разметкой, используемой при отображении контента страницы в рамках целевой темы</li>
                </ul>
            </li>
            <li>...</li>
        </ul>
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files" target="_blank">files/</a> - папка для файлов, используемых в контенте сайта
        <ul>
            <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/media" target="_blank">media/</a> - папка для файлов изображений и анимации
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/media/images" target="_blank">images/</a> - папка для отдельных изображений</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/media/images_gallery" target="_blank">images_gallery/</a> - папка для изображений, используемых в галерее
                        <ul>
                            <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/media/images_gallery/original" target="_blank">original/</a> - папка для изображений оригинального размера</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/media/images_gallery/preview" target="_blank">preview/</a> - папка для изображений уменьшенного размера (preview-изображения)</li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/files/share" target="_blank">share/</a> - папка для файлов, которые можно предоставлять для выгрузки посетителям сайта</li>
        </ul>
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/functional" target="_blank">functional/</a> - папка, в которой находятся исполняемые файлы отдельных частей функционала CMS
        <ul>
            <li><strong>имя_функционала/</strong> - папки в которых хранятся файлы, относящиеся к отдельному фунционалу CMS
                <ul>
                    <li><strong>funcEngine.php</strong> - основной файл, к которому обращается CMS, в момент использования данного функционала</li>
                </ul>
            </li>
            <li>...</li>
        </ul>
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/jq_libs" target="_blank">jq_libs/</a> - папка, в которой находятся js-файлы, используемые в лицевой части(frontend) сайта
        <ul>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/jq_libs/collectFormElems.func.js" target="_blank">collectFormElems.func.js</a> - js-файл, содержащий функции, используемые для работы с полями формы</li>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/jq_libs/sendAjaxRqst.func.js" target="_blank">sendAjaxRqst.func.js</a> - js-файл, содержащий функции, используемые для реализации ajax-запросов</li>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/jq_libs/textFieldsManager.plugin.js" target="_blank">textFieldsManager.plugin.js</a> - js-плагин, используемый для полноценной проверки текстовых полей формы</li>
        </ul>
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/workspace" target="_blank">workspace/</a> - папка, содержащая ядро движка CMS
        <ul>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/workspace/baseTagGen.php" target="_blank">baseTagGen.php</a> - генератор тега &lt;base&gt;</li>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/workspace/siteEngine.php" target="_blank">siteEngine.php</a> - движок</li>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/workspace/unescaped_file.php" target="_blank">unescaped_file.php</a> - файл, содержащий некоторые доп. элементы функционала, используемые движком(например, реализация автоподгрузки классов, обработчик ошибок, получение данных из GET)</li>
        </ul>
    </li>
    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office" target="_blank">office/</a> - папка, с админ. частью CMS
        <ul>
            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/enter.php" target="_blank">enter.php</a> - php-файл, отвечающий за функционал авторизации в админ.части(страница+обработка данных)</li>
             <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/execAjaxRqst.php" target="_blank">execAjaxRqst.php</a> - php-файл, работающий как обработчик всех ajax-запросов, выполненных в рамках админ. части CMS</li>
             <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/index.php" target="_blank">index.php</a> - основной генератор страниц всех разделов админ. части CMS</li>
             <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/index.php" target="_blank">index.php</a> - основной генератор страниц всех разделов админ. части CMS</li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/adminTemplate" target="_blank">adminTemplate/</a> - папка с темой для отображения страниц админ. части
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/adminTemplate/main_dummy.html" target="_blank">main_dummy.html</a> - файл с базовой разметкой страниц админ. части</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/adminTemplate/htmlDummyes" target="_blank">htmlDummyes/</a> - папка с частями разметки страниц админ. части
                        <ul>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/adminTemplate/htmlDummyes/admin_navigation.fbd.tpl" target="_blank">admin_navigation.fbd.tpl</a> - файл с навигацией по админ. части, отображающаяся на всех страницах кроме стартовой</li>
                        </ul>
                    </li>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/adminTemplate/style" target="_blank">style/</a> - папка с настройками оформления админ. части</li>
                </ul>
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/functional_departments" target="_blank">functional_departments/</a> - папка содержащая редакторы для отдельных элементов функционала CMS(управление контентом страниц, реклама, новости и т.п.)
                <ul>
                     <li><strong>имя_папки</strong> - папка, содержащая элементы управления определённым функционалом CMS
                        <ul>
                            <li><strong>имя.php</strong> - файл отвечающий за отображаение страниц, предоствляющих возможность управления отдельными элементами функционала</li>
                            <li><strong>augmentation/</strong> - папка, содержащая php-файлы, используемые при формировании страниц отдельных "ветвей" управления отдельным функционалом, а также при обработке отдельных ajax-запросов по сохранению внесённых изменений</li>
                            <li><strong>design/</strong> - папка, содержащая настройки отображения страниц отдельных "ветвей" управления отдельным функционалом</li>
                            <li><strong>dummyes/</strong> - папка, содержащая фрагменты разметки, используемые для отображения управляющих элементов на отдельных страницах "ветвей" управления функционалом</li>
                            <li><strong>executors/</strong> - папка, содержащая файлы, исполняемые в результате ajax-запросов, при сохранении измений связанных с отдельным функционалом CMS</li>
                            <li><strong>ramification/</strong> - папка, содержащая файлы, исполняемые при формировании страниц отдельных "ветвей" управления отдельным функционалом</li>
                            <li><strong>settings/</strong> - папка, содержащая файлы, хранящие информацию о том, как должна быть сформирована страница отдельной "ветви" управления отдельным функционалом</li>
                        </ul>
                    </li>
                    <li>...</li>
                </ul>
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/gatekeeper" target="_blank">gatekeeper/</a> - папка содержащая файлы, используемые в процессе проверки доступа пользователей админ. части CMS
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/gatekeeper/abcqwerty.fbd.dat" target="_blank">abcqwerty.fbd.dat</a> - файл, содержащий ключ шифрования для проверки данных передаваемых при помощи сессии</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/gatekeeper/genNewKey.php" target="_blank">genNewKey.php</a> - файл, генерирующий случайный пароль</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/gatekeeper/users.fbd.xml" target="_blank">users.fbd.xml</a> - файл, хранящий информацию о зарегистрированных пользователях админ. части CMS, уровне их доступа и ограничениях, соответствующих различным уровням доступа</li>
                </ul>
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/information_base" target="_blank">information_base/</a> - папка содержащая файлы с различной доп. информацией для админ. части CMS
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/information_base/domain.txt" target="_blank">domain.txt</a> - файл, хранящий текущее доменное имя сайта (в случае его смены произойдёт автоматическое исправление содержания карты сайта и самого этого файла, с учётом нового домена)</li>
                </ul>
            </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/jq_libs" target="_blank">jq_libs/</a> - папка содержащая, используемую в админ. части библиотеку jQuery
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/specials" target="_blank">specials/</a> - папка, содержащая ядро админ. части CMS
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/form_to_enter.php" target="_blank">form_to_enter.php</a> - файл, участвующий в формировании формы входа в админ. часть CMS</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/pageOutput.php" target="_blank">pageOutput.php</a> - файл, используемый для подготовки и вывода всех страниц управления функционалом в админ. части</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/unescaped_file.php" target="_blank">unescaped_file.php</a> - файл, содержащий некоторые доп. элементы, используемые в админ. части CMS(например, реализация автоподгрузки классов, обработчик ошибок, получение данных из GET)</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/specials/jss" target="_blank">jss/</a> - папка, содержащая js-файлы, используемые на любой странице админ. части CMS
                        <ul>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/jss/attachElemToWindow.plugin.js" target="_blank">attachElemToWindow.plugin.js</a> - js, управляющий прикреплением элементов разметки в момент прокрутки страницы</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/jss/fields_manager.js" target="_blank">fields_manager.js</a> - js, управляющий проверкой полей формы авторизации в админ.части</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/jss/navigation_manager.js" target="_blank">navigation_manager.js</a> - js, управляющий поведением навигации</li>
                        </ul>
                    </li>
                </ul>
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/subsidiary" target="_blank">subsidiary/</a> - папка, содержащая файлы, используемые в качестве поддержки процесса управления функционалом в админ. части CMS
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/subsidiary/js" target="_blank">js/</a> - папка, содержащая js-файлы
                        <ul>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/attachEventsToBoxOfFiles.func.js" target="_blank">attachEventsToBoxOfFiles.func.js</a> - js, содержащий функции, используемые для добавления и отслеживания событий к модальному окну выбора файлов</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/collectFormElems.func.js" target="_blank">collectFormElems.func.js</a> - js, содержащий функции, используемые для работы с элементами формы</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/fullControlElements.func.js" target="_blank">fullControlElements.func.js</a> - js, содержащий функции, используемые для реализации функционала отдельных панелей управления на страницах админ. части (например, панель позволяющая управлять элементом навигации(изменять положение относительно других, назначать род. элемент и т.д.))</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/galleryControl.func.js" target="_blank">galleryControl.func.js</a> - js, содержащий функции, используемые для управления редактором галерей изображений</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/sendAjaxRqst.func.js" target="_blank">sendAjaxRqst.func.js</a> - js, содержащий функции, используемые для осуществления ajax-запросов в рамках админ. части CMS</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/js/textFieldsManager.plugin.js" target="_blank">sendAjaxRqst.func.js</a> - js, для полной проверки полей формы страниц админ. части</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/subsidiary/js/tiny_mce" target="_blank">tiny_mce/</a> - папка с реализацией WYSIWYG-редактора TinyMCE</li>
                        </ul>
                    </li>
                    <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/subsidiary/php" target="_blank">php/</a> - папка, содержащая php-файлы
                        <ul>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/php/baseTagGen.php" target="_blank">baseTagGen.php</a> - генератор тега &lt;base&gt; для админ. части</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/php/listOfFiles.php" target="_blank">listOfFiles.php</a> - файл, используемые для формирования списка файлов для модальных окон выбора файлов, при редактировании в админ. части</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/php/listOfPages.php" target="_blank">listOfPages.php</a> - файл, используемые для формирования списка страниц для модальных окон выбора страниц сайта, при редактировании в админ. части</li>
                            <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/subsidiary/php/tinymceImagesList.php" target="_blank">tinymceImagesList.php</a> - файл, используемые для формирования списка изображений, используемых для добавления в контент страниц (для редактора TinyMCE)</li>
                        </ul>
                    </li>
                </ul>
             </li>
             <li><a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/workspace" target="_blank">workspace/</a> - папка, содержащая файлы, используемые при работе во время авторизации в админ. части пользователей, а также при проверке действий уже авторизовавшихся пользователей
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/workspace/Gatekeeper.php" target="_blank">Gatekeeper.php</a> - файл, используемый, в качестве распорядителя в процессе мониторинга пользователей и авторизации (проверка уровня доступа, проверка метки авторизации, переадресация на страницу входа в адми. и т.п.)</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/workspace/MasterEncryption.php" target="_blank">MasterEncryption.php</a> - файл, используемый, в качестве управляющего процесса шифрования/дешифрования авторизационных данных</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/workspace/SafeguardMaster.php" target="_blank">SafeguardMaster.php</a> - файл, используемый для непосредственной проверки данных</li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
