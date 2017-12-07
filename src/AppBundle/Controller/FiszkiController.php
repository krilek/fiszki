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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FiszkiController extends Controller
{
    /**
    * @Route("/", name="main")
    */
  
    public function showMain(Request $request)
    {
        $background = new Background();
        $background = $background->getBackground();

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
        if ($request->cookies->has("name")) {
            //render main template
            $templateFile = "main/main.html.twig";
            $name = $request->cookies->get("name");
            $parameters = [
        "name" => $name,
        "background" => $background
      ];
        } elseif ($form->isSubmitted() && $form->isValid()) {
            //create cookie and render main
            $name = $form->getData()->name;
            $cookieExpiration = time() + (10 * 365 * 24 * 60 * 60);
            $nameCookie = new Cookie("name", $name, $cookieExpiration);
            $templateFile = "main/main.html.twig";
            $parameters = [
        "name" => $name,
        "background" => $background
      ];
        } else {
            //render beforeName template
            $templateFile = "main/beforeName.html.twig";
            $parameters = [
        "background" => $background,
        "form" => $form->createView()
      ];
        }
    
        $response = $this->render($templateFile, $parameters);
        if (isset($nameCookie)) {
            $response->headers->setCookie($nameCookie);
        }
        return $response;
    }
    /**
    * @Route("/add", name="show_add_word")
    */
    public function showAddWord(Request $request)
    {
        //Randomize background
        $background = new Background();
        $background = $background->getBackground();

        //Setting up doctrine
        $manager = $this->getDoctrine()->getManager();
        
        //Creating new object used for db queries and form creation
        $word = new Word();

        //Setup form for template
        $transparentInput =  array(
            "attr" => array(
                "class" => "input-transparent",
                "aria-label" => "Enter english word"));
        $form = $this->createFormBuilder($word)
        ->add('wordEn', TextType::class, $transparentInput)
        ->add('wordPl', TextType::class, $transparentInput)
        ->add('add', SubmitType::class, array('label' => 'Add new word'))
        ->getForm();
        
        $templateFile = "newWord/newWord.html.twig";
        $parameters = array(
            "background" => $background,
            "form" => $form->createView());
        
        //Get inputed values
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $word = $form->getData();
            //Tell doctrine that I need to store object in db (in future)
            $manager->persist($word);
    
            //Execute queries
            $manager->flush();
        }



        return $this->render($templateFile, $parameters);
    }
    /**
    * @Route("/lang/{lang}", name="show_lang")
    */
    public function showLangChoose(Request $request, $lang, SessionInterface $session)
    {
        $session->start();

        //Background randomize
        $background = new Background();
        $background = $background->getBackground();

        /* program has to get in one round $allSWords questions from the data base,
        write to variable $sWords,
        then every one that appeared delete from array,
        and again rand the next one */

        //question to data base
        // $sWords = [];

        // $actualSWord = array_rand($sWords, 1);
    
        //Migrated to symfony session
        $session->set('goodWords', 0);
        //Here I will add some data pulling from db
        
        //For now dummy classes which you should use maybe
        $word = new Word("english", "angielski");
        $word->id = 1;
        $session->set("lastWord", $word);
        /*
        DUMMY DATA
        */
        $w2 = new Word('cat', 'kot');
        $w2->id = 2;
        $w3 = new Word('sausage', 'kielbasa');
        $w3->id = 3;
        $w4 = new Word('headphones', 'sluchawki');
        $w4->id = 4;
        $w5 = new Word('mouse', 'myszka');
        $w5->id = 5;

        /*
            END OF DUMMY DATA
        */
        //Prepare form and template
        $templateFile = "learn/word.html.twig";
        $transparentInput =  array(
            "attr" => array(
                "class" => "input-transparent",
                "aria-label" => "Enter english word"));
        $form = $this->createFormBuilder($word)
            ->add('word'.ucfirst($lang), TextType::class, $transparentInput)
            ->add('save', SubmitType::class, array('label' => 'Check answer'))
            ->getForm();
    
        //Get user data
        $form->handleRequest($request);
        $submitedWord = "";
        if ($form->isSubmitted()  && $form->isValid()) {
            $submitedWord = $form->getData();
            if ($session->get("lastWord")->getWordPl() == $submitedWord->getWordPl()) {
                $counter = $session->get('goodWords') + 1;
                $session->set('goodWords', $counter);
                if ($session->has("anwseredId")) {
                    $tmp = $session->get("anwseredId");
                    $tmp[] = $session->get("lastWord")->getId();
                    $session->set("anwseredId", $tmp);
                } else {
                    $session->set("anwseredId", array($session->get("lastWord")->getId()));
                }
            }
            //           \/ - word from form
            // New if ($submitedWord->getWordEn() == $savedWord or id in something){
                // $lastWords[] =
            // }
            // if ($submitedWord == $sWords[$actualSWord]) {
            //     $_SESSION['goodWords'] += 1;
            //     unset($sWords[$actualSWord]);
            // }
        }
        //Preparing parameters
        $parameters = [
            'sWord' => $word->getWordEn(),
            'w' => $submitedWord,
            'background' => $background,
            'form' => $form->createView(),
            'goodWords' => $session->get('goodWords'),
            'lang' => $lang,
            'session' => $session
            // 'sWord' => $actualSWord,
            // 'uWord' => $uWord->getWord(),
            // 'HALOSPRAWDZAMZMIENNE1' =>$doSprawdzenia1,
            // 'HALOSPRAWDZAMZMIENNE2' =>$doSprawdzenia2
        ];
        if ($lang == 'pl') {
            $parameters['sWord'] = $word->getWordPl();
        } elseif ($lang == 'en') {
            $parameters['sWord'] = $word->getWordEn();
        }
        return $this->render($templateFile, $parameters);
    }
}
