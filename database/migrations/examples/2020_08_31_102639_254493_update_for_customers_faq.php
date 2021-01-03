<?php

use App\Models\ForCustomers\FAQ;
use App\Models\ForCustomers\Info;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateForCustomersFaq20200831102639254493 extends BitrixMigration
{

    /** @var string - Символьный код типа инфоблока */
    const IBLOCK_TYPE_ID = 'for_customers';

    /** @var string - Символьный код инфоблока */
    const IBLOCK_CODE = 'for_customers_faq';

    /** @var Info $element */
    private $element;

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $result = CIBlockElement::GetList
        (
            [],
            ['IBLOCK_ID' => (int)iblock_id(self::IBLOCK_CODE),]
        );

        while ($element = $result->Fetch()) {
            CIBlockElement::Delete($element['ID']);
        }

        FAQ::create([
            'NAME' => 'Кто может приобретать бриллианты и ювелирные украшения с нами?',
            'CODE' => 'faq-1',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Кто может приобретать бриллианты и ювелирные украшения с нами?',
                'QUESTION_EN' => 'Who can buy polished diamonds?',
                'QUESTION_CN' => '谁可以买钻石？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => 'Any individual registered in the territory of the Russian Federation or a foreign state can become a retail buyer of certified polished diamonds.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '在俄罗斯联邦境内或外国注册的任何个人可以当认证钻石的买方。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Покупателем сертифицированных бриллиантов и ювелирных украшений с бриллиантами может быть любое физическое лицо, достигшее возраста 18 лет и имеющее паспорт, выданный государством, гражданином которого является.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Какие документы необходимо предоставить для заключения договора на покупку бриллианта и ювелирных украшений с бриллиантами?',
            'CODE' => 'faq-2',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Какие документы необходимо предоставить для заключения договора на покупку бриллианта и ювелирных украшений с бриллиантами?',
                'QUESTION_EN' => 'What documents need to be provided to conclude a contract for the purchase of a diamond?',
                'QUESTION_CN' => '需要提供哪些文件才能签订购买钻石的合同？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "In case the value of your diamond does not exceed the amount of 100,000 rubles, it is enough to present a passport when paying at the company's office. If the cost exceeds 100,000 rubles, please complete the registration form in your customer account for the contract preparation.
We comply with The Russian Federation Federal Law No. 115-FZ of 7 August 2001 on Countering the Legalisation (Laundering) of Criminally Obtained Incomes and the Financing of Terrorism.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '如果您的钻石价值不超过100,000卢布，在公司办公室支付时，出示护照就足够。
 ·如果费用超过100,000卢布，为准备合同，请在您的个人账户中完成注册。
我们遵守俄罗斯联邦联邦法“关于打击犯罪和资助恐怖主义收益的合法化（洗钱）”第115号联邦法律的要求。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => ' В случае если стоимость Вашего бриллианта не превышает сумму 100 000 рублей, достаточно предъявить паспорт при оплате его в офисе компании.  Если стоимость превышает 100 000 рублей, пожалуйста, пройдите полную регистрацию в вашем личном кабинете для подготовки договора.
Мы соблюдаем требования Федерального закона Российской Федерации «О противодействии легализации (отмыванию) доходов, полученных преступным путем, и финансированию терроризма» №115-ФЗ.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Как часто обновляется ассортимент на сайте?',
            'CODE' => 'faq-3',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Как часто обновляется ассортимент на сайте?',
                'QUESTION_EN' => 'How often is the assortment updated on the website?',
                'QUESTION_CN' => '网站上的分类多久更新一次？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "The range of products on the website is updated in real time.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '网站上的范围实时更新。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => ' Ассортимент на сайте обновляется в режиме реального времени.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Как осуществляется оплата приобретаемых бриллиантов и украшений?',
            'CODE' => 'faq-4',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Как осуществляется оплата приобретаемых бриллиантов и украшений?',
                'QUESTION_EN' => 'How to pay for purchased diamonds?',
                'QUESTION_CN' => '如何支付购买的钻石？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "Payment for the purchased diamonds can be made by bank transfer against the invoice, or by credit card at the company's office.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '购买钻石的付款方式是通过银行转账支付发票或通过公司办公室的信用卡支付。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Оплата за приобретаемые бриллианты осуществляется банковской картой на сайте, банковским переводом по выставленному счету или банковской картой в офисе компании. <a target="_blank" href="/customer-service/order-and-shipping/#order">Подробнее</a>',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Возможен ли обмен или возврат приобретённого бриллианта или украшения?',
            'CODE' => 'faq-5',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Возможен ли обмен или возврат приобретённого бриллианта или украшения?',
                'QUESTION_EN' => 'Is it possible to exchange or return the purchased diamond?',
                'QUESTION_CN' => '是否可以兑换或退回购买的钻石？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "No, according to the legislation of the Russian Federation, all diamonds are final sale.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '可以，根据俄罗斯联邦的法律，钻石不受返还和交换。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Нет, по законодательству Российской Федерации бриллианты не подлежат возврату и обмену.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Что делать, если в каталоге не оказалось нужного бриллианта?',
            'CODE' => 'faq-6',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Что делать, если в каталоге не оказалось нужного бриллианта?',
                'QUESTION_EN' => 'What to do if there is no right diamond in the catalog?',
                'QUESTION_CN' => '如果目录中没有钻石怎么办？',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "ALROSA is a leading manufacturer of diamonds in Russia selling a wide selection of diamonds of all shapes, size and weight groups, color and quality characteristics. In the absence of the desired option in the catalog, you can submit a request for the company manager assistance in diamond selection or for manufacturing a custom diamond with its further certification in one of Russian or international laboratories.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => '《阿尔罗萨钻石》公司是一家俄罗斯领先的钻石生产商，销售各种形状，尺寸和重量组，颜色和质量特征的钻石。 在目录中没有所需的选项的情况下，您可以留下协助请求，受到公司的选钻石经理的协助，或在生产钻石部门留下申请，以便定制并获得俄罗斯或国际实验室证书。',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Компания АЛРОСА является ведущим производителем бриллиантов в России и реализует широкий спектр бриллиантов всех форм, размерно-весовых групп, цветовых и качественных характеристик. В случае отсутствия желаемого варианта в каталоге Вы можете оставить заявку на помощь в подборе бриллианта менеджером компании или изготовления бриллианта на заказ с дальнейшей его сертификацией в одной из российских или международных лабораторий.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'В какие города осуществляется доставка?',
            'CODE' => 'faq-7',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'В какие города осуществляется доставка?',
                'QUESTION_EN' => 'Which cities are delivered to?',
                'QUESTION_CN' => 'Which cities are delivered to?',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "Delivery to individuals is carried out to the administrative centers of the Russian Federation. Delivery to other cities and settlements is possible by prior agreement.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => 'Delivery to individuals is carried out to the administrative centers of the Russian Federation. Delivery to other cities and settlements is possible by prior agreement.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Доставка физическим лицам осуществляется до административных центров Российской Федерации. По предварительному согласованию возможна доставка в иные города и населенные пункты. <a target="_blank" href="/customer-service/order-and-shipping/#shipping">Подробнее</a>',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Могу ли я отказаться от получения товара?',
            'CODE' => 'faq-8',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Могу ли я отказаться от получения товара?',
                'QUESTION_EN' => 'Can I refuse to receive the goods?',
                'QUESTION_CN' => 'Can I refuse to receive the goods?',
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "You may cancel your order before payment at any time prior to receipt and opening the package of the parcel. If you refuse to receive the goods after delivery, the cost of transportation is withheld when refunded.
Gemstones and jewelry are not subject to exchange or return after opening the package of the order.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => 'You may cancel your order before payment at any time prior to receipt and opening the package of the parcel. If you refuse to receive the goods after delivery, the cost of transportation is withheld when refunded.
Gemstones and jewelry are not subject to exchange or return after opening the package of the order.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Вы вправе отменить заказ до оплаты в любое время до его получения и вскрытия упаковки посылки. В случае отказа от получения товара после осуществления доставки, стоимость перевозки удерживается при возврате денежных средств.
После вскрытия упаковки заказа драгоценные камни и ювелирные украшения обмену и возврату не подлежат.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Какой срок хранения у товара в отделении перевозчика?',
            'CODE' => 'faq-9',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Какой срок хранения у товара в отделении перевозчика?',
                'QUESTION_EN' => "What is the shelf life of the goods at the carrier's premises?",
                'QUESTION_CN' => "What is the shelf life of the goods at the carrier's premises?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "The goods are kept in the carrier's office for no more than 5 working days. The extension of storage period is paid and is agreed with you. If there is no information about the need to extend the storage period and it is impossible to contact you at the specified contact details, the goods are returned to the seller, and the cost of transportation is withheld when returning the money.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "The goods are kept in the carrier's office for no more than 5 working days. The extension of storage period is paid and is agreed with you. If there is no information about the need to extend the storage period and it is impossible to contact you at the specified contact details, the goods are returned to the seller, and the cost of transportation is withheld when returning the money.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Товар находится в отделении перевозчика не более 5 рабочих дней. Увеличение срока хранения является платным и происходит по согласованию с вами. При отсутствии информации о необходимости продления срока хранения и невозможности связаться с вами по указанным контактным данным товар возвращается продавцу, а стоимость перевозки удерживается при возврате денежных средств.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Что делать, если при получении заказа было обнаружено нарушение целостности упаковки?',
            'CODE' => 'faq-10',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Что делать, если при получении заказа было обнаружено нарушение целостности упаковки?',
                'QUESTION_EN' => "What should I do if a breach of packaging integrity was found upon receipt of an order?",
                'QUESTION_CN' => "What should I do if a breach of packaging integrity was found upon receipt of an order?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "When receiving the goods, inspect their packaging. In case of detection of infringement of integrity of packing (opening, torn, other damages influencing tightness) at the moment of reception of the goods inform the employee of courier service about it. The staff member will draw up a report and pick up the goods for return to the seller and clarify the circumstances of the opening.
After confirming the integrity of the goods by the seller you will be re-shipped.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "When receiving the goods, inspect their packaging. In case of detection of infringement of integrity of packing (opening, torn, other damages influencing tightness) at the moment of reception of the goods inform the employee of courier service about it. The staff member will draw up a report and pick up the goods for return to the seller and clarify the circumstances of the opening.
After confirming the integrity of the goods by the seller you will be re-shipped.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'При получении товара осмотрите его упаковку. В случае обнаружения нарушения целостности упаковки (вскрытие, надорванность, иные повреждения, влияющие на герметичность) в момент получения товара сообщите об этом сотруднику курьерской службы. Сотрудник составит акт и заберет товар для возврата продавцу и выяснения обстоятельств вскрытия.
После подтверждения целостности товара со стороны продавца вам будет осуществлена повторная отправка.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Может ли кто-то получить заказ вместо меня?',
            'CODE' => 'faq-11',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Может ли кто-то получить заказ вместо меня?',
                'QUESTION_EN' => "Can someone get an order instead of me?",
                'QUESTION_CN' => "Can someone get an order instead of me?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "Yes, by prior agreement with the manager. If you can't get the order yourself, please coordinate the changes of the recipient with the manager. A passport will be required from the new recipient.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "Yes, by prior agreement with the manager. If you can't get the order yourself, please coordinate the changes of the recipient with the manager. A passport will be required from the new recipient.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Да, по предварительному согласованию с менеджером. Если вы не можете получить заказ самостоятельно, пожалуйста, согласуйте изменения получателя с менеджером. От нового получателя потребуется предоставление паспорта.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Как я могу узнать о статусе доставки?',
            'CODE' => 'faq-12',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Как я могу узнать о статусе доставки?',
                'QUESTION_EN' => "How can I know the delivery status?",
                'QUESTION_CN' => "How can I know the delivery status?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "After paying for your order, you will receive the tracking number of the parcel and will be able to track its movement on the website of the carrier https://www.cccb.ru.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "After paying for your order, you will receive the tracking number of the parcel and will be able to track its movement on the website of the carrier https://www.cccb.ru.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'После оплаты заказа вы получите трек-номер посылки и сможете отслеживать ее передвижение на сайте перевозчика https://www.cccb.ru.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Могу ли я изменить адрес, дату, время доставки?',
            'CODE' => 'faq-13',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Могу ли я изменить адрес, дату, время доставки?',
                'QUESTION_EN' => "Can I change the address, date, delivery time?",
                'QUESTION_CN' => "Can I change the address, date, delivery time?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "Yes, by prior agreement with the manager and before the agreed delivery time. In case of changes in the delivery region, the cost and time may also be adjusted.
Please note that in case of delivery of the goods in the agreed time and address, but the goods have not been transferred due to your circumstances, the repeated delivery can be paid for.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "Yes, by prior agreement with the manager and before the agreed delivery time. In case of changes in the delivery region, the cost and time may also be adjusted.
Please note that in case of delivery of the goods in the agreed time and address, but the goods have not been transferred due to your circumstances, the repeated delivery can be paid for.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Да, по предварительному согласованию с менеджером и до наступления согласованного времени доставки. В случае изменения региона доставки стоимость и сроки могут быть также скорректированы.
Пожалуйста, обратите внимание, что в случае если доставка товара произведена в согласованные с вами сроки и по указанному адресу, но товар не был передан по вашим обстоятельствам, повторная доставка может быть платной.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Могу ли я отплатить свой заказ курьеру?',
            'CODE' => 'faq-14',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Могу ли я отплатить свой заказ курьеру?',
                'QUESTION_EN' => "Can I pay my order back to the courier?",
                'QUESTION_CN' => "Can I pay my order back to the courier?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "Couriers do not accept payment for goods and additional services. All calculations are made with the seller.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "Couriers do not accept payment for goods and additional services. All calculations are made with the seller.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Курьеры не принимают оплату товара и дополнительных услуг. Все расчеты производятся с продавцом.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Какие документы мне передаст курьер вместе с заказом?',
            'CODE' => 'faq-15',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Какие документы мне передаст курьер вместе с заказом?',
                'QUESTION_EN' => "What documents will the courier give me along with the order?",
                'QUESTION_CN' => "What documents will the courier give me along with the order?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => "The courier will give you the sealed package with the order and the delivery note confirming the delivery of the goods.
Inside the package along with the goods will be the seller's documents: certification results, product receipt or your copy of the contract.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => "The courier will give you the sealed package with the order and the delivery note confirming the delivery of the goods.
Inside the package along with the goods will be the seller's documents: certification results, product receipt or your copy of the contract.",
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Курьер передаст вам запломбированный пакет с заказом и накладную, подтверждающую доставку товара.
Внутри пакета вместе с товаром будут находится документы продавца: результаты сертификации, товарный чек или ваш экземпляр договора.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Могу ли я отправить своего курьера, чтобы забрать заказ?',
            'CODE' => 'faq-16',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Могу ли я отправить своего курьера, чтобы забрать заказ?',
                'QUESTION_EN' => "Can I send my courier to pick up the order?",
                'QUESTION_CN' => "Can I send my courier to pick up the order?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => 'Transportation of valuable cargo must be performed by an organization that meets the requirements of Article 29 of the Federal Law No. 41-FZ "On Precious Metals and Precious Stones" dated 29.03.1998. If you cooperate with such company, inform the manager for the accreditation procedure of your carrier.

If the goods will be received and transported by a private person, a passport and a notary power of attorney will be required.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => 'Transportation of valuable cargo must be performed by an organization that meets the requirements of Article 29 of the Federal Law No. 41-FZ "On Precious Metals and Precious Stones" dated 29.03.1998. If you cooperate with such company, inform the manager for the accreditation procedure of your carrier.

If the goods will be received and transported by a private person, a passport and a notary power of attorney will be required.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Перевозку ценных грузов должна осуществлять организация, отвечающая требованиям ст. 29 Федерального закона от 29.03.1998 г. № 41-ФЗ «О драгоценных металлах и драгоценных камнях». Если вы сотрудничаете с такой компанией, сообщите менеджеру для проведения процедуры аккредитации вашего перевозчика.

Если товар будет получать и перевозить частное лицо, потребуется предоставление паспорта и нотариальной доверенности.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Как осуществить доставку заказа на сумму свыше 100 000 рублей?',
            'CODE' => 'faq-17',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Как осуществить доставку заказа на сумму свыше 100 000 рублей?',
                'QUESTION_EN' => "How to carry out delivery of the order for the sum over 100 000 rubles?",
                'QUESTION_CN' => "How to carry out delivery of the order for the sum over 100 000 rubles?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => 'For the organization of delivery of the order on the sum more than 100 000 rubles your personal presence for signing the contract in office of the seller in Moscow or Smolensk is preliminary required.

To sign the contract, you will need to provide your passport.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => 'For the organization of delivery of the order on the sum more than 100 000 rubles your personal presence for signing the contract in office of the seller in Moscow or Smolensk is preliminary required.

To sign the contract, you will need to provide your passport.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Для организации доставки заказа на сумму свыше 100 000 рублей предварительно потребуется ваше личное присутствие для подписания договора в офисе продавца в Москве или Смоленске.

Для подписания договора потребуется предоставление паспорта.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME' => 'Осуществляется ли доставка за пределы Российской федерации?',
            'CODE' => 'faq-18',
            'PROPERTY_VALUES' => [
                'QUESTION_RU' => 'Осуществляется ли доставка за пределы Российской федерации?',
                'QUESTION_EN' => "Is there any delivery outside the Russian Federation?",
                'QUESTION_CN' => "Is there any delivery outside the Russian Federation?",
                'ANSWER_EN' => [
                    'VALUE' => [
                        'TEXT' => 'You can discuss individual conditions and delivery options with your manager.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_CN' => [
                    'VALUE' => [
                        'TEXT' => 'You can discuss individual conditions and delivery options with your manager.',
                        'TYPE' => 'HTML',
                    ]
                ],
                'ANSWER_RU' => [
                    'VALUE' => [
                        'TEXT' => 'Индивидуальные условия и возможности доставки вы можете обсудить с вашим менеджером. <a target="_blank" href="/customer-service/order-and-shipping/#shipping">Подробнее</a>',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);

        // Для покупателей/Информация/блок упаковка текст
        $this->element = Info::filter([
            'CODE' => 'upakovka_1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'Сертифицированные в российской лаборатории бриллианты помещаются в прозрачную пластиковую упаковку – блистер. Он имеет просмотровое окно для изучения бриллианта без искажений, а также защищен от вскрытия во избежание подмены сертифицированного бриллианта. <p>К дополнительным системам защиты относятся:</p>
<ul class="ul-list page-for-customers__ul">
                <li>оттиск знака соответствия Системы сертификации ограненных драгоценных камней;</li>
                <li>микротекст на оборотной стороне блистера;</li>
                <li>голограмма, которая разрушается при вскрытии защитной упаковки.</li>
        </ul>'
        ]);

        // Для покупателей/Информация/блок упаковка текст после картинки
        $this->element = Info::filter([
            'CODE' => 'upakovka_2'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_2 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'Внутрь упаковки помещается этикетка с названием геммологической лаборатории, знаком соответствия Системы, основными характеристиками бриллианта и номером сертификата.'
        ]);

        // Для покупателей/Информация/блок сертификат GIA текст
        $this->element = Info::filter([
            'CODE' => 'gia_certificate'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент gia_certificate не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'В дополнение к обязательной сертификации по российским стандартам мы можем по вашему желанию предоставить услугу по сертификации в международной лаборатории GIA – Геммологического института Америки. Сертификаты, выданные на один и тот же бриллиант разными сертификационными центрами мира, могут отличаться. Это связано с особенностями систем и критериев оценки бриллиантов разных геммологических лабораторий.'
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
        //
    }
}
