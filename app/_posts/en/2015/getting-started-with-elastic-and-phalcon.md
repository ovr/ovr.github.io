Every day, month, We see new new tools for development. For 4 years I have used `Sphinxsearch`, but last days,
with [Sergei @serebro](https://github.com/serebro)'s help, I started to use `Elasticsearch`.

Today I'll tell you about `Elasticsearch`,
which is becoming to be the standard in the choice of search engine, or NoSQL database to store data for next full text search on them.

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

Installing the `Elasticsearch` is not a hard process. First you need to click on the link [http://www.elasticsearch.org/overview/elkdownloads/](http://www.elasticsearch.org/overview/elkdownloads/).

## Ubuntu\Debian

If you are using Debian family system:

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

Do not forget to check the server by open [http://localhost:9200/](http://localhost:9200/).
We can use `curl`

```sh
curl -X GET http://localhost:9200/
```

## Plugins

Most popular:

[Kibana](https://github.com/elastic/kibana) - admin-panel for analysis analytics and search.

[Elasticsearch Gui](https://github.com/jettro/elasticsearch-gui) - GUI on AngularJS.

I hightly recommend [Postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm/related?hl=en), it's an exstension for browsers based on Chromium (Easy way to create REST API calls by GUI).

# Try in on HTTP REST

### Adding documents into index

For example, I am going to create an index and add 3 documents into It:

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

Searching all products with title = `Super`:

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=title:Super&pretty=true'
```

Counting products with price = `500`:

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=price:500&pretty=true'
```

# Clients for PHP

In order to use ElasticSearch with PHP, We need:

* PHP >= 5.3.9
* Composer
* Curl ext

Okey, will change our dir to project's root dir and install PHP client:

```bash
composer require ruflin/elastica
```

## Example of usage

After installing the library will create a client:

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

The best way to learn it, will be [Docs](http://elastica.io/api/namespaces/Elastica.html).

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

A good example of working with `Elasticsearch` will be [http://phalconist.com/](https://github.com/phalconist/phalconist) project.

# Findings

`Elasticsearch` is an excellent replacement for `Sphinxsearch`, which it is already possible to pick up and use right now :) Try it :3
