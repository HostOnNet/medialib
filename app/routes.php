<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', 'SearchController@search');
Route::any('media', 'MediaController@listMedia'); # displays all media, paginated
Route::post('media/save', 'MediaController@save');

Route::get('watch/{media_id}/{ref_page}','WatchController@show')->where('media_id','[0-9]+')->where('ref_page','.+');

Route::get('links','links@show');
Route::get('links_edit','links@edit');
Route::post('links_save','links@save');

Route::get('add', 'AddController@index');
Route::post('add/save', 'AddController@save');
Route::get('rebuild_media_likes','AddController@rebuild_media_likes');
Route::get('thumb', 'AddController@thumb'); # create thumbnail for all medias
Route::get('thumb/{media_id}/{time}', 'AddController@thumb_single')->where('media_id','[0-9]+')->where('time','[0-9]+'); # create thumbnail for speficied video from specified time.


Route::get('tags','TagController@index');
Route::get('tag_rebuild', 'TagController@rebuild');
Route::get('tag/{tag_id}', 'TagController@search')->where('tag_id','[0-9]+');
Route::post('tag/watch','TagController@watch');

Route::get('playlists','PlaylistController@listPlaylist'); # shows all playlist
Route::get('playlist/empty/{playlist_id}','PlaylistController@emptyPlaylist')->where('playlist_id', '[0-9]+'); # delete medias from playlist
Route::get('playlist/watch/{playlist_id}','PlaylistController@playlist_watch')->where('playlist_id', '[0-9]+'); # redirect to first media in playlist
Route::get('playlist/{playlist_id}','PlaylistController@view')->where('playlist_id', '[0-9]+'); # set skip_to_bookmark and redirect to playlist/watch/(:num)
Route::get('playlist_make/{playlist_id}','PlaylistController@make')->where('playlist_id','[0-9]+');

Route::get('ajax/like/(:any)','AjaxController@like');
Route::get('ajax_tag_suggest','AjaxController@tag_suggest');
Route::post('ajax/media_tag_time_like','AjaxController@media_tag_time_like');
Route::get('settings','SettingsController@index');
Route::post('settings','SettingsController@save');


Route::get('tools_validate_thumb','ToolsController@validate_thumb');
Route::get('tools_validate_media_tag_time','ToolsController@validate_media_tag_time');
Route::get('tools_join_medias_single','ToolsController@join_medias_single');
Route::post('tools_join_medias_single','ToolsController@join_medias_single_post');
Route::get('test','TestController@test');

Route::get('playlist_seed_generate/{seed_id}','PlaylistSeedController@generate')->where('seed_id','[0-9]+');
Route::get('playlist_seed_edit/{seed_id}','PlaylistSeedController@edit')->where('seed_id','[0-9]+');
Route::post('playlist_seed_edit/{seed_id}','PlaylistSeedController@editSave')->where('seed_id','[0-9]+');
Route::get('playlist_seed_add','PlaylistSeedController@add');
Route::post('playlist_seed_add','PlaylistSeedController@addSave');
