# BileMo Documentation 

[![Maintainability](https://api.codeclimate.com/v1/badges/764500765a42661592a4/maintainability)](https://codeclimate.com/github/bigboss-oualid/project_7/maintainability)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/07b0e710497147f1a9457e5868b2f1e6)](https://www.codacy.com/gh/bigboss-oualid/project_7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bigboss-oualid/project_7&amp;utm_campaign=Badge_Grade)

## About the project

![Project](https://img.shields.io/badge/Project-7-white.svg)
![Symfony](https://img.shields.io/badge/Symfony-v4.4-45CB3E)
![Project](https://img.shields.io/w3c-validation/html?preset=HTML%2C%20SVG%201.1%2C%20MathML%203.0&targetUrl=https%3A%2F%2Fwww.bilemo.it-bigboss.de)
[![Repo Size](https://img.shields.io/github/repo-size/bigboss-oualid/project_7?label=Repo+Size)](https://github.com/bigboss-oualid/project_7/tree/master)
[![request](https://img.shields.io/github/issues-pr-closed/bigboss-oualid/project_7?color=33FFCC)](https://github.com/bigboss-oualid/project_7/pulls?q=is%3Apr+is%3Aclosed)
[![Issues](https://img.shields.io/github/issues-closed/bigboss-oualid/project_7?logo=logo)](https://github.com/bigboss-oualid/project_7/issues?q=is%3Aissue+is%3Aclosed)

Development of a mobile phone showcase for a company that offers access to its catalog via an API (Application Programming Interface) for all platforms that require it. So these are exclusively B2B sales (business to business).
We are going to expose a number of APIs to enable the applications of other web platforms to perform operations.

## Documentation
* [Getting started](#getting-started)
  * [Prepare your work environment](#prepare-your-work-environment)
    * [Prerequisites](#prerequisites)
    * [Set up the Project](#set-up-the-project)
  * [Try the Application](#try-the-application)
  * [Run Tests](#run-tests)
    * [PHPUnit](#phpunit)
    * [Behat](#behat)
  * [API Demonstration](#demo)
  * [Information](#info)

## Getting Started

### Prepare your work environment

Download & install all prerequisites tools

##### Prerequisites
* [![WampServer](https://img.shields.io/badge/WampServer-v3.2.0-F70094)](https://www.wampserver.com/) OR [![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4.7-7377AD)](https://www.php.net/manual/fr/install.php) + [![MySQL](https://img.shields.io/badge/MySQL-v8.0.19-DF6900)](https://dev.mysql.com/downloads/mysql/#downloads)
* [![Git](https://img.shields.io/badge/Git-v2.27-E94E31)](https://git-scm.com/download)
* [![Openssl](https://img.shields.io/badge/Openssl-v2.27-5D0000)](https://blog.devolutions.net/2020/09/tutorial-manually-installing-openssl-on-windows-linux-macos)
* [![SymfonyCLI](https://img.shields.io/badge/Symfony-v4.20-000000)](https://symfony.com/download)
* [![Composer](https://img.shields.io/badge/Composer-v1.10.13-5F482F)](https://getcomposer.org/download)
* [![Nodes](https://img.shields.io/badge/Nodejs-v14.5.0-026E00)](https://nodejs.org)

##### Set up the Project
###### Installation
1. Download the project [![Download](https://img.shields.io/badge/-Here-D3D345)](https://codeload.github.com/bigboss-oualid/project_7/zip/master "click to start download"), or clone the repository by executing the command line from your 
terminal.
```shell
$ git clone https://github.com/bigboss-oualid/project_7.git
```

2. In your terminal change the working directory to the project folder and run the below command line to install all 
dependencies:
```shell 
$ composer install
```

###### Generate the SSH keys:
3. Create a directory where we save SSH keys 
``` bash
$ mkdir -p config/jwt
```

4. Generate private key
``` bash
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```

5. Generate public key
``` bash
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

>By key generation the terminal will ask you for passphrase. your passphrase is located at line ``43`` in the file **```./.env```**.

###### Set up Database:
6. Edit the variable ***DATABASE_URL*** at line ``28`` on file **```./.env```** with your database details
 
 > [More info about how you Configure the Database in Symfony](https://symfony.com/doc/current/doctrine.html#configuring-the-database)
 
7. Run ***WampServer*** (Or run Mysql separately, if you don't use Wamp).

8. Create the application database: 
```shell 
$ php bin/console doctrine:database:create
```

9. Create the Database script Tables/Schema:
```shell
$ php bin/console make:migration
```

10. Add tables in the Database:
```shell 
$ php bin/console --no-interaction doctrine:migrations:migrate
```

11. Load the initial data into the application database:
```shell 
$ php bin/console doctrine:fixtures:load -n
```

### Try the Application:
Only referenced customers or superadmin can access the APIs. therefore the API clients must be authenticated through Json Web Token. use the default user account to generate your token.

1. Run Symfony server:
```shell 
# start the server and display the log messages in the console
$ symfony server:start
 
# start the server in the background
$ symfony server:start [-d] 
```

###### Open your favorite browser

2. BileMo-API [Documentation interface](http://localhost:8000/api):
 >  you can here read the documentation of the API and try some endpoints. Log in with the account ``customerx`` to modify, create tricks & post comments.

3. BileMo-API [Backend interface ](http://localhost:8000): 
>  here you can manage the API Data (e.g. create, read, update or delete resources). Log in with the account ``admin`` to modify, create tricks & post comments.

###### Users accounts

username   | password | role            | endpoints access
---------- | -------- | --------------- | --------
 admin     |   demo   | SUPERADMIN | All APIs
 customerx |   demo   | USER       | *```GET /api/products```*  *```GET /api/products/{id}```*   *```GET /api/users```*  *```GET /api/users/{id}```*  *```POST /api/users```*  *```DELETE /api/users/{id}```*
 
### Run Tests
* ##### PHPUnit
    **PHPUnit** tests are located in the folder ``./tests``.
```shell
# Run all tests of the app
$ ./bin/phpunit

# Run tests for one class (replace CLASS_NAME with the name of class you want test)
$ ./bin/phpunit --filter CLASS_NAME
```

* ##### Behat
    **Behat** tests are located in the folder ``./features``.
```shell
# Run all tests of the app
$ vendor\bin\behat

# Run only one test (replace TAGS_NAME with the name of tag you want test)
$ vendor\bin\behat.bat --tags=TAGS_NAME
```

### Demo
**Visit the API demonstration:** 

[![BileMo-backoffice](https://img.shields.io/badge/BileMo-Backoffice-white.svg)](https://bilemo.it-bigboss.de/ 
"Manage your data")
 
[![BileMo](https://img.shields.io/badge/BileMo-Documentation-green.svg)](https://bilemo.it-bigboss.de/api "How to Use the API")

## Info 
###### Author
[**Walid BigBoss**](https://it-bigboss.de)

###### Copyright
Code released under the MIT License.

[![GitHub License](https://img.shields.io/github/license/bigboss-oualid/projet_6?label=License)](https://github.com/bigboss-oualid/project_7/blob/master/LICENSE.md)