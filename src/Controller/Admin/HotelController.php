<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Hotel;
use App\Entity\HotelTranslation;
use App\Form\Type\HotelLogoType;
use App\Form\Type\HotelPhotoType;
use App\Form\Type\HotelTranslationType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class HotelController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

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
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setSearchFields(['id', 'created', 'updated'])
            ->setFormOptions(
                ['validation_groups' => ['add']],
                ['validation_groups' => []]
            );
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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();
        yield ImageField::new('logo.fileName', 'labels.logo')
            ->setBasePath($this->photoPath)
            ->hideOnForm();
        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield FormField::addPanel('labels.main_details')->setCssClass('inputs-layout');
        yield AssociationField::new('location', 'labels.location');
        yield TextField::new('address', 'labels.address');
        yield TextField::new('email', 'labels.email')->onlyOnForms();
        yield TextField::new('phone', 'labels.phone')->onlyOnForms();
        yield TextField::new('logo', 'labels.logo')->setFormType(HotelLogoType::class)->onlyOnForms();
        yield TextField::new('businessHours', 'labels.business_hours')->onlyOnForms();
        yield IntegerField::new('position', 'labels.position')->onlyOnForms();

        yield FormField::addPanel('labels.prices_table')->setCssClass('editor-layout');
        yield TextareaField::new('pricesTable', false)
            ->onlyOnForms()
            ->setFormType(CKEditorType::class);

        yield FormField::addPanel('labels.location')->setCssClass('inputs-layout');
        yield NumberField::new('longitude', 'labels.longitude')->onlyOnForms();
        yield NumberField::new('latitude', 'labels.latitude')->onlyOnForms();

        yield FormField::addPanel('labels.social_media')->setCssClass('inputs-layout');
        yield UrlField::new('facebook', 'labels.facebook')->onlyOnForms();
        yield UrlField::new('instagram', 'labels.instagram')->onlyOnForms();

        yield AssociationField::new('rooms', 'labels.rooms')->onlyOnIndex();

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(HotelTranslationType::class);

        yield FormField::addPanel('labels.terms_and_conditions')->setCssClass('editor-layout');
        yield TextareaField::new('termsAndConditions', false)
            ->onlyOnForms()
            ->setFormType(CKEditorType::class);

        yield FormField::addPanel('labels.photos')->setCssClass('grid-layout');
        yield CollectionField::new('photos', false)
            ->onlyOnForms()
            ->setEntryType(HotelPhotoType::class);

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });
    }
}