/*
|--------------------------------------------------------------------------
| {ucf_name} Routes
|--------------------------------------------------------------------------
*/
Route::get('{name}', '{Controller}@getIndex');
Route::get('{name}/data', '{Controller}@getData');
Route::post('{name}/form', '{Controller}@postForm');
Route::post('{name}/delete', '{Controller}@postDelete');