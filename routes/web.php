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
    $not_member = '{"update_id":469073662,"my_chat_member":{"chat":{"id":287956415,"first_name":"𝕱𝖎𝖗𝖉𝖆𝖛𝖘⁹⁹","username":"UserFS","type":"private"},"from":{"id":287956415,"is_bot":false,"first_name":"𝕱𝖎𝖗𝖉𝖆𝖛𝖘⁹⁹","username":"UserFS","language_code":"en"},"date":1634920546,"old_chat_member":{"user":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"status":"member"},"new_chat_member":{"user":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"status":"kicked","until_date":0}}}';
    $member = '{"update_id":469073663,"my_chat_member":{"chat":{"id":287956415,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","type":"private"},"from":{"id":287956415,"is_bot":false,"first_name":"\ud835\udd71\ud835\udd8e\ud835\udd97\ud835\udd89\ud835\udd86\ud835\udd9b\ud835\udd98\u2079\u2079","username":"UserFS","language_code":"en"},"date":1634920548,"old_chat_member":{"user":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"status":"kicked","until_date":0},"new_chat_member":{"user":{"id":2058468217,"is_bot":true,"first_name":"Interactive Media","username":"interactive_media_bot"},"status":"member"}}}';
    $not_member = json_decode($not_member, true);
    $member = json_decode($member, true);
    dd($not_member, $member);
    return view('welcome');
});
