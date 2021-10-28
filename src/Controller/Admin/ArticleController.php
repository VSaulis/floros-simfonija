<?php

namespace App\Controller\Admin;

use App\Constant\Locales;
use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Form\Type\ArticlePhotoType;
use App\Form\Type\ArticleTranslationType;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->setSearchFields(['id', 'created', 'updated'])
            ->setFormOptions(
                ['validation_groups' => ['add']],
                ['validation_groups' => []]
            );
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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();
        yield BooleanField::new('visible', 'labels.visible')->hideOnForm();

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
        yield DateField::new('dateFrom', 'labels.date_from');
        yield DateField::new('dateTo', 'labels.date_to');
        yield AssociationField::new('photos', 'labels.photos')->onlyOnIndex();

        yield FormField::addPanel('labels.translations')->setCssClass('grid-layout');
        yield CollectionField::new('translations', false)
            ->onlyOnForms()
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(ArticleTranslationType::class);

        yield FormField::addPanel('labels.photos')->setCssClass('grid-layout');
        yield CollectionField::new('photos', false)
            ->onlyOnForms()
            ->setEntryType(ArticlePhotoType::class);
    }
}