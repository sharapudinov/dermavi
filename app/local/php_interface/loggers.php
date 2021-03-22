<?php

/**
 * В данном файле регистрируются Monolog-логгеры.
 * https://github.com/Seldaek/monolog
 * Никакие другие php-инструменты для логирования в проекте использоваться не должны (включая встроенные в Битрикс AddMessage2Log и т д)
 * Для удобства получения нужного логгера из регистра в helpers.php есть хэлпер logger($name = 'common')
 */

use App\Console\BaseCommand;
use App\Import\FileChecker\Logger as FileCheckerLogger;
use Arrilot\BitrixSync\Telegram\TelegramFormatter;
use Arrilot\BitrixSync\Telegram\TelegramHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Registry;

// common logger
$logger = new Logger('common');
$logger->pushHandler(new StreamHandler(logs_path('common.log')));
Registry::addLogger($logger, 'common');

//api logger
$logger = new Logger(\App\Api\BaseController::LOGGER_NAME);
$logger->pushHandler(new StreamHandler(logs_path('api.log')));
Registry::addLogger($logger, 'api');

//// base command logger
//$logger = new Logger(BaseCommand::LOGGER_NAME);
//$logger->pushHandler(new StreamHandler(logs_path(sprintf('%s.log', BaseCommand::LOGGER_NAME))));
//Registry::addLogger($logger, BaseCommand::LOGGER_NAME);
//
//// cron command on file changed logger
//$logger = new Logger(FileCheckerLogger::LOGGER_NAME);
//$logger->pushHandler(new StreamHandler(logs_path(sprintf('%s.log', FileCheckerLogger::LOGGER_NAME))));
//Registry::addLogger($logger, FileCheckerLogger::LOGGER_NAME);
//
//// import logger example
//$logger = new Logger('import');
//// пишем в файл
//$logger->pushHandler(new StreamHandler(logs_path('import.log')));
//// пишем в телеграмм алерты
//$handler = new TelegramHandler(config('telegram.alerts_bot'), config('telegram.alerts_channel'), Logger::ALERT);
//$handler->setFormatter(new TelegramFormatter('Во время импорта произошли ошибки'));
//$handler->setProxy(config('telegram.proxy'));
//$logger->pushHandler($handler);
//// пишем в почту алерты
//$handler = new \Monolog\Handler\NativeMailerHandler(
//    config('import-alerts.emails_to'),
//    'Alrosa Import alerts',
//    'AlrosaDiamonds@alrosa.ru'
//    , Logger::ALERT );
//$logger->pushHandler($handler);
//Registry::addLogger($logger, 'import');
//
//// import logger example
//$logger = new Logger('import_success');
//// пишем в файл
//$logger->pushHandler(new StreamHandler(logs_path('import_success.log')));
//// пишем в телеграмм алерты
//$handler = new \Arrilot\BitrixSync\Telegram\TelegramHandler(config('telegram.alerts_bot'), config('telegram.alerts_channel'), Logger::ALERT);
//$handler->setFormatter(new \Arrilot\BitrixSync\Telegram\TelegramFormatter('Импортированы бриллианты'));
//$handler->setProxy(config('telegram.proxy'));
//$logger->pushHandler($handler);
//// пишем в почту алерты
//$handler = new \Monolog\Handler\NativeMailerHandler(
//    config('import-alerts.emails_to'),
//    'Alrosa Import alerts',
//    'AlrosaDiamonds@alrosa.ru'
//    , Logger::ALERT );
//$logger->pushHandler($handler);
//Registry::addLogger($logger, 'import_success');
//
//// handlers logger example
//$logger = new Logger('handlers');
//$logger->pushHandler(new StreamHandler(logs_path('handlers.log')));
//Registry::addLogger($logger, 'handlers');
//
//// catalog logger
//$logger = new Logger('catalog');
//$logger->pushHandler(new StreamHandler(logs_path('catalog.log')));
//Registry::addLogger($logger, 'catalog');
//
//// pdf logger
//$logger = new Logger('pdf');
//$logger->pushHandler(new StreamHandler(logs_path('pdf.log')));
//Registry::addLogger($logger, 'pdf');
//
//// personal section logger
//$logger = new Logger('personal');
//$logger->pushHandler(new StreamHandler(logs_path('personal.log')));
//Registry::addLogger($logger, 'personal');
//
//// catalog logger
//$logger = new Logger('crm');
//$logger->pushHandler(new StreamHandler(logs_path('crm.log')));
//Registry::addLogger($logger, 'crm');
//
//// catalog logger
//$logger = new Logger('api_v1');
//$logger->pushHandler(new StreamHandler(logs_path('api_v1.log')));
//Registry::addLogger($logger, 'api_v1');
//
//// auctions logger
//$logger = new Logger('auctions');
//$logger->pushHandler(new StreamHandler(logs_path('auctions.log')));
//Registry::addLogger($logger, 'auctions');
//
//// jewelry import logger
//$logger = new Logger('jewel_import');
//$logger->pushHandler(new StreamHandler(logs_path('jewel_import.log')));
//Registry::addLogger($logger, 'jewel_import');
//
//// jewelry blanks import logger
//$logger = new Logger('jewel_blanks_import');
//$logger->pushHandler(new StreamHandler(logs_path('jewel_blanks_import.log')));
//Registry::addLogger($logger, 'jewel_blanks_import');
//
/** Api/Internal loggers */
// Логгер для сбора информации App\Api\Internal\User\AuthController
$logger = new Logger('internal_user_auth_info');
$logger->pushHandler(new StreamHandler(logs_path('/api/internal/auth/info.log')));
Registry::addLogger($logger, 'internal_user_auth_info');

