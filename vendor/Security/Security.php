<?php

namespace Security;

class Security
{

    private $entity;

    public function getEntity($table)
    {
        require_once __DIR__ . "/../../src/BackOffice/Entities.php";
        return $this->entity = ${$table};
    }

    /*
      La fonction clean sert à nettoyer les variables avec l'utilisation de htmlentities
      Les variables d'entr�es autorisées:
      $arg => array, object, string, float, integer, boolean, float...
     */

    public function clean(&$args)
    {
        // Cas ou la variable est un tableau
        if (is_array($args))
        {
            foreach ($args as &$data)
            {
                if (!is_array($data) && !is_object($data))
                {
                    $data = trim($data);
                    $data = $this->SpecialHTML(htmlentities($data, ENT_QUOTES, "UTF-8"));
                }
                else
                {
                    //on rapelle la fonction tant que tout l'array n'y est pas passée
                    $this->clean($data);
                }
            }
        }
        // Cas ou la variable est un objet
        elseif (is_object($args))
        {
            foreach ($args as $k => &$data)
            {
                if (!is_object($data) && !is_array($data))
                {
                    $data = trim($data);
                    $data = $this->SpecialHTML(htmlentities($data, ENT_QUOTES, "UTF-8"));
                }
                else
                {
                    clean($data);
                }
            }
        }
        // Cas où la variable et autre q'un tableau ou qu'objet
        else
        {
            $args = trim($args);
            $args = $this->SpecialHTML(htmlentities($data, ENT_QUOTES, "UTF-8"));
        }
    }
    
    public function SpecialHTML($args, $html = false)
    {
        $accent = array("á","â","à","å","ã","ä","æ","ç","é","ê","è","ë","í","î","ì","ï","ñ","ó","ô","ò","õ","ö","œ","š","ú","û","ù","ü","ý","Á","Â","À","Å","Ã","Ä","Æ","Ç","É","Ê","È","Ë","Í","Î","Ì","Ï","Ñ","Ó","Ô","Ò","Õ","Ö","Œ","Š","Ú","Û","Ù","Ü","Ý");
        $accentHtmlentities = array("&aacute;","&acirc;","&agrave;","&aring;","&atilde;","&auml;","&aelig;","&ccedil;","&eacute;","&ecirc;","&egrave;","&euml;","&iacute;","&icirc;","&igrave;","&iuml;","&ntilde;","&oacute;","&ocirc;","&ograve;","&otilde;","&ouml;","&oelig;","&scaron;","&uacute;","&ucirc;","&ugrave;","&uuml;","&yacute;", "&Aacute;","&Acirc;","&Agrave;","&Aring;","&Atilde;","&Auml;","&Aelig;","&Ccedil;","&Eacute;","&Ecirc;","&Egrave;","&Euml;","&Iacute;","&Icirc;","&Igrave;","&Iuml;","&Ntilde;","&Oacute;","&Ocirc;","&Ograve;","&Otilde;","&Ouml;","&Oelig;","&Scaron;","&Uacute;","&Ucirc;","&Ugrave;","&Uuml;","&Yacute;");
        $others = array("€","'");
        $baliseHtml = array("<",">","/","\"");
        $baliseHtmlEntities = array("&lt;","&gt;","&frasl;","&quot;");
        $othersHtmlentities= array("&euro;", "&#039;");
        if(!empty($args))
        {
            if ($html) $args = str_replace($baliseHtmlEntities, $baliseHtml, $args);
            $args = str_replace($accentHtmlentities, $accent, $args);
            $args = str_replace($othersHtmlentities, $others, $args);
        }
        return $args;
    }
    
    
    /* La méthode checkParameters sert à valider les donn�es avant de les injecter dans une requête SQL.
      Arguments d'entrée:
      1.$table(string) correspond au nom de la table auquel les donn�es seront comparées (Cf.BackOffice/Entities.php)
      2.$Data(array) correspondant au valeur des données à valider
      3.$dataMoins (array) correspond aux donn�es que tu n'as pas besoin de valider.
      Arguments de sortie:
      1.	Si ok, return (array) avec les valeurs.
      Les valeurs d'un tableau peut avoir comme valeur (bool)false si valeur non valide ou la valeur si la valeur est valide
      2.	Si erreur, return (boolean) false.
     */

