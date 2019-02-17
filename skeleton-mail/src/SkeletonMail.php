<?php

namespace SkeletonMail;

class SkeletonMail
{
    /**
     * Email subject
     *
     * @var string
     */
    private $subject;

    /**
     * Email senders
     *
     * @var array
     */
    private $from;

    /**
     * Email receivers
     *
     * @var array
     */
    private $to;

    /**
     * Email message
     *
     * @var string|html
     */
    private $message;

    /**
     * Set the default subject of the email, senders, receivers and message
     */
    public function __construct()
    {
        $this->subject("")
            ->from("")
            ->to("")
            ->message("");
    }

    /**
     * Set the email subject
     *
     * @param  string $subject
     * @return Mailer
     */
    protected function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the email senders
     *
     * @param  array $from
     * @return Mailer
     */
    protected function from($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set the email receivers
     *
     * @param  array $to
     * @return Mailer
     */
    protected function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Set the email message
     * @param  string $message
     * @return Mailer
     */
    protected function message($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the email message
     *
     * @param  string $message
     * @return Mailer
     */
    protected function messageSourceFile($twig_file, $params=[])
    {
        $loader = new \Twig_Loader_Filesystem(resources_path("views/emails"));
        $twig = new \Twig_Environment($loader, config('mailer.settings'));
        $template = $twig->load($twig_file);

        $this->message = $template->render($params);
        return $this;
    }

    /**
     * Send the email
     *
     * @return int Number of recipient
     */
    public function send()
    {
        $config = config('skeleton-mail');

        $mail_host = $config['host'];
        $mail_port = $config['port'];
        $mail_username = $config['username'];
        $mail_password = $config['password'];

        $transport = (new \Swift_SmtpTransport($mail_host, $mail_port))
                        ->setUsername($mail_username)
                        ->setPassword($mail_password);

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($this->subject))
                    ->setFrom($this->from)
                    ->setTo($this->to)
                    ->setBody($this->message, "text/html");

        $recipient_number = $mailer->send($message);

        return $recipient_number;
    }
}
