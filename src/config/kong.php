<?php

/*
 * This file is part of KongServicePublisher
 * Contains configuration parameters to register service and routes
 */

return [
    'kong_api_url'      => '', // The Kong api url with port (ex. http://172.18.0.1:8001)

    'service_name'      => '', // The service name to register
    'service_protocol'  => 'http', // The protocol for service communication, default is http
    'service_host'      => '', // The host for service
    'service_port'      => '', // The exposed port for service
    'service_path'      => '', // The path to service (ex. /v1/test)

    /**
     * Routes Params
     */
    'route_name'  => '', // The route name, used to identify routes. If empty, the name generated with routes paths
    'route_hosts' => ['localhost'], // Hosts that will access routes
    /**
     * Optional parameter for routes to register
     * if not informed, all routes of the application will be registered
     * ex. /v1/autenticate
     */
    'route_paths'      => [], // Paths
    'route_strip_path' => false,
    'methods'          => ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
];
