<?
return [
    "env" => "dev", // dev prod
    "errorHandler" => [ \App\Controllers\IndexController::class, 'error' ]  // [\Name\Space\MyClass::class, method from MyClass]
];