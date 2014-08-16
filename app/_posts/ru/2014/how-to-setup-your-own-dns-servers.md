
# Немного понятий

## Типы записей



# Программная часть

В моем расспорежении у меня имееться сервер на базе Ubuntu 14.04.1 LTS.

## NS1

Пример настройки ns1.dmtry.me:

```
DMTRY.ME.       3600    IN      SOA     ns1.dmtry.me. admin.dmtry.me. (
; SOA Record
                                2014072618
                                28800
                                7200
                                604800
                                600
);

@ IN NS ns1.dmtry.me.
@ IN MX 0 smtp

@ IN A 176.9.77.73
ns1 IN A 176.9.77.73
smtp IN A 176.9.77.73

* IN CNAME @
```

Запись из SOA `admin.dmtry.me.` преобразуеться в email адресс admin@dmtry.me

## Обратная зона /etc/bind/176.9.77.73.in-addr.arpa

Являеться важной для почты.

```
$TTL 84841
@       IN      SOA     ns1.dmtry.me.        admin.dmtry.me. (
                        2012100103
                        85400
                        7200
                        3600000
                        86400 )

@       IN      NS      ns1.dmtry.me.
@       IN      NS      ns2.dmtry.me.

@       IN      PTR     dmtry.me.
@       IN      PTR     smtp.dmtry.me.
```

## Настройки и логирование

```
options {
        recursion no;
        additional-from-cache no;
        directory "/etc/bind/";
        dump-file "log/cache_dump.db";
        statistics-file "log/named_stats.txt";
        memstatistics-file "log/named_mem_stats.txt";
        version "Dmtry.me DNS Server"; // Для сокрытия версии DNS сервера
        listen-on {localhost; 176.9.77.73; }; // Ip адресс на котором слушаем
        forwarders {
                8.8.8.8; // Google open dns 1
                8.8.4.4; // Google open dns 2
        };
        allow-recursion { none; }; // Отключение рекурсивных запросов
};

logging {
        channel default_ch {
        file "log/named-base.log";
        severity info;
        print-time yes;
        print-category yes;
        };
        channel security_ch {
        file "log/named-security.log";
        severity info;
        print-time yes;
        print-category yes;
        };
        category default { default_ch; };
        category security { security_ch; };
};

include "my-zones.conf";
```

## Описание my-zones.conf

zone "dmtry.me" IN {
        type master;
        file "dmtry.me";
};
zone "176.9.77.73.in-addr.arpa" IN {
        type master;
        file "176.9.77.73.in-addr.arpa";
};

# Указание IP адрессов у регистратора домена

## GoDaddy

Для того что бы зарегистрировать домен на ns1.dmtry.me нам нужно указать айпи адресса для этого домена в разделе ```Имена хостов```