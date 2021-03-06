After starting the russian [gitter.im](https://gitter.im/phalcon-rus/chat) chat about Plalcon, I revealed that most of all people don`t know anything about phalcon's build system, why we need build folder, and what does it mean "compile from source code (ext folder)",
and are not familiar with the process of assembling the project, written in C. All this questions i will explain in this article.

## A little bit about how to configure, preprocessor, compilation and linking of the project.

This paragraph is written for those who have not written any programms in c language, I'll tell you, about what stages We've in build process.

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

Preprocessor is a program that processes its input data to produce output that is used as input to another program.
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

In the output file, We see that lines

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

Compilation is a process, when compiler is translating our program's sources into an equivalent program in low-level language.
Так как программа состоит из множества файлов, то и компилируются они все по раздельности в бинарные файлы с расширениями .o.
When all units is compiled, it's need to be linking into one file.
Because we are using make (build generation util), we need to start make process and weight for the end.

```
make
```

### Installation

Now ext is compiled, but we need to install it in php extensions system path.

```
make install
```

In the final we need to show our PHP env, that we are using Phalcon extension:

For example use nano to edit this file:

```
nano /etc/conf.d/phalcon.ini
```

and write include for extension:

```
exstension=phalcon.so
```

### What is a build?

Build is a file with all c files from ext dir, with machine fixes for specified bits (32 or 64).
It's needed for fast compilation process in production env and more optimizations in compiler level.
They are generated by script, ./build/gen-build.php, usually generation builds are generated by person from Phalcon team (@niden, @phalcon, @sjinks).

### How to compile a development build in one line?

```
cd ext && phpize && ./configure CFLAGS="-g3 -O1 -fno-delete-null-pointer-checks -Wall -fvisibility=hidden" && make clean && make -B && sudo make install
```

Well, By this article I am bringing to you a concept of compilation, optimization, code execution and "How to compile Phalcon 1".
