<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Product;
use App\Entity\ProductTranslation;
use App\Form\Type\ProductTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.product')
            ->setEntityLabelInPlural('titles.products')
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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();
        yield BooleanField::new('visible', 'labels.visible')->hideOnForm();
        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield FormField::addPanel('labels.main_details')->setCssClass('inputs-layout');
        yield AssociationField::new('category', 'labels.category');
        yield AssociationField::new('location', 'labels.location');
        yield MoneyField::new('price', 'labels.price')
            ->setCurrency('EUR')
            ->setCssClass('money-input')
            ->setStoredAsCents(false);

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(ProductTranslationType::class);

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });
    }
}