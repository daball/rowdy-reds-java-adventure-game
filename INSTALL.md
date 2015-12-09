# Installation Guide

I'm going to try to keep this short and simple, as installing the individual components are beyond the scope of this documentation. This could take a short while to install, even without the automated deployment environment we used. I recommend you set aside at least 8 hours to perform this installation. It took me 2 days to get GitLab working. Some of that was because the server kept running out of memory, corrupting the database, and part of it was all of the small things that make installing GitLab a gigantic pain. So, if you want to do the automated installation environment, good luck to you getting it done in a day.

## Requirements

Any operating system capable of running the following components:

- Web server capable of running:
  - PHP 5 or higher
- Java Servlet server capable of running:
  - Oracle Java 8

These are not presumably required in production.

- Development server:
  - GitLab
    - Ruby/Rails
    - Postgres
    - custom git post-receive hook for automatic deployment
    - integration with Redmine
    - custom nginx proxy
  - Redmine
    - Ruby/Rails
    - Postgres
    - integration with GitLab git container
    - custom nginx proxy
- Iteration 2:
  - MySQL (current) *deprecated at Iteration 3*

These are not presumably required in production.

- Development tools:
  - Command-line interface PHP 5 or higher
    - Composer
    - PHPUnit *tests unstable at time of submission*
    - Xdebug enabled in php.ini
  - Node.js tools:
    - Node.js
    - NPM
    - Bower
  - Java tools:
    - Eclipse
    - JUnit
    - Selenium (for UAT tests within Iteration 0) *tests deprecated*

## Install Operating System

We have tested all components on Ubuntu Server 14.04.3 LTS on Amazon Web Services/EC2.

In could presumably work in Windows, however, pay close attention to security settings. It has never been tested to work.

## Install Java Runtime

Be sure to use Oracle Java SE 8 or higher. We have only tested with Oracle JRE.

## Install Java Development Kit

Be sure to use Oracle Java SE SDK 8 or higher. We have only tested with Oracle JDK.

## Install Web Server

We have tested the PHP side of the application using Nginx and Apache2. It could presumably work with any server designed to handle PHP requests.

## Install PHP5 or Higher

We have tested the application works with PHP 5.5.9-1ubuntu4.14/Zend Engine 2.5.0 with Zend OPcache 7.0.3 with Xdebug 2.2.3.

*NOTICE:* Xdebug is not necessary, but if you want any meaningful messages in the event of an error, Xdebug will provide the necessary stack trace. It is also required in development if you wish to run code coverage tools during any unit testing. Xdebug will cause PHP to run slower in production.

## Configure PHP

You must configure, at a minimum, the following line in php.ini:

    allow_url_include = On

Without this line, PHP will not be able to connect to the Java Servlet service in order to process Java requests. This will produce errors almost immediately into the application, since the includes required to connect to Java are automatically invoked upon each request.

## Configure Web Server to Accept PHP Connections

You must allow PHP to accept requests whenever .php is encountered along a URL route. No further configuration is necessary.

## Install Servlet Server

We tried to use Apache Tomcat 8, but for some reason it never wanted to load the servlet. Apache Tomcat 7 worked, so we used it. There is no hypothetical reason it cannot work with Apache Tomcat 8 (nor any other JEE server), but we know Tomcat 7 works.

## Obtain Source Code

Check out [README.md](README.md) for instructions on how to obtain project source code.

## Deploy Source Code To Web Server

Copy (or move) source code from the `master` branch into the web server or virtual host directory. Just suppose it is installed at `/srv/http`. Put all the files in there.

## Set Security Permissions On Hosted Folder

If you have `/srv/http` as the base directory and you use `www-data` as the user name and group name:

`chown -R www-data:www-data /srv/http`

*NOTICE:* Not sure how important it is, but our folder structure is setup so that `/srv/http` contains many subfolders. The top-level folder is owned by `git:www-data`, where `git` represents the user name for GitLab and www-data represents the group for nginx. It is chmod u+rwx, g+rx, a+rx. Then each of our subfolders are owned the same way, but have chmod u+rwx, g+rwx. So in theory, while git needs write access to the top-level folder, www-data does not. Neither should require write access any deeper, but since we used an automatic deployment script on the git server, git automatically set up the permissions at each deployment.

