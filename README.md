BulkAPI
=======

In order to write hybrid or native mobile application  - you often needs to communicate with your bussiness logic at your server.
You probably use and/or prefer JSON based REST API - likewise I do :) for better parsing and binding data.
While I was working with SenchaTouch framework (for frontend) I noticed that the REST URI style is not switable always - afterward I started working with PHP/MySQL/Apache to meet my requirements ... Perhaps I found indeed.

The BulkAPI library will let you write your business logic  efficiently using Object oriented PHP and currently only with MySQL database (though I'm working to add PDO instead mysqli, so that we can use 12 kindof DB)
The BulkAPI is designed to write API specially for Mobile Application and more particularly HTML5 hybrid applications.
So, most of required backend functionality has done on this library.

Have a look:
========
 		# Efficient and dynamic URL and parameter passing
 
 		# Lot's of module to devide the workflow
 
 		# Google Cloud Messaging module added to send push messages
 		
 		# Full RESTful based header setup
 
 		# Easy and clear OOP coding
 
 		# Secured file system and very efficient uploading process
 
 		# XHR2 uploading process
 
 		# PhoneGap Camera photo upload system included
 
 		# Customizable file I/O and directory system
 
 		# Basic statistics provided for API usage
 	
 and lot's more things - though it is an initial release.
 
Getting started:
========
 Basically the application is very based on Object Oriented PHP but we're not using MVC pattern - because we designed
 this Application for using API development and more specifically for mobile application's business logic and we already  aware of that thing.
 
 Please read all files documentation, those will also lead you to understanding the Application concept and coding style as well.
 
 Well, we have directory designed as :
      
      /application
      /core
      /system
      /users
      .htaccess
      index.php
 
 for getting started open -> system -> configuration.php -> That file is well organized and this will lead you to configure your settings and offcourse our Application doesn't require too much configuartion to start.
 
 /core/modules directory containing all plugins/modules those will let you use external facilities, by just executing 
    
    $this->load('module name');
 
 Let's write down some codes for real life API call:
 Open /application -> Application.php -> Now we can see 
       
       class Application extends BulkAPI {
       
 there we have to go , let's think we have to create an API call name /login.json
 it would be very simple to just start the making the API call -
 Write a new method under the Appication class (described above) with access modifier to "public final" 
 Our system will only recognize methods with final modifier for making a new API or any call.
 lets look at the example codes:
 
     class Application extends BulkAPI {
         
         public final function login()
         {
             $params = $this->load('parameters'); // $_GET / $_POST parameters loaded
             $mysqli = $this->load('mysql'); // load mysqli module 
             
             if(!empty($params->username) || !empty($params->password))
             {
             
                 //Your login verifing code should e gose here
             
             }else{
                
                throw new Exception('Username/Password field can not be empty');
                
             }
         }
        
     }
 
 	
for better API documentation http://bulkapi.anonnafrontend.com
