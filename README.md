## General Info
***
* Project Name : FileStorageProject
* Author : Bentouhami Belhaj Mohamed
* Matricule : 56387
* Group : D111
<br>

***

### 3 ways to run my project 

1. [Via a link using an online server](#online-deployement)
2. [In local by using xampp with http protocol](#online-deployement)
3. [In local by using xampp with https](#online-deployement)

<br>

## Online Deployement
***
Here is the link to access to my project : [https://filestorage.alwaysdata.net/]

<br>


## Local Project Without HTTPS
***
* You need to launch the appache server and mySQL at the control panel of Xampp

* Setup the data base with phpmyadmin
and create your .env with the appropriate information like name of your database, etc.

* Then execute the next command line in the computerProject directory
```
composer install        (-> to install dependencies of projects in the vendor folder)
npm install             (-> to install modules defined in the dependencies section of the package.json file )
npm run dev             (-> run the dev script defined in the project’s package.json file.)
php artisan migrate     (->create table in your data base configured)
php artisan serve       (->lauch the app in this address : http://127.0.0.1:8000)

```

<br>

## Important

Don't forget to add to the next given path file generated by vendor to place the next given code so that our master key is generated at each log in
here : [FileStorageProject\vendor\laravel\ui\auth-backend\AuthenticatesUsers.php]
```
 $password =  [

            'password' => $request->password,
        ];

        $uuid = User::getUuid(Auth::id())[0]->uuid;
        $hashed = hash("sha512", $uuid . $password['password'] . $uuid);
        Session()->put('masterKey', $hashed);

```

<br>

## Local Project With HTTPS
***

### Set up database
* First, you need to create the data base in Xampp with phpmyadmin
* Then, you need to configure the server apache for use https
<br>
It is necessary to generate a certificate for that it will be necessary to download two files that here the link
 [filesConf.zip](https://www.youtube.com/redirect?event=video_description&redir_token=QUFFLUhqbWJkNGZfazBCRGllN3czUnNhNml2b20xbnRpUXxBQ3Jtc0tuSzZ3STlLSWZMZHBES3dJN01lak1UMnZFdWRmNTgwZGJRRTB3MjRrTDZtRjJjbWVfMEJNdDVxd0l4VkhKNDB0cm5UcWUyQThzOTJocEw5ZWFnc210X2dlb3d5SGdTTVQ3NUt0THVKbFlHX2FlMTRmOA&q=https%3A%2F%2Fgithub.com%2Fprolongservices%2FCodeigniter-4%2Freleases%2Fdownload%2Fv1%2Fxampp.zip&v=zrbaE1Wdviw)
 <br>These two files will be put in a new folder crt in the appache folder that will be like that located in xampp/apache/crt
in the first file you will change the commonName_default by localhost for example and replace also field DNS.1 by localhost

* Then you need to execute the second file and enter the domaine name like localhost

Unce finished a file containing a certificate and a matches managed key
Install the certificate.
Then go to the xampp/apache /conf/extra/htpd-xampp.conf folder and add the configuration of your project like this
```
<VirtualHost *:443>
  DocumentRoot "PathOfTheProject"
  ServerName localhost
  ServerAlias  *.localhost
<Directory PathOfTheProject>
         DirectoryIndex index.php
         AllowOverride All
         Require all granted
         Order allow,deny
         Allow from all
</Directory>
  
  # Your SSL configuration. Update the File and KeyFile information
  # below to point to your SSL certificate.
  	SSLEngine on
	SSLCertificateFile "crt/localhost/server.crt"
	SSLCertificateKeyFile "crt/localhost/server.key"
</VirtualHost>
```

* finaly you must add the next line In this path Window\System32\Drivers\etc\hosts
```
127.0.0.1 localhost
```

### Set up the project


At the end, you need to start apache server and mySql at the control panel of Xampp

and execute the next command in the computerProjectDirectory
```
composer install        (-> to install dependencies of projects in the vendor folder)
npm install             (-> to install modules defined in the dependencies section of the package.json file )
npm run dev             (-> run the dev script defined in the project’s package.json file.)
php artisan migrate     (-> create table in your data base configured)


```
Then you can enter https:://localhost in the url

Same notice that at the top add [this part](#important)







