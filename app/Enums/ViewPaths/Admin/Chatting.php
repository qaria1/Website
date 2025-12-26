<?php

namespace App\Enums\ViewPaths\Admin;

enum Chatting
{

    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.chatting.index',
    ];
    const MESSAGE = [
        URI => 'message',
        VIEW => 'admin-views.chatting.index',
    ];

    // const VIEW = [
    //     URI => 'chat',
    //     VIEW => 'admin-views.delivery-man.chat'
    // ];

    // const MESSAGE = [
    //     URI => 'ajax-message-by-delivery-man',
    //     VIEW => ''
    // ];

    // const ADD = [
    //     URI => 'admin-message-store',
    //     VIEW => ''
    // ];


}
