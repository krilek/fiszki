<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FiszkiController extends Controller{
  /**
  * @Route("/")
  */
  public function showMain(){
    $BG_IMAGES = './backgrounds.json';
    $backgrounds = json_decode(file_get_contents($BG_IMAGES))->images;
    $choosedBackground = $backgrounds[rand(0,count($backgrounds)-1)];
    // $name = "Karol";
    if(isset($name)){
    return $this->render("main/main.html.twig",[
      "name" => $name,
      "background" => $choosedBackground,
    ]);

    }else{
      return $this->render("main/beforeName.html.twig",[
      "background" => $choosedBackground
    ]);
    }
  }
  // public function showMain(){
  //   $name = "Karol";
  //   $templating = $this->container->get("templating");
  //   $html = $templating->show("main/show.html.twig",[
  //     "name" => $name
  //   ]);
  //   return new Response($html);
  // }
  /**
  * @Route("/new")
  */
  public function showNewWord(){
    return new Response("NEW WORD");
  }
  /**
  * @Route("/lang/{lang}")
  */
  public function showLangChoose($lang){
    return new Response("Choosed language: ".$lang);
  }
  public function randomizeBackground(){
    $landscapes;
  }
}
?>