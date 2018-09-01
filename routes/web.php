<?php

//Users
Route::resrouce('/users', 'UsersController');
//Roles
Route::resource('/roles', 'RolesController')->only(['index', 'edit', 'update']);