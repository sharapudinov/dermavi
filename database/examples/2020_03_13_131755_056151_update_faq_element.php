<?php

use App\Models\ForPartners\FAQ;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для изменения элемента ИБ "FAQ"
 * Class UpdateFaqElement20200313131755056151
 */
class UpdateFaqElement20200313131755056151 extends BitrixMigration
{
    /** @var FAQ $element */
    private $element;

    /**
     * UpdateFaqElement20200313131755056151 constructor.
     */
    public function __construct()
    {
        $this->element = FAQ::filter([
            'NAME' => 'Какие документы необходимо предоставить для заключения контракта на оптовую покупку бриллиантов?'
        ])->first();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => '<p>Иностранные юридические лица:</p>
<ul class="ul-list">
                        
<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Рекомендательное письмо банка, обслуживающего покупателя, не более, чем месячной давности;</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени хозяйствующего субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы.</li>

<p>Юридические лица – резиденты Российской Федерации:</p>

<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Свидетельство о внесении в Единый государственный реестр юридических лиц Российской Федерации, выписку из реестра не более, чем месячной давности;</li>
<li>Устав и договор о создании общества;</li>
<li>Свидетельство о постановке на налоговый учёт по месту нахождения;</li>
<li>Уведомление (свидетельство) о постановке на специальный учёт в Пробирной палате Российской Федерации;</li>
<li>Справки из банков-резидентов об открытых счетах;</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы;</li>
<li>Карточка учёта основных сведений фирмы.</li>

<p>Индивидуальные предприниматели –  резиденты Российской Федерации:</p>

<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Свидетельство о внесении в Единый государственный реестр юридических лиц Российской Федерации, выписку из реестра не более, чем месячной давности;</li>
<li>Свидетельство о постановке на налоговый учёт по месту нахождения;</li>
<li>Уведомление (свидетельство) о постановке на специальный учёт в Пробирной палате Российской Федерации;</li>
<li>Справки из банков-резидентов об открытых счетах;</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы.</li>
</ul>',
            'PROPERTY_ANSWER_EN_VALUE' => '<p>Foreign legal entities:</p>
<ul class="ul-list">
                        
<li>Registration data, a questionnaire and consent to personal data processing;</li>
<li>A letter of good standing from a customer’s bank, not more than one month old;</li>
<li>A power of attorney in favour of persons authorised to enter into negotiations on behalf of the business entity;</li>
<li>A power of attorney, or an order specifying a duly authorised signatory.</li>

<p>Legal entities-Russian residents:</p>

<li>Registration details, a questionnaire and consent to personal data processing;</li>
<li>A certificate of an entry made in the Unified State Register of Organisations of the Russian Federation, with an extract from the Register generated not more than a month before;</li>
<li>Articles of Association;</li>
<li>A tax registration certificate at the domicile;</li>
<li>Notification (certificate) of special registration with the Assay Chamber of Russia;</li>
<li>Statements from resident banks as to account opening;</li>
<li>A power of attorney, in favour of persons authorised to enter into negotiations on behalf of the sole proprietor;</li>
<li>A power of attorney or an order, specifying a duly authorised signatory;</li>
<li>Company background registration card.</li>

<p>Sole proprietors-Russian residents:</p>

<li>Registration details, a questionnaire and consent to personal data processing;</li>
<li>A certificate of an entry made in the Unified State Register of Organisations of the Russian Federation, an extract from the Register generated not more than a month before;</li>
<li>A tax registration certificate at the domicile;</li>
<li>Notification (certificate) of special registration with the Assay Chamber of Russia;</li>
<li>Statements from resident banks as to account opening;</li>
<li>A power of attorney, in favour of persons authorised to enter into negotiations on behalf of the sole proprietor;</li>
<li>A power of attorney or an order, specifying a duly authorised signatory.</li>
</ul>',
            'PROPERTY_ANSWER_CN_VALUE' => '<p>外国法人实体：</p>
 <ul class="ul-list">
<li>注册数据，申请表和同意处理个人数据;</li>
<li>不超过一个月前为客户提供服务的银行推荐信;</li>
<li>代表经济实体进行谈判的人的授权书;</li>
<li>授权书或指示正式签署文件的订单。</li>
<p>法人实体为俄罗斯联邦居民:</p>
<li>注册数据，申请表和同意处理个人数据;</li>
<li>俄罗斯联邦法人实体统一注册登记证书，不超过一个月前从登记册中摘录;</li>
<li>关于创建公司的章程和协议;</li>
<li>所在地的税务登记证明;</li>
<li>俄罗斯联邦分析室特别注册通知书 (证书);</li>
<li>来自开户账户的居民银行的证明;</li>
<li>授权代表该主题进行谈判的人的授权书;</li>
<li>授权书或指示官方签署文件的命令;</li>
<li>公司主要信息的会计卡.</li>
<p>个体企业家 - 俄罗斯联邦居民:</p>
<li>注册数据，申请表和同意处理个人数据;</li>
<li>俄罗斯联邦法人实体统一注册登记证书，不超过一个月前从登记册中摘录;</li>
<li>所在地的税务登记证明;</li>
<li>所在地的税务登记证明;</li>
<li>俄罗斯联邦分析室特别注册通知书(证书);</li>
<li>来自开户账户的居民银行的证明;</li>
<li>授权代表该主题进行谈判的人的授权书;</li>
<li>授权书或指示官方签署文件的命令.</li>
</ul>'
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => '<p>Иностранные юридические лица:</p>
<ul class="ul-list">
                        
<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Рекомендательное письмо банка, обслуживающего покупателя, не более, чем месячной давности;</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени хозяйствующего субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы.</li>

<p>Юридические лица – резиденты Российской Федерации:</p>

<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Свидетельство о внесении в Единый государственный реестр юридических лиц Российской Федерации, выписку из реестра не более, чем месячной давности;</li>
<li>Устав и договор о создании общества;</li>
<li>Свидетельство о постановке на налоговый учёт по месту нахождения;</li>
<li>Уведомление (свидетельство) о постановке на специальный учёт в Пробирной палате Российской Федерации;</li>
<li>Справки из банков-резидентов об открытых счетах</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы;</li>
<li>Карточка учёта основных сведений фирмы;</li>

<p>Индивидуальные предприниматели –  резиденты Российской Федерации:</p>

<li>Регистрационные данные, анкета и согласие на обработку персональных данных;</li>
<li>Свидетельство о внесении в Единый государственный реестр юридических лиц Российской Федерации, выписку из реестра не более, чем месячной давности;</li>
<li>Свидетельство о постановке на налоговый учёт по месту нахождения;</li>
<li>Уведомление (свидетельство) о постановке на специальный учёт в Пробирной палате Российской Федерации;</li>
<li>Справки из банков-резидентов об открытых счетах;</li>
<li>Доверенность на лиц, уполномоченных вести переговоры от имени субъекта;</li>
<li>Доверенность или приказ с указанием должностного лица, подписывающего документы.</li>
',
            'PROPERTY_ANSWER_EN_VALUE' => '<p>Foreign legal entities:</p>
<ul class="ul-list">
                        
<li>Registration data, a questionnaire and consent to personal data processing;</li>
<li>A letter of good standing from a customer’s bank, not more than one month old;</li>
<li>A power of attorney in favour of persons authorised to enter into negotiations on behalf of the business entity;</li>
<li>A power of attorney, or an order specifying a duly authorised signatory.</li>

<p>Legal entities-Russian residents:</p>

<li>Registration details, a questionnaire and consent to personal data processing;</li>
<li>A certificate of an entry made in the Unified State Register of Organisations of the Russian Federation, with an extract from the Register generated not more than a month before;</li>
<li>Articles of Association;</li>
<li>A tax registration certificate at the domicile;</li>
<li>Notification (certificate) of special registration with the Assay Chamber of Russia;</li>
<li>Statements from resident banks as to account opening;</li>
<li>A power of attorney, in favour of persons authorised to enter into negotiations on behalf of the sole proprietor;</li>
<li>A power of attorney or an order, specifying a duly authorised signatory;</li>
<li>Company background registration card.</li>

<p>Sole proprietors-Russian residents:</p>

<li>Registration details, a questionnaire and consent to personal data processing;</li>
<li>A certificate of an entry made in the Unified State Register of Organisations of the Russian Federation, an extract from the Register generated not more than a month before;</li>
<li>A tax registration certificate at the domicile;</li>
<li>Notification (certificate) of special registration with the Assay Chamber of Russia;</li>
<li>Statements from resident banks as to account opening;</li>
<li>A power of attorney, in favour of persons authorised to enter into negotiations on behalf of the sole proprietor;</li>
<li>A power of attorney or an order, specifying a duly authorised signatory.</li>
</ul>',
            'PROPERTY_ANSWER_CN_VALUE' => '外国法人实体：
 <ul class="ul-list">
<li>注册数据，申请表和同意处理个人数据;</li>
<li>不超过一个月前为客户提供服务的银行推荐信;</li>
<li>代表经济实体进行谈判的人的授权书;</li>
<li>授权书或指示正式签署文件的订单。</li>
<li>法人实体为俄罗斯联邦居民：</li>
<li>注册数据，申请表和同意处理个人数据;</li>
<li>俄罗斯联邦法人实体统一注册登记证书，不超过一个月前从登记册中摘录;</li>
<li>关于创建公司的章程和协议;</li>
<li>所在地的税务登记证明;</li>
<li>俄罗斯联邦分析室特别注册通知书（证书）;</li>
<li>来自开户账户的居民银行的证明</li>
<li>授权代表该主题进行谈判的人的授权书;</li>
<li>授权书或指示官方签署文件的命令;</li>
<li>公司主要信息的会计卡;</li>
<li>个体企业家 - 俄罗斯联邦居民：</li>
<li>注册数据，申请表和同意处理个人数据;</li>
<li>俄罗斯联邦法人实体统一注册登记证书，不超过一个月前从登记册中摘录;</li>
<li>所在地的税务登记证明;</li>
<li>所在地的税务登记证明;</li>
<li>俄罗斯联邦分析室特别注册通知书（证书）;</li>
<li>来自开户账户的居民银行的证明</li>
<li>授权代表该主题进行谈判的人的授权书;</li>
<li>授权书或指示官方签署文件的命令;</li>
</ul>'
        ]);
    }
}
