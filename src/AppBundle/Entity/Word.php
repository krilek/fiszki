<?php
namespace AppBundle\Entity;
class Word{
  public $word;
  public function __construct($word = null){
    if($word != null)
      $this->setword($word);
  }
  public function setWord($word){
    $this->word = $word;
  }
  public function getWord(){
    return $this->word;
  }
}

?>