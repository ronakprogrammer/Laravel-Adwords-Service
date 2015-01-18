<?php
namespace Library\Adwords;

use Illuminate\Support\ServiceProvider;
class AdwordsServiceProvider extends ServiceProvider {
    
    public function register() {
        $this->app->bind('adwordsService', function ($app){
            return new AdwordsService();
        });   
    }
}
?>