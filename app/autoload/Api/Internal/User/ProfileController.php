<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Api\External\CRM\CrmClient;
use App\Api\External\CRM\CrmClientAbstract;
use App\Core\CRM\FieldsIntegrality\FieldIntegralityCheckerException;
use App\Core\CRM\IdentityDocumentType;
use App\Core\User\User;
use App\Core\User\UserInterface;
use App\EventHandlers\PersonalSectionHandlers;
use App\Models\Client\PersonalSectionDocumentKind;
use App\Models\HL\Address;
use App\Models\HL\AddressType;
use App\Models\HL\DiamondOrder;
use App\Models\HL\DiamondOrderItem;
use App\Models\HL\DiamondOrderStatus;
use App\Models\HL\PassportData;
use App\Models\HL\UserRequestCrmChange;
use App\Models\User as UserModel;
use Arrilot\BitrixCacher\Cache;
use CFile;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use DateTime as DateTimePhp;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс для работы с профилем
 * Class ProfileController
 * @package App\Api\Internal\User
 */
class ProfileController extends BaseController
{
    /**
     * Обновляет профиль в личном кабинете
     *
     * @return ResponseInterface
     */
    public function updateProfile(): ResponseInterface
    {
        /** @var array $request - Массив с полями формы */
        $request = htmlentities_on_array($_REQUEST);
        $fields = $this->request->getParsedBody();
        /** @var UserModel $user - Текущий пользователь */
        $user = user();

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;

            /** @var UserInterface $personType - Тип лица пользователя (физ/юр) */

        try {
            $personType=(new User)->setUserAndDefineUserPersonType($user);
            $personType=$personType->getPersonType();

            $res=$personType->setProfileData($user, $fields);

            if ($res) {
                $response = $this->respondWithSuccess();
                Cache::flush(User::PERSONAL_INFO_PROFILE_CACHE_INIT_DIR . $user->getId());
                logger(User::LOGGER_NAME_PROFILE_SUCCESS)
                    ->info('Пользователь ' . $user->getEmail() . ' успешно обновил данные своего профиля');
            } else {
                $response = $this->respondWithError();
                logger(User::LOGGER_NAME_PROFILE_ERROR)
                    ->info(
                        'Пользователю ' . $user->getEmail()
                        . ' не удалось обновить данные своего профиля. Необходимо обратиться к разработчику.'
                    );
            }
        } catch (FieldIntegralityCheckerException $exception) {
            $response = $this->respondWithError($exception->getMessage(), $exception->getCode());
        } finally {

            return $response;
        }
    }

    /**
     * Отправляет профиль пользователя в crm
     *
     * @return ResponseInterface
     */
    public function sendProfileToReview(): ResponseInterface
    {
        user()->update(['UF_IS_APPROVING' => true]);
        UserRequestCrmChange::create(['UF_USER_ID' => user()->getId()]);
        return $this->respondWithSuccess();
    }

