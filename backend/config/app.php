<?php
return [
 'name'=>env('APP_NAME','Schoolyar'),
 'env'=>env('APP_ENV','production'),
 'debug'=>(bool)env('APP_DEBUG',false),
 'url'=>env('APP_URL','http://localhost'),
 'timezone'=>'Asia/Tehran',
 'locale'=>'fa',
 'fallback_locale'=>'en',
 'faker_locale'=>'fa_IR',
 'key'=>env('APP_KEY'),
 'previous_keys'=>array_filter(explode(',',(string)env('APP_PREVIOUS_KEYS',''))),
 'cipher'=>'AES-256-CBC',
 'maintenance'=>['driver'=>env('APP_MAINTENANCE_DRIVER','file'),'store'=>env('APP_MAINTENANCE_STORE','database')],
];