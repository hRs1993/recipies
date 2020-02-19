<?php


namespace App\Messenger\Message;


use Symfony\Component\Mime\Email;

class EmailMessage
{
    /**
     * @var Email
     */
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return EmailMessage
     */
    public static function create(Email $email): EmailMessage
    {
        return new self($email);
    }
}