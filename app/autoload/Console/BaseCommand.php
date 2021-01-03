<?php

namespace App\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class BaseCommand extends Command
{
    public const LOGGER_NAME = 'base_command';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Execute the console command.
     *
     * @return null|int
     */
    abstract protected function fire();

    /**
     * Configures the current command.
     *
     * @param string $message
     * @throws Exception
     */
    protected function abort($message = '')
    {
        if ($message) {
            $this->error($message);
        }

        throw new Exception();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     * @param bool $passException true - в случае исключения - исключение передается дальше, иначе просто возвращается 1
     *
     * @return null|int null or 0 if everything went fine, or an error code.
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output, $passException = false)
    {
        $this->input = $input;
        $this->output = $output;

        try {
            return $this->fire();
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            $this->error('Abort!');

            $this->logError($e->getMessage(), [
                'exception' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ]);

            if ($passException) {
                throw $e;
            }
            return 1;
        }
    }

    /**
     * Echo an error message.
     *
     * @param string $message
     */
    protected function error($message)
    {
        $this->output->writeln("<error>{$message}</error>");
    }

    /**
     * Echo an info.
     *
     * @param string $message
     */
    protected function info($message)
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * Echo a message.
     *
     * @param string $message
     */
    protected function message($message)
    {
        $this->output->writeln("{$message}");
    }

    protected function logInfo(string $message, array $context = [])
    {
        logger(self::LOGGER_NAME)->info(sprintf('[%s] %s', $this->getName(), $message), $context);
    }

    protected function logError(string $message, array $context = [])
    {
        logger(self::LOGGER_NAME)->error(sprintf('[%s] %s', $this->getName(), $message), $context);
    }
}