BasicFormsTutorial
==================

Step-by-step tutorial for building basic forms in Symfony 2.1.x. The level required is beginner

This tutorial starts with a raw Symfony 2.1.3 project and a few Enitites created to be used during the guide.
On each step we are going to extend our forms system adding a new feature to create a fully functional project with
basic forms including a creation and edition form, validation, form theming ...

Each feature will be included in a new branch and includes the former ones. So the branches will be named according to
*step-feature*

But, first of all, here are the steps to install the project:

## Installation

1.  First download the repository code to your local:

    ``` bash
        cd /path/to/workspace
        git clone git@github.com:nass600/BasicFormsTutorial.git basic-forms-tutorial
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

4. Add the ServerName to `/etc/hosts` file to be accesible via browser:

    ```
        127.0.0.1	basic-forms-tutorial.localhost
    ```

5. Restart Apache:

    ``` bash
        sudo service apache2 restart
    ```

6. Set up the parameters.yml. Copy the included sample file and set your database's parameters inside the new file:

    ``` bash
        cp app/config/parameters.yml.sample app/config/parameters.yml
    ```

7.  Download composer:

    ``` bash
        curl -s https://getcomposer.org/installer | php
        sudo mv composer.phar /user/bin/composer
    ```

8. Run composer to install symfony in your project:

    ``` bash
        cd /path/to/workspace/basic-forms-tutorial
        composer install
    ```

9. Give permissions to your user and the Apache user in order to clean the cache and logs directories:

    ``` bash
        sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
        sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    ```

10. Clean the cache:

    ``` bash
        app/console cache:clear
    ```

11. Now you should be able to see the welcome page of Symfony2 in the browser on this direction:

    http://basic-forms-tutorial.localhost/app_dev.php


## Tutorial

Now, we start with the tutorial. The application consists on a Developer portfolio's backoffice.

Imagine you want to create a portfolio including all the projects you have been involved in. At first glance, if you
have made one or two projects you could be thinking on just writing HTML to present these two projects to the world, but
if you will continue developing sites, the creation of a backoffice where you can add easily new projects would be a
good idea.

Said that, we can model our backoffice with an Entity Project where we can set:
* Project's title (type: string; nullable=false)
* Site's url (type: string; nullable=true)
* Description (type: string; nullable=true)

Before start coding go to the first branch:

``` bash
    git checkout -b 0-start origin/0-start
```

On this branch we have done some initial tasks like:

* Removed the default AcmeDemoBundle
* Prepared routing
* Added bootstrap.css for templating styles
* Created the basic Project Entity
* Created a list projects action on the Controller


Follow the online Symfony documentation along with this tutorial. In each feature I will point to the related symfony
component.

### 1. Basic form

**Starting branch**: *0-start*

Move to the initial branch to start with this feature:

``` bash
    git checkout -b 0-start origin/0-start
```

**Symfony doc**: http://symfony.com/doc/current/book/forms.html

Before starting with forms we need to create the database and its tables physically so execute this on a terminal:

``` bash
    app/console doctrine:database:create
    app/console doctrine:schema:update --force
```

If you go to our project's url: http://basic-forms-tutorial.localhost/app_dev.php you will find out that there is no
projects stored yet. We do not have fixtures and we do not intend to create them so we are
going to create a new project feature, a basic form.


**Form rendering**

1.  Create a new file in `src/Ivelazquez/AdminBundle/Form/Type/ProjectFormType.php`. This file will contain the form
    structure.
2.  Add a new action to `ProjectController.php` called **newAction** where the form will be created and sent to the view
    to be rendered. This new action has the route `/new` and the name `admin_project_new`.
3.  Create a new template for this form in `src/Ivelazquez/AdminBundle/Resources/views/Project/new.html.twig` and add:

    ``` jinja
        <form action="{{ path('admin_project_new') }}" method="post" {{ form_enctype(form) }}>
            {{ form_widget(form) }}
            <input type="submit" class="btn" value="Create">
        </form>
    ```
4.  Add to layout's sidemenu a link to our new action
5.  After that you will be able to see the form by clicking on the link created above.

**Form processing**

We have drawn a form but right now it is not processing any data. The processing consists on binding the date, validate
it and persists/flush the new entity to the database.

1.  Extend the newAction. Insert inside this function an if sentence clause for processing POST requests.
2.  Inside this sentence we are going to delegate all the form processing to a new class called ProjectFormHandler
    living in `/src/Ivelazquez/AdminBundle/Form/Handler/ProjectFormHandler.php`. Let's create it.
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

**Symfony doc**: http://symfony.com/doc/current/book/forms.html

Now we would like to update information on a particular project.

1.  Create an **editAction** in our `ProjectController.php`. The route is `/edit/{id}` with name `admin_projetc_edit`.
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

**Symfony doc**: http://symfony.com/doc/current/book/forms.html#built-in-field-types
                 http://symfony.com/doc/current/book/forms.html#rendering-each-field-by-hand

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

**Spliting up the form rendering**

Now we are going to render the form by fields to give more flexibility to our frontend developer. In the next part of
the tutorial (theming) this would be useful to customize the validation error messages.

1.  Remove this sentence from `form.html.twig`:

    ``` jinja
        {{ form_widget(form) }}
    ```

2.  Start creating the HTML you want and add each field via:

    ``` jinja
        {{ form_label(form.task) }}
        {{ form_errors(form.task) }}
        {{ form_widget(form.task) }}
    ```

    This allows you to use the elements you need of each field.



### 4. Validation

**Starting branch**: *3-new-widgets*

Move to this branch to continue with this feature:

``` bash
    git checkout -b 3-new-widgets origin/3-new-widgets
