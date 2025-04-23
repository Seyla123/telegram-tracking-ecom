<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use SeavSeyla\ApiResponse\Traits\ApiResponse;

/**
 * Class TestController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TestController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return view('admin.test', [
            'title' => 'Test',
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                'Test' => false,
            ],
            'page' => 'resources/views/admin/test.blade.php',
            'controller' => 'app/Http/Controllers/Admin/TestController.php',
        ]);
    }
    public function response()
    {
       return $this->successResponse([]);
    }
}
