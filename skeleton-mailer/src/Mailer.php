<?php

namespace SkeletonMailer;

class Mailer
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
    public function subject($subject)
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
    public function from($from)
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
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Set the email message
     * @param  string $message
     * @return Mailer
     */
    public function message($message)
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
    public function messageSourceFile($twig_file, $params=[])
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
        return send_mail($this->subject, $this->from, $this->to, $this->message);
    }
}
