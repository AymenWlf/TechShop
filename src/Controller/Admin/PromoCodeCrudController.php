<?php

namespace App\Controller\Admin;

use App\Entity\PromoCode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PromoCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PromoCode::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user',"Utilisateur"),
            AssociationField::new('subscriber',"Abonné"),
            TextField::new('code',"CodePromo"),
            BooleanField::new('used',"Utlisée?"),
            IntegerField::new('discount',"Promotion de (%)")
        ];
    }    
}
