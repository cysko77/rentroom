<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;

class ville_franceController extends Controller
{

    public function rechercher($cp = null)
    {
        $request = new Request();
        if ($request->isAjax())
        {
            $ville = $this->getRepository('ville_france');
            $villes = $ville->findVilleBy($cp);
            if (count($villes) > 0)
            {
                $reponse = "ok";
            }
            else
            {
                $reponse = "error";
            }

            echo json_encode(array("reponse" => $reponse, "ville" => $villes));
        }
    }

}

?>