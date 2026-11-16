<?php
return [
 'driver'=>env('SESSION_DRIVER','database'),'lifetime'=>env('SESSION_LIFETIME',120),'expire_on_close'=>false,
 'encrypt'=>env('SESSION_ENCRYPT',true),'connection'=>env('SESSION_CONNECTION'),'table'=>env('SESSION_TABLE','sessions'),
 'store'=>env('SESSION_STORE'),'lottery'=>[2,100],'cookie'=>env('SESSION_COOKIE','schoolyar_session'),
 'path'=>env('SESSION_PATH','/'),'domain'=>env('SESSION_DOMAIN'),'secure'=>env('SESSION_SECURE_COOKIE',false),
 'http_only'=>env('SESSION_HTTP_ONLY',true),'same_site'=>env('SESSION_SAME_SITE','lax'),'partitioned'=>false,
];