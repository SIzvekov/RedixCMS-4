<?php
/* RedixCMS 4.0
Файл конфигурации
*/

error_reporting(0);//E_ALL & ~E_NOTICE); // выводить ошибки в браузер: 0 - нет, E_ALL - все

/* определяем переменные базы данных */
define(DB_TYPE,"mysql"); // тип базы данных (mysql, mssql и т.п.)
define(DB_HOST,"localhost"); // хост подключения к БД
define(DB_LOGIN,"root"); // логин базы данных
define(DB_PASSWORD,""); // пароль базы данных
define(DB_TABLE,""); // базa данных
define(DB_PREFIX,"sys_"); // основной, системный префикс базы данных (обычно пустой)
define(DB_CACHE,0); // кэшировать ли запросы БД, 1 - да, 0 - нет
define(DB_CACHE_TTL,0); // время жизни закешированных запросов в секундах
///////////////////////////////////////

define(NO_LICENS_USE,1); // кэшировать ли страницы

define(PAGE_CACHE,0); // кэшировать ли страницы
define(PAGE_CACHE_TTL,0); // время жизни закешированных страниц

define(COLONPAGE_DEFAULT,"10"); // лицензия ЦМСки

define(DOCUMENT_ROOT,$_SERVER['DOCUMENT_ROOT']); // документ рут файлов движка, обычно совпадает с $_SERVER['DOCUMENT_ROOT']
define(HTTP_HOST,$_SERVER['HTTP_HOST']); // хост текущего сайта
define(REQUEST_URI,$_SERVER['REQUEST_URI']); // REQUEST_URI

define(ADMINDIRNAME,"admin"); // имя папки админа
define(REDIX_API_KEY,""); // api ключ для работы с iRedix

$ver = file(DOCUMENT_ROOT."/version.txt");
define(CMS_VERSION,trim($ver[0])); // тип базы данных (mysql, mssql и т.п.)
define(ADM_VERSION,trim($ver[1])); // тип базы данных (mysql, mssql и т.п.)

define(USE_IMG_RESIZER,1); // использовать или нет ресайзер
define(IMG_MAX_W,1200); // максимальная ширина, которую можно установить для ресайза
define(IMG_MAX_H,700); // максимальная высота, которую можно установить для ресайза
define(JPG_DEF_QUALITY,80); // качество jpg по-умолчанию
define(PNG_DEF_QUALITY,1); // качество png по-умолчанию, менять смысла не вижу, при =1 качество такое-же, как при 9, а размер гораздо меньше

$_LANGS = array('ru');
?>