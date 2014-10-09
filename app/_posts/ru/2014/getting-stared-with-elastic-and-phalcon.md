Каждый день, месяц появляеются новые инструменты для разработки. На протежении 4 лет я использовал sphinxsearch но на днях
мне благодаря @serebro мне пришлось познакомиться с elasticsearch.

# Плюсы

Я думаю само правильно начать с плюсов

+ Скорость
+ Плагины
+ Простота настройки (вспоминая sphinx не могу не нарадываться)
+ JSON так как протокол сделан поверх Http сервера
+ Кросплатформеность (написан на Java)
+ Realtime индексация
+ Облачность
+ Открытый исходный код

# Минусы

# Установка

Установка elastic не сложный процесс. Для начало нужно перейти по ссылке http://www.elasticsearch.org/overview/elkdownloads/

## Ubuntu\Debian

Если вы используете Debian style систему то процесс установки будет следующим

```bash
cd /tmp
wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.3.4.deb
sudo dpkg -i elasticsearch-1.3.4.deb
sudo service elasticsearch start
```

## Mac OS X

Когда есть brew все просто

```bash
brew install elasticsearch
```

Не забываем проверить сервер зайдя http://localhost:9200/.

## Плагины

Elastic радует поддержкой много функциональных плагинов к нему.

Рекомендую еще рассширения (Postman)[https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm/related?hl=en] для браузеров на основе Chromium.

# Добавление записей в индекс

Для примера создадим индекс продуктов магазина site и добавим туда 3 продукта

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

# А в Phalcon можно?

# Выводы



