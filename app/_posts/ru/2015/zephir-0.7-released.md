Вот и наступило долгожданное время выхода версии 0.7 и официального перехода проекта в состояние `Beta`.  Новая версия несет более чем 30 исправлений и улучшений.

## Новый модификатор Internal для методов

Модификатор `internal` позволяет сделать из нашего PHP метода полноценный аналог Си-функции для ускорения производительности.
Напишем простой класс с методом реализующий нахождения чисел Фибоначчи:

```zep
class McallInternal
{
    /**
     * Обратите внимания на рекурсию
     */
    internal function fibonacci(long n) -> long
    {
        if n == 1 || n == 2 {
            return 1;
        }
        return this->fibonacci(n - 1) + this->fibonacci(n - 2);
    }

    public function callFibonacci(long n) -> long
    {
        return this->fibonacci(n);
    }
}
```

Напишем аналог на PHP, и запустим оба варианта:

```php
<?php

class Math
{
    function fibonacci($n)
    {
        if ($n == 1 || $n == 2) {
                return 1;
        }
        return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }
}

$t = microtime(true);
echo (new Test\Mcallinternal)->callFibonacci(31), PHP_EOL;
echo 'Zephir: ', microtime(true) - $t, PHP_EOL;

$t = microtime(true);
echo (new Math)->fibonacci(31), PHP_EOL;
echo 'PHP: ', microtime(true) - $t, PHP_EOL;
```

Результаты:

```
Zephir: 0.12126803398132
PHP 5.6: 0.613104820251464

Zephir: 0.12285614013672
PHP 5.6: 0.55841708183289

4.5/5x 450%/500% improvement
```

#### Под капотом

Почему такой выйгрышь? Давайте разбираться. Обычный PHP land метод сгенерированный Zephir будет выглядить таким образом:
```c
PHP_METHOD(Test_McallInternal, other) {
    zval *a_param = NULL, *b_param = NULL;
    long a, b;

    //Парсинг параметров и стэка
    zephir_fetch_params(0, 2, 0, &a_param, &b_param);

    // Перевод значение zval в long
    a = zephir_get_intval(a_param);
    b = zephir_get_intval(b_param);


    RETURN_DOUBLE(zephir_safe_div_long_long(a, b TSRMLS_CC));
}
```

Вызов PHP land метода:
```c
// Слот кэша
zephir_fcall_cache_entry *_6 = NULL;
// Флаг статуса функции после вызова, что бы понять есть ли exception?
int ZEPHIR_LAST_CALL_STATUS;

// Результат работы функции
zval *_result = NULL,

// Параметры 1 и 2
zval *_parameter1 = NULL, *_5 = NULL;

//Необходимо выделить память под первый параметр
ZEPHIR_INIT_NVAR(_parameter1);
//Положим значение в структуры zval
ZVAL_LONG(_parameter1, 1);

//И не забыть про второй параметр ;)
ZEPHIR_INIT_NVAR(_parameter2);
//Положим значение в структуры zval
ZVAL_LONG(_parameter2, 2);

/**
 * Динамически вызой метода
 * this_ptr это zend_class_entry на текущий класс
 */
ZEPHIR_CALL_METHOD(&_result, this_ptr, "other", &_6, 0, _parameter1, _parameter2);

// Проверяем из контекста какой код вернул метод
zephir_check_call_status();
```

Как уже стало видно, тут мы видим очень большой оверхед на вызовах. Ну а что же будет с использование модификатора `internal`:
```c
static void zep_Test_McallInternal_other(int ht, zval *return_value, zval **return_value_ptr, zval *this_ptr, int return_value_used, zval *a_param_ext, zval *b_param_ext TSRMLS_DC) {
    zval *a_param = NULL, *b_param = NULL;
    long a, b;

    /**
     * Надеюсь gcc/clang не такие дураки как мы ;)
     */
    a_param = a_param_ext;
    b_param = b_param_ext;

    // Перевод значение zval в long
    a = zephir_get_intval(a_param);
    b = zephir_get_intval(b_param);


    RETURN_DOUBLE(zephir_safe_div_long_long(a, b TSRMLS_CC));
}
```

Ну и сам вызов `internal` метода:

