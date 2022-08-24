<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// Grupo de rotas para os usuários
$router->group(['prefix' => 'users','middleware' => 'auth'], function () use ($router) {
    // Rota para exibir todos os usuários
    $router->get('/', 'UserController@index');
    // Rota para exibir um usuário
    $router->get('/{id}', 'UserController@show');
    // Rota para cadastrar um usuário
    $router->post('/users','UserController@store');
    // Rota para editar um usuário
    $router->put('/{id}', 'UserController@update');
    // Rota para excluir um usuário
    $router->delete('/{id}', 'UserController@destroy');
});

// Grupo de rotas para os funcionários
$router->group(['prefix' => 'employees','middleware' => 'auth'], function () use ($router) {
    // Rota para exibir todos os funcionários
    $router->get('/', 'EmployeeController@index');
    // Rota para exibir um funcionário
    $router->get('/{id}', 'EmployeeController@show');
    // Rota para cadastrar um funcionário
    $router->post('/', 'EmployeeController@store');
    // Rota para editar um funcionário
    $router->put('/{id}', 'EmployeeController@update');
    // Rota para excluir um funcionário
    $router->delete('/{id}', 'EmployeeController@destroy');
});

// Grupo de rotas para os cargos dos funcionários 
$router->group(['prefix' => 'occupations','middleware' => 'auth'], function () use ($router) {
    // Rota para exibir todos os cargos
    $router->get('/', 'OccupationController@index');
    // Rota para exibir um cargo
    $router->get('/{id}', 'OccupationController@show');
    // Rota para cadastrar um cargo
    $router->post('/', 'OccupationController@store');
    // Rota para editar um cargo
    $router->put('/{id}', 'OccupationController@update');
    // Rota para excluir um cargo
    $router->delete('/{id}', 'OccupationController@destroy');
});

// Grupo de rotas para os departmentos da empresa 
$router->group(['prefix' => 'departments','middleware' => 'auth'], function () use ($router) {
    // Rota para exibir todos os departamentos
    $router->get('/', 'DepartmentController@index');
    // Rota para exibir um departamento
    $router->get('/{id}', 'DepartmentController@show');
    // Rota para cadastrar um departamento
    $router->post('/', 'DepartmentController@store');
    // Rota para editar um departamento
    $router->put('/{id}', 'DepartmentController@update');
    // Rota para excluir um departamento
    $router->delete('/{id}', 'DepartmentController@destroy');
});

