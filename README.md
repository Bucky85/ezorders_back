# ezorders_back
ezorders back app

# To set up the dev environment 
Requirement : 
  - PHP 7 installed and included in the system path
  - Composer installed and included in the system path
  
 To set up : 
  - I. In php.ini :
      - extension_dir = "C:\php\%extension_dir%\"
      - extension=php_mongodb.dll
      - extension=openssl
  - II. Add the driver https://pecl.php.net/package/mongodb/1.4.2/windows in C:\php\%extension_dir%
  - III. Install mongodb
  - IV. git clone https://github.com/Bucky85/ezorders_back.git
  - V. composer install
  - VI. To launch web server : composer run-script dev_init
