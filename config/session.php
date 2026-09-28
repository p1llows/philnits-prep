<?php

// Session handler workaround - this creates the session directory if it doesn't exist
use Illuminate\Support\Facades\Storage;

if (!Storage::exists(config('session.directory', 'sessions'))) {
    Storage::put(str_replace('/', DIRECTORY_SEPARATOR, config('session.directory') . DIRECTORY_SEPARATOR), '');
}
