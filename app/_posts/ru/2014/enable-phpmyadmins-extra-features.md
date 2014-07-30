#Установка
Перед тем как начать пожалуйста удастовертесь что phpmyadmin запущен. После авторизации вы будете оповещены сообщением внизу

```
The phpMyAdmin configuration storage is not completely configured, some extended features have been deactivated. To find out why click here.
```

```php
$cfg['Servers'][$i]['controluser'] = 'pma';
$cfg['Servers'][$i]['controlpass'] = 'pmapass';
$cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
$cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
$cfg['Servers'][$i]['relation'] = 'pma__relation';
$cfg['Servers'][$i]['table_info'] = 'pma__table_info';
$cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
$cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
$cfg['Servers'][$i]['column_info'] = 'pma__column_info';
$cfg['Servers'][$i]['history'] = 'pma__history';
$cfg['Servers'][$i]['recent'] = 'pma__recent';
$cfg['Servers'][$i]['table_uiprefs'] = 'pma__table_uiprefs';
$cfg['Servers'][$i]['users'] = 'pma__users';
$cfg['Servers'][$i]['usergroups'] = 'pma__usergroups';
$cfg['Servers'][$i]['navigationhiding'] = 'pma__navigationhiding';
$cfg['Servers'][$i]['tracking'] = 'pma__tracking';
$cfg['Servers'][$i]['userconfig'] = 'pma__userconfig';
$cfg['Servers'][$i]['designer_coords'] = 'pma__designer_coords';
$cfg['Servers'][$i]['favorite'] = ‘pma__favorite’;
$cfg['Servers'][$i]['savedsearches'] = 'pma__savedsearches';
```

Далее, нам необходимо создать пользователя с паролем.
Запустите запросы ниже, с именем пользователя и паролем, который вы определили при создании.

```sql
GRANT USAGE ON mysql.* TO 'pma'@'localhost' IDENTIFIED BY 'pmapass';
GRANT SELECT (
    Host, User, Select_priv, Insert_priv, Update_priv, Delete_priv,
    Create_priv, Drop_priv, Reload_priv, Shutdown_priv, Process_priv,
    File_priv, Grant_priv, References_priv, Index_priv, Alter_priv,
    Show_db_priv, Super_priv, Create_tmp_table_priv, Lock_tables_priv,
    Execute_priv, Repl_slave_priv, Repl_client_priv
    ) ON mysql.user TO 'pma'@'localhost';
GRANT SELECT ON mysql.db TO 'pma'@'localhost';
GRANT SELECT ON mysql.host TO 'pma'@'localhost';
GRANT SELECT (Host, Db, User, Table_name, Table_priv, Column_priv)
    ON mysql.tables_priv TO 'pma'@'localhost';
```

```sql
GRANT SELECT, INSERT, UPDATE, DELETE ON phpmyadmin.* TO 'pma'@'localhost';
```

После всех манипуляций при входе в систему все сообщения которые мы видели ранее должны исчезнуть.
Если они все еще там нажмите на ссылку что бы увидеть чего не хватает.
Давайте погрузимся во все эти дополнительные возможности.

#Закладки

#Связи

#Информация о таблице

#PDF страницы

#Информация о столбцах

#История
 
#Пользователи и пользовательские группы

#Сокрытие пунктов меню

#Отслеживание

#Настройки пользователя

#Связи в дизайнере таблиц

#Избранное

#Сохранения поиска

Когда вы откроете базу данных, и вы собираетесь на вкладку запроса можно настроить обширный поиск здесь. Вы можете сохранить этот поиск, закладки его. 
Обратите внимание, что это другой закладку чем закладки SQL мы видели ранее. 
Тем не менее, таким образом, вы можете сохранить ваши поиски и выполнять их позже.

#Вывод

Активация этих расширений проста и может действительно повысить удобство работы с PhpMyAdmin. 
Мне особенно нравится то, что я могу нажать на внешних ключей, чтобы я мог увидеть, к которой запись он подключен. 
Собираетесь ли вы, чтобы активировать эти расширения в вашей PhpMyAdmin и если да, то ты идешь, чтобы активировать?
Используете ли вы их? Мне очень хотелось бы услышать от вас в комментариях ниже.
