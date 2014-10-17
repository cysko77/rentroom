<?php

namespace DisplayData;

class Data
{
    public  function erreur($dataReponse, $name)
    {

        if (isset($dataReponse[$name]))
        {
            if ($dataReponse[$name] === false)
            {
                return array("has-error", "remove");
            }
            elseif ($dataReponse[$name] === null)
            {
                return array("has-null", "null");
            }
            else
            {
                return array("has-success", "ok");
            }
        }
        else
        {
            return array("has-null", "null");
        }
    }

    
    public function valeur($dataReponse, $name = null)
    {

        if (isset($dataReponse[0]))
        {
            $data = $dataReponse[0];
            if (isset($data->$name) && ($data->$name !== false))
            {
                $data->$name = str_replace("\\", "", $data->$name);
                return $data->$name;
            }
        }  
        
        if(!is_array($dataReponse))
        {
            $data = str_replace("\\", "", $dataReponse);
            return $data;
        }

        

        if (isset($dataReponse[$name]) && ($dataReponse[$name] !== false))
        {

            $dataReponse[$name] = str_replace("\\", "", $dataReponse[$name]);
            return $dataReponse[$name];
        }
    }
}