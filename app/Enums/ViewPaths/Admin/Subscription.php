<?php

namespace App\Enums\ViewPaths\Admin;

enum Subscription
{

    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.subscription.index',
        ROUTE =>'admin.business-settings.subscription.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.subscription.update-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

}