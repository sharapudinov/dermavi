<?php

use App\Core\BitrixProperty\Property;
use App\Models\Client\PersonalSectionDocumentKind;
use App\Models\HL\UserPersonType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления ИБ "Документы для пользователей в личном кабинете"
 * и добавления элементов в этот ИБ
 * Class UpdateDocumentsIblockAndAddElements20200207190000632890
 */
class UpdateDocumentsIblockAndAddElements20200207190000632890 extends BitrixMigration
{
    /** @var array|array[] $elements Массив, описывающий элементы */
    private $elements = [
        'LEGAL_ENTITY' => [
            'Электронная копия' => [
                [
                    'NAME' => 'Документ (сертификат, лицензия), дающий право на ведение алмазного бизнеса в стране регистрации юридического лица или индивидуального предпринимателя — нерезидента',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Документ (сертификат, лицензия), дающий право на ведение алмазного бизнеса в стране регистрации юридического лица или индивидуального предпринимателя — нерезидента',
                        'NAME_EN' => 'Document (certificate, license) granting the right to run the diamond business in the country of registration of a non-resident legal entity or individual entrepreneur',
                        'NAME_CN' => 'Document (certificate, license) granting the right to run the diamond business in the country of registration of a non-resident legal entity or individual entrepreneur',
                        'DESCRIPTION_RU' => 'В случае, если законодательство страны регистрации предусматривает необходимость наличия подобного документа',
                        'DESCRIPTION_EN' => 'If the legislation of the country of registration provides for the presence of such a document',
                        'DESCRIPTION_CN' => 'If the legislation of the country of registration provides for the presence of such a document'
                    ]
                ],
                [
                    'NAME' => 'Выписка из торгового реестра страны регистрации или иного эквивалентного доказательства правоспособности в соответствии с законодательством страны регистрации',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Выписка из торгового реестра страны регистрации или иного эквивалентного доказательства правоспособности в соответствии с законодательством страны регистрации',
                        'NAME_EN' => 'Extract from the commercial register of the country of registration or any other equivalent proof of legal capacity in compliance with the laws of the country of registration',
                        'NAME_CN' => 'Extract from the commercial register of the country of registration or any other equivalent proof of legal capacity in compliance with the laws of the country of registration'
                    ]
                ],
                [
                    'NAME' => 'Свидетельство о постановке на налоговый учет по месту нахождения или эквивалентный документ в соответствии с законодательством страны регистрации',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Свидетельство о постановке на налоговый учет по месту нахождения или эквивалентный документ в соответствии с законодательством страны регистрации',
                        'NAME_EN' => 'Certificate of registration of a legal entity with a tax authority at its location  or equivalent document in accordance with the laws of the country of registration',
                        'NAME_CN' => 'Certificate of registration of a legal entity with a tax authority at its location  or equivalent document in accordance with the laws of the country of registration'
                    ]
                ],
                [
                    'NAME' => 'Уведомление о постановке на специальный учет юридического лица или индивидуального предпринимателя, осуществляющего операции с драгоценными металлами и драгоценными камнями, и присвоении ему учетного номера и Карта специального учета юридических лиц и инд',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Уведомление о постановке на специальный учет юридического лица или индивидуального предпринимателя, осуществляющего операции с драгоценными металлами и драгоценными камнями, и присвоении ему учетного номера и Карта специального учета юридических лиц и индивидуальных предпринимателей, осуществляющих операции с драгоценными металлами и драгоценными камнями в государственной инспекции пробирного надзора Российской государственной пробирной палаты',
                        'NAME_EN' => 'Notification of registration of a legal entity or an individual entrepreneur carrying out transactions with precious metals and gem stones, and assignment of a number to it, as well as  Special Card of Registration of legal entities and individual entrepreneurs carrying out transactions with precious metals and gem stones, issued by the State Inspectorate of Assay Supervision of the Russian State Assay Chamber',
                        'NAME_CN' => 'Notification of registration of a legal entity or an individual entrepreneur carrying out transactions with precious metals and gem stones, and assignment of a number to it, as well as  Special Card of Registration of legal entities and individual entrepreneurs carrying out transactions with precious metals and gem stones, issued by the State Inspectorate of Assay Supervision of the Russian State Assay Chamber'
                    ]
                ],
                [
                    'NAME' => 'Протокол или иной документ, подтверждающий избрание (назначение) органов управления юридического лица (единоличный (директор, генеральный директор и т.п.) и/или коллегиальный исполнительный (правление и т.п.) орган, совет директоров/наблюдательный совет и',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Протокол или иной документ, подтверждающий избрание (назначение) органов управления юридического лица (единоличный (директор, генеральный директор и т.п.) и/или коллегиальный исполнительный (правление и т.п.) орган, совет директоров/наблюдательный совет и т.п.)',
                        'NAME_EN' => 'Minutes or another document confirming the election (appointment) of a legal entity’s management bodies (sole (director, general manager, etc.) and/or collective executive body (executive committee, etc.), board of directors/supervisory board, etc.)',
                        'NAME_CN' => 'Minutes or another document confirming the election (appointment) of a legal entity’s management bodies (sole (director, general manager, etc.) and/or collective executive body (executive committee, etc.), board of directors/supervisory board, etc.)'
                    ]
                ],
                [
                    'NAME' => 'Свидетельство о государственной регистрации юридического лица или Свидетельство о внесении записи в Единый государственный реестр юридических лиц о юридическом лице, зарегистрированном до 1 июля 2002 года',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Свидетельство о государственной регистрации юридического лица или Свидетельство о внесении записи в Единый государственный реестр юридических лиц о юридическом лице, зарегистрированном до 1 июля 2002 года',
                        'NAME_EN' => 'Certificate of state registration of a legal entity or Certificate of Entry in the Unified State Register of Legal Entities for a legal entity registered before July 1, 2002',
                        'NAME_CN' => 'Certificate of state registration of a legal entity or Certificate of Entry in the Unified State Register of Legal Entities for a legal entity registered before July 1, 2002'
                    ]
                ],
                [
                    'NAME' => 'Учредительные документы',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Учредительные документы',
                        'NAME_EN' => 'Constitutional documents',
                        'NAME_CN' => 'Constitutional documents'
                    ]
                ]
            ],
            'Оригинал' => [
                [
                    'NAME' => 'Выписка из Единого государственного реестра юридических лиц  (для юридического лица — резидента Российской Федерации).',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Выписка из Единого государственного реестра юридических лиц  (для юридического лица — резидента Российской Федерации).',
                        'NAME_EN' => 'Extract from the Unified State Register of Legal Entities (USRLE) (for the Russian Federation resident legal entity)',
                        'NAME_CN' => 'Extract from the Unified State Register of Legal Entities (USRLE) (for the Russian Federation resident legal entity)',
                        'DESCRIPTION_RU' => 'C момента получения указанной выписки должно пройти не более 1 (одного) месяца',
                        'DESCRIPTION_EN' => 'The extract is valid for 1 (one) month from the date of issue',
                        'DESCRIPTION_CN' => 'The extract is valid for 1 (one) month from the date of issue'
                    ]
                ],
                [
                    'NAME' => 'Анкета клиента',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Анкета клиента',
                        'NAME_EN' => 'Client questionnaire',
                        'NAME_CN' => 'Client questionnaire'
                    ]
                ],
                [
                    'NAME' => 'Надлежащим образом оформленная доверенность',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Надлежащим образом оформленная доверенность',
                        'NAME_EN' => 'Properly executed power of attorney',
                        'NAME_CN' => 'Properly executed power of attorney',
                        'DESCRIPTION_RU' => 'В случае, если документы подписаны представителем хозяйствующего субъекта',
                        'DESCRIPTION_EN' => 'In case documents are signed by a representative of an economic entity',
                        'DESCRIPTION_CN' => 'В случае, если документы подписаны представителем хозяйствующего субъекта'
                    ]
                ]
            ],
        ],
        'PHYSICAL_ENTITY' => [
            'Электронная копия' => [
                [
                    'NAME' => 'Копия паспорта: первой страницы и страницы регистрации',
                    'PROPERTY_VALUES' => [
                        'NAME_RU' => 'Копия паспорта: первые страницы и страницы регистрации',
                        'NAME_EN' => 'Passport copy: first pages and registration pages',
                        'NAME_CN' => 'Passport copy: first pages and registration pages'
                    ]
                ]
            ]
        ]
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var \App\Core\BitrixProperty\Entity\Property|null $personTypeProperty Объект, описывающий свойство */
        $personTypeProperty = Property::getListPropertyValues(
            PersonalSectionDocumentKind::iblockId(),
            'PERSON_TYPE'
        )->first();

        if ($personTypeProperty) {
            CIBlockProperty::Delete($personTypeProperty->getPropertyId());
        }

        (new CIBlockProperty)->Add([
            'NAME' => 'Тип лица',
            'CODE' => 'PERSON_TYPE',
            'SORT' => '498',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'directory',
            'USER_TYPE_SETTINGS' => [
                'size' => 1,
                'width' => 0,
                'group' => 'N',
                'multiple' => 'N',
                'TABLE_NAME' => UserPersonType::TABLE_CODE
            ],
            'IBLOCK_ID' => PersonalSectionDocumentKind::iblockId()
        ]);

        $typesQuery = CIBlockProperty::GetPropertyEnum(
            'TYPE',
            [],
            ['IBLOCK_ID' => PersonalSectionDocumentKind::iblockId(), 'CODE' => 'TYPE']
        );

        /** @var array|array[] $typesArray Массив, описывающий типы документов (оригинал, электронная копия) */
        $typesArray = [];
        while ($type = $typesQuery->GetNext()) {
            $typesArray[] = $type;
        }

        foreach ($this->elements as $entity => $types) {
            foreach ($types as $type => $elements) {
                foreach ($elements as $element) {
                    $element['PROPERTY_VALUES']['PERSON_TYPE'] = $entity;
                    $element['PROPERTY_VALUES']['TYPE'] = array_values(
                        array_filter($typesArray, function (array $typeArray) use ($type) {
                            return $typeArray['VALUE'] == $type;
                        })
                    )[0]['ID'];

                    PersonalSectionDocumentKind::create($element);
                }
            }
        }
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
