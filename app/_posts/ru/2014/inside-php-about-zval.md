Немногие 


# Структура Zval

Для версии php 5.6 структура zval в коде выглядит следующим образом:

```
typedef union _zvalue_value {
    long lval;
    double dval;
    struct {
        char *val;
        int len;
    } str;
    HashTable *ht;
    zend_object_value obj;
} zvalue_value;
```

Вроде бы все понятно с первого взгляда но что же за ключевое слово `union`.
Union - структура данных, члены которой расположены по одному и тому же адресу. Поэтому размер объединения равен размеру его наибольшего члена. В любой момент времени объединение хранит значение только одного из членов.

[Прочитать больше про union в Википедии](https://ru.wikipedia.org/wiki/%D0%9E%D0%B1%D1%8A%D0%B5%D0%B4%D0%B8%D0%BD%D0%B5%D0%BD%D0%B8%D0%B5_(%D1%81%D1%82%D1%80%D1%83%D0%BA%D1%82%D1%83%D1%80%D0%B0_%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D1%85))

# Как работать с типом

Для работы с Zval принятно использовать макросы обвертки для сохранения совместимости между версиями ядра Zend Engine.

## Определение типа

Определение типа осуществляется с помощью макроса `Z_TYPE`.

```
zval zv;
zval *zv_ptr;
zval **zv_ptr_ptr;
zval ***zv_ptr_ptr_ptr;

Z_TYPE(zv);                 // = zv.type
Z_TYPE_P(zv_ptr);           // = zv_ptr->type
Z_TYPE_PP(zv_ptr_ptr);      // = (*zv_ptr_ptr)->type
Z_TYPE_PP(*zv_ptr_ptr_ptr); // = (**zv_ptr_ptr_ptr)->type
```

## Установка значение

