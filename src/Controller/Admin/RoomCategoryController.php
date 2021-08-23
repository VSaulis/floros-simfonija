<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\RoomCategory;
use App\Entity\RoomCategoryTranslation;
use App\Form\Type\RoomCategoryTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class RoomCategoryController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('pages.products.labels.product')
            ->setEntityLabelInPlural('pages.products.titles.products')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function createEntity(string $entityFqcn): RoomCategory
    {
        $roomCategory = new RoomCategory();

        foreach (Locales::list() as $locale) {
            $predicate = function (RoomCategoryTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($roomCategory->getTranslations()->filter($predicate)->first() == null) {
                $translation = new RoomCategoryTranslation();
                $translation->setLocale($locale);
                $roomCategory->addTranslation($translation);
            }
        }

        return $roomCategory;
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield CollectionField::new('translations', 'labels.translations')
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(RoomCategoryTranslationType::class);

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