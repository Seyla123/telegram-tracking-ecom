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
    |--------------------------------------------------------------------------
    | Default Model of Created By
    |--------------------------------------------------------------------------
    |
    | The default model that should be used for announcements
    |
    */
    'create_by_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Amount of records to create when seeding
    |--------------------------------------------------------------------------
    |
    | The amount of records to create when running the seeder.
    |
    */
    'seed_count' => 10,
];

