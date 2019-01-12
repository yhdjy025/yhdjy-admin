<?php

return [

    /**
     * The endpoint to access the routes.
     */
    'url' => 'routes',

    /**
     * The middleware(s) to apply before attempting to access routes page.
     */
    'middlewares' => [],

    /**
     * Indicates whether to enable pretty routes only when debug is enabled (APP_DEBUG).
     */
    'debug_only' => true,

    /**
     * The methods to hide.
     */
    'hide_methods' => [
        'HEAD',
    ],

    /**
     * The routes for homepage.
     */
    'homepage_matching' => [
    	'index/err',
    	'index/log',
    	'login',
    	'logout',
    	'register',
    	'password/reset',
    	'password/email',
        'test'
    ],

    /**
     * The routes to hide with regular expression
     */
    'hide_matching' => [
        '#^oauth#',
        '#^_debugbar#',
        '#^routes$#',
    	'#^aetherupload#'
    ],
];