    public function ParametersIsValid($entity, $data)
    {

        require __DIR__ . "/../../src/BackOffice/Entities.php";
        // On compare les indices de 2 tableaux (ex: (array)$data et (array)$produit).
        $ArrayDiff = array_diff_key($data, ${$entity});
        if (!empty($ArrayDiff))
        {
            // On supprime les colonnes inutiles dans le tableau $data
            $arrayKeys = array_keys($ArrayDiff);
            foreach ($arrayKeys as $value)
            {
                unset($data[$value]);
            }
        }

        // On compare si les données attendues et les données reçues sont identiques.
        // Si oui, on valide les données avec méthode valideParameters($arg1,$arg2)
        // Si non, on renvoi un message d'erreur.
        $arrayInter = array_intersect_key($data, ${$entity});

        if ((count(${$entity}) - 1 == count($arrayInter)) && !isset($data['id']) || (isset($data['id'])))
        {
            // On va vérifier les données
            $this->valideParameters($data, ${$entity});
            return $data;
           return ( !in_array(false, $data, true)) ? false : true;

        }
        else
        {
            return false;
        }
    }

    public function valideParameters(&$data, $Entity)
    {
        if (!is_array($Entity))
        {
            $Entity = $this->getEntity($Entity);
        }

        foreach ($data as $key => $values)
        {
            if(isset($Entity[$key]))
            {               
// On vérifie si la valeur est null et que le champ de la table($Entity) associé à cette valeur peut être null
                if ((!in_array(null, $Entity[$key], true)) || ((in_array(null, $Entity[$key], true)) && !empty($data[$key])))
                {
                    // On recupère les informations relatives à chaque champ de la table ($Entity)=> type, etc... 
                    foreach ($Entity[$key] as $k => $v)
                    {
                        
                        if ($k === 0 && $v !== null)
                        {
                            $type = $v;
                            $options = null;
                            // On recupère le resultat de la validation de la valeur testée (false si non valide et valeur si valide)
                            $data[$key] = $this->valide($values, $type, $options, $null = false);
                        }
                        elseif ($k === 0 && $v == null || $k === 1 && $v == null)
                        {
 
                        }
                        else
                        {
                            $type = $k;
                            $options = $v;
                            // On recupère le resultat de la validation de la valeur testée
                            $data[$key] = $this->valide($values, $type, $options, $null = false);

                        }
                    }
                }
                else
                {
                    $data[$key] = null;
                }
            }
        }
    }

    public function valide($values, $type, $options, $null)
    {

        if ($type === "int")
        {
            if ($options !== null)
            {
                $options = array(
                    'options' => array(
                        'min_range' => $options["min"],
                        'max_range' => $options["max"]
                ));
            }

            return $values = filter_var($values, FILTER_VALIDATE_INT, $options);
        }


        if ($type === "email")
        {
            $values = filter_var($values, FILTER_VALIDATE_EMAIL);

            if (strlen($values) > $options["max"])
            {
                $values = false;
            }

            if (strlen($values) < $options["min"])
            {
                $values = false;
            }

            return $values;
        }


        if ($type === "datetime")
        {
            $date = preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/(2([0-9]{3}))\s((0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?)$/i", "$values");
            if (!$date)
            {
                $values = false;
            }
            else
            {
                $values = str_replace("/", "-", $values);
                $values = new \DateTime($values);
                $values = $values->format('Y-m-d H:i:s');
            }
            return $values;
        }


        if ($type === "varchar")
        {
            if (!(strlen($values) <= $options["max"]) || !(strlen($values) >= $options["min"]))
            {
                $values = false;
            }
            return $values;
        }


        if ($type === "float")
        {
            if ($options !== null)
            {
                $options = array(
                    "options" => array(
                        "decimal" => $options["decimal"]
                ));
            }
            return $values = filter_var($values, FILTER_VALIDATE_FLOAT, $options);
        }


        if ($type === "enum")
        {
            if (!in_array($values, $options))
            {
                $values = false;
            }
            return $values;
        }

        if ($type === "text")
        {
            return (strlen($values) > 0) ? $values : false;
        }
    }

    public function isConnected()
    {

        if (isset($_SESSION['Auth']->id) && !empty($_SESSION['Auth']->id))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isAdmin()
    {

        if (isset($_SESSION['Auth']->role_id) && ($_SESSION['Auth']->role_id === "0"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function supDateNow($values)
    {
        $date = preg_match("/^(2([0-9]{3}))\-(0[1-9]|1[0-2])\-(0[1-9]|1[0-9]|2[0-9]|3[0-1])\s((0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?)$/i", "$values");
        if (!$date)
        {
            $values = false;
        }
        else
        {
            $date1 = new \DateTime("now");
            $values = new \DateTime($values);
            $values = $values->format('Y-m-d H:i:s');
            $date2 = new \DateTime($values);
            $values = ($date2 < $date1) ? false : $values;
        }
        return $values;
    }
    
    // Fonction pour rediriger 
    public function accessDenied()
    {
        include(__DIR__ . '/../../web/403.php');
        exit();
    }
}
