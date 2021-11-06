<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use function PHPUnit\Framework\returnSelf;

class OrderCrudController extends AbstractCrudController
{
    private $em;
    private $aug;
    public function __construct(EntityManagerInterface $em,AdminUrlGenerator $aug)
    {
        $this->em = $em;
        $this->aug = $aug;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $delete = Action::new('Delete',"Supprimer","fas fa-trash")->linkToCrudAction('Delete');
        $cancel = Action::new('Cancel',"Annulée","fas fa-minus-circle")->linkToCrudAction('Cancel');
        $updatePreparation = Action::new('updatePreparation',"Préparation en cours" , 'fas fa-box')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery',"Livraison en cours", 'fas fa-truck')->linkToCrudAction('updateDelivery');
        $Delivried = Action::new('Delivried',"Livrée" , 'fas fa-box-open')->linkToCrudAction('Delivried');
        $Paid = Action::new('Paid',"Payée" , 'fas fa-box-cash')->linkToCrudAction('Paid');
        

        
        return $actions
        ->add('detail',$delete)
        ->add('detail',$cancel)
        ->add('detail',$updatePreparation)
        ->add('detail',$updateDelivery)
        ->add('detail',$Delivried)
        ->add('detail',$Paid)
        ->add('index','detail')
        ->setPermission(Action::DELETE,'ROLE_ADMIN');
    }

    public function Delete(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $orderDetails = $this->em->getRepository(OrderDetails::class)->findBy(['myOrder' => $order]);

        foreach ($orderDetails as $item) {
            $this->em->remove($item);
        }
        

        $this->em->remove($order);
        $this->em->flush();
        //Ajouter Notification

        $url = $this->aug
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        //Envoyer mail

        return $this->redirect($url);
    }

    public function Cancel(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if ($order->getState() == 5 || $order->getState() == 6) 
        {
            // Notification deja annulée
        }else{
            $order->setState(5);
            $this->em->flush();
        }

        // Envoyer mail

        $url = $this->aug->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if ($order->getState() == 2 ) 
        {
            // Notification deja annulée
        }else{
            $order->setState(2);
            $this->em->flush();
        }

        // Envoyer mail

        $url = $this->aug->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if ($order->getState() == 3 ) 
        {
            // Notification deja annulée
        }else{
            $order->setState(3);
            $this->em->flush();
        }

        // Envoyer mail

        $url = $this->aug->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function Delivried(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if ($order->getState() == 4 ) 
        {
            // Notification deja annulée
        }else{
            $order->setState(4);
            $this->em->flush();
        }

        // Envoyer mail

        $url = $this->aug->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }
    public function Paid(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if ($order->getIsPaid() == 1 ) 
        {
            // Notification deja annulée
        }else{
            $order->setIsPaid(1);
            $this->em->flush();
        }

        // Envoyer mail

        $url = $this->aug->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    


    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference','Référence'),
            TextField::new('createdAt','Passée le'),
            AssociationField::new('user','Utilisateur'),
            TextField::new('carrierName','Transporteur'),
            MoneyField::new('carrierPrice','Frais de port')->setCurrency('MAD'),
            ChoiceField::new('state')->setChoices([
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
