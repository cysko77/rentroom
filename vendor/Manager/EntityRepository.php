<?php

namespace Manager;

use Manager\PDOManager;
use PDO;

class EntityRepository
{
    /*
     * @variable (object)
     */

    private $db;

    /**
     * Cette méthode permet de recupérer l'objet PDO (connection BDD)  
     * @return	(object)
     */
    public function getDb()
    {
        if (!$this->db)
        {
            $this->db = PDOManager::getInstance()->getPdo();
        }
        return $this->db;
    }

    /**
     * Cette méthode permet de recupérer le nom de la table  
     * @return	(string)
     */
    public function getTableName()
    {
        return strtolower(str_replace(array('BackOffice\Repository\\', 'Repository'), '', get_called_class()));
    }

    /**
     * Cette méthode permet de recupérer toutes les données d'une table  
     * @return	(array)
     */
    public function findAll($fiel = array())
    {
        if (empty($fiel))
        {
            $fields = "*";
        }
        else
        {
            $fields = "";
            foreach ($fiel as $values)
            {
                $fields .= "$values, ";
            }
            $fields = substr($fields, 0, -2);
        }

        $query = $this->getDb()->prepare('SELECT ' . $fields . ' FROM ' . $this->getTableName());
        $query->setFetchMode(PDO::FETCH_OBJ);
        try {
            $query->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
            exit();
        }
        $result = $query->fetchAll();
        if (!$query)
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    /**
     * Cette méthode permet de recupérer les données filtrés d'une table
     * @parameters (array) $args
     * @return	(array)
     */
    public function find($args = array())
    {
        $sql = "SELECT ";
        foreach ($args as $key => $data)
        {
            if ($key == "fields")
            {
                foreach ($data as $values)
                {
                    $sql .= "$values, ";
                }
                $sql = substr($sql, 0, -2);
            }
            elseif ($key == "tables")
            {
                $sql .= " FROM ";
                foreach ($data as $key => $values)
                {
                    $sql .= "$values, ";
                }
                $sql = substr($sql, 0, -2);
            }
            elseif ($key == "leftJoin")
            {

                if (!empty($data))
                {
                    foreach ($data as $key => $values)
                    {
                        $sql .= " LEFT JOIN $key ON $values";
                    }
                }
            }
            elseif ($key == "rightJoin")
            {
                if (!empty($data))
                {
                    foreach ($data as $key => $values)
                    {
                        $sql .= " RIGHT JOIN $key ON $values";
                    }
                }
            }
            elseif ($key == "filtre")
            {
                $i = 0;

                if (!empty($data))
                {
                    foreach ($data as $values)
                    {
                        if ($i == 0)
                        {
                            $sql .= " WHERE $values ";
                        }
                        else
                        {
                            $sql .= " AND $values ";
                        }
                        $i++;
                    }
                }
            }
            elseif ($key == "tri")
            {
                if (!empty($data))
                {
                    foreach ($data as $k => $values)
                    {
                        $sql .= " $k $values ";
                    }
                }
            }
            else
            {
                
            }
        }
        $query = $this->getDb()->prepare($sql);
        $query->setFetchMode(PDO::FETCH_OBJ);
        try {
            $query->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
            exit();
        }

        $result = $query->fetchAll();

        if (!$query)
            return false;
        else
            return $result;
    }

    /**
     * Cette méthode permet de sauver(insert && update) des données dans une table
     * @parameters (array) $data
     * @return	(integer) 
     * insert => return id ou 0 si echec 
     * update => return 1 si success ou 0 si echec
     */
    public function save($data)
    {

        $fields = "";
        $values = "";
        if (isset($data['id']))
        {
            // Cas d'une requête UPDATE
            $id = $data['id'];
            unset($data['id']);
            $sql = "UPDATE " . $this->getTableName() . " SET ";
            foreach ($data as $key => $value)
            {
                $sql .= "$key=:$key , ";
                $dataSave[":$key"] = $value;
            }
            $dataSave[":id"] = $id;
            $sql = substr($sql, 0, -2);
            $sql .=" WHERE id=:id";
        }
        else
        {
            // Cas d'une requête INSERT
            $sql = "INSERT INTO " . $this->getTableName();
            foreach ($data as $key => $value)
            {
                // on recupère les champs de la requête 
                $fields .= $key . ", ";
                // on recupère les valeurs 
                $values .=":" . $key . ', ';
                $dataSave[":$key"] = $value;
            }

            $fields = substr($fields, 0, -2);
            $values = substr($values, 0, -2);
            $sql .= "(" . $fields . ")  ";
            $sql .="VALUES(" . $values . ")";
        }
        // On effectue la requête
        $query = $this->getDb()->prepare($sql);

        try {
            $query->execute($dataSave);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return $count = 0;
        }

        return ($query->rowCount() === 1 && !isset($id)) ? $this->db->lastInsertId() : $query->rowCount();
    }

    /**
     * Cette méthode permet de supprimer des données d'une table
     * @parameters (integer) $arg
     * @return	(integer) 0 or 1
     */
    public function delete($arg)
    {
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE id=' . $arg;
        try {
            $count = $this->getDb()->exec($sql);
        } catch (\PDOException $e) {

            return $count = 0;
        }
        return $count;
    }

    /*
     * Permet de filtrer des données string .
     * @parameters (string)$arg && (string)$key && (integer)$length
     *
     */

    public function filtreText(&$arg, $key, $length)
    {
        if (is_array($arg) && !empty($arg))
        {
            foreach ($arg as &$value)
            {
                if (strlen($value->$key) > $length)
                {
                    $value->$key = substr($value->$key, 0, $length) . " ...";
                }
            }
        }
    }

    /**
     * Cette méthode permet de mettre les caractères accentués en masjuscule
     * @parameters (array) $args
     * @return	(array)
     */
    public function specialMasjuscule(&$data, $indice)
    {
        $accentMinuscule = array("á", "â", "à", "å", "ã", "ä", "æ", "ç", "é", "ê", "è", "ë", "í", "î", "ì", "ï", "ñ", "ó", "ô", "ò", "õ", "ö", "œ", "š", "ú", "û", "ù", "ü", "ý");
        $accentMajuscule = array("Á", "Â", "À", "Å", "Ã", "Ä", "Æ", "Ç", "É", "Ê", "È", "Ë", "Í", "Î", "Ì", "Ï", "Ñ", "Ó", "Ô", "Ò", "Õ", "Ö", "Œ", "Š", "Ú", "Û", "Ù", "Ü", "Ý");

        if (is_array($data) && !empty($data))
        {
            foreach ($data as &$value)
            {
                if (isset($value->$indice) && !empty($value->$indice))
                {
                    $value->$indice = str_replace($accentMinuscule, $accentMajuscule, $value->$indice);
                }
            }
            return $data;
        }
    }

}

?>