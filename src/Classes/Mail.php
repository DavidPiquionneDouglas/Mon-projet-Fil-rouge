<?php
    namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;

    class Mail
    {
        // public function send($to_email, $to_name, $subject, $content)
        public function send($to_email, $to_name, $subject, $template, $vars)
        {
            // Récupérer le chemin en cours
            // dd(dirname(__DIR__).'/mail/welcome.html');
            // Récupérer le contenu du fichier html
            $content = file_get_contents(dirname(__DIR__).'/mail/'.$template);
            
            if($vars) {
                foreach ($vars as $key => $var) {
                    $content = str_replace('{' . $key .'}' , $var, $content);
                }
            }
            // MailJet
            $mj = new Client($_ENV["MJ_APIKEY_PUBLIC"],$_ENV["MJ_APIKEY_PRIVATE"], true, ['version' => 'v3.1']);

            // Define your request body

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "dpiksoo@gmail.com", // adresse email qui à été validé par mailjet
                            'Name' => "QuickTask"
                        ],
                        'To' => [
                            [
                                'Email' => $to_email,
                                // "managing.projects@yopmail.com",
                                'Name' => $to_name
                                // "You"
                            ]
                        ],
                        //  'TemplateID' => 6703135,
                        //  'TemplateLanguage' => true,
                        'Subject' => $subject,
                        // "My first Mailjet Email!",
                        // 'TextPart' => "Greetings from Mailjet!",
                         'HTMLPart' => $content
                        //  "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3>
                        //  <br />May the delivery force be with you!"
                    ]
                ]
            ];
            // All resources are located in the Resources class

            // $response = 
            $mj->post(Resources::$Email, ['body' => $body]);

            // Read the response

            // $response->success() && var_dump($response->getData());
        }
    }