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
            ->setUploadDir('public/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('name','Nom du Produit'),
            SlugField::new('slug','Slug')->setTargetFieldName('name')->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            AssociationField::new('category','Categorie'),
            TextareaField::new('description','Description')->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            BooleanField::new('isBest'),
            TextField::new('subtitle','Sous-Titre')->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            MoneyField::new('price')->setCurrency('MAD'),
            IntegerField::new('qte_stock','Quantité en stock'),
            TextField::new('marque','Marque')->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            ImageField::new('illustration2')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            ImageField::new('illustration3')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            ImageField::new('illustration4')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->onlyOnDetail()->onlyWhenCreating()->onlyWhenUpdating(),
            
        ];
    }
    
}

