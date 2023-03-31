<?
use App\Controllers\IndexController;
use App\Controllers\BlogController;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\VarDumper\VarDumper;

Route::get("/", [IndexController::class, "index"]);

Route::post("/login", [IndexController::class, "login"]);
Route::get("/blog", [BlogController::class, "index"]);
Route::get("/blog/page/{page}", [BlogController::class, "index"], ['page'=>1]);
Route::get("/blog/{id}", [BlogController::class, "show"]);
Route::put("/blog/{id}", [BlogController::class, "update"]);
Route::delete("/blog/{id}", [BlogController::class, "delete"]);
Route::post("/blog", [BlogController::class, "add"]);

Route::get("/i", function(){
    return phpinfo();
});