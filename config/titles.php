<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Titles for routes names
    |--------------------------------------------------------------------------
    |
    | Set Titles for each admin routes names
    */

    'admin' => 'dashboard',
    'users' => [
        'index' => 'usersGestion',
        'edit' => 'userEdit',
        'create' => 'userCreate',
    ],
    'contacts' => [
        'index' => 'contactsGestion',
    ],
    'posts' => [
        'index' => 'repairList',
        'edit' => 'postEdit',
        'create' => 'postCreate',
        'show' => 'postShow',
    ],
    'notifications' => [
        'index' => 'notificationsGestion',
    ],
    'comments' => [
        'index' => 'commentsGestion',
    ],
    'medias' => [
        'index' => 'mediasGestion',
    ],
    'settings' => [
        'edit' => 'settings',
    ],
    'categories' => [
        'index' => 'categoriesGestion',
        'create' => 'categoryCreate',
        'edit' => 'categoryEdit',
    ],

];