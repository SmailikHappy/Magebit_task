# Magebit task
*This project was made for the vacancy of a web-developer at Magebit.*


## My instance
I have made and run this project on OpenServer. It worked fine for me. <br>
[Download OpenServer](https://ospanel.io/download/)<br><br>
Modules:
- PHP 7.1
- Apache 2.4
- MySQL 8.0 (Windows 10)


## Characteristics
> - A preprocessor SASS for CSS and Vue.js 3 for JavaScript are used in this project.
> - Basic page (homepage / first-page) is `/html/index.php`
> - If JavaScript is disabled it automatically switches to `/html/if-js-disabled.php`
> - The site connects to a MySQL database always via `/src/db.php` file. Please check it before running an instance
> - To connect to the database's control panel you have to type in address bar `<domain>/admin.php`
> - User can access only /html folder, so important code is hidden for user in folder `/src`

## Usage
### 1. Install a server and setup it

It's up to you what server you choose
I used [OpenServer](https://ospanel.io/download/) as mentioned.
<br>
<br>

### 2. Make a database
To make site working run 3 commands to MySQL database before, to create essential database and tables <br> *(for example using PhpMyAdmin, which is already installed in OpenServer)*:
```bash
CREATE DATABASE `subscriptions`;
```
```bash
CREATE TABLE `subscriptions`.`emails` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_email` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `email_domain_id` INT(11) NOT NULL , `email_domain` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `sub_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
```
```bash
CREATE TABLE `subscriptions`.`domains` ( `domain_id` INT(11) NOT NULL AUTO_INCREMENT , `domain_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , PRIMARY KEY (`domain_id`)) ENGINE = InnoDB;
```
<br>

### 3. Download and paste
Download the repository. <br><br><br>
If you want just copy-paste and run:
#### 1. Make a folder, which is called as your own domain will be, in `../OpenServer/domains` folder.
As an example `C:\OpenServer\domains\test.com`
#### 2. Copy everything from the folder `html` and paste it into your `../OpenServer/domains/<domain_called_folder>`
#### 3. But folder `src` insert into `../OpenServer/domains`.
#### 4. Set up a `db.php` in `src` folder to connect your database.
<br><br>
### Enjoy