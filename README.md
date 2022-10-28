<h1>Система управления контентом сайта или просто CMS</h1>
<ul>
    <li><a href="#languages_in_cms_info">Что использовано в CMS?</a></li>
    <li><a href="#few_words_from_developer">Пара слов о CMS от её разработчика</a></li>
    <li><a href="#files_structure_info">Структура CMS</a></li>
    <li><a href="#principles_of_operation_of_cms">Принципы работы CMS</a>
        <ul>
            <li><a href="#front_part_of_cms">&quot;Лицевая&quot; часть CMS</a></li>
            <li><a href="#back_part_of_cms">Административная часть CMS</a></li>
        </ul>
    </li>
    <li><a href="#cms_functional_list">Функционал CMS</a></li>
    <li><a href="#install_and_access_to_admin_part">Установка CMS и доступ в админ. часть</a></li>
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
    <li>Что следует из п.2 - все данные CMS хранятся в отдельных файлах</li>
    <li>На данный момент я не занимаюсь улучшением этой CMS</li>
    <li>С позиции моих текущих опыта и знаний: улучшение этой CMS в рамках той идеи, которая указана в п.2, до оптимального состояния привела бы к изменению порядка 90% всего исходного кода</li>
    <li>Зачем же я её выложил? Ответ прост: чтобы подтвердить тот факт, что я разработал свою собственную CMS с нуля</li>
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
<h2 id="principles_of_operation_of_cms">Принципы работы CMS</h2>
<h3 id="front_part_of_cms">&quot;Лицевая&quot; часть CMS (генерирование страниц)</h3>
<ul>
    <li>Контент и оформление генерируемых страниц хранятся раздельно (контент: папка <a href="https://github.com/MonoBrainCell/cms/blob/main/src/content" target="_blank">content</a>, оформление: папка <a href="https://github.com/MonoBrainCell/cms/blob/main/src/design" target="_blank">design</a>)</li>
    <li>В процессе генерирования страницы происходит слияние разметки, соответствующей активной теме, с контентом запрашиваемой страницы</li>
    <li>Слияние происходит на основе использования функциональных меток, указанных в разметке темы (например, <strong>{_TITLE_}</strong> будет заменена на имя страницы; <strong>{_HEADER_1_}</strong> будет заменена на основной заголовок страницы и т.п.)</li>
    <li>Формирование финальных частей контента, которые будут заменять функциональные метки происходит при помощи отдельных функциональных элементов CMS (папка <a href="https://github.com/MonoBrainCell/cms/tree/main/src/functional" target="_blank">functional</a>)</li>
    <li>Каждый функциональный элемент CMS представлен в виде отдельного класса, содержащего определённые методы, которые и вызываются генератором страниц для формирования полноценного контента</li>
    <li>При генерировании контента, CMS определяет набор используемых функциональных элементов и их методов, исходя из их полного списка, хранящегося в файле настроек CMS(<a href="https://github.com/MonoBrainCell/cms/blob/main/src/settings.fbd.php" target="_blank">settings.fbd.php</a>); также в этом списке хранится информация о том является ли тот или иной метод функционала активным, т.е. будет ли он вызываться в процессе генерирования контента</li>
    <li>В рамках CMS существует 2 типа страниц:
        <ol>
            <li>обычные страницы - генерирование которых происходит стандартными средствами CMS (файл <a href="https://github.com/MonoBrainCell/cms/blob/main/src/index.php" target="_blank">index.php</a>)</li>
            <li>страницы &quot;анклавы&quot; - генерирование таких страниц осуществляется отдельными файлами, но в рамках функционала и настроек самой CMS
                <ul>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/news.php" target="_blank">news.php</a> - новостной блок CMS</li>
                    <li><a href="https://github.com/MonoBrainCell/cms/blob/main/src/guestbook.php" target="_blank">guestbook.php</a> - страница отзывов</li>
                </ul>
            </li>
        <ol>
    </li>
