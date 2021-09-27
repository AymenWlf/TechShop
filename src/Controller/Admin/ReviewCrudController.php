<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','ID'),
            AssociationField::new('user','Utilisateur'),
            AssociationField::new('product','Produit'),
            TextField::new('title','Titre'),
            TextEditorField::new('description','Description'),
        ];
    }
}
