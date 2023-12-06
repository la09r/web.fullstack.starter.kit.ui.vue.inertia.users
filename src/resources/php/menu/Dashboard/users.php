<?php

$accessUsers = Illuminate\Support\Facades\Gate::allows('isUserAccess');

$itemsNotUsersAccess = [
    [
        'title' => __('Profile'),
        'link'  => route('users.web.route.dashboard.profile'),
    ],
];

$itemsWithUsersAccess = [
    [
        'title' => __('Users'),
        'link'  => route('users.web.route.dashboard.user.list'),
    ],
    [
        'title' => __('Roles'),
        'link'  => route('users.web.route.dashboard.role.list'),
    ],
    [
        'title' => __('Welcome'),
        'link'  => route('users.web.route.dashboard.welcome'),
    ],
];

if(!$accessUsers)
{
    return $itemsNotUsersAccess;
}
else
{
    return [...$itemsNotUsersAccess, ...$itemsWithUsersAccess];
}
