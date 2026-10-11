<?php

use Cake\Routing\Route\InflectedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(InflectedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Dashboards', 'action' => 'index']);
        $builder->connect('/Users/register', ['controller' => 'Users', 'action' => 'add']);
        $builder->connect('/users/register', ['controller' => 'Users', 'action' => 'add']);
        $builder->connect('/Settings', ['controller' => 'Users', 'action' => 'index']);
        $builder->connect('/settings', ['controller' => 'Users', 'action' => 'index']);
        $builder->connect('/CandidatesJobs/hire/{id}', ['controller' => 'CandidatesJobs', 'action' => 'hire'])
            ->setPass(['id'])
            ->setPatterns(['id' => '\d+']);
        $builder->connect('/CandidatesJobs/reject/{id}', ['controller' => 'CandidatesJobs', 'action' => 'reject'])
            ->setPass(['id'])
            ->setPatterns(['id' => '\d+']);
        $builder->connect('/CandidatesJobs/reopen/{id}', ['controller' => 'CandidatesJobs', 'action' => 'reopen'])
            ->setPass(['id'])
            ->setPatterns(['id' => '\d+']);
        $builder->connect('/pages/*', 'Pages::display');

        $builder->connect('/{controller}', ['action' => 'index']);
        $builder->connect('/{controller}/{action}/*', []);
    });
};
