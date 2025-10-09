<?php 

class KSLandingMail{

    private $to;
    private $subject;
    private $message;
    private $headers;

    public function __construct(){
        // Для отправки HTML-письма должен быть установлен заголовок Content-type
        $this->headers = 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    }

    public function set($name, $value){
        if(!empty($name) && property_exists($this, $name)){
            $this->{$name} = $value;
        }
        return $this;
    }

    public function send(){
        if(!empty($this->to) && !empty($this->subject) && !empty($this->message)){
            return mail($this->to, $this->subject, $this->message, $this->headers);
        }
        return false;
    }

    public function setHeaders($headers){
        if(!empty($headers)){
            $this->headers .= $headers;
        }
        return $this;
    }
}