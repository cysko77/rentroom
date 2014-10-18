<?php

namespace Entity;

Class Entity
{
          /*
     * Pour hydratyer un objet
     * @parameters (array)
     * @return void
     *      
     */
   public function hydrate(array $donnees)
  {
      foreach ($donnees as $key => $value) 
      {
         
          $method = 'set'. ucfirst($key); 
          if(method_exists($this, $method))
          {
              
              $this->$method($value);
          }
          
       }
  }
}