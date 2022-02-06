<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name','Nom du Header'),
            TextField::new('topCmnt','Commentaire Haut'),
            TextField::new('middleCmnt','Commentaire Gras'),
            TextField::new('lastCmnt','Commentaire Bas'),
            TextField::new('btnTitle','Titre du bonton'),
            ImageField::new('illustration')
                ->setBasePath('uploads/')
                ->setUploadDir('public_html/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
    
}
