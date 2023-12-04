<?php

$accessUsers = Illuminate\Support\Facades\Gate::allows('isUserAccess');

$itemsNotUsersAccess = [
    [
        'title' => __('Profile'),
        'link'  => route('web.route.dashboard.profile'),
    ],
];

$itemsWithUsersAccess = [
    [
        'title' => __('Users'),
        'link'  => route('web.route.dashboard.user.list'),
    ],
    [
        'title' => __('Roles'),
        'link'  => route('web.route.dashboard.role.list'),
    ],
    [
        'title' => __('Welcome'),
        'link'  => route('route.dashboard.welcome'),
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