    /**
     * Добавляет/изменяет данные пользователя в форме в разделе документов
     *
     * @return ResponseInterface
     */
    public function addUserInfoInDocs(): ResponseInterface
    {
        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            /** @var array $request - Массив, описывающий запрос из формы */
            $request = htmlentities_on_array($_REQUEST);

            /** @var array|mixed[] $passportData - Массив изменненых паспортных данных */
            $passportData = [
                'UF_TYPE' => (new IdentityDocumentType())
                    ->setCountryId((int)$request['register-address-country'])
                    ->getIdentityDocumentTypeByCountry()
                    ->getId(),
                'UF_SERIES' => $request['passport-data-form-passport-serial'],
                'UF_NUMBER' => $request['passport-data-form-passport-number'],
                'UF_ISSUE_DATE' => Date::createFromPhp(
                    new DateTimePhp($request['passport-data-form-passport-date-of-issue'])
                ),
                'UF_DOCUMENT_ORGAN' => $request['passport-data-form-passport-authority'],
                'UF_ISSUE_ORG_CODE' => $request['passport-data-form-passport-department-code'],
                'UF_VALIDITY_DATE' => Date::createFromPhp(
                    new DateTimePhp($request['passport-data-form-passport-valid-to'])
                ),
                'UF_CITIZENSHIP' => $request['documents-data-form-citizenship'],
                'UF_BIRTH_COUNTRY' => $request['documents-data-form-country-of-birth'],
                'UF_BIRTH_PLACE' => $request['documents-data-form-place-of-birth']
            ];

            /** @var PassportData $passport - Паспортные данные пользователя */
            $passport = null;
            if (user()->passportData) {
                $passport = user()->passportData;
                $passport->update($passportData);
            } else {
                $passport = PassportData::create($passportData);
            }

            /** @var array|mixed[] $registrationData - Массив измененных данных адреса регистрации */
            $registrationData = [
                'UF_USER_ID' => user()->getId(),
                'UF_ZIP' => $request['register-address-zip'],
                'UF_COUNTRY' => $request['register-address-country'],
                'UF_REGION' => $request['register-address-region'],
                'UF_CITY' => $request['register-address-city'],
                'UF_STREET' => $request['register-address-street'],
                'UF_HOUSE' => $request['register-address-house'],
                'UF_FLAT' => $request['register-address-flat']
            ];

            /** @var Address $registration - Адрес регистрации пользователя */
            $registration = null;
            if (user()->registrationAddress) {
                $registration = user()->registrationAddress;
                $registration->update($registrationData);
            } else {
                $registrationData['UF_TYPE_ID'] = AddressType::getRegisterAddressType()->getId();
                $registration = Address::create($registrationData);
            }

            user()->update([
                'UF_TAX_NUMBER' => $request['documents-data-form-tax-number'],
                'UF_PASSPORT_ID' => $passport->getId(),
                'UF_REGISTRATION_ID' => $registration->getId()
            ]);

            $response = $this->respondWithSuccess();
            Cache::flush(PersonalSectionDocumentKind::PERSONAL_DOCUMENTS_CACHE_INIT_DIR . user()->getId());
        } catch (Throwable $exception) {
            $this->writeErrorLog(
                self::class,
                'Не удалось создать или обновить данные пользователя. Причина: ' . $exception->getMessage()
            );
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Создает заказ на создание бриллиантов
     *
     * @return ResponseInterface
     */
    public function addDiamondOrder(): ResponseInterface
    {
        unset($_REQUEST['csrf_token']);

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            /** @var \App\Models\User $user - Текущий пользователь */
            $user = user();

            /** @var array|int[] $files - Массив идентификаторов загруженных файлов */
            $files = [];
            foreach ($_FILES as $file) {
                $files[] = CFile::SaveFile([
                    'name' => $file['name'],
                    'size' => $file['size'],
                    'type' => $file['type'],
                    'description' => '',
                    'tmp_name' => $file['tmp_name']
                ], 'profile/diamonds-to-order-docs/');
            }

            /** @var array $orderFields - Основные поля заказа (Контакты, комментарий) */
            $orderFields = htmlentities_on_array((array) json_decode($_REQUEST['main']));
            unset($_REQUEST['main']);

            /** @var DiamondOrder $order - Заказ на изготовление бриллиантов */
            $order = DiamondOrder::create([
                'UF_DATE' => new DateTime,
                'UF_STATUS' => DiamondOrderStatus::getByCode(DiamondOrderStatus::NEW)->getId(),
                'UF_PHONE' => $orderFields['phone'],
                'UF_FILE' => $files,
                'UF_EMAIL' => $orderFields['email'],
                'UF_MESSAGE' => $orderFields['comment'],
                'UF_CONTACT_PERSON' => $orderFields['name'],
                'UF_USER_ID' => $user->getId(),
                'UF_COMPANY' => $user->isLegalEntity() ? $user->company->getName() : null
            ]);

            try {
                foreach ($_REQUEST as $key => $value) {
                    if (strstr($key, 'diamonds')) {
                        /** @var array $diamond - Бриллиант под заказ */
                        $diamond = htmlentities_on_array((array) json_decode($value));

                        DiamondOrderItem::create([
                            'UF_ORDER_ID' => $order->getId(),
                            'UF_SHAPE' => $diamond['shape'],
                            'UF_CUT' => $diamond['cut'],
                            'UF_COLOR' => $diamond['color'],
                            'UF_CLARITY' => $diamond['clarity'],
                            'UF_SIZE' => $diamond['weight']
                        ]);
                    }
                }

                PersonalSectionHandlers::newDiamondOrder($order);
                Cache::flush(DiamondOrder::DIAMOND_ORDER_CACHE_INIT_DIR . user()->getId());
                $response = $this->respondWithSuccess();
            } catch (Throwable $exception) {
                $this->writeErrorLog(self::class, $exception->getMessage());
                /**
                 * @var \Illuminate\Support\Collection|DiamondOrderItem[] $diamondsForOrder
                 * Коллекция бриллиантов под заказ
                 */
                $diamondsForOrder = DiamondOrderItem::filter(['UF_ORDER_ID' => $order->getId()])->getList();
                foreach ($diamondsForOrder as $diamondForOrder) {
                    $diamondForOrder->delete();
                }
                $order->delete();

                foreach ($files as $file) {
                    CFile::Delete($file);
                }
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
