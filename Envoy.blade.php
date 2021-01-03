@servers(['test' => '-A greensight@185.93.109.20', 'localhost' => '127.0.0.1'])

@setup
    use Arrilot\DotEnv\DotEnv;

    require_once 'vendor/autoload.php';
    DotEnv::load('./config/.env.php');
@endsetup

@story('reinit-cadas2test')
    clear-catalog-local
    clear-catalog-test
    import-cadas-local
    make-dump
    move-dump2test
    import-dump2test
    update-catalog-test
@endstory

@story('reinit-cadas2test-docker')
    clear-catalog-local-docker
    clear-catalog-test
    import-cadas-local-docker
    make-dump-docker
    move-dump2test
    import-dump2test
    update-catalog-test
@endstory

@story('import-cadas2test')
    import-cadas-local
    make-dump
    move-dump2test
    import-dump2test
    update-catalog-test
@endstory

@story('import-cadas2test-docker')
    import-cadas-local-docker
    make-dump-docker
    move-dump2test
    import-dump2test
    update-catalog-test
@endstory

@task('status', ['on' => ['localhost']])
    echo {{ DotEnv::get('DB_LOGIN', 'empty local login') }}
    echo {{ DotEnv::get('DB_PASSWORD', 'empty local password') }}
    echo {{ DotEnv::get('DB_DATABASE', 'empty local database') }}
    echo {{ DotEnv::get('DB_TEST_LOGIN', 'empty test login') }}
    echo {{ DotEnv::get('DB_TEST_PASSWORD', 'empty test password') }}
    echo {{ DotEnv::get('DB_TEST_DATABASE', 'empty test database') }}
@endtask

@task('import-cadas-local-docker', ['on' => ['localhost']])
    docker exec bitrix_php71 php -f /home/www/bitrix/alrosa/www/bxcli import:cadas_packet
    docker exec bitrix_php71 php -f /home/www/bitrix/alrosa/www/bxcli import:cadas_dict
@endtask

@task('import-cadas-local', ['on' => ['localhost']])
    php -f bxcli import:cadas_packet
    php -f bxcli import:cadas_dict
@endtask

@task('clear-catalog-local-docker', ['on' => ['localhost']])
    docker exec bitrix_php71 php -f /home/www/bitrix/alrosa/www/bxcli catalog:clear
@endtask

@task('clear-catalog-local', ['on' => ['localhost']])
    php -f bxcli catalog:clear
@endtask

@task('make-dump-docker', ['on' => ['localhost']])
    docker exec bitrix_mysql mysqldump -u {{ DotEnv::get('DB_LOGIN', '') }} -p{{ DotEnv::get('DB_PASSWORD', '') }} {{ DotEnv::get('DB_DATABASE', 'alrosa') }} stone_location dict_color dict_culet dict_factory dict_fluor dict_from dict_intensity dict_ownership dict_persona dict_polish dict_qual dict_quality dict_size dict_symmetry diamond_packet packet_additional_info > alrosa_data.sql
@endtask

@task('make-dump', ['on' => ['localhost']])
    mysqldump -u {{ DotEnv::get('DB_LOGIN', 'root') }} -p{{ DotEnv::get('DB_PASSWORD', '') }} {{ DotEnv::get('DB_DATABASE', 'alrosa') }} stone_location dict_color dict_culet dict_factory dict_fluor dict_from dict_intensity dict_ownership dict_persona dict_polish dict_qual dict_quality dict_size dict_symmetry diamond_packet packet_additional_info > alrosa_data.sql
@endtask

@task('move-dump2test', ['on' => ['localhost']])
    scp alrosa_data.sql greensight@185.93.109.20:/var/www/predprod/
@endtask

@task('import-dump2test', ['on' => ['test']])
    cd /var/www/predprod/
    mysql -u {{ DotEnv::get('DB_TEST_LOGIN', 'root') }} -p{{ DotEnv::get('DB_TEST_PASSWORD', '') }} {{ DotEnv::get('DB_TEST_DATABASE', 'alrosa') }} < alrosa_data.sql
@endtask

@task('update-catalog-test', ['on' => ['test']])
    cd /var/www/predprod/master.alrosa.greensight.ru
    php -f bxcli import:catalog
@endtask

@task('clear-catalog-test', ['on' => ['test']])
    cd /var/www/predprod/master.alrosa.greensight.ru
    php -f bxcli catalog:clear
@endtask