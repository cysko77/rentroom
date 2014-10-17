<?php

namespace Controller;

use Manager\EntityRepository;
use Manager\PDOManager;
use DisplayData\Data;
use HttpRequest\Request;

class Controller
{
    /*
     * @variable (string)
     */
    protected $table;
    /*
     * @variable (integer)
     */
    protected $end;
    /*
     * @variable (string)
     */
    protected $orderBy;
    /*
     * @variable (string)
     */
    protected $typeTri;
    /*
     * @variable (integer)
     */
    protected $start;

    /**
    * Cette méthode permet d'instancier le model correspondant au controller appelé
    * @parameters (string) $table
    * @return	(object)
    */
    public function getRepository($table)
    {
        $class = 'BackOffice\Repository\\' . htmlentities($table, ENT_QUOTES) . 'Repository';
        if (!isset($this->table))
        {
            $this->table = new $class();
        }
        return $this->table;
    }

    /**
    * Cette méthode render permet d'afficher le resultat en html
    * @parameters (string) $template, (array) $parameters, (string)$lay  or null par défault
    * @return	(string) => html
    */
    public function render($template, $parameters = array(), $lay = null)
    {
        $layout = ($lay === null) ? __DIR__ . '/../../src/BackOffice/Views/layout.php' : $layout;
        $template = __DIR__ . '/../../src/BackOffice/Views/' . $template;
        // Extract definie les indices des array en variable
        extract($parameters);
        /*
         * On evite l'affichage du fichier inclut($template) 
         * avec la fonction ob_start. Ensuite on stock le contenu du
         * fichier dans une variable $contenu grace à:
         * $content = ob_get_clean. 
         * Cette varible sera ensuite appelée par le layout.php
         */
        ob_start();
        $data = new Data();
        $request = new Request();
        $connected = $request->isConnected();
        require_once $template;
        $content = ob_get_clean();
        require_once $layout;
        ob_end_flush();
    }

    /**
    * Cette méthode seTri permet d'initialiser le tri
    * @parameters (array) $tri
    * @return
    */
    public function setTri($tri = array() )
    {
        $tri = (empty($tri)) ? array("id", "asc", 0, 10) : $tri;
            $this->orderBy = $tri[0];
            $this->typeTri = $tri[1];
            $this->start = $tri[2];
            $this->end = $tri[3];    
    }

    
    public function triAffichage($arg = array(), $triAffichage, $tri = array())
    {
        $this->setTri($tri);
        if (isset($arg[0]) && isset($arg[1]))
        {
            if (array_key_exists($arg[0], $triAffichage) && ($arg[1] == "asc" || $arg[1] == "desc"))
            {
                $this->orderBy = $arg[0];
                $this->typeTri = $arg[1];
            }
        }
        if (isset($arg[2]) && intval($arg[2]))
        {
            $this->start = $arg[2];
        }
        $triAffichage[$this->orderBy] = $this->typeTri;
        // On va chercher les données correspondantes aux trois dernières offres
        return $tri = array("ORDER BY " . $this->orderBy => $this->typeTri, "LIMIT" => $this->start . "," . $this->end);
    }

    
    public function pagination($totalPage,$url)
    {
        $nbrePage = ceil($totalPage / $this->end);
        $pagination = "";
        if ($nbrePage > 1)
        {
            $pagination = '<ul id="pagination" class="pagination pagination-sm pull-right">';

            for ($i = 0; $i < $nbrePage; $i++)
            {
                $active = ($this->start == $i * $this->end) ?  "active" : "";
                $pagination.= '<li class="' . $active . '"><a href="'.$url.'&p=' . ($i * $this->end) . '">' . ($i + 1) . '</a>';    
            }
            $pagination .= '</ul>';
        }

        return $pagination;
    }

    
    public function cleanAntiSlash($data)
    {
        return str_replace("\\", "", $data);
    }
}

?>