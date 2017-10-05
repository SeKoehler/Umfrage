$container->loadFromExtension('doctrine', array(
    'dbal' => array(
        'driver'   => 'pdo_mysql',
        'host'     => '%database_host%',
        'dbname'   => '%database_name%',
        'user'     => '%database_user%',
        'password' => '%database_password%',
        ),
    ));

$configuration->loadFromExtension('doctrine', array(
    'dbal' => array(
        'charset' => 'utf8mb4',
        'default_table_options' => array(
            'charset' => 'utf8mb4'
            'collate' => 'utf8mb4_unicode_ci'
            )
        ),
    ));