</ul>
<h3 id="back_part_of_cms">Административная часть CMS (управление контентом)</h3>
<ul>
    <li>Доступ к административной части
        <ul>
            <li>Осуществляется через указание логина и пароля пользователя и проверки их на соответствие существующей информации (логины и пароли хранятся в <a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/gatekeeper/users.fbd.xml" target="_blank">office/gatekeeper/users.fbd.xml</a>)</li>
            <li>Реализация страницы входа и проверки данных осуществляется при помощи файла <a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/gatekeeper/users.fbd.xml" target="_blank">office/enter.php</a></li>
            <li>За осуществление полноценной проверки и подтверждения авторизации отвечают классы, файлы которых хранятся в <a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/workspace" target="_blank">office/workspace/</a></li>
        </ul>
    </li>
    <li>Управление контентом
        <ul>
            <li>Управление отдельными частями контента CMS осуществляется отдельными фунциональными группами (см. папки внутри <a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/functional_departments" target="_blank">office/functional_departments/</a>)</li>
            <li>Внутри каждой функциональной группы имеется &quot;корневой&quot; php-файл, который отвечает за формирование страницы с полным списком доступных действий для редактирования контента
            </li>
            <li>Файлы, формирующие страницы с редакторами различных элементов целевой части контента располагаются в папке <strong>ramification/</strong></li>
            <li>Каждый из вышеперечисленных файлов действует следующим образом: внутри него подключается файл с настройками, определяющими как необходимо сформировать страницу, в т.ч. и какие классы использовать для формирования редакционной части (данные классы расположены в отдельных файлах в папке <strong>augmentation</strong>), данные настройки передаются объекту класса отвечающего за формирование разметки всех страниц админ. части (<a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/specials/pageOutput.php" target="_blank">Spl__pageOutput</a>)</li>
            <li>Сохранение внесённых изменений осуществляется при помощи ajax-запросов, отправляемых в единый php-обработчик ajax-запросов для админ. части(<a href="https://github.com/MonoBrainCell/cms/blob/main/src/office/execAjaxRqst.php" target="_blank">office/execAjaxRqst.php</a>), который в свою очередь подключит для обработки запроса целевой исполнительный файл, соответствующий части контента и действию над ним (см. папку <strong>executors</strong>, внутри папок функциональных групп)</li>
            <li>В отдельных случаях редактирования конетнта требуются дополнительные данные или манипуляции, такие элементы подключаются из папки <a href="https://github.com/MonoBrainCell/cms/tree/main/src/office/subsidiary" target="_blank">office/subsidiary</a></li>
        </ul>
    </li>
</ul>
<h2 id="cms_functional_list">Функционал CMS</h2>
<ul>
    <li>Управление страницами
        <ul>
            <li>Добавление</li>
            <li>Изменение</li>
            <li>Удаление</li>
        </ul>
    </li>
    <li>Управление навигационными элементами (2 навигации: основная и дополнительная)
        <ul>
            <li>Изменение порядка следования пунктов навигации</li>
            <li>Назначение родительского пункта/Извлечение из родительского пункта навигации</li>
            <li>Изменение названия страницы в навигации</li>
            <li>Изменение alias'а страницы в навигации</li>
            <li>Перемещение пунктов навигации между блоками (основным и дополнительным)</li>
        </ul>
    </li>
    <li>Управление оформлением
        <ul>
            <li>Выбор активной темы</li>
            <li>Правка разметки активной темы</li>
        </ul>
    </li>
    <li>Управление рекламным контентом
        <ul>
            <li>Добавление баннеров</li>
            <li>Изменение баннеров</li>
            <li>Изменение списка страниц на которых демонстрируются отдельные баннеры</li>
            <li>Удаление баннеров</li>
        </ul>
    </li>
    <li>Управление новостным контентом
        <ul>
            <li>Добавление</li>
            <li>Изменение</li>
            <li>Удаление</li>
        </ul>
    </li>
    <li>Предоставление страницы отзывов</li>
    <li>Управление галереями изображений
        <ul>
            <li>Добавление</li>
            <li>Изменение</li>
            <li>Удаление</li>
        </ul>
    </li>
    <li>Управление видео контентом
        <ul>
            <li>Добавление</li>
            <li>Изменение источника видео</li>
            <li>Удаление</li>
        </ul>
    </li>
    <li>Управление виджетами
        <ul>
            <li>Добавление</li>
            <li>Изменение кода вставки виджета</li>
            <li>Удаление</li>
        </ul>
    </li>
    <li>Предоставление функционала обратной связи</li>
    <li>Управление выгрузкой файлов
        <ul>
            <li>Добавление списков файлов доступных для выгрузки</li>
            <li>Изменение списков файлов доступных для выгрузки</li>
            <li>Удаление списков файлов доступных для выгрузки</li>
        </ul>
    </li>
    <li>Загрузка файлов для различных функциональных частей CMS через админ. часть</li>
    <li>Управление файлами отдельных функц. частей через админ. часть
        <ul>
            <li>Изменение имен файлов</li>
            <li>Копирование файлов</li>
            <li>Перемещение файлов</li>
            <li>Удаление файлов</li>
        </ul>
    </li>
    <li>Управление учетными записями админ. части CMS
        <ul>
            <li>Добавление учетных записей</li>
             <li>Изменение логина учетной записи</li>
             <li>Изменение пароля учетной записи</li>
             <li>Изменение типа доступа для учетной записи</li>
            <li>Удаление учетных записей</li>
        </ul>
    </li>
    <li>Управление функциональными элементами CMS
        <ul>
            <li>Выключение функц. элементов из формирование страниц сайта</li>
             <li>Включение функц. элементов в формирование страниц сайта</li>
        </ul>
    </li>
    <li>Управление работой движка CMS (Вкл/Выкл)</li>
</ul>
<h2 id="install_and_access_to_admin_part">Установка CMS и доступ в админ. часть</h2>
<ul>
    <li><strong>Установка:</strong>
    <br>
    Просто выложить все файлы cms в корневую папку сайта на сервере
    </li>
    <li><strong>Путь к админ. части:</strong>
    <br>
    доменное-имя/office
    </li>
    <li><strong>Логин:</strong> trustee
    <br>
    <strong>Пароль:</strong> permit
    </li>
</ul>