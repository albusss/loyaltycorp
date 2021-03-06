<?php
declare(strict_types=1);

/** @var \Laravel\Lumen\Routing\Router $router */

// MailChimp group
$router->group(['prefix' => 'mailchimp', 'namespace' => 'MailChimp'], function () use ($router) {
    // Lists group
    $router->group(['prefix' => 'lists'], function () use ($router) {
        $router->post('/', 'ListsController@create');
        $router->get('/{listId}', 'ListsController@show');
        $router->put('/{listId}', 'ListsController@update');
        $router->delete('/{listId}', 'ListsController@remove');
    });

    // Member group
    $router->group(['prefix' => 'lists/{mailChimpId}'], function () use ($router) {
        $router->post('/members', 'MemberController@create');
        $router->get('/members', 'MemberController@show');
        $router->put('/members/{memberId}', 'MemberController@update');
        $router->delete('/members/{memberId}', 'MemberController@remove');
    });
});
