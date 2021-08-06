<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Product;
use App\Entity\ProductTranslation;
use App\Form\Type\LocationPhotoType;
use App\Form\Type\ProductPhotoType;
use App\Form\Type\ProductTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
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

    public function createEntity(string $entityFqcn): Product
    {
        $product = new Product();

        foreach (Locales::list() as $locale) {
            $predicate = function (ProductTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($product->getTranslations()->filter($predicate)->first() == null) {
                $translation = new ProductTranslation();
                $translation->setLocale($locale);
                $product->addTranslation($translation);
            }
        }

        return $product;
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield ImageField::new('photos[0].fileName', 'labels.photo')
            ->setBasePath($this->photoPath)
            ->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield AssociationField::new('category', 'labels.category');
        yield AssociationField::new('location', 'labels.location');

        yield MoneyField::new('price', 'labels.price')
            ->setCurrency('EUR')
            ->setStoredAsCents(false);

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
            ->setEntryType(ProductTranslationType::class);

        yield CollectionField::new('photos', 'labels.photos')
            ->onlyOnForms()
            ->setEntryType(ProductPhotoType::class);
    }
}