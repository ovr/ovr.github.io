Достаточную часть своего времени я трачу на написания REST бекендов и клиентов к ним.

## Что есть в проекте SocialConnect?

## Auth

[Проект на GitHub](https://github.com/SocialConnect/auth)

## Common

Библиотека `Common` является связующим звеном между всем фреймворком и выполняет собой контейнер для основых компонентов, а именно:
 
### Http клиентов

### Базовых сущностей

### Hydator

[Проект на GitHub](https://github.com/SocialConnect/common)

## ВКонтакте SDK

Первым делом для реализация возможностей библиотеки я выбрал социальную сеть ВКонтакте и принялся за написание к ней SDK.

Примеры использование:

Для начало нам нужно создать клиент SDK и передать ему Http\Client

```php
// Your Vk Application Settings
$appId = 123456;
$appSecret = 'secret';

$vkService = new \SocialConnect\Vk\Client($appId, $appSecret);
$vkService->setHttpClient(new \SocialConnect\Common\Http\Client\Curl());
```

Мы можем выбирать пользователя или пользователей

```php
$user = $vkService->getUser(1);
if ($user) {
    var_dump($user);
}

$users = $vkService->getUsers([1, 2]);
if ($users) {
    var_dump($users);
}
```

[Проект на GitHub](https://github.com/SocialConnect/vk-sdk)

# Интеграция с Phalcon

Покамесь проект находиться на самой раней стадии развития но обойти Phalcon он не смог.

Для начало нужно настроить ваше приложение :

```php
    'oauth' => array(
        'redirectUri' => 'http://phalcon-module.local/oauth/index/callback',
        'provider' => array(
            'Facebook' => array(
                'applicationId' => '',
                'applicationSecret' => ''
            ),
            'Twitter' => array(
                'applicationId' => '',
                'applicationSecret' => '',
                'enabled' => false // Вы можете отключить не рабочий провайдер через флаг
            ),
            'Google' => array(
                'applicationId' => '',
                'applicationSecret' => '',
                'enabled' => false
            ),
            'Vk' => array(
                'applicationId' => '',
                'applicationSecret' => ''
            ),
            'Github' => array(
                'applicationId' => '',
                'applicationSecret' => ''
            )
        )
    )
```

(Исходный код модуля на GitHub)[https://github.com/ovr/phalcon-module-skeleton/tree/master/application/modules/oauth] (Демо)[http://phalcon-module.dmtry.me/]

# А что дальше?

Дальше я планирую развивать идею путем добавления новых провайдеров, новых SDK и поддержанием уже готовых.