<?php

namespace App\Models\HL;

use App\Models\Auxiliary\CRM\IdentityDocumentType;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Main\Type\Date;
use CFile;

/**
 * Класс-модель для сущности "Паспортные данные"
 * Class PassportData
 *
 * @package App\Models\HL
 *
 * @property-read Country $registerCountry
 * @property-read Country $citizenship
 * @property-read Country $birthCountry
 */
class PassportData extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_passport_data';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает идентификатор паспортных данных
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает страну регистрации
     *
     * @return int|null
     */
    public function getRegistrationCountry(): ?int
    {
        return $this['UF_REG_COUNTRY'];
    }

    /**
     * Возвращает серию паспорта
     *
     * @return string|null
     */
    public function getSeries(): ?string
    {
        return $this['UF_SERIES'];
    }


    /**
     * Возвращает номер паспорта
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this['UF_NUMBER'];
    }

    /**
     * Возвращает номер и серию паспорта
     *
     * @return string|null
     */
    public function getFullNumber(): ?string
    {
        return $this->getSeries() . ' ' . $this->getNumber();
    }

    /**
     * Возвращает дату выпуска
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getIssueDate(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_ISSUE_DATE'] ? $this['UF_ISSUE_DATE']->format($format) : null;
    }

    /**
     * Возвращает дату начала действия
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getStartDate(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_START_DATE'] ? $this['UF_START_DATE']->format($format) : null;
    }

    /**
     * Возвращает дату окончания действия паспорта
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getValidTo(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_VALIDITY_DATE'] ? $this['UF_VALIDITY_DATE']->format($format) : null;
    }

    /**
     * Возвращает организацию, выдавшую документ
     *
     * @return string|null
     */
    public function getIssueOrganization(): ?string
    {
        return $this['UF_DOCUMENT_ORGAN'];
    }

    /**
     * Возвращает код подразделения
     *
     * @return string|null
     */
    public function getOrganizationCode(): ?string
    {
        return $this['UF_ISSUE_ORG_CODE'];
    }

    /**
     * Возвращает место рождения
     *
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return $this['UF_BIRTH_PLACE'];
    }

    /**
     * Возвращает дату рождения
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getBirthday(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_BIRTHDAY'] ? $this['UF_BIRTHDAY']->format($format) : null;
    }

    /**
     * Возвращает срок пребывания на территории РФ
     *
     * @return string|null
     */
    public function getStayDuration(): ?string
    {
        return $this['UF_STAY_DURATION'];
    }

    /**
     * Сохраняет в модели пути до файлов сканов паспорта
     *
     * @return void
     */
    public function setPassportScans(): void
    {
        $this['UF_SCANS'] = array_map(function ($scanId) {
            return CFile::GetFileArray($scanId);
        }, $this['UF_SCANS']);
    }

    /**
     * Возвращает массив прикрепленных сканов паспорта
     *
     * @return null|array|array[]
     */
    public function getPassportScans(): ?array
    {
        if ($this['UF_SCANS']) {
            if (is_int($this['UF_SCANS'][0]) || is_string($this['UF_SCANS'][0])) {
                $this->setPassportScans();
            }
        } else {
            $this['UF_SCANS'] = [];
        }

        return $this['UF_SCANS'];
    }

    /**
     * Возвращает миграционную карту
     *
     * @return string|null
     */
    public function getMigrationCard(): ?string
    {
        return $this['UF_MIGRATION_CARD'];
    }

    /**
     * Возвращает адрес регистрации
     *
     * @return string|null
     */
    public function getRegisterAddress(): ?string
    {
        return $this['UF_REGISTER_ADDRESS'];
    }

    /**
     * Возвращает город регистрации
     *
     * @return string|null
     */
    public function getRegisterCity(): ?string
    {
        return $this['UF_REG_CITY'];
    }

    /**
     * Возвращает основание
     *
     * @return string|null
     */
    public function getAccount(): ?string
    {
        return $this['UF_ACCOUNT'];
    }

    /**
     * ID страны гражданства
     * @return int|null
     */
    public function getCitizenshipId():?int
    {
        return $this['UF_CITIZENSHIP'];
    }

    /**
     * ID страны рождения
     * @return int|null
     */
    public function getBirthCountryId():?int
    {
        return $this['UF_BIRTH_COUNTRY'];
    }

    /**
     * Возвращает crm id объекта
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Возвращает модель типа документа
     *
     * @return IdentityDocumentType
     */
    public function getType(): IdentityDocumentType
    {
        return IdentityDocumentType::where('id', $this['UF_TYPE'])->first();
    }

    /**
     * Возвращает запрос для получения страны гражданства пользователя
     *
     * @return BaseQuery
     */
    public function citizenship(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_CITIZENSHIP');
    }

    /**
     * Возвращает запрос для получения страны рождения пользователя
     *
     * @return BaseQuery
     */
    public function birthCountry(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_BIRTH_COUNTRY');
    }

    /**
     * Возвращает запрос для получения страны регистрации пользователя
     *
     * @return BaseQuery
     */
    public function registerCountry(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_REG_COUNTRY');
    }
}
