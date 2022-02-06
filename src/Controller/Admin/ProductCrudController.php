<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            ->setUploadDir(self::PUBLIC_DIR.'/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('name','Nom du Produit'),
            SlugField::new('slug','Slug')->setTargetFieldName('name'),
            AssociationField::new('category','Categorie'),
            TextAreaField::new('description','Description')->hideOnIndex(),
            BooleanField::new('isBest'),
            TextField::new('subtitle','Sous-Titre'),
            MoneyField::new('price')->setCurrency('MAD'),

        ];
    }

}


