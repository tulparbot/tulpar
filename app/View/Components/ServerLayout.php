<?php

namespace App\View\Components;

use App\Models\Server;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServerLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public Server $server)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        $_own_servers = auth()->user()->servers;
        $server = $this->server;
        $nav_links = [
            'General' => [
                [
                    'svg' => '<svg class="w-8 h8 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 36 36" preserveAspectRatio="xMidYMid meet" fill="currentColor"><title>dashboard-line</title><path class="clr-i-outline clr-i-outline-path-1" d="M25.18,12.32l-5.91,5.81a3,3,0,1,0,1.41,1.42l5.92-5.81Z"></path><path class="clr-i-outline clr-i-outline-path-2" d="M18,4.25A16.49,16.49,0,0,0,5.4,31.4l.3.35H30.3l.3-.35A16.49,16.49,0,0,0,18,4.25Zm11.34,25.5H6.66a14.43,14.43,0,0,1-3.11-7.84H7v-2H3.55A14.41,14.41,0,0,1,7,11.29l2.45,2.45,1.41-1.41L8.43,9.87A14.41,14.41,0,0,1,17,6.29v3.5h2V6.3a14.47,14.47,0,0,1,13.4,13.61H28.92v2h3.53A14.43,14.43,0,0,1,29.34,29.75Z"></path><rect x="0" y="0" width="36" height="36" fill-opacity="0"></rect></svg>',
                    'title' => 'Dashboard',
                    'href' => route('servers.dashboard', $server),
                    'active' => request()->routeIs('servers.dashboard'),
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" fill="currentColor"><path d="M13.5,8.4c0-0.1,0-0.3,0-0.4c0-0.1,0-0.3,0-0.4l1-0.8c0.4-0.3,0.4-0.9,0.2-1.3l-1.2-2C13.3,3.2,13,3,12.6,3c-0.1,0-0.2,0-0.3,0.1l-1.2,0.4c-0.2-0.1-0.4-0.3-0.7-0.4l-0.3-1.3C10.1,1.3,9.7,1,9.2,1H6.8c-0.5,0-0.9,0.3-1,0.8L5.6,3.1C5.3,3.2,5.1,3.3,4.9,3.4L3.7,3C3.6,3,3.5,3,3.4,3C3,3,2.7,3.2,2.5,3.5l-1.2,2C1.1,5.9,1.2,6.4,1.6,6.8l0.9,0.9c0,0.1,0,0.3,0,0.4c0,0.1,0,0.3,0,0.4L1.6,9.2c-0.4,0.3-0.5,0.9-0.2,1.3l1.2,2C2.7,12.8,3,13,3.4,13c0.1,0,0.2,0,0.3-0.1l1.2-0.4c0.2,0.1,0.4,0.3,0.7,0.4l0.3,1.3c0.1,0.5,0.5,0.8,1,0.8h2.4c0.5,0,0.9-0.3,1-0.8l0.3-1.3c0.2-0.1,0.4-0.2,0.7-0.4l1.2,0.4c0.1,0,0.2,0.1,0.3,0.1c0.4,0,0.7-0.2,0.9-0.5l1.1-2c0.2-0.4,0.2-0.9-0.2-1.3L13.5,8.4z M12.6,12l-1.7-0.6c-0.4,0.3-0.9,0.6-1.4,0.8L9.2,14H6.8l-0.4-1.8c-0.5-0.2-0.9-0.5-1.4-0.8L3.4,12l-1.2-2l1.4-1.2c-0.1-0.5-0.1-1.1,0-1.6L2.2,6l1.2-2l1.7,0.6C5.5,4.2,6,4,6.5,3.8L6.8,2h2.4l0.4,1.8c0.5,0.2,0.9,0.5,1.4,0.8L12.6,4l1.2,2l-1.4,1.2c0.1,0.5,0.1,1.1,0,1.6l1.4,1.2L12.6,12z"></path><path d="M8,11c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3C11,9.6,9.7,11,8,11C8,11,8,11,8,11z M8,6C6.9,6,6,6.8,6,7.9C6,7.9,6,8,6,8c0,1.1,0.8,2,1.9,2c0,0,0.1,0,0.1,0c1.1,0,2-0.8,2-1.9c0,0,0-0.1,0-0.1C10,6.9,9.2,6,8,6C8.1,6,8,6,8,6z"></path><rect fill="none" width="16" height="16"></rect></svg>',
                    'title' => 'Settings',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" width="40" height="32" viewBox="0 0 40 32"><title>embed2</title><path d="M26 23l3 3 10-10-10-10-3 3 7 7z"></path><path d="M14 9l-3-3-10 10 10 10 3-3-7-7z"></path><path d="M21.916 4.704l2.171 0.592-6 22.001-2.171-0.592 6-22.001z"></path></svg>',
                    'title' => 'Embed Messages',
                    'href' => route('servers.embed-messages', $server),
                    'active' => request()->routeIs('servers.embed-messages'),
                ],
            ],
            'Module Settings' => [
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"></rect><rect x="4" y="18" width="13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect><rect x="17" y="6" width="13" height="36" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></rect><rect x="30" y="26" width="13" height="16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></rect></svg>',
                    'title' => 'Levels',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16" fill="currentColor"><path fill="currentColor" d="M14 14.2c0 0 0 0 0 0 0-0.6 2-1.8 2-3.1 0-1.5-1.4-2.7-3.1-3.2 0.7-0.8 1.1-1.7 1.1-2.8 0-2.8-2.9-5.1-6.6-5.1-3.5 0-7.4 2.1-7.4 5.1 0 2.1 1.6 3.6 2.3 4.2-0.1 1.2-0.6 1.7-0.6 1.7l-1.2 1h1.5c1.6 0 2.9-0.5 3.7-1.1 0 0.1 0 0.1 0 0.2 0 2 2.2 3.6 5 3.6 0.2 0 0.4 0 0.6 0 0.4 0.5 1.7 1.4 3.4 1.4 0.1-0.1-0.7-0.5-0.7-1.9zM7.4 1c3.1 0 5.6 1.9 5.6 4.1s-2.6 4.1-5.8 4.1c-0.2 0-0.6 0-0.8 0h-0.3l-0.1 0.2c-0.3 0.4-1.5 1.2-3.1 1.5 0.1-0.4 0.1-1 0.1-1.8v-0.3c-1-0.8-2.1-2.2-2.1-3.6 0-2.2 3.2-4.2 6.5-4.2z"></path></svg>',
                    'title' => 'Auto Responder',
                    'href' => route('servers.auto-responders', $server),
                    'active' => request()->routeIs('servers.auto-responders'),
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor"><g><rect fill="none" x="0"></rect></g><g><g><path d="M19,9l1.25-2.75L23,5l-2.75-1.25L19,1l-1.25,2.75L15,5l2.75,1.25L19,9z M11.5,9.5L9,4L6.5,9.5L1,12l5.5,2.5L9,20l2.5-5.5 L17,12L11.5,9.5z M19,15l-1.25,2.75L15,19l2.75,1.25L19,23l1.25-2.75L23,19l-2.75-1.25L19,15z"></path></g></g></svg>',
                    'title' => 'Auto Roles',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M16.32 7.1A8 8 0 1 1 9 4.06V2h2v2.06c1.46.18 2.8.76 3.9 1.62l1.46-1.46 1.42 1.42-1.46 1.45zM10 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM7 0h6v2H7V0zm5.12 8.46l1.42 1.42L10 13.4 8.59 12l3.53-3.54z"></path></svg>',
                    'title' => 'Timers',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 2A1.5 1.5 0 0 1 5 3.5V5H3.5a1.5 1.5 0 1 1 0-3zM6 5V3.5A2.5 2.5 0 1 0 3.5 6H5v4H3.5A2.5 2.5 0 1 0 6 12.5V11h4v1.5a2.5 2.5 0 1 0 2.5-2.5H11V6h1.5A2.5 2.5 0 1 0 10 3.5V5H6zm4 1v4H6V6h4zm1-1V3.5A1.5 1.5 0 1 1 12.5 5H11zm0 6h1.5a1.5 1.5 0 1 1-1.5 1.5V11zm-6 0v1.5A1.5 1.5 0 1 1 3.5 11H5z"></path></svg>',
                    'title' => 'Custom Commands',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z"></path><path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"></path></svg>',
                    'title' => 'Welcomer',
                    'href' => route('servers.welcomer', $server),
                    'active' => request()->routeIs('servers.welcomer'),
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z"></path><path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"></path></svg>',
                    'title' => 'Leaving System',
                    'href' => route('servers.leaving-system', $server),
                    'active' => request()->routeIs('servers.leaving-system'),
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" fill="currentColor"><path d="M13.5,8.4c0-0.1,0-0.3,0-0.4c0-0.1,0-0.3,0-0.4l1-0.8c0.4-0.3,0.4-0.9,0.2-1.3l-1.2-2C13.3,3.2,13,3,12.6,3c-0.1,0-0.2,0-0.3,0.1l-1.2,0.4c-0.2-0.1-0.4-0.3-0.7-0.4l-0.3-1.3C10.1,1.3,9.7,1,9.2,1H6.8c-0.5,0-0.9,0.3-1,0.8L5.6,3.1C5.3,3.2,5.1,3.3,4.9,3.4L3.7,3C3.6,3,3.5,3,3.4,3C3,3,2.7,3.2,2.5,3.5l-1.2,2C1.1,5.9,1.2,6.4,1.6,6.8l0.9,0.9c0,0.1,0,0.3,0,0.4c0,0.1,0,0.3,0,0.4L1.6,9.2c-0.4,0.3-0.5,0.9-0.2,1.3l1.2,2C2.7,12.8,3,13,3.4,13c0.1,0,0.2,0,0.3-0.1l1.2-0.4c0.2,0.1,0.4,0.3,0.7,0.4l0.3,1.3c0.1,0.5,0.5,0.8,1,0.8h2.4c0.5,0,0.9-0.3,1-0.8l0.3-1.3c0.2-0.1,0.4-0.2,0.7-0.4l1.2,0.4c0.1,0,0.2,0.1,0.3,0.1c0.4,0,0.7-0.2,0.9-0.5l1.1-2c0.2-0.4,0.2-0.9-0.2-1.3L13.5,8.4z M12.6,12l-1.7-0.6c-0.4,0.3-0.9,0.6-1.4,0.8L9.2,14H6.8l-0.4-1.8c-0.5-0.2-0.9-0.5-1.4-0.8L3.4,12l-1.2-2l1.4-1.2c-0.1-0.5-0.1-1.1,0-1.6L2.2,6l1.2-2l1.7,0.6C5.5,4.2,6,4,6.5,3.8L6.8,2h2.4l0.4,1.8c0.5,0.2,0.9,0.5,1.4,0.8L12.6,4l1.2,2l-1.4,1.2c0.1,0.5,0.1,1.1,0,1.6l1.4,1.2L12.6,12z"></path><path d="M8,11c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3C11,9.6,9.7,11,8,11C8,11,8,11,8,11z M8,6C6.9,6,6,6.8,6,7.9C6,7.9,6,8,6,8c0,1.1,0.8,2,1.9,2c0,0,0.1,0,0.1,0c1.1,0,2-0.8,2-1.9c0,0,0-0.1,0-0.1C10,6.9,9.2,6,8,6C8.1,6,8,6,8,6z"></path><rect fill="none" width="16" height="16"></rect></svg>',
                    'title' => 'Moderation',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7,9.5C7,8.67,7.67,8,8.5,8S10,8.67,10,9.5c0,0.83-0.67,1.5-1.5,1.5S7,10.33,7,9.5z M12,17.5c2.33,0,4.31-1.46,5.11-3.5 H6.89C7.69,16.04,9.67,17.5,12,17.5z M15.5,11c0.83,0,1.5-0.67,1.5-1.5C17,8.67,16.33,8,15.5,8S14,8.67,14,9.5 C14,10.33,14.67,11,15.5,11z M22,1h-2v2h-2v2h2v2h2V5h2V3h-2V1z M20,12c0,4.42-3.58,8-8,8s-8-3.58-8-8c0-4.42,3.58-8,8-8 c1.46,0,2.82,0.4,4,1.08V2.84C14.77,2.3,13.42,2,11.99,2C6.47,2,2,6.48,2,12c0,5.52,4.47,10,9.99,10C17.52,22,22,17.52,22,12 c0-1.05-0.17-2.05-0.47-3h-2.13C19.78,9.93,20,10.94,20,12z"></path></svg>',
                    'title' => 'Reaction Roles',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M2.5 2.75a.75.75 0 00-1.5 0v18.5c0 .414.336.75.75.75H20a.75.75 0 000-1.5H2.5V2.75z"></path><path d="M22.28 7.78a.75.75 0 00-1.06-1.06l-5.72 5.72-3.72-3.72a.75.75 0 00-1.06 0l-6 6a.75.75 0 101.06 1.06l5.47-5.47 3.72 3.72a.75.75 0 001.06 0l6.25-6.25z"></path></svg>',
                    'title' => 'Statistics Channels',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.39 12.648a1.32 1.32 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1.06 1.06 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.512.512 0 0 0-.523-.516.539.539 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532 0 .312.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531 0 .313.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242l-.515 2.492zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z"></path></svg>',
                    'title' => 'Temporary Channels',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -1 24 24" preserveAspectRatio="xMinYMin" fill="currentColor"><path d="M2 18.262V20h16v-1.728l-3.106.753-4.86-1.004-4.977 1.004L2 18.262zM2 16.2l3.104.775 4.936-.996 4.819.996L18 16.214V15a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1.2zM13 12h4a3 3 0 0 1 3 3v7H0v-7a3 3 0 0 1 3-3h4V9h6v3zm-2 0v-1H9v1h2zm-1-3.6a3 3 0 0 1-3-3C7 4.295 8 2.495 10 0c2 2.495 3 4.295 3 5.4a3 3 0 0 1-3 3zm-.002-1.981c.552 0 1-.48 1-1.072 0-.394-.334-1.037-1-1.928-.667.891-1 1.534-1 1.928 0 .592.448 1.072 1 1.072z"></path></svg>',
                    'title' => 'Birthdays',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" viewBox="0 0 18 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="m4.488 0h2.411v6.2c2.434-.88 4.89-1.805 7.341-2.693v1.993c-2.454.88-4.889 1.775-7.341 2.655v1.027c2.451-.888 4.907-1.813 7.341-2.693v1.978c-2.433.905-4.894 1.78-7.341 2.67v10.277c3.42-.449 6.235-2.662 7.53-5.681l.024-.062c.471-1.087.746-2.352.746-3.682 0-.003 0-.005 0-.008h2.502v.38c-.058 1.825-.512 3.53-1.279 5.052l.032-.069c-.702 1.379-1.601 2.553-2.676 3.536l-.009.008c-2.106 1.933-4.926 3.117-8.023 3.117-.443 0-.88-.024-1.311-.071l.053.005v-11.92c-1.506.53-2.99 1.083-4.488 1.62v-1.92c1.501-.548 3.006-1.091 4.488-1.658v-1.019c-1.493.51-2.99 1.082-4.488 1.613v-1.91c1.491-.56 3.009-1.094 4.488-1.666z"></path></svg>',
                    'title' => 'Economy',
                    'href' => route('wip'),
                    'active' => false,
                ],
            ],
            'Social Connections' => [
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3.857 0 1 2.857v10.286h3.429V16l2.857-2.857H9.57L14.714 8V0H3.857zm9.714 7.429-2.285 2.285H9l-2 2v-2H4.429V1.143h9.142v6.286z"></path><path d="M11.857 3.143h-1.143V6.57h1.143V3.143zm-3.143 0H7.571V6.57h1.143V3.143z"></path></svg>',
                    'title' => 'Twitch',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path></svg>',
                    'title' => 'Twitter',
                    'href' => route('wip'),
                    'active' => false,
                ],
                [
                    'svg' => '<svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"></path></svg>',
                    'title' => 'YouTube',
                    'href' => route('wip'),
                    'active' => false,
                ],
            ],
        ];

        return view('components.server-layout', compact('_own_servers', 'server', 'nav_links'));
    }
}
