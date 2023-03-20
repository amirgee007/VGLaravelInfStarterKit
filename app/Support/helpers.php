<?php

if (! function_exists('settings')) {
    /**
     * Get / set the specified settings value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('anlutro\LaravelSettings\SettingStore');
        }

        return app('anlutro\LaravelSettings\SettingStore')->get($key, $default);
    }
}

if (! function_exists('check_stock_location')) {

    /**
     * Find missing location.
     *
     * @param string $location_str stock location separated by ","
     * @return string missing location if exists or return "All stock location is used."
     */
    function check_stock_location(string $location_str) {
        $location_arr = explode(',', $location_str);

        $location_key_suffix = [];
        foreach ($location_arr as $location) {
            $location_key_suffix[substr($location, 0, -1)][] =  substr($location, -1, 1);
        }

        $start = ord('A');
        $return_str = '';
        foreach ($location_key_suffix as $key => $suffix) {
            rsort($suffix);
            $end = ord($suffix[0]);
            if ($end > $start) {
                $diff_location = array_diff(range('A', $suffix[0]), $suffix);
                $diff_location && $return_str .= 'Missing location ' . $key . implode(',', $diff_location) . ";\n";
            }
        }

        if (!$return_str) {
            return 'All stock location is used.';
        }
        return $return_str;
    }
}