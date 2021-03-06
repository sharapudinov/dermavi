<?php

use App\Core\Auxiliary\UserType\UserLinkUserType;
use App\Models\HL\DiamondOrderStatus;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\HL\DiamondOrder;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/**
 * Класс, описывающий миграцию по изменению поля файл в сущности "Заказ бриллианта"
 *
 * Class ChangeDiamondOrderFileField20190524111308624132
 */
class ChangeDiamondOrderFileField20190524111308624132 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /*$entityId = 'HLBLOCK_' . highloadblock(DiamondOrder::tableName())['ID'];
        HighloadBlock::delete(highloadblock(DiamondOrder::tableName())['ID']);

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
            ->setXmlId('UF_FILE')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Файлы')
            ->setLangDefault('en', 'Files')
            ->setLangDefault('cn', 'Files')
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
            ->add();*/
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // $hlBlockId = highloadblock(DiamondOrder::tableName())['ID'];

        // /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        // $result = HighloadBlockTable::delete($hlBlockId);

        // if (!$result->isSuccess()) {
        //     $details = implode('; ', $result->getErrorMessages());
        //     throw new MigrationException("Error deleting highloadblock DiamondOrder: " . $details);
        // }
    }
}