```c
// Результат работы функции - HEAP
zval *_result = NULL;

// Параметры в структуре zval но уже не heap а stack
zval parameter1 = zval_used_for_init, parameter2 = zval_used_for_init;

// Флаг статуса функции после вызова, что бы понять есть ли exception?
int ZEPHIR_LAST_CALL_STATUS;

//Чистим пред значение в стэке
ZEPHIR_SINIT_NVAR(parameter1);
//Новое значение
ZVAL_LONG(&_4, 1);

//Чистим пред значение в стэке
ZEPHIR_SINIT_NVAR(_5);
//Новое значение
ZVAL_LONG(&_5, 2);

/**
 * Вызой нативной статичной функции
 * this_ptr это zend_class_entry на текущий класс
 *
 * Внимание! Параметры передаются уже ссылками, а не указателями
 */
ZEPHIR_CALL_INTERNAL_METHOD_P2(&_result, this_ptr, zep_Test_McallInternal_other, &parameter1, &parameter2);
// Проверяем из контекста какой код вернул метод
zephir_check_call_status();
```

Различия между `internal` методом? В `internal`:

* Нет передачи параметров по стеку через Zend, нативный Си вызов. 
* Нет Получения из стека.
* Нет магии с контекстом.
* Нет динамического слотового кэша, так как не нужен.
* Параметры для вызова `internal` метода лежат в Stack (Stack быстрее HEAP).

# Реализован глобальный статический кэш

Предыдущий кэш, реализованный в Zephir, имел проблемы с PHP-FPM или с функциями/методами, которые были переопределены. Ну и, конечно же, не был доступен в Windows. Новый кэш работает на стратегии слотов (slot-based cache), функции или методы, который являются кандидатами на кэширование, будут кэшированы в глобальном индексируемом кэше который работает намного быстрее чем кэш на статичных переменных (static-variable based cache)

Работает оптимально для:

* Final/private методов
* Функций из ядра PHP

На замерах дает выигрыш порядка 5-10%

