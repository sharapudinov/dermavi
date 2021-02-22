<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Core\CRM\FieldsIntegrality\Address as AddressChecker;
use App\Core\CRM\FieldsIntegrality\FieldIntegralityCheckerException;
use App\Core\User\General\DeliveryAddress as DeliveryAddressCore;
use App\Core\User\User;
use App\Core\User\UserInterface;
use App\Models\HL\Address;
use App\Models\User as UserModel;
use Arrilot\BitrixCacher\Cache;
use Bitrix\Main\Type\DateTime;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы с адресами
 * Class AddressController
 * @package App\Api\Internal\User
 */
class AddressController extends BaseController
{
    /**
     * Записывает новый адрес доставки для конкретного пользователя
     *
     * @return ResponseInterface
     */
    public function addNewAddress(): ResponseInterface
    {
        /** @var UserModel $user - Текущий пользователь */
        $user = user();

        /** @var array $request - Массив с полями формы */
        $request = htmlentities_on_array($_REQUEST);

        /** @var ResponseInterface $response */
        $response = null;

        try {
            if (DeliveryAddressCore::isAddressUnique($user, $request)) {
                if ($request['delivery-address-agree']) {
                    DeliveryAddressCore::markAddressAsDefault($user);
                }

                (new AddressChecker())->setFields(
                    ['delivery-address-country' => $request['delivery-address-country']],
                    ['delivery-address-index' => $request['delivery-address-index']],
                    ['delivery-address-region' => $request['delivery-address-region']],
                    ['delivery-address-city' => $request['delivery-address-city']],
                    ['delivery-address-street' => $request['delivery-address-street']],
                    ['delivery-address-house' => $request['delivery-address-house']],
                    ['delivery-address-apartment' => $request['delivery-address-apartment']]
                )->setUser($user)->check();

                Address::create(DeliveryAddressCore::getAddressModelArrayByForm($user, $request));

                /** @var UserInterface $personType - Тип лица */
                $personType = (new User)->setUserAndDefineUserPersonType($user)->getPersonType()->getCrmClass();
                $personType->setUser($user)->setPerson();

                $response = $this->respondWithSuccess();
                Cache::flush(Address::DELIVERY_ADDRESS_PERSONAL_CACHE_INIT_DIR . $user->getId());
            } else {
                $response = $this->errorAlreadyExists();
            }
        } catch (FieldIntegralityCheckerException $exception) {
            $response = $this->respondWithError($exception->getMessage(), $exception->getCode());
        } catch (Throwable $exception) {
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Обновление адреса
     *
     * @return ResponseInterface
     */
    public function updateAddress(): ResponseInterface
    {
        /** @var UserModel $user - Текущий пользователь */
        $user = user();

        /** @var array $request - Массив полей из формы */
        $request = htmlentities_on_array($_REQUEST);

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            if (DeliveryAddressCore::isAddressUnique($user, $request)) {
                if ($request['delivery-address-agree']) {
                    DeliveryAddressCore::markAddressAsDefault($user);
                }

                /** @var DeliveryAddress $deliveryAddress - Адрес доставки */
                $deliveryAddress = Address::getById($request['delivery-address-id']);
                $deliveryAddress->update(
                    DeliveryAddressCore::getAddressModelArrayByForm($user, $request));

                /** @var UserInterface $personType - Тип лица */
                $personType = (new User)->setUserAndDefineUserPersonType($user)->getPersonType()->getCrmClass();
                $personType->setUser($user)->setPerson();

                $response = $this->respondWithSuccess();
                Cache::flush(Address::DELIVERY_ADDRESS_PERSONAL_CACHE_INIT_DIR . $user->getId());
            } else {
                $response = $this->errorAlreadyExists();
            }
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception->getMessage());
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Удаляет адрес доставки
     *
     * @param int $addressId - Идентификатор адреса доставки
     *
     * @return ResponseInterface
     */
    public function removeAddress(int $addressId): ResponseInterface
    {
        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            /** @var DeliveryAddress $deliveryAddress - Адрес доставки */
            $deliveryAddress = Address::getById($addressId);
            $deliveryAddress->delete();

            /** @var UserInterface $personType - Тип лица */
            $personType = (new User)->setUserAndDefineUserPersonType(user())->getPersonType()->getCrmClass();
            $personType->setUser(user())->setPerson();

            $response = $this->respondWithSuccess();
            Cache::flush(Address::DELIVERY_ADDRESS_PERSONAL_CACHE_INIT_DIR . user()->getId());
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception->getMessage());
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }
}
