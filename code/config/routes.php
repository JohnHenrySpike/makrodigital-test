<?
use App\Controllers\CommentController;
use App\Controllers\IndexController;
use App\Controllers\BlogController;


Route::get("/", [IndexController::class, "index"]);
Route::post("/login", [IndexController::class, "login"]);

Route::get("/blog", [BlogController::class, "index"]);
Route::get("/blog/page/{page}", [BlogController::class, "index"], ['page'=>1]);
Route::get("/blog/{id}", [BlogController::class, "show"]);
Route::get("/blog/{id}/comments", [BlogController::class, "show_comments"]);

Route::post("/blog", [BlogController::class, "add"]);
Route::put("/blog/{id}", [BlogController::class, "update"]);
Route::delete("/blog/{id}", [BlogController::class, "delete"]);



Route::post("/comment/{post_id}", [CommentController::class, "add"]);
Route::put("/comment/{id}", [CommentController::class, "update"]);