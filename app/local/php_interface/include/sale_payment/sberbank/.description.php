<?php

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Sale\PaySystem;

Loc::loadMessages(__FILE__);

$isAvailable = PaySystem\Manager::HANDLER_AVAILABLE_TRUE;

$licensePrefix = Loader::includeModule('bitrix24') ? \CBitrix24::getLicensePrefix() : "";
$portalZone = Loader::includeModule('intranet') ? CIntranetUtils::getPortalZone() : "";

if (Loader::includeModule("bitrix24")) {
    if ($licensePrefix !== 'ru') {
        $isAvailable = PaySystem\Manager::HANDLER_AVAILABLE_FALSE;
    }
} elseif (Loader::includeModule('intranet') && $portalZone !== 'ru') {
    $isAvailable = PaySystem\Manager::HANDLER_AVAILABLE_FALSE;
}

$data = [
    'NAME'         => Loc::getMessage('SALE_HPS_SBERBANK_TITLE'),
    'IS_AVAILABLE' => $isAvailable,
    'CODES'        => [
        "SELLER_COMPANY_NAME"              => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_COMPANY_NAME_DESC'),
            "SORT"  => 100,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "SELLER_COMPANY_INN"               => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_INN_DESC'),
            "SORT"  => 200,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "SELLER_COMPANY_KPP"               => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_KPP_DESC'),
            "SORT"  => 300,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "SELLER_COMPANY_BANK_ACCOUNT"      => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_SETTLEMENT_ACC_DESC'),
            "SORT"  => 400,
            'GROUP' => 'SELLER_COMPANY'
        ],
        "SELLER_COMPANY_BANK_NAME"         => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_BANK_NAME_DESC'),
            "SORT"  => 500,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "SELLER_COMPANY_BANK_BIC"          => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_BANK_BIC_DESC'),
            "SORT"  => 600,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "SELLER_COMPANY_BANK_ACCOUNT_CORR" => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_BANK_COR_ACC_DESC'),
            "SORT"  => 700,
            'GROUP' => 'SELLER_COMPANY',
        ],
        "PAYMENT_ID"                       => [
            "NAME"    => Loc::getMessage('SALE_HPS_SBERBANK_PAYMENT_ID_DESC'),
            "SORT"    => 800,
            'GROUP'   => 'PAYMENT',
            'DEFAULT' => [
                'PROVIDER_KEY'   => 'PAYMENT',
                'PROVIDER_VALUE' => 'ACCOUNT_NUMBER'
            ]
        ],
        "PAYMENT_ORDER_ID"                 => [
            "NAME"    => Loc::getMessage('SALE_HPS_SBERBANK_ORDER_ID_DESC'),
            "SORT"    => 800,
            'GROUP'   => 'PAYMENT',
            'DEFAULT' => [
                'PROVIDER_KEY'   => 'ORDER',
                'PROVIDER_VALUE' => 'ACCOUNT_NUMBER'
            ]
        ],
        "PAYMENT_DATE_INSERT"              => [
            "NAME"    => Loc::getMessage('SALE_HPS_SBERBANK_DATA_INSERT_DESC'),
            "SORT"    => 900,
            'GROUP'   => 'PAYMENT',
            'DEFAULT' => [
                'PROVIDER_KEY'   => 'PAYMENT',
                'PROVIDER_VALUE' => 'DATE_BILL'
            ]
        ],
        "BUYER_PERSON_NAME"                => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_NAME_DESC'),
            "SORT"  => 1000,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_SURNAME"             => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_SURNAME_DESC'),
            "SORT"  => 1010,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_PATRONIMIC"          => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PATRONIMIC_DESC'),
            "SORT"  => 1010,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_ZIP"                 => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_ZIP_CODE_DESC'),
            "SORT"  => 1100,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_COUNTRY"             => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_COUNTRY_DESC'),
            "SORT"  => 1200,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_REGION"              => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_REGION_DESC'),
            "SORT"  => 1300,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_CITY"                => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_CITY_DESC'),
            "SORT"  => 1400,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_VILLAGE"             => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_VILLAGE_DESC'),
            "SORT"  => 1400,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_STREET"              => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_STREET_DESC'),
            "SORT"  => 1400,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_ADDRESS_FACT"        => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_ADDRESS_FACT_DESC'),
            "SORT"  => 1500,
            'GROUP' => 'BUYER_PERSON'
        ],
        "BUYER_PERSON_DEP"        => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_DEP_DESC'),
            "SORT"  => 1501,
            'GROUP' => 'BUYER_PERSON'
        ],


        "BUYER_PERSON_BANK_ACCOUNT" => [
            "NAME"  => Loc::getMessage('SALE_HPS_SBERBANK_PAYER_ACCOUNT_DESC'),
            "SORT"  => 1550,
            'GROUP' => 'BUYER_PERSON'
        ],
        "PAYMENT_SHOULD_PAY"        => [
            "NAME"    => Loc::getMessage('SALE_HPS_SBERBANK_SUM_DESC'),
            "SORT"    => 1600,
            'DEFAULT' => [
                'PROVIDER_KEY'   => 'PAYMENT',
                'PROVIDER_VALUE' => 'SUM'
            ],
            'GROUP'   => 'PAYMENT'
        ],
        "PAYMENT_CURRENCY"          => [
            "NAME"    => Loc::getMessage('SALE_HPS_SBERBANK_CURRENCY_DESC'),
            "SORT"    => 1700,
            'DEFAULT' => [
                'PROVIDER_KEY'   => 'PAYMENT',
                'PROVIDER_VALUE' => 'CURRENCY'
            ],
            'GROUP'   => 'PAYMENT'
        ]
    ]
];
