envWP
=====

Multi-environment development for Wordpress. Heavily inspired by Wordpress Skeleton by @markjaquith.

#### Introduction
Although in many aspects a great CMS, Wordpress is a very messy app framework. There is no great way to handle multiple environements and the system is not context-aware.

By bootstrapping Wordpress we are able to provide all the proper functionality for handling environments. Wordpress itself is installed in a subdirectory and can be updated without changing this functionality. This is possible via a slightly different structure than in your usual WP install.

##### The folder layout
```
.
- content
— wp
index.php
wp-config.php
env-config.php
```
##### "Content"-folder
This folder is used instead of WP's own wp-content for storing plugins, themes, uploads etc.

##### "WP"-folder
This is Wordpress. It's all good.

##### wp-config.php
This is the modded wp-config.php file that sets up the environment.

##### env-config.php
This file holds all the relevant configurations for this setup in the $env_config array.

#### The environment
To setup your envWP correctly you need to have an environment config file.

###### Properties
* base_url      - The base URL for the environment (string)
* debug         - Toggle debugging (On/Off - default: Off)
* debug_display - Toggle display errors on screen (On/Off - default: Off)
* language      - The locale to use for this environment (string - default: en_US)
* auto_updates  - Toggle WP auto-updates (On/Off - default: Off)
* table_prefix  - The table prefix to use for the database (string - default: wp_)

####Things to add to .gitignore
While you will no doubt have custom needs for your .gitignore file the following is a good starting point for any envWP site. One advantage of using envWP is that you can completely leave out sensitive information from your repos.

    content/*
    !content/mu-plugins
    !content/plugins
    !content/languages
    !content/themes
    
    .htaccess
    salts.php
    env-config.php

#### Contribute
Pull requests are welcome :)

#### Practical
Maintained by [@supertroels](http://www.github.com/supertroels) for [@CPHCloud](http://www.github.com/CPHCloud)

