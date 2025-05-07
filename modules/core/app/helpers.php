<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;


function modulesList()
{
    $path = base_path('modules');
    $modules = scandir($path);
    $modules = array_diff($modules, ['.', '..']);
    config()->set('app.modules', $modules);
    return $modules;
}

function addModulesProviders()
{
    $modules = modulesList();
    foreach ($modules as $module) {
        $providersDir = base_path('modules/' . $module . '/app/Providers');
        if (is_dir($providersDir)) {
            $files = scandir($providersDir);
            $files = array_diff($files, ['.', '..']);
            foreach ($files as $file) {
                $class = '\\Modules\\' . $module . '\\app\Providers\\' . str_replace('.php', '', $file);
                if (class_exists($class) && $class != '\Modules\core\app\Providers\ModuleProvider') {
                    App::register($class);
                }
            }
        }
    }
}

function addNamespaceForView($module_name): void
{
    $path = base_path('modules');
    if (is_dir($path)) {
        $view_path = $path . '/' . $module_name . '/resources/views';
        View::addNamespace($module_name, $view_path);
    }
}

function addModulesMainClass($module_name): void
{
    $file_path = base_path('modules/' . $module_name . '/Module.php');
    if (file_exists($file_path)) {
        $className = "Modules\\{$module_name}\\Module";
        if (class_exists($className)) {
            require_once($file_path);
        }
    }
}

if (!function_exists('putCache')) {
    function putCache($key, $param, $expire)
    {
        Cache::put($key, $param, $expire);
    }
}

if (!function_exists('getCache')) {
    function getCache(string $key)
    {
        return Cache::get($key);
    }
}

if (!function_exists('clearCache')) {
    function clearCache(string $key)
    {
        return Cache::forget($key);
    }
}

if (!function_exists('containsEnglish')) {
    function containsEnglish($input): false|int
    {
        return preg_match('/[a-zA-Z]/', $input);
    }
}

if (!function_exists('convertEnToFa')) {
    function convertEnToFa($input)
    {
        if (!containsEnglish($input)) {
            return $input;
        }

        $mapping = [
            'a' => 'ش', 'b' => 'ذ', 'c' => 'ز', 'd' => 'ی', 'e' => 'ث',
            'f' => 'ب', 'g' => 'ل', 'h' => 'ا', 'i' => 'ه', 'j' => 'ت',
            'k' => 'ن', 'l' => 'م', 'm' => 'ئ', 'n' => 'د', 'o' => 'خ',
            'p' => 'ح', 'q' => 'ض', 'r' => 'ق', 's' => 'س', 't' => 'ف',
            'u' => 'ع', 'v' => 'ر', 'w' => 'ص', 'x' => 'ط', 'y' => 'غ',
            'z' => 'ظ'
        ];

        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            $output .= $mapping[$char] ?? $char;
        }

        return $output;
    }
}
