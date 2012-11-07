BasicFormsTutorial
==================

Step-by-step tutorial for building basic forms in Symfony 2.1.x. The level required is beginner

This tutorial starts with a raw Symfony 2.1.3 project and a few Enitites created to be used during the guide.
On each step we are going to extend our forms system adding a new feature to create a fully functional project with
complex forms including collections, several widgets, data transformers, validation ...

Each feature will be included in a new branch and includes the former ones. So the branches will be named according to
*step-feature*

But, first of all, here are the steps to install the project:

## Installation

1. First download the repository code to your local:

``` bash
    cd /path/to/workspace
    git clone git@github.com:nass600/BasicFormsTutorial.git
```

2. Create the vhost file in `/etc/apache2/sites-available` directory. We will name it `basic-forms-tutorial`:

``` xml
    <VirtualHost *:80>
        DocumentRoot "/path/to/workspace/basic-forms-tutorial/web"
        ServerName basic-forms-tutorial.localhost
        DirectoryIndex app.php

        <Directory "/path/to/workspace/basic-forms-tutorial/web">
            Options Indexes FollowSymLinks Includes ExecCGI
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
```

3. Enable the new site in Apache:

``` bash
    sudo a2ensite basic-forms-tutorial
```

4. Add the ServerName to /etc/hosts file to be accesible via browser:

    127.0.0.1	basic-forms-tutorial.localhost

5. Restart Apache:

``` bash
    sudo service apache2 restart
```

5. Run composer to install symfony in your project:

``` bash
    cd /path/to/workspace/basic-forms-tutorial
    composer install
```

6. Give permissions to your user and Apache to clean the cache and logs directories:

``` bash
    sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
```

7. Now you should be able to see the welcome page of Symfony2 in the browser on this direction:

    http://basic-forms-tutorial.localhost/app_dev.php


## Tutorial

Now, we start with the tutorial. The application consists on a Developer's portfolio backoffice.

Imagine you want to create a portfolio including all the projects you have been involved in. At first glance, if you
have done one or two projects you could be thinking on just write HTML to describe these two projects, but if you will
continue developing sites, the creation of a backoffice where you can add easily new projects would be a good idea.

Said that, we can model our backoffice with an Entity Project where we can set:
* Project's title
* Site's url
* Description

Before start coding go to the first branch:

``` bash
    git branch -b 0-start origin/0-start
```

# 1. Basic form

Let's create an Entity called Project and the three fields described above

