{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="Telegram" icon="la la-telegram">
    <x-backpack::menu-dropdown-item title="Test Telegram" icon="la la-telegram" :link="backpack_url('test-telegram')" />
    <x-backpack::menu-dropdown-item title="Telegram Users" icon="la la-telegram" :link="backpack_url('telegram-user')" />
    <x-backpack::menu-dropdown-item title="Telegram Chat" icon="la la-telegram" :link="backpack_url('chat')" />
    <x-backpack::menu-dropdown-item title="Announcements" icon="la la-bullhorn" :link="backpack_url('announcement')" />
    <x-backpack::menu-dropdown-item title="Announcement Logs" icon="la la-bullhorn" :link="backpack_url('announcement-log')" />
</x-backpack::menu-dropdown>
