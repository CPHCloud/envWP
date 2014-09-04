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
— wp (WP as a submodule)
index.php (Bootstraps WP)
wp-config.php (a modded version of wp-config.php)
{environment_name}.env
{environment_name}-config.sample (The config file for a specific environement)
```
##### Environment files
To setup your environment correctly you need to have an environment file. Environment files are named like environment.env. That means a local environment has exactly one .env file called local.env and staging has one called stagin.env. The environement files hold key->value pairs that define some basic setup like the base url, debuggin on/off and other envrionment specific vars. The keys and their values are seperated with triple colons - like `key ::: value`. At the very least the file should contain this `base_url ::: http://mysite.com` where 'http://mysite.com' is replaced with the actual url of the environment.
