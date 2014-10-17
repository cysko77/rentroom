<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;

class statistiqueController extends Controller
{
    

    /*
     * Permet d'afficher la liste des salles.
     * @parameters (array) $arg  && (array) $alert
     *
     */

    public function afficher($arg = array(), $alert = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $statistique = $this->getRepository('statistique');
        $salles1 = $statistique->Top5SN();
        $statistique->filtreText($salles1,"titre",22);
        $salles1 = $this->getArray($salles1);
        $salles2 = $statistique->Top5SV();
        $statistique->filtreText($salles2,"titre",22);
        $salles2 = $this->getArray($salles2);
        $membres1 = $statistique->Top5MQ();
        $membres1 = $this->getArray($membres1);
        $membres2 = $statistique->Top5MP();
        $membres2 = $this->getArray($membres2);
        // On affiche les resultats
        $this->render("statistique/afficher.php", array(
            "title" => "Statisitique",
            "salles1" => $salles1,
            "salles2" => $salles2,
            "membres1" => $membres1,
            "membres2" => $membres2
        ));

    }
    
    public function getArray($data = array())
    {
        if(!empty($data))
        {
            $result = "";
            foreach ($data as $k => $value)
            {
                ($k % 2 === 0) ? $bg = "#f8f8f8" : $bg = "#fff";
                $result .= '<tr style="background:' . $bg . '">';
                foreach ($value as $key => $val)
                {
                    if($key === "Somme_depense")
                    {
                        $result .= "<td>".$this->cleanAntiSlash($val)."€</td>";
                    }
                    else
                    {
                        $result .= "<td>".$this->cleanAntiSlash($val)."</td>";
                    }
                        
                }
                $result .= "</tr>";
            } 
            
            return $result;
        }
        else
        {
            return $data;
        }
    }

}

?>