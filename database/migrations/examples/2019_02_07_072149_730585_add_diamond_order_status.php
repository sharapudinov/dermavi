<?php

use App\Models\HL\DiamondOrderStatus;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Миграция для создания таблицы для HL-блока статусов заказов бриллиантов.
 * Class AddDiamondOrderStatus20190207072149730585
 */
class AddDiamondOrderStatus20190207072149730585 extends BitrixMigration
{
    /** @var bool Используем транзакцию */
    public $use_transaction = true;

    /**
     * Run the migration.
     * @throws \Exception
     */
    public function up(): void
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('DiamondOrderStatus', DiamondOrderStatus::tableName())
            ->setLang('ru', 'Статусы заказов бриллиантов')
            ->setLang('en', 'Diamond order statuses')
            ->setLang('cn', '钻石订单状态')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Код статуса')
            ->setLangDefault('en', 'Status code')
            ->setLangDefault('cn', '状态代码')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Наименование (англ)')
            ->setLangDefault('en', 'Name (en)')
            ->setLangDefault('cn', '英文名称')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->setLangDefault('cn', '俄文名字')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_CN')
            ->setLangDefault('ru', 'Наименование (кит)')
            ->setLangDefault('en', 'Name (cn)')
            ->setLangDefault('cn', '中文名')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        $this->addStatus(DiamondOrderStatus::NEW, 'Заказ позиции', 'Order item', '订单商品');
        $this->addStatus(DiamondOrderStatus::EVALUATION, 'Оценка работы', 'Evaluation of work', '评估工作');
        $this->addStatus(DiamondOrderStatus::PAYMENT, 'Оплата', 'Payment', '付款');
        $this->addStatus(DiamondOrderStatus::PROCESS, 'Начало обработки', 'Start processing', '开始处理');
        $this->addStatus(DiamondOrderStatus::SHIPPING, 'Отправка изделий', 'Sending items', '发送物品');
    }

    /**
     * Reverse the migration.
     * @throws \Exception
     */
    public function down(): void
    {
        $hlBlockId = highloadblock(DiamondOrderStatus::tableName())['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock DiamondOrderStatus: " . $details);
        }
    }

    /**
     * Добавляет статус в таблицу.
     * @param string $code
     * @param string $nameRu
     * @param string $nameEn
     * @param string $nameCn
     * @throws MigrationException
     */
    private function addStatus(string $code, string $nameRu, string $nameEn, string $nameCn): void
    {
        $status = DiamondOrderStatus::create([
            'UF_XML_ID' => $code,
            'UF_NAME_RU' => $nameRu,
            'UF_NAME_EN' => $nameEn,
            'UF_NAME_CN' => $nameCn
        ]);

        if (!$status) {
            throw new MigrationException("Error registering status $code");
        }
    }
}
