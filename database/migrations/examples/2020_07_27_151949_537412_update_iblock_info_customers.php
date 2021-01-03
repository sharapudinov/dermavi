<?php

use App\Models\ForCustomers\FAQ;
use App\Models\ForCustomers\Info;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateIblockInfoCustomers20200727151949537412 extends BitrixMigration
{
    /** @var Info $element */
    private $element;

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        // Для покупателей/Информация/блок упаковка текст
        $this->element = Info::filter([
            'CODE' => 'upakovka_1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'Сертифицированные в российской лаборатории бриллианты помещаются в прозрачную пластиковую упаковку – блистер. Он имеет просмотровое окно для изучения бриллианта без искажений, а также защищен от вскрытия во избежание подмены сертифицированного бриллианта. К дополнительным системам защиты относятся:
- оттиск знака соответствия Системы сертификации ограненных драгоценных камней;
- микротекст на оборотной стороне блистера;
- голограмма, которая разрушается при вскрытии защитной упаковки.'
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



        // Для покупателей/Вопрос-ответ/блок ответы на вопросы/ Кто может приобретать бриллианты?
        $this->element = FAQ::filter([
            'CODE' => 'faq-1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент faq-1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => 'Покупателем сертифицированных бриллиантов и ювелирных украшений с бриллиантами может быть любое физическое лицо, достигшее возраста 18 лет и имеющее паспорт, выданный государством, гражданином которого является.'
        ]);

        // Для покупателей/Вопрос-ответ/блок ответы на вопросы
        $this->element = FAQ::filter([
            'CODE' => 'faq-1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент faq-1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => 'Покупателем сертифицированных бриллиантов и ювелирных украшений с бриллиантами может быть любое физическое лицо, достигшее возраста 18 лет и имеющее паспорт, выданный государством, гражданином которого является.'
        ]);

        // Для покупателей/Вопрос-ответ/блок ответы на вопросы/ Как осуществляется оплата приобретаемых бриллиантов?
        $this->element = FAQ::filter([
            'CODE' => 'faq-4'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент faq-1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => 'Оплата за приобретаемые бриллианты осуществляется банковской картой на сайте, банковским переводом по выставленному счету или банковской картой в офисе компании. <a href="/ru/customer-service/order-and-shipping/">Подробнее</a>'
        ]);



        // Добавление новых вопрос-ответа в FAQ для покупателей
        FAQ::create([
            'NAME'            => 'В какие города осуществляется доставка?',
            'CODE'            => 'faq-7',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'В какие города осуществляется доставка?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Доставка физическим лицам осуществляется до административных центров Российской Федерации. По предварительному согласованию возможна доставка в иные города и населенные пункты. <a href="/ru/customer-service/order-and-shipping/">Подробнее</a>',
                        'TYPE' => 'HTML',
                        ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Могу ли я отказаться от получения товара?',
            'CODE'            => 'faq-8',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Могу ли я отказаться от получения товара?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Вы вправе отменить заказ до оплаты в любое время до его получения и вскрытия упаковки посылки. В случае отказа от получения товара после осуществления доставки, стоимость перевозки удерживается при возврате денежных средств. <br>После вскрытия упаковки заказа драгоценные камни и ювелирные украшения обмену и возврату не подлежат.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Какой срок хранения товара в отделении перевозчика?',
            'CODE'            => 'faq-9',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Какой срок хранения товара в отделении перевозчика?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Товар находится в отделении перевозчика не более 5 рабочих дней. Увеличение срока хранения является платным и происходит по согласованию с вами. При отсутствии информации о необходимости продления срока хранения и невозможности связаться с вами по указанным контактным данным товар возвращается продавцу, а стоимость перевозки удерживается при возврате денежных средств.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Что делать, если при получении заказа было обнаружено нарушение целостности упаковки?',
            'CODE'            => 'faq-10',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Что делать, если при получении заказа было обнаружено нарушение целостности упаковки?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'При получении товара осмотрите его упаковку. В случае обнаружения нарушения целостности упаковки (вскрытие, надорванность, иные повреждения, влияющие на герметичность) в момент получения товара сообщите об этом сотруднику курьерской службы. Сотрудник составит акт и заберет товар для возврата продавцу и выяснения обстоятельств вскрытия. <br>После подтверждения целостности товара со стороны продавца вам будет осуществлена повторная отправка.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Может ли кто-то получить заказ вместо меня?',
            'CODE'            => 'faq-11',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Может ли кто-то получить заказ вместо меня?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Да, по предварительному согласованию с менеджером. Если вы не можете получить заказ самостоятельно, пожалуйста, согласуйте изменения получателя с менеджером. От нового получателя потребуется предоставление паспорта и доверенности на получение ценного груза на имя получателя.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Как я могу узнать о статусе доставки?',
            'CODE'            => 'faq-12',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Как я могу узнать о статусе доставки?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'После оплаты заказа вы получите трек-номер посылки и сможете отслеживать ее передвижение на сайте перевозчика <a href="https://www.cccb.ru">https://www.cccb.ru.</a>',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Могу ли я изменить адрес, дату, время доставки?',
            'CODE'            => 'faq-13',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Могу ли я изменить адрес, дату, время доставки?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Да, по предварительному согласованию с менеджером и до наступления согласованного времени доставки. В случае изменения региона доставки стоимость и сроки могут быть также скорректированы. <br>Пожалуйста, обратите внимание: в случае если доставка товара произведена в согласованные с вами сроки и по указанному адресу, но товар не был передан по вашим обстоятельствам, повторная доставка может быть платной.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Могу ли я отплатить свой заказ курьеру?',
            'CODE'            => 'faq-14',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Могу ли я отплатить свой заказ курьеру?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Курьеры не принимают оплату товара и дополнительных услуг. Все расчеты производятся с продавцом.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Какие документы мне передаст курьер вместе с заказом?',
            'CODE'            => 'faq-15',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Какие документы мне передаст курьер вместе с заказом?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Курьер передаст вам запломбированный пакет с заказом и накладную, подтверждающую доставку товара. <br>Внутри пакета вместе с товаром будут находиться документы продавца: результаты сертификации (при наличии) и товарный чек.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Могу ли я отправить своего курьера, чтобы забрать заказ?',
            'CODE'            => 'faq-16',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Могу ли я отправить своего курьера, чтобы забрать заказ?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Перевозку ценных грузов должна осуществлять организация, отвечающая требованиям ст. 29 Федерального закона от 29.03.1998 г. № 41-ФЗ «О драгоценных металлах и драгоценных камнях». Если вы сотрудничаете с такой компанией, сообщите менеджеру для проведения процедуры аккредитации вашего перевозчика. <br>Если товар будет получать и перевозить частное лицо, потребуется предоставление паспорта и нотариальной доверенности.',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
        FAQ::create([
            'NAME'            => 'Осуществляется ли доставка за пределы Российской Федерации?',
            'CODE'            => 'faq-17',
            'PROPERTY_VALUES' => [
                'QUESTION_RU'   => 'Осуществляется ли доставка за пределы Российской Федерации?',
                'ANSWER_RU'     => [
                    'VALUE' => [
                        'TEXT' => 'Индивидуальные условия и возможности доставки вы можете обсудить с вашим менеджером. <a href="/ru/customer-service/order-and-shipping/">Подробнее</a>',
                        'TYPE' => 'HTML',
                    ]
                ],
            ]
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function down()
    {
        // Для покупателей/Информация/блок упаковка текст
        $this->element = Info::filter([
            'CODE' => 'upakovka_1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'Сертифицированные в российской лаборатории бриллианты помещаются в прозрачную пластиковую упаковку, которая запаивается таким образом, что при вскрытии упаковки она разрушается.'
        ]);

        // Для покупателей/Информация/блок упаковка текст после картинки
        $this->element = Info::filter([
            'CODE' => 'upakovka_2'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_2 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'На оттиске после запайки изображен знак соответствия Системы сертификации ограненных драгоценных камней. Внутри упаковки вставлена этикетка с названием геммологической лаборатории, знаком соответствия Системы, основными характеристиками бриллианта и номером сертификата.'
        ]);

        // Для покупателей/Информация/блок сертификат GIA текст
        $this->element = Info::filter([
            'CODE' => 'upakovka_2'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент upakovka_2 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'По Вашему желанию сертификация также может проводится в международной лаборатории GIA. Сертификаты, выданные на один и тот же бриллиант разными сертификационными центрами мира, могут отличаться. Это связано с особенностью восприятия российской системой западных критериев оценки бриллиантов.'
        ]);



        // Для покупателей/Вопрос-ответ/блок ответы на вопросы/ Кто может приобретать бриллианты?
        $this->element = FAQ::filter([
            'CODE' => 'faq-1'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент faq-1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => 'Покупателем сертифицированных бриллиантов может быть любое физическое лицо, зарегистрированное на территории Российской Федерации или зарубежного государства.'
        ]);

        // Для покупателей/Вопрос-ответ/блок ответы на вопросы/ Как осуществляется оплата приобретаемых бриллиантов?
        $this->element = FAQ::filter([
            'CODE' => 'faq-4'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент faq-1 не найден, обновление контента не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_ANSWER_RU_VALUE' => 'Оплата за приобретаемые бриллианты осуществляется банковским переводом по выставленному счету или банковской картой в офисе компании.'
        ]);




        // Удаление элементов вопрос-ответ
        $this->element = FAQ::filter([
            'CODE' => 'faq-7'
        ])->first();
        if($this->element){
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-8'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-9'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-10'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-11'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-12'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-13'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-14'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-15'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-16'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }

        $this->element = FAQ::filter([
            'CODE' => 'faq-17'
        ])->first();
        if($this->element) {
            $this->element->delete();
        }
    }
}
