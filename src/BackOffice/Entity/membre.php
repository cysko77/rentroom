<?php
namespace BackOffice\Entity;

use BackOffice\Repository\membreRepository;

class membreEntity extends membreRepository{
    
    private $_id;
    private $_pseudo;
    private $_mdp;
    private $_nom;
    private $_prenom;
    private $_email;
    private $_sexe;
    private $_adresse;
    private $_statut_membre;
    private $_role_id;
    private $_token_valid;
    private $_token_mdp;
    private $_statut_newsletter;
    private $_token_secu;
    private $_photo;
    private $_ville_france_id;
    private $_cp;
    
    public function getID()
    {
        return $this->_id;
    }
    
    public function setId($id)
    {
        $this->_id=$id;
    }
    
    public function getPseudo()
    {
        return $this->_pseudo;
    }
    
    public function setPseudo($pseudo)
    {
        $this->_speudo=$pseudo;
    }
    
    public function getMdp()
    {
        return $this->_mdp;
    }
    
    public function setMdp($mdp)
    {
        $this->_speudo=$mdp;
    }
    
    public function getNom()
    {
        return $this->_nom;
    }
    
    public function setNom($nom)
    {
        $this->_nom=$nom;
    }
    
    
    public function getPrenom()
    {
        return $this->_prenom;
    }
    
    public function setPrenom($prenom)
    {
        $this->_nom=$prenom;
    }
    
    
    public function getEmail()
    {
        return $this->_email;
    }
    
    public function setEmail($email)
    {
        $this->_email=$email;
    }
    
    public function getSexe()
    {
        return $this->_sexe;
    }
    
    public function setSexe($sexe)
    {
        $this->_sexe=$sexe;
    }
    
    public function getAdresse()
    {
        return $this->_adresse;
    }
    
    public function setAdresse($adresse)
    {
        $this->_adresse=$adresse;
    }
    
    public function getStatut_membre()
    {
        return $this->_statut_membre;
    }
    
    public function setStatut_membre($statut_membre)
    {
        $this->_statut_membre=$statut_membre;
    }
    
    
    public function getRole_id()
    {
        return $this->_role_id;
    }
    
    public function setRole_id($role_id)
    {
        $this->_role_id=$role_id;
    }
    
    public function getToken_valid()
    {
        return $this->_token_valid;
    }
    
    public function setToken_valid($token_valid)
    {
        $this->_token_valid=$token_valid;
    }
    
    public function getToken_mdp()
    {
        return $this->_token_mdp;
    }
    
    public function setToken_mdp($token_mdp)
    {
        $this->_token_mdp=$token_mdp;
    }
    
    public function getStatut_newsletter()
    {
        return $this->_statut_newsletter;
    }
    
    public function setStatut_newsletter($statut_newsletter)
    {
        $this->_statut_newsletter=$statut_newsletter;
    }
    
    public function getToken_secu()
    {
        return $this->_token_secu;
    }
    
    public function setToken_secu($token_secu)
    {
        $this->_token_secu=$token_secu;
    }
    
    public function getVille_france_id()
    {
        return $this->_ville_france_id;
    }
    
    public function setVille_france_id($ville_france_id)
    {
        $this->_ville_france_id=$ville_france_id;
    }
    
    public function getCp()
    {
        return $this->_cp;
    }
    
    public function setCp($cp)
    {
        $this->_cp=$cp;
    }

}