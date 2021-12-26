<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class MailJet
{

    private $P_Key;
    private $S_Key;
    private $AdminEmail = "rajawiaymen404@gmail.com";

    public function __construct()
    {
        $this->P_Key = 'c8a52f46f88d7141d1d52c594c456cc0';
        $this->S_Key = '3b0d5b780de143fde27813c3ad3e1e7e';
    }

    /**
     * Mail: confirmation de la commande
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $reference
     * @param [type] $strDate
     * @param [type] $strHeure
     * @return void
     */
    public function ConfirmationOrder($toEmail, $toName, $reference, $strDate, $strHeure)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->AdminEmail,
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3334530,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de la commande",
                    'Variables' => [
                        'name' => $toName,
                        'reference' => $reference,
                        'Date' => $strDate,
                        'Heure' => $strHeure
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Status de la commande
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $reference
     * @param [type] $intState
     * @return void
     */
    public function OrderState($toEmail, $toName, $reference, $intState)
    {
        //Creation des state 
        $state = null;
        $subject = null;

        if ($intState == 2) {
            $state = "En cours de preparation";
            $subject = "Préparation en cours";
        } else if ($intState == 3) {
            $state = "En cours de livraison";
            $subject = "Livraison en cours";
        } else if ($intState == 4) {
            $state = "Livrée et payée";
            $subject = "Commande livrée";
        } else if ($intState == 5) {
            $state = "Annulée";
            $subject = "Commande annulée";
        }

        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->AdminEmail,
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3335958,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'name' => $toName,
                        'reference' => $reference,
                        'state' => $state
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Commande annulée
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $reference
     * @return void
     */
    public function CancelOrder($toEmail, $toName, $reference)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "$this->AdminEmail",
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3336067,
                    'TemplateLanguage' => true,
                    'Subject' => "Commande annulée",
                    'Variables' => [
                        'name' => $toName,
                        'reference' => $reference
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Confirmationd e l'inscription
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @return void
     */
    public function Register($toEmail, $toName)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "$this->AdminEmail",
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3338798,
                    'TemplateLanguage' => true,
                    'Subject' => "Inscription réussi !!",
                    'Variables' => [
                        'name' => $toName,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Confirmation de la modification des informations
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $content
     * @return void
     */
    public function CredentialsModifyConfirmation($toEmail, $toName, $content)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "$this->AdminEmail",
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3340088,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de votre demande de modification",
                    'Variables' => [
                        'name' => $toName,
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Recuperation du mot de passe
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $content
     * @return void
     */
    public function ResetPasswordConfirmation($toEmail, $toName, $content)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "$this->AdminEmail",
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3342658,
                    'TemplateLanguage' => true,
                    'Subject' => "Récuperation du mot de passe",
                    'Variables' => [
                        'name' => $toName,
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    /**
     * Mail: Contact confirmé
     *
     * @param [type] $toEmail
     * @param [type] $toName
     * @param [type] $strDate
     * @param [type] $strHeure
     * @return void
     */
    public function Contact($toEmail, $toName, $strDate, $strHeure)
    {
        $mj = new Client($this->P_Key, $this->S_Key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "$this->AdminEmail",
                        'Name' => "TechShop"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3355624,
                    'TemplateLanguage' => true,
                    'Subject' => "Réclamation reçu avec success",
                    'Variables' => [
                        'name' => $toName,
                        'Date' => $strDate,
                        'Heure' => $strHeure
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
