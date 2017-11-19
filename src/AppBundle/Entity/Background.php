<?php
namespace AppBundle\Entity;
class Background{
  private $BG_IMAGES = 'backgrounds.json';
  private $backgrounds;
  private $background;
  public function __construct(){
    // $this->backgrounds =  getcwd();
    $this->backgrounds = json_decode(file_get_contents($this->BG_IMAGES))->images;
    $this->background = $this->randomizeBackground();
  }
  public function randomizeBackground(){
    return $this->backgrounds[rand(0,count($this->backgrounds)-1)];
  }
  public function setBackground($background){
    $this->background = $background;
  }
  public function getBackground(){
    return $this->background;
  }
}

?>