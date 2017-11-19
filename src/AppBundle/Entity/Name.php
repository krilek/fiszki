<?php
namespace AppBundle\Entity;
class Name{
  public $name;
  public function __construct($name = null){
    if($name != null)
      $this->setName($name);
  }
  public function setName($name){
    $this->name = $name;
  }
  public function getName(){
    return $this->name;
  }
}

?>