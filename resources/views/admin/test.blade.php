@extends(backpack_view('blank'))

@section('content')
<section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
    <h1 class="text-capitalize mb-0" bp-section="page-heading">Test</h1>
    <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">Page for Test</p>
</section>
<section class="content container-fluid animated fadeIn" bp-section="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    Go to <code>{{ $page }}</code> to edit this view or <code>{{ $controller }}</code> to edit the controller.
                </div>
            </div>
        </div>
    </div>
    <livewire:dashboard-widgets/>
@endsection
