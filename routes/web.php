<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'user'],function ()use($router){
    $router->post('create-chat-room','MultiChannelController@createChatRoom');
});
$router->group(['prefix' => 'agent'],function ()use($router){
   $router->post('login','MultiChannelController@loginAgent');
   $router->get('get-chat-room/{agent_id}','MultiChannelController@getChatRoomForAgent');
   $router->post('resolve-room/{token}','MultiChannelController@resolveChatRoom');
});
$router->get('chat-room/{token}','MultiChannelController@chatRoom');
$router->post('chat-room/{token}','MultiChannelController@sendMessage');
$router->get('status-room/{token}','MultiChannelController@cekRoomStatus');
