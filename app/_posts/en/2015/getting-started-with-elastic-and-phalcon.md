Every day, month, We see new new tools for development. For 4 years I have used `Sphinxsearch`, but last days,
with [Sergei @serebro](https://github.com/serebro)'s help, I started to use `Elasticsearch`.

Сегодня я буду рассказывать вам о новом поисковом движке `Elasticsearch`,
который становиться стандартом в выборе поискового движка или NoSQL базы данных для хранения данных в целях текстового поиска по ним.

Website [http://www.elasticsearch.org/](http://www.elasticsearch.org/).

But at all, `Elasticsearch` is a frontend for popular index `Lucene`.
The main difference from competitors are flexibility and easiness. Working with service is really easy, because We are communicating via REST HTTP interface.

# Advantages

+ Perfromance
+ Plugins
+ Easy to configure (It's not a sphinx :D)
+ JSON format, because We are communicating via REST HTTP
+ Cross-platform  (written in Java)
+ Realtime indexation
+ Cloudiness (It's realy easy to setup new nodes)
+ Open-source

# Negative

I did not find anything, but if We are going to compared with `Sphinxsearch`:  `Sphinxsearch` is not okey, without any ways (configuration, morphology, setting indexation, not supported communicating via REST HTTP).

# Setting

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

Brew way like milky way :3

```bash
brew install elasticsearch
```

Не забываем проверить сервер зайдя на [http://localhost:9200/](http://localhost:9200/).
We can use `curl`

```sh
curl -X GET http://localhost:9200/
```

## Plugins

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

### Search

Ищем все продукты с названием Super:

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=title:Super&pretty=true'
```

Подсчитываем количество продуктов с ценой равной 500

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=price:500&pretty=true'
```

# Clients for PHP

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

### Adding document

```php
$id = 1;

$product = array(
    'id'      => $id,
    'title'   => 'test product',
    'price'   => 12345.00,
    '_boost'  => 1.0
);

$productDocument = new \Elastica\Document($id, $product);

// Add the product
$elasticaType->addDocument($productDocument);

// Update index
$elasticaType->getIndex()->refresh();
```

### Search

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

var_dump($search->count()); // Elements count (integer)

/** @var \Elastica\ResultSet */
$resultSet = $search->search();

foreach ($search->scanAndScroll() as $scrollId => $resultSet) {
    // ...
}
```

Лучше всего смотреть все в [документации](http://elastica.io/api/namespaces/Elastica.html).

# Working in Phalcon


We need to install PHP library to work with it:

```bash
composer require ovr/phalcon-elasticsearch
```

Next, create our service:

```php
$client = new \Elastica\Client($di->get('config')->elastica->toArray());
return $client;
```

Create model:

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

Simple query:

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

# Findings

`Elasticsearch` - это отличнейшая замена `Sphinxsearch`, которую уже можно взять и использовать прямо сейчас :) Выбор за тобой :3
