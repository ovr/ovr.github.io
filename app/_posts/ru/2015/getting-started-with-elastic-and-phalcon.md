Каждый день, месяц появляются новые инструменты для разработки. На протяжении 4 лет я использовал `Sphinxsearch`, но на днях,
благодаря [Сергею@serebro](https://github.com/serebro), мне пришлось познакомиться с `Elasticsearch`.

Сегодня я буду рассказывать вам о новом поисковом движке `Elasticsearch`,
который становиться стандартом в выборе поискового движка или NoSQL базы данных для хранения данных в целях текстового поиска по ним.

Сайт проекта [http://www.elasticsearch.org/](http://www.elasticsearch.org/).

По сути — это новый фронтенд к широко известному индексу Lucene.
Главное отличие от конкурентов — это гибкость и простота в использовании. Работа с сервисом происходит в виде общения с помощью REST HTTP интерфейса.

# Плюсы

Я думаю, самое правильное, начать с плюсов:

+ Скорость
+ Плагины
+ Простота настройки (вспоминая sphinx не могу не нарадоваться)
+ JSON, так как протокол сделан поверх HTTP сервера
+ Кроссплатформенность  (написан на Java)
+ Realtime индексация
+ Облачность
+ Открытый исходный код

# Минусы

Как таковых не нашел. Если сравнивать с `Sphinxsearch`, то он проигрывает, причем без вариантов.

# Установка

Установка `Elasticsearch` не сложный процесс. Для начала нужно перейти по ссылке [http://www.elasticsearch.org/overview/elkdownloads/](http://www.elasticsearch.org/overview/elkdownloads/)

## Ubuntu\Debian

Если вы используете Debian family систему то процесс установки будет следующим:

```bash
cd /tmp
wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.5.2.deb
sudo dpkg -i elasticsearch-1.5.2.deb
sudo service elasticsearch start
```

## Mac OS X

Когда есть brew все просто:

```bash
brew install elasticsearch
```

Не забываем проверить сервер зайдя на [http://localhost:9200/](http://localhost:9200/).
Используя `curl`

```sh
curl -X GET http://localhost:9200/
```

## Плагины

`Elasticsearch` радует поддержкой многих функциональных плагинов к нему.

Самые популярные:

[Kibana](https://github.com/elastic/kibana) - это админ панель для анализа аналитики и поиска.

[Elasticsearch Gui](https://github.com/jettro/elasticsearch-gui) - это GUI написанный на AngularJS.

Рекомендую еще расширение [Postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm/related?hl=en) для браузеров на основе Chromium (для легкого создания запросов).

# Пробуем в деле по HTTP REST

### Добавление записей в индекс

Для примера создадим индекс продуктов магазина site и добавим 3 продукта:

```bash
curl -XPUT 'http://localhost:9200/site/products/1' -d '
{
    "title": "Super product",
    "price": 12345.00
}'
curl -XPUT 'http://localhost:9200/site/products/2' -d '
{
    "title": "Super product 1234",
    "price": 500.00
}'
curl -XPUT 'http://localhost:9200/site/products/3' -d '
{ 
    "id": "1",
    "title": "Super product fdsfs",
    "price": 500.00
}'
```

### Поиск

Ищем все продукты с названием Super:

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=title:Super&pretty=true'
```

Подсчитываем количество продуктов с ценой равной 500

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=price:500&pretty=true'
```

# Клиенты в PHP

Для того что бы использовать ElasticSearch в PHP нам нужно:

* PHP >= 5.3.9
* Composer
* Curl ext

Зайдем в корень с проектом и установим понравившийся нам клиент, мой выбор это

```bash
composer require ruflin/elastica
```

## Пример использование

После установки библиотеке создадим клиент:

```
<?php
require 'vendor/autoload.php';

$client = new \Elastica\Client();
```

### Добавление документа (пример Twit)

```php
$id = 1;

$tweet = array(
    'id'      => $id,
    'user'    => array(
        'name'      => 'mewantcookie',
        'fullName'  => 'Cookie Monster'
    ),
    'msg'     => 'Me wish there were expression for cookies like there is for apples. "A cookie a day make the doctor diagnose you with diabetes" not catchy.',
    'tstamp'  => '1238081389',
    'location'=> '41.12,-71.34',
    '_boost'  => 1.0
);

$tweetDocument = new \Elastica\Document($id, $tweet);

// Добавление твита в тип
$elasticaType->addDocument($tweetDocument);

// Обновление индекса
$elasticaType->getIndex()->refresh();
```

### Поиск

```php
$query = new Elastica\Query();

$query
    ->setFrom(50)
    ->setSize(10)
    ->setSort(['price' => 'asc'])
    ->setFields(['id', 'title', 'price'])])
    ->setExplain(true)
    ->setVersion(true)
    ->setMinScore(0.5);

$client->setQuery($query);

var_dump($search->count()); // Кол-во элементов по запросу

/** @var \Elastica\ResultSet */
$resultSet = $search->search();

foreach ($search->scanAndScroll() as $scrollId => $resultSet) {
    // ... handle Elastica\ResultSet
}
```

Лучше всего смотреть все в [документации](http://elastica.io/api/namespaces/Elastica.html).

# Использование в Phalcon

Для начало создадим наш сервис:

```php
$client = new \Elastica\Client($di->get('config')->elastica->toArray());
return $client;
```

Установим библиотеку для легкой работы:

```bash
composer require ovr/phalcon-elasticsearch
```

Создадим модель:

```php
<?php

namespace Catalog\Model;

use Elastica\Exception\NotFoundException;
use Elastica\Query;

use Phalcon\DI;
use Phalcon\DI\Injectable;

class Product extends Injectable
{
    use ElasticModelTrait;

    protected static $index = 'my-project';
    protected static $type = 'products';

    public $data;

    /**
     * @param array $query
     * @param array $options
     * @return \Elastica\ResultSet
     */
    public static function find(array $query = [], array $options = null)
    {
        return static::getStorage()->search($query, $options);
    }
}
```

Просто запрос:

```php
$query = [
    '_source' => [
        'id',
        'title'
    ],
    'size' => 5,
];

var_dump(Product::find($query)->getResults());
```

Хорошим примером работы с `Elasticsearch` будет проект [http://phalconist.com/](https://github.com/phalconist/phalconist).

# Русское сообщество

#### [Поисковый движок «Elasticsearch» во ВКонтакте](https://vk.com/elastic_search).

# Выводы

`Elasticsearch` - это отличнейшая замена `Sphinxsearch`, которую уже можно взять и использовать прямо сейчас :) Выбор за тобой :3
