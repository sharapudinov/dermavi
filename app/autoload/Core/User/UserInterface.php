<?php

namespace App\Core\User;

use App\Api\External\CRM\CrmClientAbstract;
use App\Models\User;

/**
 * Интерфейс для работы с пользователем
 * Interface UserInterface
 * @package App\Core\User
 */
interface UserInterface
{
    /**
     * Регистрирует пользователя
     *
     * @param array|string[] $formedData - Сформированный массив данных, общий для всех видов пользователей
     * @param array|string[] $notFormedData - Несформированный массив данных, индивидуальный для каждого
     * вида пользователей
     *
     * @return User|null
     */
    public function signUpUser(array $formedData, array $notFormedData): ?User;

    /**
     * Получаем символьный код типа пользователя в БД
     *
     * @return string
     */
    public function getPersonTypeCode(): string;

    /**
     * Сохраняет в базу информацию по профилю
     *
     * @param User $user - Пользователь
     * @param array $formData - Данные из формы
     * @return bool
     */
    public function setProfileData(User $user, array $formData): bool;

    /**
     * Обновляет данные пользователя в БД ИМ на основе данных из CRM
     *
     * @param User $user - Модель пользователя
     *
     * @return void
     *
     * @throws CrmUserNotFoundException
     */
    public function updateProfileFromCrm(User $user): void;

    /**
     * Создает пользователей и все их данные в БД ИМ на основе данных из CRM
     *
     * @return void
     */
    public function createProfileFromCrm(): void;

    /**
     * Сохраняет данные анкеты
     *
     * @param User $user Модель текущего пользователя
     * @param array|mixed[] $formData Массив, описывающий данные из формы
     *
     * @return void
     */
    public function setProfileFormData(User $user, array $formData): void;

    /**
     * Возвращает класс для работы с CRM в зависимости от типа лица
     *
     * @return CrmClientAbstract
     */
    public function getCrmPersonClass(): CrmClientAbstract;
}
