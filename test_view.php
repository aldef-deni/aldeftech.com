<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $errors = new \Illuminate\Support\ViewErrorBag();
    $view = view('admin.auth.login')->with('errors', $errors)->render();
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . " " . $e->getLine();
} catch (\Throwable $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n" . $e->getFile() . " " . $e->getLine();
}

