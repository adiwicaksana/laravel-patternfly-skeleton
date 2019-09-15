<?php

return [

    // Tasks
    //
    // Here you can define in the `before` and `after` array, Tasks to execute
    // before or after the core Rocketeer Tasks. You can either put a simple command,
    // a closure which receives a $task object, or the name of a class extending
    // the Rocketeer\Abstracts\AbstractTask class
    //
    // In the `custom` array you can list custom Tasks classes to be added
    // to Rocketeer. Those will then be available in the command line
    // with all the other tasks
    //////////////////////////////////////////////////////////////////////

    // Tasks to execute before the core Rocketeer Tasks
    'before' => [
        'setup'   => [],
        'deploy'  => [],
        'cleanup' => [],
    ],

    // Tasks to execute after the core Rocketeer Tasks
    'after'  => [
        'setup'   => [],
        'deploy'  => [
            'npm install',
            'cp .env.training .env',
            'npm run dev',
            'php artisan key:generate',
            'php artisan config:cache',
            'php artisan config:clear',
            'chmod 777 -R storage/ bootstrap/cache',
//            'php artisan migrate',
//            'php artisan db:seed',
            'php artisan optimize',
            'chmod +x bump_version.sh',
            './bump_version.sh',
//            'apigen generate --source app --destination api --template-theme bootstrap -q',
//            'sudo /usr/sbin/service php7.2-fpm restart',
//            'sudo /usr/bin/supervisorctl restart all'
        ],
        'cleanup' => [],
    ],

    // Custom Tasks to register with Rocketeer
    'custom' => [],

];
