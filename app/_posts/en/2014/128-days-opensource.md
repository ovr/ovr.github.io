As has become clear from this title of the article, my current open source commits streak came up to `128 days` and I write article about it.

![My open source activity](http://dmtry.me/img/articles/2014/IJt3eL5acME.jpg)

#Something about Zephir

Because most of all my projects or commits is related to Zephir I will start speaking about it first.
It's amazing that language grows every day, and it takes good feedback.
[New open source projects](https://github.com/trending?l=zephir) gives popularity to Zephir.

##0.4.3 Alpha Release

We released a new branch - 0.4.3, new futures and fixes:

-	Fixed variables initialization 	in conditions [#413](https://github.com/phalcon/zephir/issues/413)
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

Thanks for help: [@phalcon](https://github.com/phalcon/), [@nkt](https://github.com/nkt/), [@dreamsxin](https://github.com/dreamsxin/), [@christiaan](https://github.com/christiaan/), [@andycheg](https://github.com/andycheg/), [@kjdev](https://github.com/kjdev/), [@mruz](https://github.com/mruz/).

##0.4.4 What we have

- [Fix declarations for arrays](https://github.com/phalcon/zephir/pull/458)
- [Fix declarations for objects](https://github.com/phalcon/zephir/issues/459)
- [Remove auto generated part from kernel component](https://github.com/phalcon/zephir/pull/469)

Will see more details next time.

##My plans for Zephir 0.5

Language grows every day, and it's time to take a significant step and rewrite some places for new architecture.

###Split all components

The main idea of this task is that we need to split components and minimize relations between its.

Components:

- Zephir\Core
- Zephir\Parser
- Zephir\Kernel (wrapper for Zend Engine with extended functions)
- Zephir\CodePrinter

After splitting components we can create new ways, for example:

- Zephir -> PHP
- PHP -> Zephir
- Zephir & PHP -> Zephir

###Object AST in Zephir

We need to create Object AST because with current json ast we can't controll it.
After removing and create of new object AST we can create Zephir\Parsers for all languages what we want.
This is a priority task to standardize parsing components.

###Support for another compilers intro core (not only gcc)

Some moments in core component is static but I added opportunity to change compiler in ```config.json```.
For example after these changes you can change compiler from gcc to llvm-gcc. After it you get JIT by LLVM technology.

[Github branch](https://github.com/ovr/zephir/tree/0.5-dev)

##Lynx

Some moth ago I decided something about that I need to build new project because all ORMs what I used is slower and bigger.
The decision was immediate to make new project, where I will implement philosophy Of Doctrine 2, my experience and Zephir language.

[Project page](http://lynx.github.io/lynx/)

[Github repository](http://github.com/lynx/lynx/)

#Phalcon

In the Phalcon 2 I fixed build for PHP 5.6 support and sometimes ask on issues.

##Phalcon skeleton

It's a new project and now I am planing architecture and future goals.
But is I fell the general idea of the project is "build Phalcon application with predefined modules in easy way".

First I'll implement:

- Admin-panel (grud, setting, auth, acl)
- User module
- Catalog module (products catalog. Category view, product view)
- Cart module
- OAuth module (social auth module)

After BETA release i will create new branch with Lynx support.

[Project page](http://ovr.github.io/phalcon-module-skeleton/)

[Github repository](https://github.com/ovr/phalcon-module-skeleton/)

#129 Day

Yesterday I saw new [twit](https://twitter.com/phalconphp/status/495254816621600768) from [Rushmore Mushambi](https://github.com/rushmorem) about his realization of [zephir-compiler](https://github.com/rushmorem/zephir-compiler) realtime build system on bash.
The decision was immediate and I after spending 40 minutes on work, I released `watch command` with [React](https://github.com/reactphp/react) component.
With this feature compilation process doesn't take time on core bootstrapping, and next time we can create support for partial build. (build only files what we need)

See future request [Watch command #472](https://github.com/phalcon/zephir/pull/472)

#Zephir Conflicts

I decided to stop contributing Zephir because I have got a lot of conflicts with Zephir's authors.

#My Blog development

I created english version of my blog.
As you can see All my articles is about technical and it's really hard to write it because It takes up 5 hours for one article.
If you anyone wants to help me with translation or corrections you can contact with me by easy way for you.

##Upcoming articles (with planned date)

- How to set up own dns server (10 August)
- Zephir vs PHP-CPP (20 Aug)
- Screencasts about how to build the fastest application using the fastest Phalcon framework (1 September)

If you want to see my new articles or projects, please follow me or start my project. (i will be happy)

Will see you soon and I talk to you : "Happy Coding with Phalcon and Zephir".