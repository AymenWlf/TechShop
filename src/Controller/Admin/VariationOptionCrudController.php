<?php

namespace App\Controller\Admin;

use App\Entity\VariationOption;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VariationOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VariationOption::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id','ID')->onlyOnIndex(),
            AssociationField::new('product','Produit'),
            AssociationField::new('variation','Variation'),
            TextField::new('name','Variable'),
            TextField::new('var_code','code'),
            IntegerField::new('stock','Stock'),
        ];
    }
    
}
