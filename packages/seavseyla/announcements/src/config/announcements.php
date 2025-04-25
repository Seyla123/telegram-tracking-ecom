<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Default Table Name
   |--------------------------------------------------------------------------
   |
   | The default table name to use for the announcements table.
   |
   */

    'table_name' => 'announcements',

    /*
    |--------------------------------------------------------------------------
    | Default Created By Primary Key
    |--------------------------------------------------------------------------
    |
    | The default primary key to use for the created by table.
    |
    */

    'create_by_primary_key' => 'user_id',

    /*
     * The user model that should be used for announcements
     */
    'user_model' => \App\Models\User::class,

    'seed_count' => 10,
];
