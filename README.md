<br/><br/><br/>

<p align="center">
<img src="    https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg" width="50"/> Laravel 
&nbsp;&nbsp;&nbsp;&nbsp;
<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original-wordmark.svg" width="50"/> MySQL 
&nbsp;&nbsp;&nbsp;&nbsp;
<img src="https://img.shields.io/badge/Blade-Templates-orange?style=flat-square&logo=laravel"/> Blade
&nbsp;&nbsp;&nbsp;&nbsp;
<img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/tailwindcss/tailwindcss-original.svg" width="50"/> Tailwind CSS 
</p>
<br/><br/><br/>

# 📦 Laravel API: Продажи, Заказы, Склады, Доходы

**Реализация тестового задания.**  

Проект представляет собой REST API, разработанный на Laravel 8, и реализует выдачу данных по четырём сущностям: 
- `sales` — данные о продажах
- `orders` — данные о заказах
- `stocks` — данные об остатках на складе
- `incomes` — данные о доходах

Все эндпоинты возвращают данные в формате JSON с пагинацией.

Полученные данные **собираются с внешнего API** и **сохраняются в базу данных MySQL**, после чего доступны для дальнейшей обработки или отображения.

<br/>

## 🗄️ База данных (MySQL)

Проект использует базу данных **MySQL** для хранения полученных данных из внешнего API. При каждом запуске соответствующей команды данные подгружаются и сохраняются в таблицы:

После всевозможных поисков бесплатных сервисов БД(всевозможные ограничения: многие кстати не дают доступ для подключения и внешки)
Остановил свой выбор БД от Railway(деплой проекта кстати сделан на том же сервисе)

- 🧠 Host: caboose.proxy.rlwy.net
- 🔌 36311
- 📦 Database name: railway
- 👤 Database user: root
- 🔑 Database password: ZfosqYEpTlQBeJcbXZjRjmsCkojidfbY
- 🔐 Protocol: TCP

---

## 🔗 Деплой демонстрационной версии

📍 https://wb-api.artyes.dev/

<br>

![Демо проекта](resources/screenshots/demo.png)

<br/>

## ▶️ Демонстрация artisan-команд

[![asciicast](https://asciinema.org/a/X3hZtbcn0W9qf2tv9LFmc07CV.svg)](https://asciinema.org/a/X3hZtbcn0W9qf2tv9LFmc07CV)

---

## 📬 Работа с Postman

В рамках выполнения тестового задания использовался инструмент **Postman** для тестирования и отладки API-запросов.

⚙️ Ранее опыта работы с Postman не было, однако инструмент оказался интуитивно понятным и удобным — благодаря ему удалось быстро освоить формирование запросов, работу с переменными и тестирование пагинации и авторизации.

📁 В проект включена коллекция запросов Postman, которую можно использовать для быстрого запуска и проверки всех эндпоинтов.

🔗 **Ссылка на коллекцию Postman**: [Postman Collection](https://www.postman.com/cy322666/workspace/app-api-test/overview)