//// Логгер для сбора ошибок App\Api\Internal\User\AuthController
//$logger = new Logger('internal_user_auth_error');
//$logger->pushHandler(new StreamHandler(logs_path('/api/internal/auth/error.log')));
//Registry::addLogger($logger, 'internal_user_auth_error');
//
//// Логгер для сбора информации App\Api\Internal\User\ProfileController
//$logger = new Logger('internal_user_profile_info');
//$logger->pushHandler(new StreamHandler(logs_path('/api/internal/profile/info.log')));
//Registry::addLogger($logger, 'internal_user_profile_info');
//
// Логгер для сбора ошибок App\Api\Internal\User\ProfileController
$logger = new Logger('internal_user_profile_error');
$logger->pushHandler(new StreamHandler(logs_path('/api/internal/profile/error.log')));
Registry::addLogger($logger, 'internal_user_profile_error');

//// Логгер для сбора информации App\Api\Internal\Main\PrivacyPolicyController
//$logger = new Logger('internal_main_privacy_policy_info');
//$logger->pushHandler(new StreamHandler(logs_path('/api/internal/privacy_policy/info.log')));
//Registry::addLogger($logger, 'internal_main_privacy_policy_info');
//
//// Логгер для сбора ошибок App\Api\Internal\Main\PrivacyPolicyController
//$logger = new Logger('internal_main_privacy_policy_error');
//$logger->pushHandler(new StreamHandler(logs_path('/api/internal/privacy_policy/error.log')));
//Registry::addLogger($logger, 'internal_main_privacy_policy_error');
//
//// cheque logger
//$logger = new Logger('cheque');
//$logger->pushHandler(new StreamHandler(logs_path('cheque.log')));
//Registry::addLogger($logger, 'cheque');
//
//// import logger example
//$logger = new Logger('jewelry_constructor_combinations');
//// пишем в файл
//$logger->pushHandler(new StreamHandler(logs_path('jewelry_constructor_combinations.log')));
//// пишем в телеграмм алерты
//$handler = new TelegramHandler(
//    config('telegram.alerts_bot'),
//    config('telegram.alerts_channel'),
//    Logger::ALERT
//);
//$handler->setFormatter(new TelegramFormatter('Возникла ошибка при генерации комбинаций для конструктора ЮБИ'));
//$handler->setProxy(config('telegram.proxy'));
//$logger->pushHandler($handler);
//Registry::addLogger($logger, 'jewelry_constructor_combinations');
//
//// jewelry constructor combinations result
//$logger = new Logger('jewelry_constructor_combinations_result');
//$logger->pushHandler(new StreamHandler(logs_path('jewelry_constructor_combinations_result.log')));
//Registry::addLogger($logger, 'jewelry_constructor_combinations_result');
//
//// import logger example
//$logger = new Logger('jewelry_constructor_combinations_success');
//// пишем в файл
//$logger->pushHandler(new StreamHandler(logs_path('jewelry_constructor_combinations_success.log')));
//// пишем в телеграмм алерты
//$handler = new TelegramHandler(
//    config('telegram.alerts_bot'),
//    config('telegram.alerts_channel'),
//    Logger::ALERT
//);
//$handler->setFormatter(new TelegramFormatter('Генерация комбинаций ЮБИ'));
//$handler->setProxy(config('telegram.proxy'));
//$logger->pushHandler($handler);
//Registry::addLogger($logger, 'jewelry_constructor_combinations_success');
