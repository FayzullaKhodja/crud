<?php
namespace Khodja\Crud\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class Crud extends Facade {
 
    protected static function getFacadeAccessor()
    {
    	return 'crud';
    }
 
}