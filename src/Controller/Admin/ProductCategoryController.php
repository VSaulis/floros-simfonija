<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\ProductCategory;
use App\Entity\ProductCategoryTranslation;
use App\Form\Type\ProductCategoryTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCategoryController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.product_category')
            ->setEntityLabelInPlural('titles.products_categories')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function createEntity(string $entityFqcn): ProductCategory
    {
        $productCategory = new ProductCategory();

        foreach (Locales::list() as $locale) {
            $predicate = function (ProductCategoryTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($productCategory->getTranslations()->filter($predicate)->first() == null) {
                $translation = new ProductCategoryTranslation();
                $translation->setLocale($locale);
                $productCategory->addTranslation($translation);
            }
        }

        return $productCategory;
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield AssociationField::new('parent', 'labels.parent_category');
        yield AssociationField::new('children', 'labels.children')->hideOnForm();

        yield CollectionField::new('translations', 'labels.translations')
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(ProductCategoryTranslationType::class);

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