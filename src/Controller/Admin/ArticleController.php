<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Form\Type\ArticlePhotoType;
use App\Form\Type\ArticleTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleController extends AbstractCrudController
{
    private $photoPath;

    public function __construct(string $photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setEntityLabelInSingular('buttons.article')
            ->setEntityLabelInPlural('titles.news')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function createEntity(string $entityFqcn): Article
    {
        $article = new Article();

        foreach (Locales::list() as $locale) {
            $predicate = function (ArticleTranslation $translation) use ($locale) {
                return $translation->getLocale() == $locale;
            };

            if ($article->getTranslations()->filter($predicate)->first() == null) {
                $translation = new ArticleTranslation();
                $translation->setLocale($locale);
                $article->addTranslation($translation);
            }
        }

        return $article;
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();
        yield BooleanField::new('visible', 'labels.visible');

        yield ImageField::new('featuredPhoto.fileName', 'labels.photo')
            ->setBasePath($this->photoPath)
            ->hideOnForm();

        yield TextField::new('title', 'labels.title')->hideOnForm();

        yield AssociationField::new('location', 'labels.location');

        yield DateField::new('dateFrom', 'labels.date_from');
        yield DateField::new('dateTo', 'labels.date_to');

        yield AssociationField::new('photos', 'labels.photos')->onlyOnIndex();

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
            ->setEntryType(ArticleTranslationType::class);

        yield CollectionField::new('photos', 'labels.photos')
            ->onlyOnForms()
            ->setEntryType(ArticlePhotoType::class);
    }
}