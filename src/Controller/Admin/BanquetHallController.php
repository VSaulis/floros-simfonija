<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\BanquetHall;
use App\Entity\BanquetHallTranslation;
use App\Form\Type\BanquetHallPhotoType;
use App\Form\Type\BanquetHallTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BanquetHallController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public static function getEntityFqcn(): string
    {
        return BanquetHall::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.banquet_hall')
            ->setEntityLabelInPlural('titles.banquet_halls')
            ->setSearchFields(['id', 'created', 'updated'])
            ->setFormOptions(
                ['validation_groups' => ['add']],
                ['validation_groups' => []]
            );
    }

    public function createEntity(string $entityFqcn): BanquetHall
    {
        $banquetHall = new BanquetHall();

        foreach (Locales::list() as $locale) {
            $predicate = function (BanquetHallTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($banquetHall->getTranslations()->filter($predicate)->first() == null) {
                $translation = new BanquetHallTranslation();
                $translation->setLocale($locale);
                $banquetHall->addTranslation($translation);
            }
        }

        return $banquetHall;
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

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });

        yield FormField::addPanel('labels.main_details')->setCssClass('inputs-layout');
        yield AssociationField::new('location', 'labels.location');
        yield IntegerField::new('peopleCount', 'labels.people_count');

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(BanquetHallTranslationType::class);

        yield FormField::addPanel('labels.photos')->setCssClass('grid-layout');
        yield CollectionField::new('photos', false)
            ->onlyOnForms()
            ->setEntryType(BanquetHallPhotoType::class);
    }
}