[https://github.com/phalcon/zephir/pull/956](https://github.com/phalcon/zephir/pull/956)

# Реализован Low overhead function call for PHP 5.6

Для тестирования возьмем код:

```php
public static function guid()
{
    return sprintf(
        "%04X%04X-%04X-%04X-%04X-%04X%04X%04X", 
        rand(0, 65535), rand(0, 65535), 
        rand(0, 65535), rand(16384, 20479), 
        rand(32768, 49151), rand(0, 65535), 
        rand(0, 65535), rand(0, 65535)
    );
}
```

Протестируем на производительность (выполним 1'000.000 раз):

```
v0.6.2 3289 milliseconds
v0.6.3 2149 milliseconds
65% improvement
```

На замерах дает выигрыш порядка 40-80%

[https://github.com/phalcon/zephir/pull/958](https://github.com/phalcon/zephir/pull/958)

## Улучшения

- [Low overhead fcall (only PHP 5.6)](https://github.com/phalcon/zephir/pull/958)
- [Add initializer (RINIT) support to code generation](https://github.com/phalcon/zephir/pull/965)
- [Added Build date to ext info](https://github.com/phalcon/zephir/commit/6d118b9d6fb10d8bea02122362308dd042cf9cfe)
- [Api zephir theme's title is now customizable through theme options](https://github.com/phalcon/zephir/commit/31fe9a03c45799fec2c91aaa91bf98ae0f8513e6)
- [Fix unlikely and likely to also work on variables](https://github.com/phalcon/zephir/commit/99870fd3f36ae5e7e48e3f0407d066b0baa3b98b)
- [Call parent constructors if zephir is the origin of the childs constructor](https://github.com/phalcon/zephir/commit/67115ba4116b9a384e46fa3eb009d692643ed4f6)
- [Uncomment ternary complex tests](https://github.com/phalcon/zephir/commit/ff7bff19a4605906406078a31bd972fe5f9afa40)
- [Fix #905: Rework how and when [on unitialize] properties are initialized](https://github.com/phalcon/zephir/commit/400efed6e67d2cba2980d874ae1343ed71df746c)
- [Fix #665: Ensure that the ternary operator promotes temporary variables](https://github.com/phalcon/zephir/commit/1329b386a0126c864d2efbe28c3f9da01b1d3fca)
- [Fix #537: Make sure class properties are initialized in the right order](https://github.com/phalcon/zephir/commit/9448a9c89955213ca772b08eb37e2db4862b14a2)
- [Fix optimizers to init symbolVariable as late as possible](https://github.com/phalcon/zephir/commit/be1dbe40b332a7328bf0598159cb27bba33a17fe)
- [Implement arrays as default values of static properties](https://github.com/phalcon/zephir/commit/360bb5a54e538e242311f00076da58c78fa4f2e9)
- [Fix branching of superglobals by fetching them as soon as possible [Root]](https://github.com/phalcon/zephir/commit/1e831725d3e99e18441a337356bfebc1f32fb1df)
- [Add checks for builtin [user defined] functions to reduce warnings](https://github.com/phalcon/zephir/commit/7e328723dc4084cf105e59aef5363528e8142c64)
- [Fixes #960](https://github.com/phalcon/zephir/commit/c44abb4d640ed01bc3cbcdc3afff570a1b0058c7)
- [Fixed initialization of slot cache in 64bits](https://github.com/phalcon/zephir/commit/e8e2618b9b43555792e1494523c22c64dbcd710b)
- [Only using specialized function in PHP 5.6](https://github.com/phalcon/zephir/commit/41b571b99a15caef14935c17d601390eb58d3887)
- [Allow case statements contains eval-expressions, fixes #768](https://github.com/phalcon/zephir/commit/c34fb8fbde5779c07ae1034ee81e5d17d864bc0f)
- [Support built-in PHP classes static-constant-access](https://github.com/phalcon/zephir/commit/e2f0887fe1766f4a84fbe7f65c7fdd7c5d0175c7)
- [CaseOperator - implement [int] case from string type](https://github.com/phalcon/zephir/commit/8499e7dec9ed39918ebf8a09f21c711352126f3b)
- [Fix #830, added [int] case from variable - string](https://github.com/phalcon/zephir/commit/69c06dfa7ff106fe730b1de7c8df70c29b224e64)
- [Fix Redis prototype](https://github.com/phalcon/zephir/commit/37630eead506e1113ea8c83dbc733ed4d28c5edd)
- [Prototype\Redis - added new methods: get, flushDB, set, delete and new consts](https://github.com/phalcon/zephir/commit/efb7a844b8f1c77ae8df69fb85cd80024ab763db)
- [Fix bug about GreaterEqualOperator](https://github.com/phalcon/zephir/commit/09e3ac360845d18a2adfb8e0b86daf6a747cb409)
- [Remove forgotten echo for Windows build](https://github.com/phalcon/zephir/commit/b0412a06febd8d233c7dd25568df45585360c83f)
- [Allow Method and FunctionCache to also receive null-values.](https://github.com/phalcon/zephir/commit/6b63cad296403fc8f37a3300d1744727ff5e3007)
- [Prototype\Memcached - added methods: set, delete, flush, get](https://github.com/phalcon/zephir/commit/dbc08c32ac27ca33210b0780193c7d670b0d8dde)
- [Disabling static nts for all versions](https://github.com/phalcon/zephir/commit/e8b442d2141bd4bfd72efb442bc74b60b62c9027)
- [STA Mcall array can be a callable type](https://github.com/phalcon/zephir/commit/3179020671f472baa8b33b9cdb14b621321f0a11)
- [Support Passes for istring](https://github.com/phalcon/zephir/commit/0f9207249d26548b9f5770c2769514c230ee536f)
- [Fix #679: Check for local variables and treat them accordingly](https://github.com/phalcon/zephir/commit/a760a744da599605cd1b86fe4bbf02f4a243f129)
- [Fix superglobal-access not to cause warnings, since they are initialized](https://github.com/phalcon/zephir/commit/5a1205beb553f51b2e0065e4b653354a03bb7cdb)
- [Added stricter parameter types.](https://github.com/phalcon/zephir/commit/f58a4db5cac21d2b77e8af407fe42a0a9cde0c2b)
- [Fix fcall info keys to not collide with functions for static calls](https://github.com/phalcon/zephir/commit/4612effd3af191c5aadf7be9ad241eb0bd08faab)
- [Fixed #740 issue](https://github.com/phalcon/zephir/commit/4612effd3af191c5aadf7be9ad241eb0bd08faab)
- [Fix #915 issue, fetch properties for Runtime Class Definition](https://github.com/phalcon/zephir/commit/d6aa9772382f926073f7f7b5a0f989fc14e35310)
- [Fix zephir_floor and zephir_ceil to be consistent and not dangerous to use](https://github.com/phalcon/zephir/commit/17063834f12c30e73b768c5bffc6a2c0437777cf)

## Исправления

- [Internal functions: Implement static call, fix unused return value calls](https://github.com/phalcon/zephir/pull/964)
- [Fixed division using double, returning double from zephir_floor](https://github.com/phalcon/zephir/commit/fd7f8db25595b7878f488c13ad1c780e83cfa54b)
- [Fixed zephir_floor optimizer](https://github.com/phalcon/zephir/commit/fd7f8db25595b7878f488c13ad1c780e83cfa54b)
- [Add checks for builtin [user defined] functions to reduce warnings](https://github.com/phalcon/zephir/pull/966)
- [Fix branching of superglobals by fetching them as soon as possible](https://github.com/phalcon/zephir/pull/967)
- [https://github.com/phalcon/zephir/commit/00118e6202da68895656f6fcd2f60793550d07d6](https://github.com/phalcon/zephir/commit/00118e6202da68895656f6fcd2f60793550d07d6)