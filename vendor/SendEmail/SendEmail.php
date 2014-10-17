<?php

namespace SendEmail;

class SendEmail
{

    private $message;
    private $sujet;
    private $expediteur;
    private $destinateur;
    private $headers;

    public function __construct($expediteur, $destinateur, $sujet, $message)
    {
        $this->expediteur = $this->setExpediteur($expediteur);
        $this->destinateur = $this->setDestinateur($destinateur);
        $this->sujet = $sujet;
        $this->message = $message;
        $this->headers = $this->setHeader();
    }

    public function send()
    {
        return mail($this->expediteur, $this->sujet, $this->message, $this->headers);
    }

    public function setHeader()
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $this->destinateur . "\r\n";
        $headers .= 'From: ' . $this->expediteur . "\r\n";
        $headers .= "X-Mailer: PHP/".phpversion() . "\r\n";;
        return $this->headers = $headers;
    }

    public function setDestinateur($destinateur)
    {
        if (is_array($destinateur) && !empty($destinateur))
        {
            $destinateurMail = "";
            foreach ($destinateur as $nom => $email)
            {
                $destinateurMail .= ucfirst($nom) . ' <' . $email . '>, ';
            }
            $destinateurMail = substr($destinateurMail, 0, -2);
        }
        else
        {
            $destinateurMail = false;
        }

        return $this->destinateur = $destinateurMail;
    }

    public function setExpediteur($expediteur)
    {
        if (is_array($expediteur) && !empty($expediteur))
        {
            $expediteurMail = "";
            foreach ($expediteur as $nom => $email)
            {
                $expediteurMail .= ucfirst($nom) . " <" . $email . ">, ";
            }
            $expediteurMail = substr($expediteurMail, 0, -2);
        }
        else
        {
            $expediteurMail = false;
        }

        return $this->expediteur = $expediteurMail;
    }

}

?>