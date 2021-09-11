<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Util\DateUtils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReviewController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Vilnius')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setDateFormat('yyyy-MM-dd')
            ->setEntityLabelInSingular('buttons.review')
            ->setEntityLabelInPlural('titles.reviews')
            ->setSearchFields(['id', 'created', 'updated']);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('admin');
    }

    public function configureFields(string $pageName): Iterable
    {
        yield IdField::new('id', 'labels.id')->hideOnForm();
        yield BooleanField::new('visible', 'labels.visible')->hideOnForm();

        yield FormField::addPanel('labels.main_details')->setCssClass('inputs-layout');
        yield AssociationField::new('location', 'labels.location');
        yield DateField::new('date', 'labels.date');
        yield TextField::new('fullName', 'labels.fullName');
        yield ChoiceField::new('rating', 'labels.rating')->setChoices(['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5]);
        yield TextareaField::new('description', 'labels.description')->onlyOnForms();

        yield DateTimeField::new('created', 'labels.created')
            ->hideOnForm()
            ->formatValue(function ($value) {
                return DateUtils::formatDateTime($value);
            });
    }
}