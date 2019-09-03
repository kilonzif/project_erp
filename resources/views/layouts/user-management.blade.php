@extends('layouts.app')
@section('content')
    <ul class="nav nav-pills mb-1" style="background-color: #fff;">
        @ability('webmaster|super-admin', 'add-user')
        <li class="nav-item">
            <a class="btn active square mr-1 {{isRouteActiveClass('user-management.users')}}" href="{{route('user-management.users')}}" aria-expanded="true"><i class="ft-users"></i> Users</a>
        </li>
        @endability

        @role('webmaster')
        <li class="nav-item">
            <a class="btn active square mr-1 {{isRouteActiveClass('user-management.permissions')}}" href="{{route('user-management.permissions')}}" aria-expanded="true"><i class="icon-key"></i> Permissions</a>
        </li>
        @endrole

        @ability('webmaster|super-admin', 'add-roles')
        <li class="nav-item">
            <a class="btn square mr-1 {{isRouteActiveClass('user-management.roles')}}" href="{{route('user-management.roles')}}" aria-expanded="true"><i class="icon-tag"></i> Roles</a>
        </li>
        @endability

        @ability('webmaster|super-admin|admin', 'add-institutions')
        <li class="nav-item">
            <a class="btn square mr-1 {{isRouteActiveClass('user-management.institutions')}}" href="{{route('user-management.institutions')}}" aria-expanded="true"><i class="fa fa-suitcase"></i> Institutions</a>
        </li>
        @endability

        @ability('webmaster|super-admin|admin', 'add-aces')
        <li class="nav-item">
            <a class="btn square mr-1 {{isRouteActiveClass('user-management.aces')}}" href="{{route('user-management.aces')}}" aria-expanded="true"><i class="icon-graduation"></i> ACEs</a>
        </li>
        @endability
        {{--<li class="nav-item">--}}
            {{--<a class="btn square mr-1 {{isRouteActiveClass('user-management.groups')}}" href="{{route('user-management.groups')}}" aria-expanded="true"><i class="icon-users"></i> Groups</a>--}}
        {{--</li>--}}
    </ul>
    @yield('um-content')
@endsection