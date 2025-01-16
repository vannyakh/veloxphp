<?php

namespace Core\Mail;

class Mailer
{
    protected $transport;
    protected $defaults = [];

    public function __construct(Transport $transport, array $defaults = [])
    {
        $this->transport = $transport;
        $this->defaults = $defaults;
    }

    public function send(Mailable $mailable): void
    {
        $message = $mailable->build();
        $message->setDefaults($this->defaults);
        
        $this->transport->send($message);
    }

    public function queue(Mailable $mailable, string $queue = null): void
    {
        app()->queue->push(new SendMailJob($mailable), $queue);
    }

    public function later(\DateTime $delay, Mailable $mailable, string $queue = null): void
    {
        app()->queue->later($delay, new SendMailJob($mailable), $queue);
    }
} 