<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Hotel;
use App\Entity\HotelTranslation;
use App\Form\Type\HotelTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HotelController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hotel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.hotel')
            ->setEntityLabelInPlural('titles.hotels')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function createEntity(string $entityFqcn): Hotel
    {
        $hotel = new Hotel();

        foreach (Locales::list() as $locale) {
            $predicate = function (HotelTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($hotel->getTranslations()->filter($predicate)->first() == null) {
                $translation = new HotelTranslation();
                $translation->setLocale($locale);
                $hotel->addTranslation($translation);
            }
        }

        return $hotel;
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield NumberField::new('longitude', 'labels.longitude')->onlyOnForms();
        yield NumberField::new('latitude', 'labels.latitude')->onlyOnForms();
        yield AssociationField::new('location', 'labels.location');
        yield TextField::new('address', 'labels.address');

        yield AssociationField::new('rooms', 'labels.rooms')->onlyOnIndex();

        yield CollectionField::new('translations', 'labels.translations')
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(HotelTranslationType::class);

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