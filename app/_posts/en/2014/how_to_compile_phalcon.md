After starting the russian [gitter.im](https://gitter.im/phalcon-rus/chat) chat about Plalcon, I revealed that most of all people don`t know anything about phalcon's build system, why we need build folder, and what does it mean "compile from source code (ext folder)",
и не знакомы с процессом сборки проекта, написанного на си. Эти вопросы мы затронем в данной статье. Процесс компиляции си-проекта я попытаюсь осветить полностью, чтобы убрать всю тень, покрывающую данный процесс.

##A little bit about how to configure, preprocessor, compilation and linking of the project.

This paragraph is written for those who have never written, and did not get the BBC, I'll tell you, from what the stages in the assembly process of the project.

### Compilation settings

There are development utils for php: `php-config` and `phpize`. To configure default params for compilation, we need to use phpize.

In order to configure the compilation of our expansion, you need to open ext folder and execute `phpize`:

```bash
cd ext
phpize
```

After running this commands, will be prepared environment to compile PHP extension.

Now you need to configure flags for the compiler and preprocessor (example for production env):

```
./configure CFLAGS="-O2 -finline-functions -fomit-frame-pointer -fvisibility=hidden"
```

```-O2``` Enable second level for optimizations. There are 7 levels for it: ```-O```: ```-O0```, ```-O1```, ```-O2```, ```-O3```, ```-Os```, ```-Og```, и ```-Ofast```. Level O2 is most popular and is enabled by default in most used apps.

```-finline-functions``` [functions optimization](https://gcc.gnu.org/onlinedocs/gcc-4.9.0/gcc/Inline.html) with inline modifier.

```-fomit-frame-pointer``` [disable frame pointers](https://gcc.gnu.org/onlinedocs/gcc-3.4.4/gcc/Optimize-Options.html), is enabled by default in ```-O```, ```-O2```, ```-O3```, ```-Os```.

```-fvisibility``` visible scopes.

Example how to configure you env for development and debug process:

```
./configure CFLAGS="-g3 -O1 -fno-delete-null-pointer-checks -Wall -fvisibility=hidden"
```

```-g3``` enable debug messages for gdb.

```-fno-delete-null-pointer-checks``` pointers check.

```-Wall``` Enable high error detection mode.

###Preprocessor

Preprocessor — a computer program, принимающая данные на входе и выдающая данные, предназначенные для входа другой программы (например, компилятора). О данных на выходе препроцессора говорят, что они находятся в препроцессированной форме, пригодной для обработки последующими программами (компилятор). Результат и вид обработки зависят от вида препроцессора; так, некоторые препроцессоры могут только выполнить простую текстовую подстановку, другие способны по возможностям сравниться с языками программирования. Наиболее частый случай использования препроцессора — обработка исходного кода перед передачей его на следующий шаг компиляции. Языки программирования C/C++ и система компьютерной вёрстки TeX используют препроцессоры, значительно расширяющие их возможности.
I will use `g++` compiler.

Create file `example.cpp` with simple content:

```c++
#include <cstdlib>
#include <iostream>

#define MY_TEXT 1

#define SIMPLE_MACROS(a, b) { \
	cout << a << a << endl; \
	cout << b << b << endl; \
}

using namespace std;

int main()
{
	cout << MY_TEXT << endl;

	SIMPLE_MACROS("1", "b");

	return 1;
}
```

And run the compiler with preprocessor flag:

```
g++ -E example.cpp > output
```

А теперь просмотрим файл output.
We see, that lines

```c++
#include <cstdlib>
#include <iostream>
```

was replaced by content from this files.

In line cout << MY_TEXT,  MY_TEXT was replace by 1.

```c++
cout << 1 << endl;
```

And macros call was replaced by macros code.

```c++
{cout << "1" << "1" << endl; cout << "b" << "b" << endl;};
```

It's a small example, to show how Preprocessor is working.

### Compiling and linking

Compilation — это процесс преобразования (трансляции) нашего исходного кода компилятором в эквивалентную программу на низкоуровневом языке.
Так как программа состоит из множества файлов, то и компилируются они все по раздельности в бинарные файлы с расширениями .o.
После того как программа скомпилировала все файлы, ей необходимо совместить все бинарные файлы, а это и называется линковкой.
Так как для сборки расширения мы используем систему сборки проекта make, то нам просто нужно запустить команду без параметров и дождаться конца выполнения программы.

```
make
```

### Installation

Вот и все. Наше расширение скомпилировано, и осталось его только установить. Благодаря тому, что для си-проектов используется система сборки make, нам лишь осталось просто запустить make с определенным файлом и дождаться конца копирования нашего расширения.

```
make install
```

Вы скажете, вроде бы все, а нет. Мы забыли самую простую часть нашего пути. Осталось дать настройку нашему php, прописав в ini-файл название расширения.

Пример редактирования файла конфигурации через редактор nano:

```
nano /etc/conf.d/phalcon.ini
```

И указать само название расширения:

```
exstension=phalcon.so
```

### What is a build?

Build – это склеенные си-файлы в один с дополнительными правками под битность системы.
Они нужны для более быстрой компиляции и более мощных преобразований на уровне компилятора.
Собираются они через скрипт, лежаший в ./build/gen-build.php, обычно за генерацией билдов следит человек из phalcon team (niden, phalcon, sjinks).

### How to compile a development build in one line?

```
cd ext && phpize && ./configure CFLAGS="-g3 -O1 -fno-delete-null-pointer-checks -Wall -fvisibility=hidden" && make clean && make -B && sudo make install
```

Ну и все, наверное, что бы хотелось отметить по данной теме. Этой статьей я подвожу вас к понятиям компиляции, для того что бы в следующей статье поднять тему об оптимизациях и исполнении кода.
