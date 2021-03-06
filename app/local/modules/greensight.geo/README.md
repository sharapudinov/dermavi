## Геолокация по IP

### Установка

1. Переходим в директорию где хранятся модули, например: `cd local/modules`
2. Клонируем модуль командой `git clone git@gitlab.com:greensight/bitrix-geo.git greensight.geo` Получившаяся директория должна называться greensight.geo, иначе Битрикс будет её игнорировать.
2. Устанавливаем через /bitrix/admin/partner_modules.php?lang=ru
3. Заполняем таблицы данными из файлов запустив скрипт `php -f public/local/modules/greensight.geo/console/update_database.php`

### Использование

```php
Loader::includeModule('greensight.geo')
$info = \Greensight\Geo\Location::getInstance()->getInfoFromDatabase();
var_dump($info);
```

```php
array:14 [▼
  "id" => "93496"
  "city_id" => "2097"
  "country_name" => null
  "city_name" => "Москва"
  "region_name" => "Москва"
  "district_name" => "Центральный федеральный округ"
  "country_code" => "RU"
  "city_code" => null
  "region_code" => null
  "lat" => "55.7558"
  "lon" => "37.6176"
  "zip" => null
  "isp" => null
  "timezone" => null
]
```

Первым аргументом `getInfoFromDatabase()` можно перезаписать IP по которому происходит геолокация (по-умолчанию используется $_SERVER["REMOTE_ADDR"])

Второй аргумент - массив дополнительных параметров. На данный момент, в нем может быть лишь параметр `cache` в котором указывается количество минут на которое будет закэширован результат геолокации.
Так как по-умолчанию геолокация это по-сути SELECT LIMIT 1 по индексу и выполнятеся только при первом заходе пользователя, то обычно кэширование не требуется.
Однако если вы используете свой собственный провайдер и особенно если он не использует локальную базу а запрашивает данные напрямую у онлайн сервиса, то в этом случае кэширование вам может очень и очень пригодиться.

Пример кэширования на час:

```php
$info = \Greensight\Geo\Location::getInstance()->getInfoFromDatabase($ip, ['cache' => 60]);
```

### Битриксовые Местоположения (в эксперементальном режиме)

Передав в `getInfoFromDatabase()` `$params['include_bitrix_location'] = true` можно получить в результате дополнительную информацию из Битриксовой базы местоположений если они у вас установлены и настроены.
Эти данные будут в качестве массива в поле `'bitrix_location'` результата.

Стоит учитывать, что названия населенных пунктов в геобазе и в базе битриксовых местоположений никогда не будет совпадать на 100%. Из-за этого битриксовые местоположения могут не находиться.


### Обновление базы данных IpGeoBase

Модуль хранит базу для геолокации в локальной БД (битриксовая Mysql), поэтому время от времени их надо актуализировать.
К счастью он умеет это делать в автоматическом режиме, так что возиться с этим не надо.
Надо всего лишь добавить `php -f app/local/modules/greensight.geo/console/update_database.php` на запуск кроном раз в неделю или месяц.
Имейте ввиду что при обновлении таблиц они полностью очищаются и заполняются данными снова, так что секунд 30 они будут пустыми.
Крайне рекомендуется поставить автообновление на ночь.

База обновляется только для текущего активного провайдера (как поменять его - читайте ниже). Можно обновить и для другого передав в качестве параметра полное имя класса-провайдера
Например:
```php
php -f console/update_database.php \\Greensight\\Geo\\Providers\\IpApiProvider
```

### Проверка IP

Можно быстро проверить какие данные отдает База по ip адресу
`php -f app/local/modules/greensight.geo/console/test_ip.php 109.205.253.38`

Результат - вывод массива описанного в разделе использование.


### Дополнительные заготовки

Самый распостраненный кейс: у нас есть справочник городов-регионов(в виде элементов инфоблока/разделов инфоблоков/элементов хайлоадблоков или чего-то еше), и мы хотим чтобы
1. При заходе пользователю автоматически проставлялся его город, если он есть в этом справочнике (а если нет, то город по-умолчанию)
2. Была возможность поменять город в шапке сайта.

Для упрощения реализации этой функциональности есть заготовки в директории `greensight.geo/extra`
Из-за того, что справочники могут быть реализованы по-разному, в этих заготовках есть участки (методы) помеченные как `TBD`
Их нужно доделать.

1. `request_hooks/SetRegion.php` - хук который решает первую задачу. Его добавить в `app/local/php_interface/request_hooks.php` в блок `OnBeforeProlog`
2. `components/region.select` - компонент который решает вторую задачу.

### Собственные провайдеры геолокации

По-умолчанию модуль работает с базой геолокации `ipgeobase.ru`, но если она вас по каким-то причинам не устраивает, вы можете расширить модуль собственным провайдером геолокации

Для этого вам нужно:
1. создать класс-провайдер 

```php
class SomeNewProvider implements \Greensight\Geo\Providers\ProviderInterface
```

2. Реализовать в нём все методы требуемые интерфейсом. Скорее всего вам потребуются новые таблицы в БД сайта.
Формат ответа должен полностью соответсвовать формату массива приведенного в качестве примера в начале этого документа.  Если провайдер не предоставляет каких-либо полей, то их нужно заполнить как `null`
3. Зарегистировать его в `init.php` (или в `SetRegion` хуке) для использования вместо `IpGeoBaseProvider`


```php
if (Loader::includeModule('greensight.geo')) {
    \Greensight\Geo\Location::getInstance()->setProvider(new SomeNewProvider());
};
```

Если у вас получился провайдер, который имеет смысл переиспользовать на других проектах - присылайте пулл-реквест!

### Список провайдеров которые уже есть в пакете (лежат в пространстве имён `Greensight\Geo\Providers`)

1. `IpGeoBaseProvider` - провайдер по-умолчанию. Использует локальную базу скачиваемую с http://ipgeobase.ru/
2. `IpApiProvider` - использует *онлайн базу* http://ip-api.com/. Можно выставить максимальную задержку в секундах через `$params['timeout']`, по-умолчанию 10 секунд
3. `GeoLite2Provider` - Использует локальную базу скачиваемую с http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip Это довольно тяжелая база (несмотря на приписку Lite), но по нашим кейсам она даёт лучшую точность чем приведенные `IpGeoBaseProvider` и `IpApiProvider`. Обновление занимает ~7 минут