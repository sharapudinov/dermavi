<?php

namespace App\Core\PDF;

use mikehaertl\wkhtmlto\Pdf;
use App\Helpers\LanguageHelper;

/**
 * Класс для генерации pdf с использованием библиотеки wkhtmltopdf
 * Class PdfGenerator
 * @package App\Core\PDF
 */
abstract class PdfWorker
{
    /** @var string - Относительный путь до директории, содержащей сгенерированные pdf-файлы */
    private const PDF_FILES_DIRECTORY = '/pdf/docs/';

    /** @var string $lang - Язык, на котором выводится pdf */
    private static $lang = '';

    /** @var Pdf $pdf - Объект, содержащий информацию о pdf */
    private $pdf;

    /** @var array $generalSettings - Общие настройки для всех pdf */
    private $generalSettings = [
        'binary' => 'wkhtmltopdf',
        'ignoreWarnings' => true,
        'dpi' => '96',
        'commandOptions' => [
            'useExec' => true,
            'procEnv' => [
                'LANG' => 'en_US.utf-8',
            ],
        ],
    ];

    /** @var string $fileToExecute - Файл, который мы должны запустить при генерации pdf */
    protected $fileToExecute;

    /** @var string */
    private $lastError;

    /**
     * PdfWorker constructor.
     *
     * @param array $localSettings - Настройки для конкретной pdf
     */
    protected function __construct(array $localSettings)
    {
        self::$lang = LanguageHelper::isDefaultVersion() ? '' : '/' . LanguageHelper::getLanguageVersion();
        $localSettings['cookie'] = [
            LanguageHelper::LANGUAGE_VERSION_COOKIE => $_COOKIE[LanguageHelper::LANGUAGE_VERSION_COOKIE],
        ];
        $this->pdf = new Pdf(array_merge($this->generalSettings, $localSettings));
    }

    /**
     * Генерируем pdf для карточки определенного бриллианта
     *
     * @param string $elementId - Идентификатор элемента
     * @return string|null
     */
    protected function generate(string $elementId): ?string
    {
        /** @var string $pdfLink - Ссылка на pdf в браузере */
        $pdfLink = null;
        /** @var string $pdfName - Название pdf файла */
        $pdfName = $this->getPdfFileName($elementId);

        /** @var string $pdfDirectory - Абсолютный путь до директории с pdf файлами */
        $pdfDirectory = $_SERVER['DOCUMENT_ROOT'] . $this->getLang() . self::PDF_FILES_DIRECTORY;
        if (!is_dir($pdfDirectory)) {
            mkdir($pdfDirectory, 0777);
        }

        /** Генерация и сохранение pdf */
        $this->pdf->addPage($this->getPdfInput($elementId));

        clearstatcache($pdfDirectory . $pdfName);
        $this->pdf->saveAs($pdfDirectory . $pdfName);

        $this->setLastError($this->pdf->getError());

        /** Обработка ошибок */
        if ($this->pdf->getError() === '') {
            $pdfLink = get_external_url(false) . $this->getLang() . self::PDF_FILES_DIRECTORY . $pdfName;
        } else {
            logger('pdf')->error(static::class . ': ' . $this->pdf->getError());

            return $this->getPdfPath(
                get_external_url(false) . $this->getLang() . '/pdf/' . $this->fileToExecute . '.php?',
                $elementId
            );
        }

        return $pdfLink;
    }

    /**
     * Удаляем все pdf старше одного дня
     *
     * @return void
     */
    public static function removeOldPdf(): void
    {
        $pdfDirectoryPath = $_SERVER['DOCUMENT_ROOT'] . self::$lang . self::PDF_FILES_DIRECTORY;
        $dir = opendir($pdfDirectoryPath);
        clearstatcache();
        $dayAgoTimeStamp = strtotime('-1 days');
        while (false != ($file = readdir($dir))) {
            if (substr($file, -4) == '.pdf') {
                if (filemtime($pdfDirectoryPath . $file) < $dayAgoTimeStamp) {
                    unlink($pdfDirectoryPath . $file);
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getLang(): string
    {
        return self::$lang;
    }

    /**
     * @param string $elementId
     * @return string
     */
    protected function getPdfFileName(string $elementId): string
    {
        return $this->fileToExecute . '_id_' . $elementId . '.pdf';
    }

    /**
     * @param string $elementId
     * @return string
     */
    protected function getPdfInput(string $elementId): string
    {
        return $this->getPdfPath(
            get_external_url(false) . $this->getLang() . '/pdf/' . $this->fileToExecute . '.php?',
            $elementId
        );
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }

    /**
     * @param string $lastError
     * @return static
     */
    protected function setLastError(string $lastError)
    {
        $this->lastError = $lastError;

        return $this;
    }

    /**
     * Запуск генерации необходимой pdf
     *
     * @param string $elementId - Идентификатор элемента
     *
     * @return null|string
     */
    abstract public function execute(string $elementId): ?string;

    /**
     * Возвращает путь до pdf
     *
     * @param string $generalPath - Общий путь для всех PDF
     * @param string $elementId - Идентификатор элемента
     *
     * @return string
     */
    abstract public function getPdfPath(string $generalPath, string $elementId): string;
}
