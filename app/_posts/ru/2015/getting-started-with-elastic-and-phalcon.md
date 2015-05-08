Каждый день, месяц появляются новые инструменты для разработки. На протежении 4 лет я использовал `SphinxSearch`, но на днях,
благодаря [Сергею@serebro](https://github.com/serebro), мне пришлось познакомиться с `Elasticsearch`.

# Плюсы

Я думаю, самое правильное, начать с плюсов:

+ Скорость
+ Плагины
+ Простота настройки (вспоминая sphinx не могу не нарадоваться)
+ JSON, так как протокол сделан поверх http сервера
+ Кросплатформеность (написан на Java)
+ Realtime индексация
+ Облачность
+ Открытый исходный код

# Минусы

Как таковых не нашел. Если сравнивать с SphinxSearch то он прогирывает причем без вариантов.

# Установка

Установка `ElasticSearch` не сложный процесс. Для начала нужно перейти по ссылке [http://www.elasticsearch.org/overview/elkdownloads/](http://www.elasticsearch.org/overview/elkdownloads/)

## Ubuntu\Debian

Если вы используете Debian family систему то процесс установки будет следующим:

```bash
cd /tmp
wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.3.4.deb
sudo dpkg -i elasticsearch-1.3.4.deb
sudo service elasticsearch start
```

## Mac OS X

Когда есть brew все просто:

```bash
brew install elasticsearch
```

Не забываем проверить сервер зайдя на http://localhost:9200/.

## Плагины

Elastic радует поддержкой многих функциональных плагинов к нему.

Рекомендую еще рассширения [Postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm/related?hl=en) для браузеров на основе Chromium.

# Добавление записей в индекс

Для примера создадим индекс продуктов магазина site и добавим 3 продукта

```bash
curl -XPUT 'http://localhost:9200/site/products/1' -d '
{
 "title"     : "Super product",
 "price": 12345.00
}'
curl -XPUT 'http://localhost:9200/site/products/2' -d '
{
 "title"     : "Super product 1234",
 "price": 532.00
}'
curl -XPUT 'http://localhost:9200/site/products/3' -d '
{ 
 "id"   : "1",
 "title"     : "Super product fdsfs",
 "price": 631.00
}'
```

# Сейчас найду

```bash
curl -XGET 'http://localhost:9200/site/products/_search?q=title:Super&pretty=true'
```

# Клиенты в PHP

Для того что бы использовать ElasticSearch в PHP нам нужно:

* PHP >= 5.3.3
* Composer
* Curl ext

Зайдем в корень с проектом и установим понравившийся нам клиент

```bash
composer require elasticsearch/elasticsearch
```

# А в Phalcon можно?

Для начало создадим наш сервис:

```php
$client = new \Elastica\Client($di->get('config')->elastica->toArray());
return $client;
```



# Выводы

Elasticasearch это отличнейшая замена sphinxsearch, которую уже можно взять и использовать прямо сейчас :) Выбор за тобой :3