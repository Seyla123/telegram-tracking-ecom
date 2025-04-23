{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('chat') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.chat') }}</a></li>

<x-backpack::menu-item title="Test" icon="la la-question" :link="backpack_url('test')" />