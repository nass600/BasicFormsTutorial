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

1.  First download the repository code to your local:

    ``` bash
        cd /path/to/workspace
        git clone git@github.com:nass600/BasicFormsTutorial.git
    ```

2.  Create the vhost file in `/etc/apache2/sites-available` directory. We will name it `basic-forms-tutorial`:

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

    ```
        127.0.0.1	basic-forms-tutorial.localhost
    ```

5. Restart Apache:

    ``` bash
        sudo service apache2 restart
    ```

6. Set up the parameters.yml. Copy this sample file and set your database's parameters inside the new file:

    ``` bash
        cp app/config/parameters.yml.sample app/config/parameters.yml
    ```

7. Run composer to install symfony in your project:

    ``` bash
        cd /path/to/workspace/basic-forms-tutorial
        composer install
    ```

8. Give permissions to your user and Apache to clean the cache and logs directories:

    ``` bash
        sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
        sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    ```

9. Now you should be able to see the welcome page of Symfony2 in the browser on this direction:

    http://basic-forms-tutorial.localhost/app_dev.php


## Tutorial

Now, we start with the tutorial. The application consists on a Developer's portfolio backoffice.

Imagine you want to create a portfolio including all the projects you have been involved in. At first glance, if you
have done one or two projects you could be thinking on just write HTML to describe these two projects, but if you will
continue developing sites, the creation of a backoffice where you can add easily new projects would be a good idea.

Said that, we can model our backoffice with an Entity Project where we can set:
* Project's title (type: string; nullable=false)
* Site's url (type: string; nullable=true)
* Description (type: string; nullable=true)

Before start coding go to the first branch:

``` bash
    git branch -b 0-start origin/0-start
```

On this branch we have:

* Removed the default AcmeDemoBundle
* Prepared routing
* Added bootstrap.css for templating styles
* Created the basic entity Project
* Created a list projects action on the Controller



### 1. Basic form

**Starting branch**: *0-start*

Move to the initial branch to start with this feature:

``` bash
    git checkout -b 0-start origin/0-start
```

Before starting with forms we need to create the database and its tables physically so execute this on a terminal:

``` bash
    app/console doctrine:database:create
    app/console doctrine:schema:update --force
```

If you go to our project's url: http://basic-forms-tutorial.localhost/app_dev.php you'll find out that there is no
projects stored yet. We do not have fixtures and we do not intend to create them so we are
going to create a new project feature, a basic form.

**Form rendering**

1.  Create a new file in `src/Ivelazquez/AdminBundle/Form/Type/ProjectFormType.php`. This file will contain the form
    structure.
2.  Add a new action to `ProjectController.php` called **newAction** where the form will be created and sent to the view
    to be rendered. This new action has the route `/new`
3.  Create a new template for this form in `src/Ivelazquez/AdminBundle/Resources/views/Project/new.html.twig` and add:

    ``` jinja
        <form action="{{ path('admin_project_new') }}" method="post" {{ form_enctype(form) }}>
            {{ form_widget(form) }}
            <input type="submit" class="btn" value="Create">
        </form>
    ```
4.  Add to the layout's sidemenu a link to our new action
5.  After that you will be able to see the form by clicking on the link created above.

**Form processing**

We have draw a form but right now it is not processing any data. The processing consists on binding the date, validate
it and persists the new entity

1.  Extend the newAction. Insert inside this function an if sentence for processing POST requests.
2.  Inside this sentence we are going to delegate all the form processing to a new class called ProjectFormHandler
    created in `/src/Ivelazquez/AdminBundle/Form/Handler/ProjectFormHandler.php`. Let's create it.
3.  Such class will be made up of a **constructor** receiving:
    -   The created form
    -   The received request
    -   The EntityManager

    And a function **process** were we have to:
    -   Bind the request to the form
    -   Check if it is valid: if valid, then persist and flush the new entity and return true; otherwise, return false.

4.  Back to the Controller, we create this new class and pass in the needed parameters. Then we process the form and
    checks the return value of the handler processing. If true, everything is ok and our entity has been persisted so
    we redirect the response to the Project's listing. If false we will return the form again with the errors.
5.  Finally, provide feedback to the user about the form processing failure or success via flashes. Write these flashes
    on a different twig template and include it in the form listing



### 2. Edit form

**Starting branch**: *1-basic-form*

Move to this branch to continue with this feature:

``` bash
    git checkout -b 1-basic-form origin/1-basic-form
```

Now we would like to update information on a particular project.

1.  Create an **editAction** in our `ProjectController.php`. The route is `/edit/{id}`.
2.  This action is almost identical to our first newAction so it creates a form and processes it if received a POST
    request. But, before create the form, we need to look up the database for the project we want to update.
3.  If we find this project, we need to prepopulate the form type when it is created with the found project's data.
4.  After finishing the controller we are going to modify the `list.html.twig` template by adding a link on each
    project's row that allows us to reach the edition form of each project.
5.  And, of course, create this new template `edit.html.twig`. This template is identical to `new.html.twig` unless the
    form action must point to the edit project route instead of new project route. Thus we also need to send the
    project's id we are updating to the view

**Refactor form templates**

As you can see, `new.html.twig` and `edit.html.twig` only differ in the form's action, an extra id passed in when
updating and the submit button's value text. In next steps we are going to theme the form so it would be great that
both actions render the same form code.

1.  Let's create another template named `form.html.twig` where we are going to copy the form's content. Right now,
    it means only this:

    ``` jinja
        {{ form_widget(form) }}
    ```

    Now it is just a line of code but, it is going to grow up when customizing the form.
2.  Now, include this new `form.html.twig` in both templates.



### 3. New widgets

**Starting branch**: *2-edit-form*

Move to this branch to continue with this feature:

``` bash
    git checkout -b 2-edit-form origin/2-edit-form
```

At this point, we have a functional creation and edition form. It is time to extend our Project Entity and therefore,
its form.

We are going to add to the Entity the following fields:

- finishDate (type: datetime; nullable=true)
- country (type: string; nullable=true)
- status (type: string; nullable=true)

**Extending the Entity**

1.  Create the new Entity's fields and respective getters and setters.
2.  Refresh the database schema via:
    ``` bash
        app/console doctrine:schema:update --force
    ```

**Extending the form**

1.  Add the finishDate field to the form type. If you set nothing on the form field it will render 5 selects according
    to the DateTime format. We do not take care about the time for this example and we rather prefer a unique text
    input with a date format like dd/M/yyyy (Tip: Use the date widget instead of datetime). It is not required.
2.  Add the a select widget where we can choose any country. (Tip: Symfony comes with Country and Language widgets and
    validation). So this field could be null, we are going to set an empty_value for display it in the select
    widget as default.
3.  For the status field, we need a radio widget so create an expanded choice widget. The possible choices are:
    finished, maintenance and developing

**Spliting up form rendering**



