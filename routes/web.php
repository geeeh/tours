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

$router->get(
    '/', function () use ($router) {
        return $router->app->version();
    }
);

$router->group(
    ['prefix' => 'events'], function () use ($router) {
        $router->get('list', 'EventController@getAll');
        $router->post('create', 'EventController@create');
        $router->get('upcomingEvents', 'EventController@getUpcomingEvents');
        $router->put('updateEvent/{id}', 'EventController@update');
        $router->delete('deleteEvent/{id}', 'EventController@delete');
    }
);

$router->group(
    ['prefix' => 'categories'], function () use ($router) {
        $router->get('list', 'CategoryController@all');
        $router->post('create', 'CategoryController@create');
        $router->delete('delete/{id}', 'CategoryController@delete');
}
);

$router->group(
    ['prefix' => 'auth'], function () use ($router) {
        $router->post('register', 'UserController@create');
        $router->post('login', 'UserController@authenticate');
    }
);

$router->group(
    ['prefix' => 'companies'], function () use ($router) {
        $router->get('list', '');
        $router->post('create', 'CompanyController@create');
        $router->get('getCompanyByUser', 'CompanyController@getCompanyByCurrentUser');
    }
);

$router->group(
    ['prefix' => 'requests'], function () use ($router) {
        $router->post('send', 'RequestController@createRequest');
    }
);

$router->group(
    ['prefix' => 'users'], function () use ($router) {
        $router->get('getAllUsers', 'UserController@getAll');
        $router->get('{id}/companies', 'CompanyController@getAll');
        $router->post('{id}/companies', 'CompanyController@create');
        $router->delete('{id}/companies/{company_id}', 'CompanyController@delete');
    }
);

