<?php

use App\Core\Auxiliary\UserType\UserLinkUserType;
use App\Models\HL\DiamondOrder;
use App\Models\HL\DiamondOrderStatus;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Миграция для добавления таблиц для заказов бриллиантов.
 * Class AddDiamondOrder20190207075927501627
 */
class AddDiamondOrder20190207075927501627 extends BitrixMigration
{
    /** @var bool Выполняется в транзакции */
    public $use_transaction = true;

    /**
     * Run the migration.
     * @throws \Exception
     */
    public function up(): void
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('DiamondOrder', DiamondOrder::tableName())
            ->setLang('ru', 'Заказы на производство бриллиантов')
            ->setLang('en', 'Diamond manufacturing orders')
            ->setLang('cn', '钻石制造订单')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'DATE')
            ->setMandatory(true)
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата заказа')
            ->setLangDefault('en', 'Order date')
            ->setLangDefault('cn', '订单日期')
            ->add();

        (new UserField())->constructDefault($entityId, 'STATUS')
            ->setMandatory(true)
            ->setUserTypeHL(DiamondOrderStatus::tableName(), 'UF_XML_ID')
            ->setLangDefault('ru', 'Статус заказа')
            ->setLangDefault('en', 'Order status')
            ->setLangDefault('cn', '订单状态')
            ->add();

        (new UserField())->constructDefault($entityId, 'PRICE')
            ->setUserType('money')
            ->setSettings(['DEFAULT_VALUE' => '0|USD'])
            ->setLangDefault('ru', 'Цена')
            ->setLangDefault('en', 'Price')
            ->setLangDefault('cn', '价格')
            ->add();

        (new UserField())->constructDefault($entityId, 'PHONE')
            ->setLangDefault('ru', 'Телефон')
            ->setLangDefault('en', 'Phone')
            ->setLangDefault('cn', '电话')
            ->add();

        (new UserField())->constructDefault($entityId, 'EMAIL')
            ->setLangDefault('ru', 'Эл. почта')
            ->setLangDefault('en', 'Email')
            ->setLangDefault('cn', '电子邮件')
            ->add();

        (new UserField())->constructDefault($entityId, 'FILE')
            ->setUserType('file')
            ->setSettings(['SIZE' => 120])
            ->setShowInList(false)
            ->setLangDefault('ru', 'Прикрепленный файл')
            ->setLangDefault('en', 'Attached file')
            ->setLangDefault('cn', '附件')
            ->add();

        (new UserField())->constructDefault($entityId, 'MESSAGE')
            ->setShowInList(false)
            ->setSettings(['SIZE' => 100, 'ROWS' => 3])
            ->setLangDefault('ru', 'Сообщение')
            ->setLangDefault('en', 'Message')
            ->setLangDefault('cn', '消息')
            ->add();

        (new UserField())->constructDefault($entityId, 'USER_ID')
            ->setUserType(UserLinkUserType::TYPE_ID)
            ->setLangDefault('ru', 'Пользователь')
            ->setLangDefault('en', 'User')
            ->setLangDefault('cn', '用户')
            ->add();

        (new UserField())->constructDefault($entityId, 'COMPANY')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->setShowInList(false)
            ->setLangDefault('ru', 'Название компании')
            ->setLangDefault('en', 'Company name')
            ->setLangDefault('cn', '公司名称')
            ->add();

        (new UserField())->constructDefault($entityId, 'CONTACT_PERSON')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->setShowInList(false)
            ->setLangDefault('ru', 'Контактное лицо')
            ->setLangDefault('en', 'Contact person')
            ->setLangDefault('cn', '联系人')
            ->add();
    }

    /**
     * Reverse the migration.
     * @throws \Exception
     */
    public function down(): void
    {
        $hlBlockId = highloadblock(DiamondOrder::tableName())['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock DiamondOrder: " . $details);
        }
    }
}
