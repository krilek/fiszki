<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    /* program has to get in one round $allSWords questions from the data base, 
    write to variable $sWords, 
    then every one that appeared delete from array,
    and again rand the next one */

    //all Words from server;
    $allSWords = 3;

    //question to data base
    $sWords = ['cat'=>'kot', 'sausage'=>'kielbasa', 'headphones'=>'sluchawki', 'mouse'=>'myszka'];

    $actualSWord = array_rand($sWords,1);
    
    $_SESSION['goodWords'] = 0;

    $uWord = new Word("");

    $templateFile = "main/word.twig";
    
    $form = $this->createFormBuilder($uWord)
    ->add('word', TextType::class)
    ->add('save', SubmitType::class, array('label' => 'Getting word'))
    ->getForm();
    

    $doSprawdzenia1 = $actualSWord;
    $doSprawdzenia2 = $sWords[$actualSWord];
    
    $form->handleRequest($request);
    if($form->isSubmitted()  && $form->isValid())
    {
      $submitedWord = $form->getData()->word;
      $uWord->setWord($submitedWord);
      if($submitedWord == $sWords[$actualSWord])
      {
        $_SESSION['goodWords'] += 1;
        unset($sWords[$actualSWord]);
      }
      
    }
    $parameters = [
      'sWord' => $actualSWord,
      'uWord' => $uWord->getWord(),
      'background' => $background,
      'form' => $form->createView(),
      'goodWords' => $_SESSION['goodWords'],
      'HALOSPRAWDZAMZMIENNE1' =>$doSprawdzenia1,
      'HALOSPRAWDZAMZMIENNE2' =>$doSprawdzenia2
    ];
    return $this->render("main/word.twig", $parameters);
  }
  public function cookieSet(){
  }
}
?>