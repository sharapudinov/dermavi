<?php

namespace App\Models\HL;

use App\Core\CRM\PersonalForm\LegalPerson\PersonalFormLegalAbstract;
use App\Helpers\DateTimeHelper;
use App\Models\HL\Company\SingleExecutiveAuthority;
use App\Models\User;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use CFile;
use DateTime;
use Exception;
use Illuminate\Support\Collection;

/**
 * Класс-модель, описывающий сущность "Личная анкета"
 * Class PersonalForm
 *
 * @package App\Models\HL
 *
 * @property-read User user
 * @property-read PublicOfficial publicOfficial
 * @property-read Collection|Signatory[] signatories
 * @property-read Collection|Consignee[] consignees
 * @property-read SingleExecutiveAuthority singleExecutiveAuthority
 * @property-read Collection|Beneficiary[] $beneficiaries
 * @property-read Beneficiary beneficiaryOwner
 * @property-read Collection|Beneficiary[] beneficiariesDefault
 */
class PersonalForm extends D7Model
{
    /** @var string - Символьный код таблицы */
    public const TABLE_CODE = 'personal_form';

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
     * Получаем идентификатор
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает массив интересов пользователя
     *
     * @return null|array|string[]
     */
    public function getInterest(): ?array
    {
        return json_decode($this['UF_INTEREST']);
    }

    /**
     * Возвращает характер деловых отношений
     *
     * @return string|null
     */
    public function getRelationshipDescription(): ?string
    {
        return $this['UF_RELATIONSHIP_DESC'];
    }

    /**
     * Возвращает массив деклараций
     *
     * @return null|array|string[]
     */
    public function getDeclarations(): ?array
    {
        return json_decode($this['UF_DECLARATIONS']);
    }

    /**
     * Возвращает json, описывающий структуру бенефициаров
     *
     * @return string|null
     */
    public function getBeneficiariesJsonStructure(): ?string
    {
        $beneficiaries = $this->unsetChildBeneficiariesKeys();
        return json_encode(PersonalFormLegalAbstract::getBeneficiariesJsonStructure($beneficiaries));
    }

    /**
     * Убирает ключи дочерних бенефициаров
     *
     * @return Collection
     */
    public function unsetChildBeneficiariesKeys(): Collection
    {
        /**
         * @var array|int[] $childBeneficiariesKeys
         *
         * Идентификаторы дочерних бенефициаров, которые не должны быть в общей коллекции
         */
        $childBeneficiariesKeys = [];
        $beneficiaries = $this->beneficiariesDefault;
        $beneficiaries->map(function (Beneficiary $beneficiary) use (&$childBeneficiariesKeys) {
            $childBeneficiariesKeys = array_merge(
                $childBeneficiariesKeys,
                $beneficiary->childBeneficiaries->pluck('ID')->toArray()
            );
        });

        return $beneficiaries->forget($childBeneficiariesKeys);
    }

    /**
     * Сохраняет в модели пути до нотариально заверенных документов
     *
     * @return void
     */
    public function setNotarizedDocuments(): void
    {
        $this['UF_INFO_CONF'] = array_map(function ($scanId) {
            return CFile::GetFileArray($scanId);
        }, $this['UF_INFO_CONF']);
    }

    /**
     * Возвращает нотариально заверенные документы
     *
     * @return null|array|array[]
     */
    public function getNotarizedDocuments(): ?array
    {
        if ($this['UF_INFO_CONF']) {
            $firstKey = array_key_first($this['UF_INFO_CONF']);
            if (is_int($this['UF_INFO_CONF'][$firstKey]) || is_string($this['UF_INFO_CONF'][$firstKey])) {
                $this->setNotarizedDocuments();
            }
        } else {
            $this['UF_INFO_CONF'] = [];
        }

        return $this['UF_INFO_CONF'];
    }

    /**
     * Возвращает флаг, указывающий проверена ли анкета
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this['UF_APPROVED'] ?? false;
    }

    /**
     * Возвращает дату создания
     *
     * @return string
     *
     * @throws Exception
     */
    public function getDateCreate(): string
    {
        return $this['UF_DATE_CREATE']->format('d') . ' '
                . DateTimeHelper::getMonthInNecessaryLanguage(
                new DateTime($this['UF_DATE_CREATE']->format('d.m.Y')),
                true,
                '',
                null,
                'F'
            ) . ' '
            . $this['UF_DATE_CREATE']->format('Y');
    }

    /**
     * Возвращает дату обновления
     *
     * @return string
     *
     * @throws Exception
     */
    public function getDateUpdate(): string
    {
        return $this['UF_DATE_CREATE']->format('d') . ' '
            . DateTimeHelper::getMonthInNecessaryLanguage(
                new DateTime($this['UF_DATE_CREATE']->format('d.m.Y')),
                true,
                '',
                null,
                'F'
            ) . ' '
            . $this['UF_DATE_CREATE']->format('Y');
    }

    /**
     * Возвращает запрос для получения модели пользователя
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'UF_USER_ID');
    }

    /**
     * Возвращает запрос для получения модели публичного должностного лица
     *
     * @return BaseQuery
     */
    public function publicOfficial(): BaseQuery
    {
        return $this->hasOne(PublicOfficial::class, 'ID', 'UF_PUBLIC_OFFICIAL');
    }

    /**
     * Возвращает запрос для получения коллекции моделей подписантов
     *
     * @return BaseQuery
     */
    public function signatories(): BaseQuery
    {
        return $this->hasMany(Signatory::class, 'ID', 'UF_SIGNATORIES_IDS');
    }

    /**
     * Возвращает запрос для получения коллекции грузополучателей
     *
     * @return BaseQuery
     */
    public function consignees(): BaseQuery
    {
        return $this->hasMany(Consignee::class, 'ID', 'UF_CONSIGNEES_IDS');
    }

    /**
     * Возвращает запрос для получения модели единоличного исполнительного органа
     *
     * @return BaseQuery
     */
    public function singleExecutiveAuthority(): BaseQuery
    {
        return $this->hasOne(SingleExecutiveAuthority::class, 'ID', 'UF_SEA_ID');
    }

    /**
     * Возвращает запрос для получения коллекции моделей всех бенефициаров
     *
     * @return BaseQuery
     */
    public function beneficiaries(): BaseQuery
    {
        return $this->hasMany(Beneficiary::class, 'ID', 'UF_BENEFICIARIES_IDS');
    }

    /**
     * Возвращает запрос для получения модели бенефициарного владельца
     *
     * @return BaseQuery
     */
    public function beneficiaryOwner(): BaseQuery
    {
        return $this->hasMany(Beneficiary::class, 'ID', 'UF_BENEFICIARIES_IDS')
            ->filter(['UF_IS_OWNER' => true]);
    }

    /**
     * Возвращает запрос для получения коллекции моделей бенефицаров (не владельцев)
     *
     * @return BaseQuery
     */
    public function beneficiariesDefault(): BaseQuery
    {
        return $this->hasMany(Beneficiary::class, 'ID', 'UF_BENEFICIARIES_IDS')
            ->filter(['UF_IS_OWNER' => false]);
    }
}
