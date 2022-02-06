<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            ->setUploadDir('public_html/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('name','Nom de la categorie'),

        ];
    }
    
}
