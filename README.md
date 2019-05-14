<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Sommerce</h1>
    <br>
</p>


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer install
~~~

### DB Connection

rename test_db.php -> db.php

### Run database migration

~~~
yii migrate
~~~

Now you should be able to access the application through the following URL, assuming `sommerce` is the directory
directly under the Web root.

~~~
http://localhost/sommerce/web/
~~~

