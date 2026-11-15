<?php

use Illuminate\\Support\\Facades\\Artisan;

Artisan::command('about:school', function () {
    $this->comment('Madresehyar Shahidi API bootstrap is installed.');
})->purpose('Display school platform bootstrap information');
