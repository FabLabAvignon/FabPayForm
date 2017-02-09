# FabPayForm
This project is for automating FabLab's membership with FabManager API.

# Copy/Paste code for later use

```php
try {
  $oDatabase = new PDO('mysql:host=' . $config['dataBaseConf']['dbHost']
    . ';port=' . $config['dataBaseConf']['dbPort']
    . ';dbname=' . $config['dataBaseConf']['dbName']
    . ';charset=utf8', $config['dataBaseConf']['dbUser'], $config['dataBaseConf']['dbPass']);
} catch(Exception $e) {
  error_log("Database connection fail !");
  exit;
}
```
