<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $a = '{"update_id":469073572,"callback_query":{"id":"1236763387199392829","from":{"id":287956415,"is_bot":false,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","language_code":"en"},"message":{"message_id":976,"from":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"chat":{"id":287956415,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","type":"private"},"date":1634206333,"video":{"duration":11,"width":672,"height":848,"file_name":"IMG_3374.MOV","mime_type":"video\/quicktime","thumb":{"file_id":"AAMCAgADGQMAAgPQYWgCffN0nE0SlNQ8SROBV_nNEOgAAlISAAJohTlLwjLyJLjNrSABAAdtAAMhBA","file_unique_id":"AQADUhIAAmiFOUty","file_size":10461,"width":254,"height":320},"file_id":"BAACAgIAAxkDAAID0GFoAn3zdJxNEpTUPEkTgVf5zRDoAAJSEgACaIU5S8Iy8iS4za0gIQQ","file_unique_id":"AgADUhIAAmiFOUs","file_size":1548955},"reply_markup":{"inline_keyboard":[[{"text":"prev","callback_data":"media_"},{"text":"next","callback_data":"media_9"}]]}},"chat_instance":"6427932795797264207","data":"media_9"}}';
    $a = json_decode($a, true);
    dd($a);
    return view('welcome');
});
