<?php

namespace App\Controller\Admin;

use App\Entity\Subscriber;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class SubscriberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subscriber::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email',"Email")->hideWhenUpdating(),
            AssociationField::new('user',"Utilisateur")->hideWhenUpdating()
        ];
    }
    
}
