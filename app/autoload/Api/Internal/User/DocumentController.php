<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Core\CRM\Documents\Document;
use App\Models\HL\Document as DocumentModel;
use App\Models\User as UserModel;
use CFile;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы с документами
 * Class DocumentController
 *
 * @package App\Api\Internal\User
 */
class DocumentController extends BaseController
{
    /**
     * Загружает документ пользователя
     *
     * @return ResponseInterface
     */
    public function addDocument(): ResponseInterface
    {
        /** @var array $file - Массив, описывающий загружаемый файл */
        $files = [];
        foreach ($_FILES as $file) {
            $files[] = htmlentities_on_array($file);
        }

        /** @var array $request - Массив, описывающий форму */
        $request = htmlentities_on_array($_REQUEST);

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            if (Document::uploadDocument($request, $files)) {
                $response = $this->respondWithSuccess();
            } else {
                $response = $this->respondWithError();
            }
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception->getMessage());
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Обновляет документ
     *
     * @return ResponseInterface
     */
    public function updateDocument(): ResponseInterface
    {
        /** @var UserModel $user - Текущий пользователь */
        $user = user();

        //@TODO Раскоментить когда появится интеграция с CRM
        /*if (!$user->getCrmId()) {
            return $this->errorWrongArgs();
        }*/

        /** @var array $file - Массив, описывающий загружаемый файл */
        $file = htmlentities_on_array($_FILES['file']);

        /** @var array $request - Массив, описывающий форму */
        $request = htmlentities_on_array($_REQUEST);

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;

        try {
            /** @var DocumentModel $document - Документ */
            $document = DocumentModel::getById($request['document_id']);
            CFile::Delete($document->getFileID());
            $document->delete();

            if (Document::uploadDocument($request, [$file])) {
                $response = $this->respondWithSuccess();
            } else {
                $response = $this->respondWithError();
            }
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception->getMessage());
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }
}
