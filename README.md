**Кастомизация Yii2**

_Основной конфиг проекта:_
common/config/common.php

У каждой точке входа может быть аналогичный конфиг, например:
frontend/config/common.php

Так же у каждого модуля должен быть свой конфиг, например: 
common/modules/page/config/common.php
В нём должен быть настроен данный модуль

Так же для каждого файла конфига могут быть локальные настройки в файле common-local.php
Если эти файлы присутствуют в папке с конфигом, то они так же загрузятся. Данные локальные файлы имеет смысл добавлять в игнорируемые git-ом.

В основном конфиге проекта должны быть следующие параметры:
params
--- localTimezoneOffset - смещение локальной таймзоны, которая берётся по умолчанию при форматировании даты-времени
--- baseDomain          - основной домен сайта (без поддоментов, присущих точкам входа). Например: vasya.ru
                          Даже если у проекта есть поддомены типа: backend.vasya.ru, то здесь всё равно нужно указывать vasya.ru.

_Автокомплит модулей:_

Чтобы работал автокомплит при работе с модулями, т.е. при вводе выражения типа Yii::$app->moduleManager->modules->... чтобы выводился список модулей, необходимо:
 1. Скопировать файлы: 
    core/Yii.php
    core/ModuleManager.php
    core/ModuleManagerModules.php
    в ваш проект. Изменить из namespace в соответствии с каталогом, в котором они будут располагаться.
    Указать, чтобы они наследовались от тех файлов, с которых были скопированы.
 2. В скопированном файле Yii.php в php-док класса Application заменить в строчке:
    @property-read \YiiCustom\core\ModuleManager $moduleManager
    Имя класса ModuleManager на класс, который вы скопировали (вместе с namespace).
    Аналогично заменить ModuleManager ссылку на класс ModuleManagerModules.
 3. В скопированном классе ModuleManagerModules указать в phpdoc все модули.

_Маршрутизация:_
В каждом папке конфигов - config (там, где хранятся конфиги проекта или модуля) так же может располагаться конфиг роутов маршрутизации. Назвать его следует:
url-rules.php

Пример содержимого такого файла:
<?php
return [
    'backend' => [
        [
            'pattern' => '',
            'route'   => 'home/index',
            'suffix' => '/',
        ],
    ],
];
?>

_ACL:_
В каждй папке конфига так же можно добавить конфиги для разделения прав.
Файл acl_permissions.php: указываются права, названия и правила их проверки. Например:

<?php
return [
    [
        'permission' => Game::P_VIEW_PRODUCT,
        'name'       => 'Товары - Просмотр товара',
        'rule'       => [
            'class' => PublishedProductRule::class,
        ],
    ],
    [
        'permission' => Game::P_VIEW_OWN_PRODUCT,
        'name'       => 'Товары - Просмотр своего товара',
        'rule'       => [
            'class' => AuthorRule::class,
        ],
    ],
    [
        'permission' => Game::P_ADD_PRODUCT,
        'name'       => 'Товары - Создание товара',
    ],
];?>

Файл acl_assignments.php: указываются назначаемые права для ролей. Например:

<?php
return [
	[
		'role'       => AclHelper::ROLE_BUYER,
		'permission' => Game::P_VIEW_PRODUCT,
	],
	[
		'role'       => AclHelper::ROLE_BUYER,
		'permission' => Game::P_VIEW_OWN_PRODUCT,
	],
	[
		'role'       => AclHelper::ROLE_SELLER,
		'permission' => Game::P_VIEW_PRODUCT,
	],
	[
		'role'       => AclHelper::ROLE_SELLER,
		'permission' => Game::P_VIEW_OWN_PRODUCT,
	],
]?>

Все эти настройки будут обновлены при выполнении команды консольной acl-generate/index
Так же для первичной настройки следуте выполнить команду 
acl-init/create-acl-tables
Для создания таблиц acl. А команду
acl-init/remove-acl-tables
Для удаления таблиц acl.