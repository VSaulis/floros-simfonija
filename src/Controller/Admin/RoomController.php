<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Room;
use App\Entity\RoomTranslation;
use App\Form\Type\RoomPhotoType;
use App\Form\Type\RoomTranslationType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class RoomController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.room')
            ->setEntityLabelInPlural('titles.rooms')
            ->setSearchFields(['id', 'created', 'updated'])
            ->setFormOptions(
                ['validation_groups' => []],
                ['validation_groups' => ['edit']]
            );
    }

    public function createEntity(string $entityFqcn): Room
    {
        $room = new Room();

        foreach (Locales::list() as $locale) {
            $predicate = function (RoomTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($room->getTranslations()->filter($predicate)->first() == null) {
                $translation = new RoomTranslation();
                $translation->setLocale($locale);
                $room->addTranslation($translation);
            }
        }

        return $room;
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
        yield MoneyField::new('price', 'labels.price')
            ->setStoredAsCents(false)
            ->setCurrency('EUR');
        yield AssociationField::new('hotel', 'labels.hotel');
        yield UrlField::new('orderUrl', 'labels.order_url')->onlyOnForms();
        yield IntegerField::new('peopleCount', 'labels.people_count');
        yield AssociationField::new('perks', 'labels.perks')->onlyOnForms();

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(RoomTranslationType::class);

        yield FormField::addPanel('labels.photos')->setCssClass('grid-layout');
        yield CollectionField::new('photos', false)
            ->onlyOnForms()
            ->setEntryType(RoomPhotoType::class);

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });
    }
}