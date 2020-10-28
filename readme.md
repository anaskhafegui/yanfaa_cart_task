### Introduction

   Ecommerce project with stunning performance, with shopping cart deal with most cases scenarios that business need it, all functionality was tested.
    
### Technology

<ul>
    <li>APIS by Laravel 5.8</li>
     <li><a href="https://laravelsd.com">Laravelsd database analysis</a></li>
    <li>MYSQL</li>
    <li>JWT</li>
    <li>JS</li>
</ul>
## Details
My Project structure was about selling products and every product has a variations products and every variation product has different variation type like normal product or sale product and aslo product variations has many stock and aslo created live view table to catch any changes will happen to product quantity by re-compute quantities had been rested in stock after add to cart and when it's remove from cart also recomputed again,
Its good for user experience and to know how many products are available to buy and business owner to mange this products quantity. 

## New Feature
1-you can add offer to product type related product amount or self product amount (product foregin key) and precentges and offer will calucluted automatically in total amount price .
2-two currencies add to cart index ( EGP , EUR ) just send request $currencyType across cart index.
3-Fixed Tax in cart class will add automatically to total.
## Database relations
    o products many to many with categories
    o products one to many with product variations 
    o products variations one to one with product variations type 
    o products variation one to many with stock
    o user many to many with cart 
    o user one to many with order
    o order many to many with products 
## new Database relations    
    o product has one to one offer
    o product has one to one offer related product 
## traits   
    o CanBeScoped   //  used as daynmaic filter for models  
    o Orderable    //  use for any model need to be orderable
    o HasPrice     //  used money php class to format price depending on currency type
    o HasChildren  // used to get any children of model 
    
## interface    
    o Cartinterface // Add Remove Empty methods
    
## classes    
    o ShoppingCart implements Cartinterface
    o WishlistCart implements Cartinterface
    o Money   //PHP CLASS
    
### Installation

Windows users:
- Download wamp: http://www.wampserver.com/en/
- Download and extract cmder mini: https://github.com/cmderdev/cmder/releases/download/v1.1.4.1/cmder_mini.zip
- Update windows environment variable path to point to your php install folder (inside wamp installation dir) (here is how you can do this http://stackoverflow.com/questions/17727436/how-to-properly-set-php-environment-variable-to-run-commands-in-git-bash)
 

cmder will be refered as console

##Mac Os, Ubuntu and windows users continue here:
- Create a database locally named `root` utf8_general_ci 
- setup mysql db for testing or test in memory 
- Download composer https://getcomposer.org/download/
- Pull Laravel/php project from git provider.
- Rename `.env.example` file to `.env`inside your project root and fill the database information.
  (windows wont let you do it, so you have to open your console cd your project root directory and run `mv .env.example .env` )
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate`
- Run `./vendor/bin/phpunit` make sure all tests passing successfully 
- Run `php artisan serve`

#####You can now access your project at localhost:8000 :)

## If for some reason your project stop working do these:
- `composer install`
- `php artisan migrate`
