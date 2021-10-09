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
    $a = '{"update_id":469073172,"message":{"message_id":99,"from":{"id":287956415,"is_bot":false,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","language_code":"en"},"chat":{"id":287956415,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","type":"private"},"date":1633717819,"reply_to_message":{"message_id":98,"from":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"chat":{"id":287956415,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","type":"private"},"date":1633717705,"text":"Bo\'limni tanlang"},"location":{"latitude":40.107456,"longitude":65.374427}}}';
    $a = json_decode($a, true);
    dd($a);
    return view('welcome');
});