## Configure Web Server For Hosting

Be sure your web server is setup to serve files out of the `./public` folder. None of the other files need to be accessed directly from the public web server. You might set up your web server to host files at `/srv/http/public` for instance.

## Create Java Cache Directory Within Web Server Directory

You need to make sure you have a `__player_tablet_cache` directory within the root folder of the base installation folder. If you deployed to `/srv/http` then it will be at `/srv/http/__player_tablet_cache`. This folder needs to be owned by Tomcat. On our server, Tomcat 7 runs as the user `tomcat7`. You might try something like:

`sudo chown -R tomcat7:tomcat7 /srv/http/__player_tablet_cache`
`sudo chmod u+rwx /srv/http/__player_tablet_cache`
`sudo chmod g+rwx /srv/http/__player_tablet_cache`
`sudo chmod a+x /srv/http`
`sudo chmod a+x /srv`

We stored the Java and Java class folders within the PHP context because there is no easy way to determine the JEE context folder from within Tomcat. So since PHP could easily identify its own path, we used it instead. Hard-coded paths are generally bad for business.

## Deploy web archive to Java Server

The `.war` file you need is within the base folder of the installation directory under `./java/bin/deploy`. It gets automatically built from within Eclipse.

On our server, using Tomcat, we simply copy the `.war` file to the Tomcat 7 webapps directory and it automatically deploys and starts the servlet.

*Caveat:* Due to our versioning scheme, you'll need to rename the file during the copy process.

If you installed everything on Ubuntu the same way you did, Tomcat7 should use `/var/lib/tomcat7/webapps/` as its automatic deployment directory. Remember you'll need to set permissions once you deploy the application, otherwise Tomcat may not be able to read the file to deploy it. If it is the case that it doesn't automatically deploy, you might try restarting the Tomcat server once you set the correct permissions.

*Notice:* Our automated deployment script handles all these tasks very swiftly.

Destination file names:

  - `cc-development.war`
  - `cc-staging.war`
  - `cc-production.war`

Assuming you installed to `/srv/http` and did everything else just like we did, you can simply issue:

`sudo cp /srv/http/java/deploy/CustomJavaBridge.war /var/lib/tomcat7/webapps/cc-production.war`

`sudo chown tomcat7:tomcat7 /var/lib/tomcat7/webapps/cc-production.war`

## Set APPLICATION_ENV variable on web server

Valid values are `development`, `staging`, and `production`.

We used nginx, so you can add the following to our FastCGI section.

    server ... {
      location ~ \php$ {
        ...
        fastcgi_param APPLICATION_ENV production;
      }
    }

You could of course use Apache2 instead. If you do, you'll need to use the `SetEnv` directive.

Reload your server config. `sudo service nginx reload` for example.

## I've Added Some Cheat Sheets

All of the relevant server configuration we used has been added to the [server-config](server-config) folder, just for your convenience.

## Cross Your Fingers

If it doesn't work, call me, email me. Seriously, I can write about how to install this all day, but I think you should now know most of the dirty details at this point.

David Ball

http://www.daball.me/go/resume/

## Our Installation Environment

Here's what we did. Your mileage may vary.

- GoDaddy domains:
  - daball.me records:
    - A rowdyred-dev 52.2.1.252
    - A rowdyred-staging 52.2.1.252
    - A rowdyred 52.2.1.252
    - A gitlab 52.2.1.252
    - A redmine 52.2.1.252
- Ubuntu Server LTS 14.04.3 ()
  - nginx (Name-based virtual hosting) (User Name uses www-data)
    - Listening on all interfaces
    - Development: Deployed at `/srv/http/cc-development`, bound to rowdyred-dev.daball.me:80
    - Staging: Deployed at `/srv/http/cc-staging`, bound to rowdyred-staging.daball.me:80
    - Production: Deployed at `/srv/http/cc-production`, bound to rowdyred.daball.me:80
    - GitLab: Proxy configuration, bound to gitlab.daball.me:443
    - Redmine: Proxy configuration, bound to redmine.daball.me:443
  - Apache Tomcat7
