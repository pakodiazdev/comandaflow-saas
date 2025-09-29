<?php
echo "<h1>🐘 CommandaFlow SaaS - Environment Test</h1>";
echo "<hr>";

echo "<h2>🐳 Docker Services Status</h2>";
echo "<ul>";

// Test PostgreSQL connection
try {
    $pdo = new PDO(
        "pgsql:host=postgres;dbname=" . ($_ENV['DB_NAME'] ?? 'commandaflow_saas'),
        $_ENV['DB_USER'] ?? 'postgres',
        $_ENV['DB_PASS'] ?? 'secret'
    );
    echo "<li>✅ <strong>PostgreSQL:</strong> Connected successfully</li>";
} catch (PDOException $e) {
    echo "<li>❌ <strong>PostgreSQL:</strong> Connection failed - " . $e->getMessage() . "</li>";
}

// Test Mailhog connection
$mailhog_host = $_ENV['MAIL_HOST'] ?? 'mailhog';
$mailhog_port = $_ENV['MAIL_PORT'] ?? 1025;
$connection = @fsockopen($mailhog_host, $mailhog_port, $errno, $errstr, 5);
if ($connection) {
    echo "<li>✅ <strong>Mailhog:</strong> Service available on {$mailhog_host}:{$mailhog_port}</li>";
    fclose($connection);
} else {
    echo "<li>❌ <strong>Mailhog:</strong> Service not available</li>";
}

echo "</ul>";

echo "<h2>🔗 Service Links</h2>";
echo "<ul>";
echo "<li><strong>PgAdmin:</strong> <a href='http://localhost:8080' target='_blank'>http://localhost:8080</a></li>";
echo "<li><strong>Mailhog Web UI:</strong> <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></li>";
echo "</ul>";

echo "<h2>📋 PHP Information</h2>";
echo "<details><summary>Click to view phpinfo()</summary>";
phpinfo();
echo "</details>";
?>