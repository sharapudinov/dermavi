<?php

namespace App\Models\HL;

use App\Exceptions\HeaderFieldsSaveException;
use App\Exceptions\HeaderFieldsUserException;
use App\Helpers\UserHelper;
use App\Models\Auxiliary\HlD7Model;

/**
 * Параметры столбцов бриллиантов
 * для b2b
 *
 * Class HeaderFields
 * @package App\Models\HL
 */
class HeaderFields extends HlD7Model
{
    public const REQUEST_PARAM = 'manage-columns';

    /** @var string Название таблицы в БД */
    public const TABLE_CODE = 'adv_b2b_header_fields';

    /**
     * HeaderFields constructor.
     *
     * @param null $id
     * @param null $fields
     */
    public function __construct($id = null, $fields = null)
    {
        if (!UserHelper::isLegalEntity()) {
            throw new HeaderFieldsUserException('User is not legal');
        }

        $this['UF_USER_ID'] = user()->getId();

        parent::__construct($id, $fields);
    }

    /**
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return (int)$this['UF_USER_ID'];
    }

    /**
     * @return null|array
     */
    public function getHeaderFields(): ?array
    {
        return $this['UF_FIELDS'];
    }

    /**
     * @param array $headerFields
     *
     * @return HeaderFields
     */
    public function setHeaderFields(array $headerFields): HeaderFields
    {
        $this['UF_FIELDS'] = $headerFields;

        return $this;
    }

    /**
     * @return HeaderFields|null
     */
    public static function getFieldsForUser(): ?HeaderFields
    {
        if (!UserHelper::isLegalEntity()) {
            return null;
        }

        /** @var  HeaderFields $fields */
        return self::query()
                   ->filter(['UF_USER_ID' => user()->getId()])
                   ->getList()->first();
    }

    /**
     *
     */
    public function add()
    {
        if (!UserHelper::isLegalEntity()) {
            throw new HeaderFieldsSaveException('Cannot be saved for not legal user');
        }

        self::create($this->normalizeFieldsForSave(func_get_args()));
    }

    /**
     * @param array $selectedFields
     *
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @return bool
     */
    public function save($selectedFields = [])
    {
        if (!UserHelper::isLegalEntity()) {
            throw new HeaderFieldsSaveException('Cannot be saved for not legal user');
        }

        return parent::save($selectedFields);
    }
}
