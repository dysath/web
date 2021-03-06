<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Web\Http\Middleware\Bouncer;

use Closure;
use Illuminate\Http\Request;

/**
 * Class KeyBouncer.
 * @package Seat\Web\Http\Middleware
 */
class KeyBouncer
{
    /**
     * Handle an incoming request.
     *
     * This filter checks the required permissions or
     * key ownership for a API key.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $permission = null)
    {

        // Get the currently logged in user
        $user = auth()->user();

        // Having the permission / superuser here means
        // your access is fine.
        if ($user->has('apikey.' . $permission, false))
            return $next($request);

        // If we dont have the required permission, check
        // if the current user owns the key.
        if (in_array($request->key_id, $user->keys->pluck('key_id')->all()))
            return $next($request);

        $message = 'Request to ' . $request->path() . ' was ' .
            'denied by the keybouncer. The permission required is ' .
            'key.' . $permission . '.';

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');

    }
}
