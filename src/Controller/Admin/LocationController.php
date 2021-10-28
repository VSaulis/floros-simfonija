<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Location;
use App\Entity\LocationTranslation;
use App\Form\Type\LocationPhotoType;
use App\Form\Type\LocationTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class LocationController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public static function getEntityFqcn(): string
    {
        return Location::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.location')
            ->setEntityLabelInPlural('titles.locations')
            ->setSearchFields(['id', 'created', 'updated'])
            ->setFormOptions(
                ['validation_groups' => ['add']],
                ['validation_groups' => []]
            );
    }

    public function createEntity(string $entityFqcn): Location
    {
        $location = new Location();

        foreach (Locales::list() as $locale) {
            $predicate = function (LocationTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($location->getTranslations()->filter($predicate)->first() == null) {
                $translation = new LocationTranslation();
                $translation->setLocale($locale);
                $location->addTranslation($translation);
            }
        }

        return $location;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield ImageField::new('featuredPhoto.fileName', 'labels.photo')
            ->setBasePath($this->photoPath)
            ->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield FormField::addPanel('labels.main_details')->setCssClass('inputs-layout');
        yield TextField::new('address', 'labels.address')->onlyOnForms();
        yield TextField::new('email', 'labels.email')->onlyOnForms();
        yield TextField::new('phone', 'labels.phone')->onlyOnForms();
        yield TextField::new('businessHours', 'labels.business_hours')->onlyOnForms();
        yield IntegerField::new('position', 'labels.position')->onlyOnForms();

        yield FormField::addPanel('labels.social_media')->setCssClass('inputs-layout');
        yield UrlField::new('facebook', 'labels.facebook')->onlyOnForms();
        yield UrlField::new('instagram', 'labels.instagram')->onlyOnForms();

        yield FormField::addPanel('labels.company_details')->setCssClass('inputs-layout');
        yield TextField::new('companyName', 'labels.company_name')->onlyOnForms();
        yield TextField::new('companyCode', 'labels.company_code')->onlyOnForms();
        yield TextField::new('companyVAT', 'labels.company_vat')->onlyOnForms();
        yield TextField::new('companyIban', 'labels.company_iban')->onlyOnForms();
        yield TextField::new('companyBank', 'labels.company_bank')->onlyOnForms();

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(LocationTranslationType::class);

        yield FormField::addPanel('labels.photos')->setCssClass('grid-layout');
        yield CollectionField::new('photos', false)
            ->onlyOnForms()
            ->setEntryType(LocationPhotoType::class);

        yield DateTimeField::new('updated', 'labels.updated')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });
    }
}