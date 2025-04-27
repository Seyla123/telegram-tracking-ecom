<?php

namespace SeavSeyla\Announcements\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use SeavSeyla\Announcements\Models\Announcement;
use SeavSeyla\Announcements\Requests\AnnouncementRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AnnouncementCrudController
 * @package App\Http\Controllers
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AnnouncementCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Announcement::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/announcement');
        CRUD::setEntityNameStrings('announcement', 'announcements');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
        CRUD::column('content');

        CRUD::column('user')
            ->type('closure')
            ->label('Created By')
            ->function(function ($entry) {
                return $entry->user ? $entry->user->name : '-';
            });

        CRUD::column('status')->wrapper([
            'class' => function ($crud, $column, $entry) {
                return match ($entry->status) {
                    'active' => 'badge bg-success',
                    'inactive' => 'badge bg-warning',
                    default => 'badge bg-secondary',
                };
            }
        ]);

        CRUD::addButtonFromModelFunction('line', 'Send', 'sendButton', 'beginning');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AnnouncementRequest::class);
        CRUD::field('title');
        CRUD::field('content')->type('textarea');
        CRUD::field('status')
            ->type('select_from_array')
            ->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
                'draft' => 'Draft'
            ])
            ->default('draft');
        // get current user id
        Announcement::creating(function ($entry) {
            $entry->user_id = backpack_user()->id;
        });
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Show operation is loaded.
     * 
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->setupListOperation(); // reuse the list operation setup

        // Customize the status column display for show view
        CRUD::column('status')->type('text')->wrapper([
            'class' => function ($crud, $column, $entry) {
                return match ($entry->status) {
                    'active' => 'badge bg-success',
                    'inactive' => 'badge bg-warning',
                    default => 'badge bg-secondary',
                };
            }
        ]);

        // Add timestamp columns
        CRUD::column('created_at')
            ->type('datetime')
            ->format('DD/MM/YYYY HH:mm');

        CRUD::column('updated_at')
            ->type('datetime')
            ->format('DD/MM/YYYY HH:mm');

    }

}
