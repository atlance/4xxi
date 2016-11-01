4xxi
=========
Задание

Реализовать на PHP с использованием Symfony приложение со следующим функционалом:

>
* Регистрация / авторизация пользователей.
* Создание портфеля акций (5-6 акций достаточно) для пользователя: стандартный CRUD.
* Данные должны скачиваться с Yahoo Finance.
* Сделать вывод графика "стоимость портфеля от времени" за 2 последних года по выбранным в п.2 акциям.

___
Требования:
>
* Приложение должно быть опубликовано на github и должно соответствовать стандартам кодирования Symfony.
* README файл должен содержать базовое описание + затраченное на каждый этап время.
* Если настройка / запуск проекта нестандартен, то в README необходимо указать дополнительные шаги, которые необходимо сделать для этого.
* Везде, где возможно, должны использоваться аннотации (кроме DI-компоненты и дополнительных бандлов).

# Demo:
[![play on youtube](https://github.com/atlance/4xxi/blob/master/chart.gif)](https://youtu.be/d6uU__DnqIs)

## Install:
```
cd /path/to/current/project
git clone https://github.com/atlance/4xxi.git
cd 4xxi
npm install uglify-js --prefix app/Resources
npm install uglifycss --prefix app/Resources
composer install
php bin/console doctrine:schema:update -f
php bin/console assetic:dump
php bin/console server:start
```

Если в двух словах, то связка такая: FOSUserBundle + JMSSerializerBundle + YahooFinanceApiBundle.
Если про первую парочку всё понятно, то пакет YahooFinanceApiBundle реализован мною, возможности и документацию можно посмотреть тут: https://github.com/atlance/yahoo-finance-api
