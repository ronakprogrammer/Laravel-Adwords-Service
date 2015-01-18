<?php
namespace Library\Adwords;

use \Illuminate\Support\Facades\Facade;

class AdwordsFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'adwordsService';
    }
}
?>