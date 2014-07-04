В середине мая нас встречает большое обновление языка Zephir с номером 0.4.2 alpha. Очень давно мы уже не делали обновление версий, но сегодня это случилось :)

## Что нового добавлено?

-	Поддержка функции конструкции eval
-	Поддержка PHP 5.6
-	Поддержка property-string-access ( $class->{"property"} )
-	Поддержка Exit и Die
-	Поддержка ArrayObject
-	Поддержка PdoStatement
-	Поддержка ErrorException
-	Stubs\Generator док блоки
-	ArrayType builtin method reversed and rev alias

## Что исправлено?

-	Поддержка мультистрочности в строках
-	CastOperator приведение типов
-	UnsetStatement исправлена работа с типом array
-	316 issue - static property variable concat with variable)
-	Оптимизатор для array_key_exists
-	Removing debug symbols generation in ext/install
-	Abstract method
-	Stubs\Generator
-	Компиляция runtime версии zephir
-	zephir_array_unshift
-	Memory frame

А также многие другие багофиксы.

## Благодарим

Хотелось бы отметить людей которые поучавствовали в выходе этой верссии.

За разработку:

-	[Andres Gutierrez @andresgutierrez](https://github.com/andresgutierrez)
-	[Gusakov Nikita @nkt](https://github.com/nkt)
-	[Yeung Song @netyum](https://github.com/netyum)
-	А так же ваш покорный слуга :)

А так же все людей участвовавших в разработки данного релиза. Happy Coding With Zephir.
