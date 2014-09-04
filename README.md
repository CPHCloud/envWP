envWP
=====

A multi-envrionment development framework for Wordpress. Heavily inspired by Wordpress Skeleton by @markjaquith.

####The problem
Although in many aspects a great CMS, Wordpress is a very messy system. There is no great way to handle multiple environements and the system is not context-aware.

#### The solution (?)
By bootstrapping Wordpress we are able to provide all the proper functionality for handling environments. Wordpress itself is installed in a subdirectory and can be updated without changing this functionality. This is possible via a slightly different structure than in your usual WP install.

##### The folder layout
```
.
- content
— wp
index.php
{environment_name}.env
wp-config.php
{environment_name}-config.php
```
##### "Content"-folder
This folder is used instead of WP's own wp-content for storing plugins, themes, uploads etc.

##### "WP"-folder
This is Wordpress. It's all good.

##### {env_name}.env - the environment file
THis is the single most important file in envWP. The name you give it will be the name of the current environment. So a local environment would have a file called "local.env" where a staging environment would be called "staging.env". One environment one file. The contents of this file are described further down this document.

##### wp-config.php
This is the modded wp-config.php file that sets up the environment.

##### {env_name}-config.php
This file should hold the database information (which is normally set up in wp-config.php) for a given environment.

#### The environment
To setup your envWP correctly you need to have an environment file. Environment files are named like {env_name}.env. That means a local environment has exactly one .env file called local.env and staging has one called stagin.env. The environement files hold key->value pairs that define some basic setup like the base url, debuggin on/off and other envrionment specific vars. The keys and their values are seperated with triple colons - like `key ::: value`. At the very least the file should contain this `base_url ::: http://mysite.com` where 'http://mysite.com' is replaced with the actual url of the environment.

###### Properties
* base_url      - The base URL for the envrionment (string)
* debug         - Toggle debugging (On/Off - default: Off)
* debug_display - Toggle display errors on screen (On/Off - default: Off)
* language      - The locale to use for this environment (string - default: en_US)
* auto_updates  - Toggle WP auto-updates (On/Off - default: Off)
* table_prefix  - The table prefix to use for the database (string - default: wp_)
