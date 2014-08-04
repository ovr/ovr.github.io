Как уже стало ясно из название статьи, мой текущий “open source commit streak” подошел к отметке 128 дней и без статьи что же я делал все это время не обойтись.

![My open source activity](http://dmtry.me/img/articles/2014/IJt3eL5acME.jpg)

#Zephir

Так как с этим языком связано большая часть моей деятельности то я озвучу его в первую очередь.
Очень приятно что язык не стоит на месте а именно мы получаем хороший feedback от пользователей что не может не радовать.
Одним из больших стремительных толчков развития языка дают [новые опенсорс проекты](https://github.com/trending?l=zephir).

##0.4.3 Alpha

Вышла новая версия 0.4.3 которая содержит ряд исправлений, а так же улучшений:

-	Fixed variables initialisation in conditions [#413](https://github.com/phalcon/zephir/issues/413)
-	Stubs generating fixes [#418](https://github.com/phalcon/zephir/issues/418), [#421](https://github.com/phalcon/zephir/issues/421), [#434](https://github.com/phalcon/zephir/issues/434)
-	Fixed is_numeric function call
-	Fixed internal CS errors [#416](https://github.com/phalcon/zephir/issues/416)
-	Fixed identical operator [#423](https://github.com/phalcon/zephir/issues/423)
-	Fixed small memory leak inside parser [#431](https://github.com/phalcon/zephir/issues/431)
-	Improve try-catch
-	Fix string constants escaping, IssetOperator with variables [#456](https://github.com/phalcon/zephir/issues/456)
-	Added support for doubles in typeof statements
-	Fixed arithmetical errors [#441](https://github.com/phalcon/zephir/issues/441)
-	Added deprecated method modifier [#462](https://github.com/phalcon/zephir/issues/462)
-	Added ability for lookup php.ini inside project [#446](https://github.com/phalcon/zephir/issues/446)

Говорим слова благодарности [@phalcon](https://github.com/phalcon/), [@nkt](https://github.com/nkt/), [@dreamsxin](https://github.com/dreamsxin/), [@christiaan](https://github.com/christiaan/), [@andycheg](https://github.com/andycheg/), [@kjdev](https://github.com/kjdev/), [@mruz](https://github.com/mruz/).

##0.4.4 Что сейчас уже сделано

- [Исправление декларации для массивов](https://github.com/phalcon/zephir/pull/458)
- [Исправление декларации для объектов](https://github.com/phalcon/zephir/issues/459)
- [Убраны авто генерируемые части из компонента Kernel](https://github.com/phalcon/zephir/pull/469)

А что еще добавиться - узнаем в ближайшем будущем.

##Zephir 0.5-dev

Язык развивается, а значит пришло время сделать значительный шаг и переписать все места для того что бы дать архитектуре новый шаг вперед.

###Разделить все на компоненты

Главной задачей является разделение zephir на компоненты и уменьшение их связей между собой.

Главными останутся

- Zephir\Core
- Zephir\Parser
- Zephir\Kernel (библиотека расширяющая возможности ядра Zend)
- Zephir\CodePrinter

В будущем это дало бы возможность замены и написание сторонних компонентов, а для примера возможность связок:

- Zephir -> PHP
- PHP -> Zephir
- Zephir & PHP -> Zephir

###Сделать псевдо AST

После некоторых обсуждение все-таки было решено что нужно забить на текущее ast json дерево и пользоваться человеческим объектным деревом.
Этот шаг позволяет стандартизировать компонент парсинга для написание различных версий под языки.

###Поддержка компиляторов (внутри ядра)

Некоторые моменты в главной библиотеке сделаны статично.
Я добавил возможность выбора компилятора исходя из config.json в корне проекта.

Пример допустим можно сменить gcc на llvm-gcc и получить программу с jit машинной благодаря llvm.

[Ветка](https://github.com/ovr/zephir/tree/0.5-dev)

##Lynx

Пару месяц назад решил о том, что текущие мною используемые ORM системы являются громоздкими и медленными.
Решение было незамедлительным - начать новый проект, в котором я объединю философию Doctrine 2, мой опыт и прекрасный язык Zephir.

[Страница проекта](http://lynx.github.io/lynx/)

[Github репозиторий](http://github.com/lynx/lynx/)

#Phalcon

Для Phalcon 2 я отправил ряд исправление тестов что позволило добиться работоспособности для версии PHP 5.6.

##Скелет приложения на Phalcon

Это совсем новый проект и поэтому он находиться на этапе зарождения архитектуры и целей.
Главная цель проекта разработать удобный каркас приложения на Phalcon с кучей поддерживаемых вещей по умолчанию.

Для начало я хочу реализовать набор модулей:

- Админ-панель
- Пользователь
- Каталог - каталог продуктов
- Корзина - модуль реализации корзины (зависимость каталога)
- OAuth - авторизация через социальные сети сети

По выпуску бета версии в проекте зародиться новая ветка с реализация работы базы данных уже на Lynx.

[Страница проекта](http://ovr.github.io/phalcon-module-skeleton/)

[Github репозиторий](https://github.com/ovr/phalcon-module-skeleton/)

#129 День

Увидел из [Твитера](https://twitter.com/phalconphp/status/495254816621600768) о том что [Rushmore Mushambi](https://github.com/rushmorem) реализовал проект [zephir-compiler](https://github.com/rushmorem/zephir-compiler) realtime build системы на bash.
Решение было незамедлительным и потратив 40 минут своего времени благодаря [React](https://github.com/reactphp/react) написал реализацию данной фичи внутри ядра Zephir.
Благодаря текущей реализации мы не тратим время на bootstrapping ядра, а также позволяет в будущем добавить возможность частичного билда (транслируем только те файлы которые были изменены).

Собственно сам FR [Watch command #472](https://github.com/phalcon/zephir/pull/472)

#Конфликты в Zephir

Очень часто стали происходить конфликты с авторами проекта что начало нагнитать обстановку.
Было решено приостановить свою активность в отношении Zephir и направить силы в ряд своих проектов (время покажет).

#Развитие блога

Да я все такие решился на ведение блога, но у меня появилась одна проблема.
Все мои статьи являются слишком узко профилированными, а написание их с обработкой и корректировкой занимает до 5 часов.
Если у вас вдруг есть интерес к просмотру статей еще не опубликованных для помощи в корректировки просьба написать мне для удобным вам способом.

Следующие статьи (c ориентировочной датой):

- Как настроить свои личные NS сервера (10 августа)
- Zephir против PHP-CPP (20 августа)
- Начало серии скринкастов о супер быстрой разработке приложений на супер быстром Phalcon (1 сентября)

Если вам интересно читать мой блог то поддержите меня своими старами на гитхаб а так же не забываем про follow (я буду крайне рад).

Увижу вас в скором времени а сейчас по традиции я говорю: "Happy Coding with Phalcon and Zephir".
