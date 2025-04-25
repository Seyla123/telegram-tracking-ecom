<?php

namespace SeavSeyla\Announcements\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SeavSeyla\Announcements\Database\Factories\AnnouncementFactory;

class Announcement extends Model
{

    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'announcements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['title', 'content', 'status', 'user_id'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('announcements.table_name', 'announcements'));
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        // Get the User model class name from the package configuration
        $userModelClass = config('announcements.user_model');

        // Get the foreign key column name from the package configuration
        $foreignKey = config('announcements.create_by_primary_key', 'user_id'); // Provide a default fallback

        // Ensure the user model class exists before trying to create the relationship
        if (!class_exists($userModelClass)) {
            \Log::error("Announcements package: Configured user model class '{$userModelClass}' not found.");
        }

        return $this->belongsTo($userModelClass, $foreignKey);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    protected static function newFactory(): AnnouncementFactory
    {
        return AnnouncementFactory::new();
    }

    public function sendButton()
    {
        if ($this->status === 'inactive') {
            return;
        }
        return '<a href="' . backpack_url('announcement/' . $this->id . '/send') . '" 
                class="btn btn-sm btn-success" 
                data-toggle="tooltip" 
                title="Send Announcement">
                <i class="la la-paper-plane"></i> Send
            </a>';
    }
}