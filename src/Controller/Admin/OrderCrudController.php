<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference','Référence')->hideOnDetail(),
            TextField::new('createdAt','Passée le'),
            AssociationField::new('user','Utilisateur'),
            TextEditorField::new('delivery','Adresse de livraison')->onlyOnDetail(),
            TextField::new('carrierName','Transporteur'),
            MoneyField::new('carrierPrice','Frais de port')->setCurrency('MAD'),
            ChoiceField::new('state')->setChoices([
                'Non payée' => 0,
                'En attente' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Livrée' => 4,
                'Annulée' => 5,
                'Annulée par User' => 6
            ]),
            BooleanField::new('isPaid','Payée ?'),
            MoneyField::new('total','Totals')->setCurrency('MAD'),
            ArrayField::new('orderDetails','Produit Achetée')->onlyOnDetail()
        ];
    }
    
}
