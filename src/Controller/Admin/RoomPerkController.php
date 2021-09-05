<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\RoomPerk;
use App\Entity\RoomPerkTranslation;
use App\Form\Type\RoomPerkTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomPerkController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomPerk::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.perk')
            ->setEntityLabelInPlural('titles.perks')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function createEntity(string $entityFqcn): RoomPerk
    {
        $roomPerk = new RoomPerk();

        foreach (Locales::list() as $locale) {
            $predicate = function (RoomPerkTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($roomPerk->getTranslations()->filter($predicate)->first() == null) {
                $translation = new RoomPerkTranslation();
                $translation->setLocale($locale);
                $roomPerk->addTranslation($translation);
            }
        }

        return $roomPerk;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(RoomPerkTranslationType::class);

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