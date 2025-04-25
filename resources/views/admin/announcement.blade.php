@extends(backpack_view('blank'))

@php
    use Backpack\CRUD\app\Library\Widget;

    Widget::add()
        ->to('before_content')
        ->type('livewire')
        ->content('dashboard-widgets')
        ->wrapperClass('col-md-12 text-center');
@endphp
@section('content')
    <livewire:dashboard-widgets />
@endsection
