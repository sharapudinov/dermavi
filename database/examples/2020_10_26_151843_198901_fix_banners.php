<?php

use App\Models\Banners\PhysicBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Banners\LegalBanner;
use App\Core\BitrixProperty\Property;

class FixBanners20201026151843198901 extends BitrixMigration
{

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $propertyLegal = new Property(LegalBanner::iblockID());
        $propertyLegal->addPropertyToQuery('IMAGE_768');
        $propertyInfoLegal = $propertyLegal->getPropertiesInfo()['IMAGE_768'];
        CIBlockProperty::Delete($propertyInfoLegal['PROPERTY_ID']);

        $propertyLegalTwo = new Property(LegalBanner::iblockID());
        $propertyLegalTwo->addPropertyToQuery('IMAGE_1024');
        $propertyInfoLegalTwo = $propertyLegalTwo->getPropertiesInfo()['IMAGE_1024'];
        CIBlockProperty::Delete($propertyInfoLegalTwo['PROPERTY_ID']);

        $propertyNatural = new Property(PhysicBanner::iblockID());
        $propertyNatural->addPropertyToQuery('IMAGE_768');
        $propertyInfoNatural = $propertyNatural->getPropertiesInfo()['IMAGE_768'];
        CIBlockProperty::Delete($propertyInfoNatural['PROPERTY_ID']);

        $propertyNaturalTwo = new Property(PhysicBanner::iblockID());
        $propertyNaturalTwo->addPropertyToQuery('IMAGE_1024');
        $propertyInfoNaturalTwo = $propertyNaturalTwo->getPropertiesInfo()['IMAGE_1024'];
        CIBlockProperty::Delete($propertyInfoNaturalTwo['PROPERTY_ID']);
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
