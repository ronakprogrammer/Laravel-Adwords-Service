# Laravel-Adwords-Service

Connects with Google Adwords api and fetches the adwords performance reports via api, just pass on variables and set the oauth tokens details

# How to use the service provider class
Follow the below instructions on how to use this class
1)  "require": {
        "googleads/googleads-php-lib": "dev-master"
    },
	"classmap": [
            "app/libraries",
            "app/libraries/adwords"
        ]

Download the latest google adwords library by just adding the above package in composer.json file and run the command php composer.phar update.

2) After successfull  library installation open the file auth.ini(/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/AdWords/auth.ini) and settings.ini (/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/AdWords/settings.ini) and Replace the Developer token

3) Place the repository files in your respective project directory.

4)  Open the laravel config file app.php and make following changes
'providers' => array(
                'Library\Adwords\AdwordsServiceProvider',
	)

'aliases' => array(
                'AdwordsBO'         => 'Library\Adwords\AdwordsFacade'
	)

5) Run command php composer.phar dump-autoload

6) Open the file Clientservice.php and refer the downloadReport function before this make sure to inject the AdwordsService class object with Client service class.
<?php
class ClientServiceServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('clientService', function ($app){
            return new ClientService(
                $app->make('adwordsService')    
            );
            
        });
    }
}
?>