```

**Symfony doc**: http://symfony.com/doc/current/book/validation.html

Right now, this form validates according to the Symfony default Type Constraints but we want to enhance the security of
our data input adding some more Constraints and security to this form.

If you inspect your Entity we can add the following rules:

* title (We need this value so it is required)
* url (We need a well-formed url)
* description (For instance, only 255 characters)
* finishDate (A valid date. In addition we want the exact format described in the widget)
* country (Valid country)

Let's go to our Project Entity and add the convinient Asserts. You can find all Symfony Constraints under
`vendor/symfony/symfony/src/Component/Validator/Constraints` and choose the one that fit your needs. In addition,
change the default message if you want.

Note: Remember to disable default HTML5 Validation to see our backend validation in action. You can do this via adding
`novalidate` attribute to the `<form>` tag

The date field needs special mention. In addition to a Date Constraint we need to set some variables in the
ProjectFormType. Imagine you insert 22222222 as date, Symfony will try to create a DateTime with such string and the
Type Validation will show you a different message than the one you set in your Date Constraint.

To fix this we are going to set our custom message in the invalid_message parameter in the type in the finishDate
field.



### 5. Theming

**Starting branch**: *4-validation*

Move to this branch to continue with this feature:

``` bash
    git checkout -b 4-validation origin/4-validation
```

**Symfony doc**: http://symfony.com/doc/current/book/forms.html#form-theming

Let's pimp the form layout. As you can see we still have the default lists of errors when a field is not valid. We
rather red alerts for example. So we are using Twitter Bootstrap we would like to get a tooltip when hovering
a not valid input.

For achieve this task we can override the default twig template for simple widgets:

*   First of all, look for `form_div_layout.html.twig`. This template contains the HTML of any widget.
*   Afterwards copy the widget we need and copy it to a new file `form-theme.html.twig` located at Resources/views/Form
    directory. In this file we are going to code all template overrides.
*   Insert at the top of the `form.html.twig` the next line:

    ``` jinja
        {% form_theme form "IvelazquezAdminBundle:Form:form-theme.html.twig" %}
    ```

*   Now is time to change the html structure of the widgets need. We want to change only the text widgets adding all
    the redundant HTML we insert previously when splitting up the form in just one place. So move the div.controls
    surrounding the widgets for title, url and finishDate to the form-theme template.
*   Copy also from `form_div_layout.html.twig` the textarea widget for the description field.
*   We have replaced the widget structures we need and now we have to adapt it to Bootsrap. We are going to fire the
    tooltip when hover on an input or textarea containing the class `error` so let's remove the `form_errors` helper
    on the form and add such check inside the widget itself. It consists on merge the class `error` to the already set
    classes if errors exist. Add some style like red border to it.
*   Last, bootstrap tooltips get the displaying message from an attribute called data-original-title so add the message
    in this attribute for displaying the validation message.
*   The final text widget will look like this:

    ``` jinja
    {% block form_widget_simple %}
        {% spaceless %}
            {% set type = type|default('text') %}
            {% if errors|length > 0 %}
                {% set attr = attr|merge({'class': (attr.class|default('') ~ ' error')|trim}) %}
            {% endif %}
            <div class="controls">
                <input type="{{ type }}" {{ block('widget_attributes') }} {% if value is not empty %}value="{{ value }}" {% endif %}
                {% if errors|length > 0 %}
                    rel="tooltip"
                    data-original-title="
                        {% for error in errors %}
                            {{ error.messageTemplate|trans(error.messageParameters, 'validators') ~'<br>' }}
                        {% endfor %}
                    "
                {% endif %}
                />
            </div>
        {% endspaceless %}
    {% endblock form_widget_simple %}
    ```


## Tutorial Finished

Now you have all the basic skills about handling forms. We have learn to create forms and their widgets, validate and
process the input data and modify the look and feel of them

To check the whole code just go to the last branch: `git checkout -b 5-theming origin/5-theming`