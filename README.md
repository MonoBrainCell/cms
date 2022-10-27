<h1>Система управления контентом сайта или просто CMS</h1>
<ul>
    <li><a href="#languages_in_cms_info">Что использовано в CMS?</a></li>
    <li><a href="#few_words_from_developer">Пара слов о CMS от её разработчика</a></li>
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
            <li></li>
        </ul>
</ul>
