<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Entity\Name;
use AppBundle\Entity\Word;
use AppBundle\Entity\Background;
class FiszkiController extends Controller{
  /**
  * @Route("/", name="main")
  */
  
  public function showMain(Request $request){
    $background = new Background();
    $background = $background->getBackground();
	//dobry komentarz
    $name = new Name();
    $form = $this->createFormBuilder($name)
            ->add('name', TextType::class, array(
              "attr" => array(
                "class" => "input-transparent",
                "aria-label" => "Enter name"
              )
            ))
            ->getForm();

    $form->handleRequest($request);

    $templateFile;
    $parameters;
    $nameCookie;
    if($request->cookies->has("name")){
      //render main template
      $templateFile = "main/main.html.twig";
      $name = $request->cookies->get("name");
      $parameters = [
        "name" => $name,
        "background" => $background
      ];
    }else if($form->isSubmitted() && $form->isValid()){
      //create cookie and render main
      $name = $form->getData()->name;
      $cookieExpiration = time() + (10 * 365 * 24 * 60 * 60);
      $nameCookie = new Cookie("name", $name, $cookieExpiration);
      $templateFile = "main/main.html.twig";
      $parameters = [
        "name" => $name,
        "background" => $background
      ];
    }else{
      //render beforeName template
      $templateFile = "main/beforeName.html.twig";
      $parameters = [
        "background" => $background,
        "form" => $form->createView()
      ];
    }
    
    $response = $this->render($templateFile,$parameters);
    if(isset($nameCookie))
      $response->headers->setCookie($nameCookie);
    return $response;
  }
  /**
  * @Route("/add", name="show_add_word")
  */
  public function showAddWord(){
    return new Response("NEW WORD");
  }
  /**
  * @Route("/lang/{lang}", name="show_lang")
  */
  public function showLangChoose(Request $request, $lang){

    $background = new Background();
    $background = $background->getBackground();

    $a = 0;
    
    //User Word -uword -> word, that user is writing
    $uword = new word();
    $uword->setWord("".$a);

    //Server Word -sword -> word, that is correct
    //misiek tutaj wrzuc to zapytanie do bazy
    $sword = new word();
    $sword->setWord("");
    
    $templateFile = "main/word.twig";
    $parameters = [
      'word' => $uword,
      'background' => $background,

    ];
    /*
    if(isset($_POST['userWord'])) {
      $parameters = [
      'word' => $uword,
      'background' => $background,
    ];
    }
    else {
      $uword = $form->getData()->word;
      $parameters = [
        'word' => $uword,
        'background' => $background,
      ];
    }
    */

    $form = $this->createFormBuilder($uword)
        ->add('word', TextType::class)
        ->add('', SubmitType::class, array('label' => 'Getting word'))
        ->getForm();

    
    $form->hanleRequest($request);

    if($form->isSubmitted())
    {
      $uword = $form->getData();
    }

    return $this->render("main/word.twig", array(
      'background' => $background,
      'word' => $uword,
      'form' => $form->createView(),
    ));
  }
  public function cookieSet(){
  }
}
?>