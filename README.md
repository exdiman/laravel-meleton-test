## Задание 1. Решение

```
SELECT
    users.id AS `ID`,
    CONCAT_WS(' ', users.first_name, users.last_name) AS `Name`,
    GROUP_CONCAT(DISTINCT books.author) AS `Author`,
    GROUP_CONCAT(books.name SEPARATOR ', ') AS `Books`
FROM
    users
    INNER JOIN user_books AS ub ON ub.user_id = users.id
    INNER JOIN books ON ub.book_id = books.id
WHERE
    users.age BETWEEN 7 AND 17
GROUP BY
    ub.user_id
HAVING
    COUNT(ub.id) = 2
    AND COUNT(DISTINCT books.author) = 1

```

Пример тестовой базы приведен в файле `meleton_db.sql`

## Задание 2. Решение

###Установка

1. Установить Git, PHP 7.3, composer, MariaDB/MySQL, создать базу данных для проекта.
2. Клонировать проект из git репозитория:

```shell
git clone https://github.com/exdiman/laravel-meleton-test.git
```
3. С помощью composer установить зависимости: 

```shell
composer install
```

4. Проверить наличие конфигурационного файла `.env` в корне проекта. Если его нет, то создать его вручную, путем 
копирования файла `.env.example`.
   
5. В файле `.env` указать параметры для подключения к БД, bearer token, размер комиссии при конвертациии валют:

```dotenv
FEE_PERCENT=2
BEARER_TOKEN=

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

6. Выполнить миграции в БД из корня проекта
```shell
php artisan migrate
```

7. Запустить проект командой

```shell
php artisan serve
```
Проект будет доступен по адресу `http://127.0.0.1:8000`

Проверить работу API:

GET http://127.0.0.1:8000/api/v1/rates

GET http://127.0.0.1:8000/api/v1/rates?filter[currency]=USD

GET http://127.0.0.1:8000/api/v1/rates?filter[currency]=USD,AUD

POST http://127.0.0.1:8000/api/v1/convert

Параметры:

currency_from: USD // исходная валюта
currency_to: BTC // валюта в которую конвертируем
value: 1.00 // количество единиц исходной валюты

### Tests

Частично написаны тесты - на примере одного сервиса.

```shell
php artisan test
```

### Затраченное время

~8-9 часов с 12 часов 25.02 по 10 часов 26.02


## Условия задания

Необходимо решить 2 задания, результат залить в публичный репозиторий и в readme файле указать решение первого задания в виде запроса и пример вашей тестовой базы, на которой вы протестировали запрос, а так-же инструкцию и описание по второму заданию.

Зафиксируйте время, дату начала и конца выполнения тестового задания. Напишите это время в документе.

### Задание 1

Mysql

Есть три таблицы:

#### users
id
first_name
last_name
age

1
Ivan
Ivanov
18

2
Marina
Ivanova
14

#### books

id
name
author

1
Romeo and Juliet
William Shakespeare

2
War and Peace
Leo Tolstoy


#### user_books

id
user_id
book_id

1
1
2

2
2
1



Необходимо написать SQL запрос, который найдет и выведет всех читателей, возраста от 7 и до 17 лет, которые взяли только 2 книги и все книги одного и того же автора.

Формат вывода:

ID, Name (first_name  last_name), Author, Books (Book 1, Book 2, ...)

1, Ivan Ivanov, Leo Tolstoy, Book 1, Book 2, Book 3

### Задание 2


PHP / Laravel


Для выполнения этого задания используйте

Laravel 7.3
PHP 7.3.14
10.4.12-MariaDB


Необходимо реализовать на  фреймворке LARAVEL RESTful API для работы с курсами обмена валют для BTC. В качестве источника курсов будем использовать: https://blockchain.info/ticker и будем работать только с этим методом.


Данное API будет доступно только после авторизации. Все методы будут приватными.
Написать middleware, который закрывает методы к апи и проверяет токен, сам токен для экономии времени можно статично записать в файл .env


Для авторизации будет использоваться фиксированный токен (64 символа включающих в себя a-z A-Z 0-9 а так-же символы - и _ ), передавать его будем в заголовках запросов. Тип Authorization: Bearer.

Важно:

Весь код, которые производит какие-либо вычисления или операции с базой должен быть написать в сервисах. Все сервисы инициализировать через DI в контроллерах в методе __construct, либо в в экшене контроллера.

Для фильтрации, построения ответов от апи использовать библиотеку
https://github.com/spatie/laravel-query-builder

Все апи должны возвращать ресурсы или коллекции ресурсов в случае успеха

https://laravel.com/docs/8.x/eloquent-resources


Формат ответа API: JSON (все ответы при любых сценариях JSON)

Все значения курса обмена должны считаться учитывая нашу комиссию = 2%


Примеры запросов

GET http://base-api.url/api/v1/rates?filter[currency]=USD // фильтр по валюте

GET http://base-api.url/api/v1/rates // все курсы


API должен иметь 2 метода:

rates: Получение всех курсов с учетом комиссии = 2% (GET запрос) в формате:
{
"USD” : <rate>,
...
}

В случае ошибки связанной с токеном: код ответа должен быть 403, в случае успеха код ответа 200 + данные


Сортировка от меньшего курса к большему курсу.

В качестве параметров может передаваться интересующая валюта, в формате USD,RUB,EUR и тп В этом случае, отдаем указанные в качестве параметра currency значения.



Запрос на конвертацию валют, результат запроса сохранять в базу

2. POST http://base-api.url/api/v1/convert


Параметры:
``` 
currency_from: USD // исходная валюта
currency_to: BTC // валюта в которую конвертируем
value: 1.00 // количество единиц исходной валюты
```


convert: Запрос на обмен валюты c учетом комиссии = 2%. POST запрос с параметрами:
```
currency_from: USD
currency_to: BTC
value: 1.00
```


или в обратную сторону
``` 
currency_from: BTC
currency_to: USD
value: 1.00
```
В случае успешного запроса, отдаем:

```json
{
    "currency_from” : BTC,
    "currency_to” : USD,
    "value”: 1.00,
    "converted_value”: 1.00,
    "rate” : 1.00,
    "created_at”: TIMESTAMP
}
```


В случае ошибки:
```json
{
    "status”: "error”,
    "code”: 403,
    "message”: "Invalid token”
}
```

Важно, минимальный обмен равен 0,01 валюты from
Например: USD = 0.01 меняется на 0.0000005556 (считаем до 10 знаков)
Если идет обмен из BTC в USD - округляем до 0.01
