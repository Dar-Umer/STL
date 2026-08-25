<?php

if (!function_exists('resolve_notification_message')) {
    function resolve_notification_message(string $template, array $vars): string
    {
        $search = array_map(fn(string $k) => '{' . $k . '}', array_keys($vars));
        return str_replace($search, $vars, $template);
    }
}
