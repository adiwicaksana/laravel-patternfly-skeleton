<?php

return [

    /* Use Role Based Access Controll (RBAC) */
    'enable' => true,

    /**
     * Role : Administrator
     */
    'administrator' => [
        /* Home */
        'home',

        /* User */
        'user.index',
        'user.create',
        'user.edit',
        'user.show',
        'user.destroy',

        /* Role */
        'role.index',
        'role.create',
        'role.edit',
        'role.show',
        'role.destroy',

        /* Store */
        'store.index',
    ],

    /**
     * Role : Others....
     */

];
