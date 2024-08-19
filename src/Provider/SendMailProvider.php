<?php
namespace App\Provider;

use Mailjet\Client;
use Mailjet\Resources;
use Twig\Environment;

class SendMailProvider
{

    public function __construct(private Environment $twig)
    {
    }

    public function sendMail($user)
    {
        $mj = new Client($_ENV['PUBLIC_KEY'], $_ENV['SECRET_KEY'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['FROM'],
                        'Name' => "Belghith"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFirstName()
                        ]
                    ],
                    'Subject' => "Greetings from Mailjet.",
                    'TextPart' => "My first Mailjet email",
                    'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href='https://www.mailjet.com/'>Mailjet</a>!</h3><br />May the delivery force be with you!",
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());


    }

    public function sendResetPasswordMail($user,$resetToken)
    {
        $mj = new Client($_ENV['PUBLIC_KEY'], $_ENV['SECRET_KEY'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['FROM'],
                        'Name' => "Belghith"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFirstName()
                        ]
                    ],
                    'Subject' => "Your password reset request",
                    //'TextPart' => "My first Mailjet email",
                    'HTMLPart' => $this->twig->render('reset_password/email.html.twig',['resetToken'=> $resetToken ]),
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if(!$response->success() ){
            throw new \Exception('Mailjet client error: ');
        }


    }

}

?>



