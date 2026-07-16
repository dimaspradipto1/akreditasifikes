<?php
$files = glob(__DIR__ . '/../app/Http/Controllers/*Controller.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix destroyBukti
    $content = preg_replace_callback('/public function destroyBukti\((?:\\\\?App\\\\Models\\\\)?([A-Za-z]+Bukti)\s+\$bukti\)\s*\{/', function($matches) {
        $model = $matches[1];
        return "public function destroyBukti(\$id)\n    {\n        \$bukti = \\App\\Models\\$model::findOrFail(\$id);";
    }, $content);
    
    // Fix updateBukti
    $content = preg_replace_callback('/public function updateBukti\((.*?Request)\s+\$request,\s+(?:\\\\?App\\\\Models\\\\)?([A-Za-z]+Bukti)\s+\$bukti\)\s*\{/', function($matches) {
        $req = $matches[1];
        $model = $matches[2];
        return "public function updateBukti($req \$request, \$id)\n    {\n        \$bukti = \\App\\Models\\$model::findOrFail(\$id);";
    }, $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Fixed $file\n";
    }
}
