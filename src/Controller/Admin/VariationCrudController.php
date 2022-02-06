<?php

namespace App\Controller\Admin;

use App\Entity\Variation;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class VariationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Variation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','ID')->onlyOnIndex(),
            TextField::new('name','Nom de la variation'),
            ImageField::new('illustration','Illustration')
            ->setBasePath('uploads/')
            ->setUploadDir(self::PUBLIC_DIR.'/uploads')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
    
}
