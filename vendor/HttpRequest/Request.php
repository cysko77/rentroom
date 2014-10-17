<?php

namespace HttpRequest;

use Security\Security;

class Request extends Security
{
    /*
     * @ variable (string)
     */

    protected $method;
    protected $defaultController;

    public function __construct()
    {
        
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->defaultController = "BackOffice\Controller\produitController";
    }

    /*
     * Permetde récupérer la methode de la requête
     */

    public function getMethod()
    {
        return $this->method;
    }
    
    

    /*
     * Permet de récupérer les données GET, POST..., avec nettoyage des balise html 
     * @parameters (string)  => "POST","GET", etc
     *
     */

    public function getParameters($par)
    {
        // Parameters GET
        if ($par === "GET")
        {
            $this->clean($_GET);
            return $_GET;
        }
        // Parameters POST
        if ($par === "POST")
        {
            $this->clean($_POST);
            return $_POST;
        }

        // Parameters SESSION
        if ($par === "SESSION")
        {
            $this->clean($_SESSION);
            return $_SESSION;
        }

        // Parameters COOKIE
        if ($par === "COOKIE")
        {
            $this->clean($_COOKIE);
            return $_COOKIE;
        }

        // Parameters FILES
        if ($par === "FILES")
        {
            $this->clean($_FILES);
            return $_FILES;
        }
    }

    /*
     * Permet de définir le controlleur et la méthode appelée dans l'url .
     */

    public function getRoute()
    {
        $this->getParameters("GET");
        if ((isset($_GET['controller']) && !empty($_GET['controller'])) && (isset($_GET['action']) && !empty($_GET['action'])))
        {
            $data = (object) array("controller" => $_GET['controller'],
                        "action" => $_GET['action']
            );
        }
        else
        {
            /*
              On attribut des valeurs par defaut au variables controlleur et action
             */
            $data = (object) array("controller" => "produit",
                        "action" => "accueil"
            );
        }

        $controller = "BackOffice\Controller\\" . $data->controller . "Controller";
        $file_controller = "..\\src\\" . $controller . ".php";
        $file_controller = str_replace("\\", "/", $file_controller);
        $action = $data->action;

        /*
         * On vérifie si le controleur et la methode du controleur exitent.
         * S'ils existent on instencie la class du controleur et on execute la methode
         * Sinon, on redirige vers la page d'erreur404.
         */

        if (file_exists($file_controller))
        {
            $emp = new $controller();
            if (method_exists($emp, $action))
            {
                $arg = array();
                // On récupère les arguments à passer dans la methode du controlleur appelée
                foreach ($_GET as $key => $value)
                {
                    if ($key !== "controller" && $key !== "action")
                    {
                        array_push($arg, $value);
                    }
                }

                if (!empty($arg))
                {
                    $emp->$action($arg);
                }
                else
                {
                    $emp->$action();
                }
            }
            else
            {
                $this->defaultRoute();
            }
        }
        else
        {
            $this->defaultRoute();
        }
    }

    /*
     * Permet de définir une route par défault (page Accueil dans notre cas).
     * @parameters (array) $arg  ou $arg[0] est l'id de salle (salle_id) lié au produit
     *
     */

    public function defaultRoute()
    {
        $controller = $this->defaultController;
        $controller = new $controller();
        $controller->accueil();
        exit();
    }

    /*
     * Permet de savoir si c'est une requête asynchrone.
     * @return (boolean)
     */

    public function isAjax()
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] = "XMLHttpRequest")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Permet de savoir si c'est une requête asynchrone.
     * @return (boolean)
     */

    public function isUploadFile()
    {
        if (isset($_SERVER["CONTENT_TYPE"]) && !empty($_SERVER["CONTENT_TYPE"]))
        {
            $server = explode(";", $_SERVER["CONTENT_TYPE"]);
            if (isset($server[0]) && ($server[0] == "multipart/form-data"))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Permet de savoir si l'upload de fichier est en ajax .
     * @return (boolean)
     */

    public function isUploadFileAjax()
    {
        if ($this->isAjax())
        {
            if (isset($_SERVER["HTTP_X_FILE_NAME"]) && !empty($_SERVER["HTTP_X_FILE_NAME"]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Permet de rediriger vers une page .
     * @parameters (string)$controller && (string)$action
     */

    public function redirection($controller, $action, $message = array())
    {
        if(count($message) >0 )
        {   
            $_SESSION["message"] = $message;
        }
        $controller = "BackOffice\Controller\\" . $controller . "Controller";
        $cont = new $controller();
        $cont->$action();
        exit();
    }

    /*
     * Permet de savoir si js activé .
     * @return (boolean)
     */

    public function isJavascriptActivate()
    {
        if (isset($_COOKIE['js']) && $_COOKIE['js'] === "js")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /*
     * Permet de faire une redirection avec Header Location .
     */
    public function redirectionHeader($arg, $message = array())
    {
        $url = $arg;
        if(count($message) >0 )
        {   
            $_SESSION["message"] = $message;
        }
        require("headerLocation.php");
    }
    
    

}

?>