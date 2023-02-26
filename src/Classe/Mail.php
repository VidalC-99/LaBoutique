<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key = 'd83f86c8afb81a68584829346e2730af';
    private string $api_key_secret = "5d1e69ffac72d4d5ebf99a4fb6a7f796";

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
         $body = [
             'Messages' => [
                 [
                     'From' => [
                         'Email' => "laboutiquecosme@gmail.com",
                         'Name' => "Administrateur"
                     ],
                     'To' => [
                         [
                             'Email' => $to_email,
                             'Name' => $to_name
                         ]
                     ],
                     'TemplateID' => 4134577,
                     'TemplateLanguage' => true,
                     'Subject' => $subject,
                     'Variables' => json_decode('{
                            "content":
                          }', true)
                     ]
                 ]
         ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());

    }
}