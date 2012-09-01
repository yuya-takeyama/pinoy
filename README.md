Pinoy
=====

Taggable logger for PHP.

Features
--------

* Tagging
* Logging backtraces
* Multiple writers

Usage
-----

### Basic logging

```php
<?php
// Setting minimum level
$logger = new Pinoy_Logger::(Pinoy_Logger::LEVEL_WARN);

// Every logging uses this writer
$logger['**'] = new Pinoy_Writer_TextFileLogger('./logfile');

// Logged with tag "default"
$logger->debug('Error message');
// => 2012-09-02 02:45:24	DEBUG	default	Error message /path/to/logging.php:9
```

### Logging with specific tag

```php
<?php
$sql = "SELECT * FROM users";
executeQuery($sql);
$logger->debug('db.sql', $sql);
// => 2012-09-02 02:49:33	DEBUG	db.sql	SELECT * FROM users /path/to/query.php:4

// Below is almost same meaning
$logger['db.sql']->debug($sql);

// All logging below is tagged as "db.sql"
$dbLogger = $logger['db.sql'];
$dbLogger->debug('DESC users');
$dbLogger->debug('SHOW TABLES');
```

### Logging methods

```php
<?php
$logger->debug('Error message');
$logger->info('Error message');
$logger->warn('Error message');
$logger->error('Error message');
$logger->fatal('Error message');
```

### Changing trace to log

```php
<?php
$sql = "SELECT * FROM users";

executeQueryWithMeaninglessLogging($sql);
// => 2012-09-02 02:51:17	DEBUG	db.sql	SELECT * FROM users /path/to/query.php:14

executeQueryWithMeaningfullLogging($sql);
// => 2012-09-02 02:51:17	DEBUG	db.sql	SELECT * FROM users /path/to/query.php:7

function executeQueryWithMeaninglessLogging($sql) {
    global $logger;

    executeQuery($sql);
    $logger->debug('sql.sql', $sql);
}

function executeQueryWithMeaningfullLogging($sql) {
    global $logger;

    executeQuery($sql);
    $logger->debug('sql.sql', $sql, array(
        'trace_pos' => 1, // Relative specification means +1
    ));
}
```

Author
------

Yuya Takeyama
