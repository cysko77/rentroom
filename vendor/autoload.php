<?php

class Autoload
{

    public static function className($className)
    {
        // cas où le fichier coorespondant à la classe se trouve dans  le repertoire /src/
        $dirSrc = __DIR__ . '\..\src\\' . $className . '.php';
        $dirSrc = str_replace("\\", "/", $dirSrc);
        // cas où le fichier coorespondant à la classe se trouve dans  le repertoire /vendor/
        $dirVendor = __DIR__ . '\..\vendor\\' . $className . '.php';
        $dirVendor = str_replace("\\", "/", $dirVendor);
        // Suivant le cas où on se trouve, on inclut le fichier de la classe à instancier
        $arrayPath = array($dirSrc, $dirVendor);
        foreach ($arrayPath as $values)
        {
            if (file_exists($values))
            {
                $path = $values;
                require_once $path;
                break;
            }
        }
    }

}

// La fonction spl__autoload_register va declenche la fonction className à chaque instanciation d'une classe et va inclure le fichier correspondant à cette même classe 
spl_autoload_register(array('Autoload', 'className'));
?>