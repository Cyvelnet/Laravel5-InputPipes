# Laravel5-InputPipes

[![Build Status](https://travis-ci.org/Cyvelnet/Laravel5-InputPipes.svg)](https://travis-ci.org/Cyvelnet/Laravel5-InputPipes)
[![Latest Stable Version](https://poser.pugx.org/cyvelnet/laravel5-inputpipe/v/stable)](https://packagist.org/packages/cyvelnet/laravel5-inputpipe)
[![Latest Unstable Version](https://poser.pugx.org/cyvelnet/laravel5-inputpipe/v/unstable)](https://packagist.org/packages/cyvelnet/laravel5-inputpipe)
[![License](https://poser.pugx.org/cyvelnet/laravel5-inputpipe/license)](https://packagist.org/packages/cyvelnet/laravel5-inputpipe)


We all face the problems when user submit a form, and all these form data is a mess, sometime we even wanted to trim the inputs, cast them, and reformat them, in fact it is not the hardest thing in the world, but these small tasks really make our code look lengthy, and InputPipes comes into play.

`$inputs = Pipe::make(Input::only('email', 'name'), ['trim|lower', 'trim|ucword'])->get();`

This single line of code simply the time we spend on formatting input.

Require this package with composer using the following command:

`composer require cyvelnet/cyvelnet/laravel5-inputpipe`


Add the ServiceProvider to the providers array in config/app.php 

`Cyvelnet\InputPipe\InputPipeServiceProvider::class`

and register Facade

`Cyvelnet\InputPipe\Facades\PipeFacade::class`

##### Extend functionality


Sometime we wanted to add some logic which is currently not provided or not a general scope, no worry you can extend it to match you usage

`Pipe::extend('sample', function ($data, $parameters) {
    return $data;
});
`

The above scenario is perfectly fine, if only a small number of extra functionality to add on. When extensions get crowded, it is better to organize them into class.

``` php   
class CustomPipes extends \Cyvelnet\InputPipe\Pipes {     
     public function pipeFoo ($data, $parameters) {
         // process your logic;
     }
     public function pipeBar($data, $parameters) {
         // process another logic;
     }
}
```

`Pipe::extra(function() {
    return new CustomPipes;
});
`

Finally trigger your pipes

`$inputs = Pipe::make(Input::only('foo', 'bar'), ['foo', 'bar'])->get();`





