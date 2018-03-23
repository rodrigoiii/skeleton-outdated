<?php

namespace NotificationSlim;

class Mail
{
    private $smpt;
    private $host;
    private $username;
    private $password;

    private $subject;
    private $from;
    private $to;
    private $message;

    private $twig;

    private $config;
    /**
     * [Set the configuration of email]
     */
    public function __construct()
    {
        global $container; // this is the slim/app container

        $this->config = $container['settings']['mail'];
        $options = $this->config['options'];

        $this->smtp = $this->config['driver'] . "." . $this->config['host'];
        $this->host = $this->config['port'];
        $this->username = $this->config['username'];
        $this->password = $this->config['password'];

        $this->subject = "";
        $this->from = "";
        $this->to = "";
        $this->message = "";

        $loader = new \Twig_Loader_Filesystem($options['view_path']);
        $this->twig = new \Twig_Environment($loader, ['cache' => $options['cache']]);
    }

    /**
     * [Set the subject of mailing]
     * @param  [string] $subject [The subject that attach in email]
     * @return [this]          [Chaining this class]
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * [Set the sender of email]
     * @param  [array] $from [key => value, key for email, and value for name]
     * @return [this]        [Chaining this class]
     */
    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * [Set the receiver of email]
     * @param  [array] $to [key => value, key for email, and value for name]
     * @return [this]      [Chaining this class]
     */
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * [Set the message using twig view template]
     * @param  [string] $path [View path]
     * @param  [array]  $data [data in twig view]
     * @return [this]       [Chaining this class]
     */
    public function view($path, array $data = [])
    {
        $template = $this->twig->load($path);
        $this->message = $template->render($data);

        try {
            # Create the Transport
            $transport = (new \Swift_SmtpTransport($this->smtp, $this->host))
                ->setUsername($this->username)
                ->setPassword($this->password);

            # Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            # Create a message
            $message = (new \Swift_Message($this->subject))
                ->setFrom($this->from)
                ->setTo($this->to)
                ->setBody($this->message, "text/html");

            # Send the message
            $result = $mailer->send($message);

            return $result;
        } catch (\Swift_TransportException $e) {
            if ($this->config['options']['debug']) var_dump($e->getMessage());

            return false;
        }
    }
}