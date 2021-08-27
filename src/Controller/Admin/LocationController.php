<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Location;
use App\Entity\LocationTranslation;
use App\Form\Type\LocationPhotoType;
use App\Form\Type\LocationTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            ->setSearchFields(['id', 'created', 'updated']);
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

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield ImageField::new('featuredPhoto.fileName', 'labels.photo')
            ->setBasePath($this->photoPath)
            ->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield TextField::new('address', 'labels.address')->onlyOnForms();
        yield TextField::new('email', 'labels.email')->onlyOnForms();
        yield TextField::new('phone', 'labels.phone')->onlyOnForms();
        yield UrlField::new('facebook', 'labels.facebook')->onlyOnForms();
        yield UrlField::new('instagram', 'labels.instagram')->onlyOnForms();

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

        yield CollectionField::new('translations', 'labels.translations')
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(LocationTranslationType::class);

        yield CollectionField::new('photos', 'labels.photos')
            ->onlyOnForms()
            ->setEntryType(LocationPhotoType::class);
    }
}