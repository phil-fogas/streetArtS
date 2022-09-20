<?php

namespace App\Controller;

//include_once('./function/function.php');

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Street;
use App\Entity\Droit;
use App\Entity\Categorie;
use App\Entity\Users;
use App\Entity\Comment;
use App\Entity\Statut;
use App\Entity\Mail;
use App\Entity\Validation;

class DefaultController extends AbstractController
{
    
    public function __construct()
    {
        $session = new Session(); 
        //$session->start(); 

        $this->title = 'acceuil';
        $this->keywords= 'street Art, fresque, art urbain';
        $this->description='streetArt ';

        if ($session->get('auth'))
        {
                   
        $this->droit=$session->get('auth')['droit'];
        $this->statut=$session->get('auth')['statut'];
        $this->valid=$session->get('auth')['valid'];
        $this->iduser=$session->get('auth')['iduser'];  
        } else {
            $this->droit=0;
            $this->statut=0;
            $this->valid=0;
            $this->iduser=1; 

        }
       
       
       
    }


    public function index(): Response
    {
        $streets = $this->getDoctrine()->getRepository(Street::class)->findBy([],[],3);    
       
     $coun = $this->getDoctrine()->getRepository(Street::class);
     
     $count['total'] = $coun->count([]);
     $count['ef'] = $coun->count(['statut'=> 2]);
        //$count="";
   
        return $this->render('homepage.html.twig',[
            'bonjour'=>bienvenu(),
            'title'=>$this->title,
            'keywords'=>$this->keywords,
            'description'=>$this->description,
            'streets'=>$streets,
            'count'=>$count,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);
        
    }


    public function propos(): Response
    {
        $coun = $this->getDoctrine()->getRepository(Street::class);
     
     $count['total'] = $coun->count([]);
     
        return $this->render('propos.html.twig',[
            'title'=>'propos',
            'keywords'=>'propos,'.$this->keywords,
            'description'=>$this->description,
            'count'=>$count,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);
        
    }

    public function search(Request $request): string
    {
       

        $sql = "SELECT street.id as id ,street.name as name ,adresse,photo,description,dateCreation,dateFiche,users.pseudo as pseudo ,valid,categorie.name AS categorie,statut.statut as statut ,latitude,longitude 
        ";
  if (!empty($request->get('km'))) {
    $latitude=$request->get('latitude');
    $longitude=$request->get('longitude');
//$request->get('e')

     if (!$latitude||!$longitude) {

 $adresse=str_replace(' ', '+',$request->get('adresse'));
 
 $url='https://api-adresse.data.gouv.fr/search/?q='.$adresse.'';

 $result = file_get_contents($url);

 $json = json_decode($result, true);

 $latitude = $json['features'][0]['geometry']['coordinates'][1];
 $longitude = $json['features'][0]['geometry']['coordinates'][0];

 }
 
 $formule="(6366 * acos(cos(radians($latitude)) * cos(radians(`latitude`)) * cos(radians(`longitude`) - radians($longitude)) + sin(radians($latitude)) * sin(radians(`latitude`))))";
    $sql .= " ,".$formule." AS dist ";
//var_dump($formule);
     } else {

        $sql .= " ,(0) AS dist ";   
     }

 $sql .= " FROM `street`
 INNER JOIN `users` ON street.id_user = users.id
 INNER JOIN `categorie` ON categorie.id = street.categorie
 INNER JOIN `statut` ON street.statut = statut.id
 WHERE street.valid >= :valid AND street.statut >= :statut
 ";
    

     if (!empty($request->get('categorie'))) {
         $sql .= " AND categorie = :categorie ";
     
     }
     if (!empty($request->get('rue'))) {
         $sql .= " AND `adresse` LIKE :rue ";
      
     }
     if (!empty($formule)){
        
         $sql .= " HAVING dist <=".$request->get('km')." ";
     $sql .= " ORDER BY dist ASC ;";
     }

     if (empty($formule)){

         $sql .= " ORDER BY `street`.`dateFiche` ASC ;";
     }
          
        return $sql;
    }

    public function galerie(Request $request): Response
    {
               
        $conn = $this->getDoctrine()->getConnection();
        $sql = $this->search($request);
        
        $streets = $conn->prepare($sql);
        $params = ([
            'valid' => $this->valid,
            'statut' => $this->statut,
            ]);
                     

            if($request->get('categorie') ){
                $params = (['categorie'=>(int)$request->get('categorie')]) + $params;

            }
            if($request->get('rue') ){
                $params = (['rue'=> '%'.$request->get('rue').'%']) + $params;

            }
          
              
             $streets->execute($params);

          $counts = $this->getDoctrine()->getRepository(Comment::class)->count(['idStreet'=>1, ]);
           
         $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('galerie.html.twig',[
            'title'=>'galerie de street',
            'keywords'=>'galerie,'.$this->keywords,
            'description'=>$this->description,
            'streets'=>$streets,
            'categorie'=>$categories,
            'counts'=>$counts,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);
        
    }

    public function plan(Request $request): Response
    {
        $conn = $this->getDoctrine()->getConnection();
        $sql = $this->search($request);
        
        $streets  = $conn->prepare($sql);
        $params = ([
            'valid' => $this->valid,
            'statut' => $this->statut,
            ]);
            $streets ->execute($params);

            $counts = $this->getDoctrine()->getRepository(Comment::class);
           
            $counts=$counts->count(['idStreet'=>1, ]);
     
              
        return $this->render('plan.html.twig',[
            'title'=>'plan',
            'keywords'=>'plan,'.$this->keywords,
            'description'=>'plan des street Art dans la ville '.$this->description,
            'streets'=>$streets,
            'counts'=>$counts,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);
        
    }

    public function modif($id): Response
    {
        $streets = $this->getDoctrine()->getRepository(Street::class);
        $streets = $streets->find($id);
        
                if (isset($streets))
                {
                // droit de modifier selon les droits
                 if ($this->droit == 9 || $this->droit == 5 || $streets->getIdUser()==$this->iduser)
                    {
                      
                    $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
                     
                    $statuts  = $this->getDoctrine()->getRepository(Statut::class)->findAll();
                       
                    $users= $this->getDoctrine()->getRepository(Users::class)->findAll();
                    $vote = $this->getDoctrine()->getRepository(Validation::class)->findOneBy(['idUser'=>$this->iduser,'idStreet'=>$id]);
                        
                return $this->render('modif.html.twig',[
            'title'=>'modifier fiche',
            'keywords'=>'modif,'.$this->keywords,
            'description'=>'modification de fiche '.$this->description,
            'categorie'=>$categories,
            'statuts'=>$statuts,
            'streets'=>$streets,
            'users'=>$users,
            'vote'=>$vote,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);     
                
                } else {

                    return $this->redirectToRoute('noPage');    
                    }
            } else {

            return $this->redirectToRoute('noPage');    
            }
    }

    public function compte(): Response
    {
      
        if ($this->droit){
            return $this->render('compte.html.twig',[
            'title'=>'compte',
            'keywords'=>'compte,'.$this->keywords,
            'description'=>$this->description,
            'bonjour'=>bienvenu(),
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]); 
        } else {
            return $this->redirectToRoute('noPage');
            
        }
       
        
    }

    public function addcompte(): Response
    {
      
        
        return $this->render('addcompte.html.twig',[
            'title'=>'compte',
            'keywords'=>'compte,'.$this->keywords,
            'description'=>$this->description,
            'bonjour'=>bienvenu(),
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);  
                     
    }

    public function addcontact(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager(); 
        $e=2;
       
         if ($request->get('email'))
         {
         $envoi = "web@site.fr";
         // netoyage et validation mail selon format
         $email = filter_var($request->get('email'), FILTER_SANITIZE_EMAIL);
       
          //traitement mail
          if(empty($request->get('sujet')))
            {
            $sujet="message de ".$request->get('email');
             } else {
            $sujet=$request->get('sujet');
             }

          $passage_ligne = "\r\n";
          $header = "";
          $header .= "MIME-Version: 1.0" . $passage_ligne;
          $header .= "Subject: ".$sujet." " . $passage_ligne;
          $header .= "Date: " . date('r') . $passage_ligne;
          $header .= "From: ".$email." " . $passage_ligne;
          $message = (wordwrap($request->get('message'), 70, $passage_ligne));
          $message = htmlentities(preg_replace("/[\r\n]+/", "", $message), ENT_QUOTES, 'UTF-8');
       
          $message = $passage_ligne . $message . $passage_ligne;
       
          $message = str_replace("\n.", "\n..", $message);
          }
          if (!empty($_POST) && filter_var($request->get('email'), FILTER_VALIDATE_EMAIL))
    {

// envoie mail

    $verif_envoi_mail = @mail($envoi, $sujet, $message, $header);
    // verrifier si y a envoie mail
    
       if ($verif_envoi_mail === FALSE)
       {
        $e = 2;

        } else {
        $e = 1;

        }

    }else {
        $e = 2;

        }
        if (isset($e))
        {   
        $log = new Mail();
        $log->setEmail($request->get('email'));
        $log->setSujet($request->get('sujet'));
        $log->setMessage($request->get('message'));
        $log->setDate(new \DateTime("now"));
        $entityManager->persist($log);
        $entityManager->flush();
    }else {
        $e = 3;

        }

       
        return $this->redirectToRoute('contact',['e'=>$e]);
                     
    }
    public function adcompte(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $log = new Users();
        
       
        $log->setEmail($request->get('email'));
        $log->setPseudo($request->get('pseudo'));
        $log->setDroit(2);
         $log->setDatevisi(new \DateTime("now"));
         $log->setCreatedAt(new \DateTime("now"));
         $log->setPassword(password_hash($request->get('password'),PASSWORD_BCRYPT));
         
         $entityManager->persist($log);
         $entityManager->flush();
        
         $dd = $entityManager->getRepository(Droit::class);

            $sv = $dd->findBy(['id'=>2]);

            $session = new Session(); 
   
            $session->set('auth', [
                 'iduser' => $log->getId(),
                 'pseudo' => $request->get('pseudo'),
                 'droit' => 2,
                 'statut' => $sv[0]->getStatut('statut'),
                 'valid' => $sv[0]->getValid('valid'),
             ]) ;
           
             return $this->redirectToRoute('compte');
                     
    }

    public function comments(): Response
    {
        $commentAll = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findBy(['idUsers'=>$this->iduser]);
        return $this->render('comment.html.twig',[
            'title'=>'commentaire',
            'keywords'=>'commentaire,'.$this->keywords,
            'description'=>$this->description,
            'comment'=>$comment,
            'commentAll'=>$commentAll,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            ]);
        
    }

    public function categories(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('categorie.html.twig',[
            'title'=>'categorie',
            'keywords'=>'categorie,'.$this->keywords,
            'description'=>$this->description,
            'categorie'=>$categories,
            'iduser'=>$this->iduser,
            'droit'=>$this->droit
            ]);
        
    }

    public function connection(Request $request): Response
    {
        
        return $this->render('connection.html.twig',[
            'title'=>'connection',
            'keywords'=>'connection,'.$this->keywords,
            'description'=>$this->description,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            'e'=>$request->get('e')
            ]);
        
    }

    public function setconnection(Request $request): Response
    {
       
 
         // verrication si user est present
         $entityManager = $this->getDoctrine()->getManager();
         $log = $entityManager->getRepository(Users::class);
         $login = $log->findBy(['pseudo'=>$request->get('pseudo')]);

         if (empty($login)){
           $login = $log->findBy(['email'=>$request->get('pseudo')]);   
         }
       // dd($login); 
         if (empty($login))
         {
             
            return $this->redirectToRoute('connection',['e'=>2]); 
         } else {
 
         //verification du mot de pass
         if (password_verify($request->get('password'), $login[0]->getPassword('password')))
         {
            $dd = $entityManager->getRepository(Droit::class);
            $sv = $dd->findBy(['id'=>$login[0]->getDroit()]);
           // dd($sv[0]->getStatut('statut'));
            $session = new Session(); 
            //$session->start(); 
            $session->set('auth', [
                 'iduser' => $login[0]->getId('id'),
                 'pseudo' => $login[0]->getPseudo('pseudo'),
                 'droit' => $login[0]->getDroit('droit'),
                 'statut' => $sv[0]->getStatut('statut'),
                 'valid' => $sv[0]->getValid('valid'),
             ]) ;
 
     
                 // mise a jour date dermiere visite
         $log = $log->find(['id'=>$login[0]->getId('id')]);
       
         $log->setDatevisi(new \DateTime("now"));
         
         $entityManager->flush();

    
                return $this->redirectToRoute('home');   
            
 
         } else {
            return $this->redirectToRoute('connection',['e'=>2]); 
         
         }
 
       }
          
        
    }

    public function deconnec(): Response
    {
        $session = new Session(); 
        $session->clear();  
        return $this->redirectToRoute('home');    
    }

    public function oublier(): Response
    {
        
        return $this->render('oublier.html.twig',[
            'title'=>'oublier',
            'keywords'=>'oublier,'.$this->keywords,
            'description'=>'mot de passe oublier'.$this->description,
            'iduser'=>$this->iduser,
            'droit'=>$this->droit
            ]);
        
    }

    public function setoublier(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $log = $entityManager->getRepository(Users::class);
        $login = $log->findBy(['pseudo'=>$request->get('pseudo'),'email'=>$request->get('email')]);
      
        if (empty($login)){
        return $this->redirectToRoute('home'); 

       } else {

         //verification du mot de pass
        
        $log = $log->find(['id'=>$login[0]->getId('id')]);
       
         $log->setDatevisi(new \DateTime("now"));
         $log->setPassword(password_hash($request->get('password'),PASSWORD_BCRYPT));
         $entityManager->flush();

            $session = new Session(); 
            //$session->start(); 
            $session->set('auth', [
                 'iduser' => $login[0]->getId('id'),
                 'pseudo' => $login[0]->getPseudo('pseudo'),
                 'droit' => $login[0]->getDroit('droit'),
                 'statut' => $login[0]->getStatut('statut'),
                 'valid' => $login[0]->getValid('valid'),
             ]) ;
 

        return $this->redirectToRoute('compte'); 
       }

            
    }

    public function setcategorie(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cat = new Categorie();
        
        $cat->setName($request->get('categorieAd'));
        $entityManager->persist($cat);
        $entityManager->flush();
      
     

        return $this->redirectToRoute('categories'); 
    }

    public function updroit($id, $d): Response
    {
         $entityManager = $this->getDoctrine()->getManager();
 
        $dd = $entityManager->getRepository(Users::class)->find($id);
            
        if ($d == 2)
        {
        $dr = 5;
        }
        if ($d == 5)
        {
        $dr = 2;
        }   
      
        $dd->setDroit($dr);
       $entityManager->flush();
      

        return $this->redirectToRoute('menbres'); 
    }           
    

    public function messages(): Response
    {
        $messages = $this->getDoctrine()->getRepository(Mail::class)->findAll();
       
        return $this->render('message.html.twig',[
           'title'=>'messagerie',
           'keywords'=>'messagerie,'.$this->keywords,
          'description'=>'messagerie'.$this->description,
          'droit'=>$this->droit,
          'iduser'=>$this->iduser,
          'messages'=>$messages
          ]);
        
    }

    public function menbres(): Response
    {
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        
        return $this->render('menbre.html.twig',[
            'title'=>'menbre',
            'keywords'=>'menbre,'.$this->keywords,
            'description'=>'les menbres '.$this->description,
            'droit'=>$this->droit,
            'iduser'=>$this->iduser,
            'users'=>$users
            ]);
        
    }

    public function suggestion(Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('suggestion.html.twig',[
            'title'=>'suggestion',
            'keywords'=>'suugestion,'.$this->keywords,
            'description'=>'suugestion de nouveaux streetArt'.$this->description,
            'categorie'=>$categories,
            'e'=>$request->get('e'),
            'iduser'=>$this->iduser,
            'droit'=>$this->droit
            ]);
        
    }

    public function streets(): Response
    {
        //$streetsAll= $this->getDoctrine()->getRepository(Street::class)->findAll();
        //$streets = $this->getDoctrine()->getRepository(Street::class)->findBy(['idUser'=>$this->iduser]);

        $conn = $this->getDoctrine()->getConnection();

        $sql = "SELECT
        street.id as id,street.name as name ,adresse,photo,description,dateCreation,dateFiche,users.pseudo as pseudo,categorie.name AS categorie,statut.statut
        FROM `street`
        INNER JOIN `users` ON street.id_user = users.id
        INNER JOIN `categorie` ON categorie.id = street.categorie
        INNER JOIN `statut` ON street.statut = statut.id
        ";

        if ($this->droit== 9 ||$this->droit== 5 )
        {
        $streetsAll  = $conn->prepare($sql.'ORDER BY `street`.`dateFiche` DESC;');
        $streetsAll ->execute();
        $sql .= " WHERE users.id = :id ";
        $streets  = $conn->prepare($sql.'ORDER BY `street`.`dateFiche` DESC;');
        $streets ->execute(['id' => $this->iduser]);
        }

        if ($this->droit==2)
        {
            $streetsAll=[];
        $sql .= " WHERE users.id = :id ";
        $streets  = $conn->prepare($sql.'ORDER BY `street`.`dateFiche` DESC;');
        $streets ->execute(['id' => $this->iduser]);

        }
        
                
        return $this->render('street.html.twig',[
            'title'=>'street',
            'keywords'=>'street,'.$this->keywords,
            'description'=>'streetArt'.$this->description,
            'streets'=>$streets,
            'iduser'=>$this->iduser,
            'streetsAll'=>$streetsAll,
            'droit'=>$this->droit
            ]);
        
    }
    public function contact(Request $request): Response
    {
        
        return $this->render('contact.html.twig',[
            'title'=>'contact',
            'keywords'=>'contact,'.$this->keywords,
            'description'=>$this->description,
            'e'=>$request->get('e'),
            'iduser'=>$this->iduser,
            'droit'=>$this->droit
            ]);
        
    }

    public function jeu(): Response
    {
        
        return $this->render('jeu.html.twig',[
            'title'=>'jeu',
            'keywords'=>'jeu,'.$this->keywords,
            'description'=>$this->description,
            'e'=>0,
            'iduser'=>$this->iduser,
            'droit'=>$this->droit
            ]);
        
    }

    public function vote($v,$vot): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

        $vote = new Validation();
        $vote->setIdUser($this->iduser);
        $vote->setIdStreet($v);
        $vote->setChose($vot);

        $entityManager->persist($vote);
     
        $entityManager->flush();

        //return new Response('a vote id '.$vote->getId());
        return $this->redirectToRoute('detail', ['id' => $v]);
        
    }

    public function setcomment(Request $request): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

       $query = new Comment();
       $query->setIdUsers($this->iduser);
       $query->setText($request->request->get('comment'));
       $query->setIdStreet($request->request->get('street'));
  
       $query->setDateAd(new \DateTime("now"));
       $entityManager->persist($query);
     
        $entityManager->flush();

       
        return $this->redirectToRoute('detail', ['id' => $request->request->get('street')]);;
        
    }

    public function uppost(Request $request, $id): Response
    {
      
       $entityManager = $this->getDoctrine()->getManager();

       $query = $entityManager->getRepository(Street::class)->find($id);

       if(!$query){
        return $this->redirectToRoute('noPage');
       }

       $query->setIdUser($request->request->get('user'));
       $query->setName($request->request->get('name'));
       $query->setDescription($request->request->get('description'));
       $query->setCategorie($request->request->get('categorie'));
       $query->setAdresse($request->request->get('adresse'));
       $query->setStatut($request->request->get('statut'));
       $query->setValid($request->request->get('valid'));

      $query->setLongitude($request->request->get('longitude'));
      $query->setLatitude($request->request->get('latitude'));
      
      $query->setDatecreation($request->request->get('datecreation'));
     
     
        $entityManager->flush();

       
        return $this->redirectToRoute('detail', ['id' => $query->getId()]);;
        
    }

    public function setpost(Request $request): Response
    {
      
       $entityManager = $this->getDoctrine()->getManager();

       $query = new street();
        $name=null; 
        $imag=null;
       if(!$query){
        return $this->redirectToRoute('noPage');
       }
//dd($request);
       if(!empty($request->request->get('image')))
       {
       // traitement de l'image
           $extensions=mime_content_type($request->request->get('image'));
           $extension = substr($extensions, 6);
           $mime = substr($extensions,0,-4);
           $photo=uniqid() . '.' . $extension;
           $img = str_replace('data:'.$extensions.';base64,', '', $request->request->get('image'));

           $img = str_replace(' ', '+', $img);
           $data = base64_decode($img);
           
           // pas de data annulation image
           if (!$data)
           {
            $imag = null;
           }

           // verification si ces une image
           if (empty($mime) || strpos($mime, 'image/') !== 0)
           {
            $imag = null;
           }

           // verification si bon format image autorise
           if (!in_array($extension, ['png', 'gif', 'jpeg']))
           {
            $imag = null;
           }

       if (in_array($extension, ['png', 'gif', 'jpeg']))
       {
              
         // enregistre des donnee image
        file_put_contents('./uploads/'.$photo, $data);
        // tranfert de l'image
        $imag=array('tmp_name'=>'./uploads/'.$photo,'name'=>$photo);

           if ($imag)
           {
           $name = transfertImage($imag);
           }
          
       }

       }
      

       if (!$request->request->get('categorie'))
       {
        $categorie = 1;
       } else {

        $categorie = $request->request->get('categorie');
       }
// recuperation gps si pas entrer dans le formullaire
   if (!$request->request->get('longitude') || !$request->request->get('latitude'))
   {
       $adresse=str_replace(' ', '+',$request->request->get('adresse'));
       $urls='https://api-adresse.data.gouv.fr/search/?q='.$adresse.'&lat=45.75&lon=4.85';
       $result = file_get_contents($urls);
       $json = json_decode($result, true);

       if($json['features'])
       {
       $latitude=$json['features'][0]['geometry']['coordinates'][1];
       $longitude=$json['features'][0]['geometry']['coordinates'][0];

       } else {
       
        return $this->redirectToRoute('suggestion',['e'=>2]);
       }
   }else{

    $latitude = $request->request->get('latitude');
    $longitude = $request->request->get('longitude');
   }
       $query->setIdUser($this->iduser);
       $query->setPhoto($name);
       $query->setDescription($request->request->get('description'));
       $query->setCategorie($categorie);
       $query->setAdresse($request->request->get('adresse'));
       $query->setStatut(1);
       $query->setValid($request->request->get('valid'));

      $query->setLongitude($longitude);
      $query->setLatitude($latitude);
       $query->setDatefiche(new \DateTime("now"));
      $query->setDatecreation($request->request->get('datecreation'));
     
      $entityManager->persist($query);
        $entityManager->flush();

       
        return $this->redirectToRoute('detail', ['id' => $query->getId()]);;
        
    }
    public function delpost($id): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

       $query= $entityManager->getRepository(Street::class)->find($id);
       $idp= $query->getIdStreet(); 
      $entityManager->remove($query);

      $query= $entityManager->getRepository(Comment::class)->find($idp);
      $entityManager->remove($query);

       $entityManager->flush();
       return $this->redirectToRoute('galerie');
        
    }

    public function delmessage($id): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

       $query= $entityManager->getRepository(Mail::class)->find($id);
     
      $entityManager->remove($query);

  
       $entityManager->flush();
       return $this->redirectToRoute('messages');
        
    }

    public function delcategorie(Request $request): Response
    {
     
       
       $entityManager = $this->getDoctrine()->getManager();

       $query= $entityManager->getRepository(Categorie::class)->find($request->get('categorieDel'));
     
      $entityManager->remove($query);
  
       $entityManager->flush();
       return $this->redirectToRoute('categories');
        
    }

    public function delmenbre($id): Response
    {
     
       
       $entityManager = $this->getDoctrine()->getManager();

       $query= $entityManager->getRepository(Users::class)->find($id);
     
      $entityManager->remove($query);
  
       $entityManager->flush();
       return $this->redirectToRoute('menbres');
        
    }

    public function delcomment($id): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

       
       $query = $entityManager->getRepository(Comment::class)->find($id);
            
        $idp = $query->getIdStreet(); 
      
        
        if (($idp))
        { 
            $entityManager->remove($query);
            $entityManager->flush();
            
            return $this->redirectToRoute('detail', ['id' => $idp]);
        } else {
            return $this->redirectToRoute('noPage');
            
        }
        
    }

    public function archive($id): Response
    {
       
       $entityManager = $this->getDoctrine()->getManager();

       
       $query = $entityManager->getRepository(Street::class)->find($id);
      
       if (!$query) {
        return $this->redirectToRoute('noPage');
    }    
       $query -> setStatut(1);  
       
       $entityManager->flush();
        //return new Response('a vote id '.$query->getIdStreet());
        return $this->redirectToRoute('detail', ['id' => $id]);
        
    }


    public function detail($id): Response
    {
        
       if (is_numeric($id))
        {
       
        $street = $this->getDoctrine()->getRepository(Street::class);
        $street = $street->find($id);
         } else {
     
       $street = $this->getDoctrine()->getRepository(Street::class);
       $street = $street->findOneBy(['name'=>$id]);
       $id = $street->getId();
        }
            

        if ($street)
        {
           
            $streets=[];
            $conn = $this->getDoctrine()->getConnection();
            $sql = "SELECT comment.id,text,date_ad as dateAd,comment.id_street as idStreet, name, pseudo, comment.id_users as idusers FROM `comment` INNER JOIN `users` ON comment.id_users = users.id INNER JOIN `street` ON comment.id_Street = street.id WHERE id_street=:id ORDER BY `comment`.`date_ad` DESC ;";
            $comments  = $conn->prepare($sql);
            $comments ->execute(['id' => $id]);
         
  
        $streets['maps'] =lienGoogle($street->getAdresse());
        //$idstatut=$street->getStatut();
        //$idUser=$street->getidUser();
        $vote = $this->getDoctrine()->getRepository(Validation::class)->findOneBy(['idUser'=>$this->iduser,'idStreet'=>$id]);
        //$streets['statut'] = $this->getDoctrine()->getRepository(Statut::class)->find($idstatut)->getStatut();
       
        
        //$streets['pseudo'] = $this->getDoctrine()->getRepository(Users::class)->find($idUser)->getPseudo();          

        if ($street->getName() )
        {
            $name=$street->getName();
        } else {
            $name=$street->getId();   
        }
        
 
        return $this->render('detail.html.twig',[
            'title'=>'détail du street '.$name.'',
            'keywords'=>'détail, '.$name.', '.$this->keywords,
            'description'=>'détail sur street '.$name.' '.$this->description,
            'streets'=>$streets,
            'street'=>$street,
            'comments'=>$comments,
            'vote'=>$vote,
            'iduser'=>$this->iduser,
            'droit'=>$this->droit,
            ]);
        } 
        
        else
        {
            return $this->redirectToRoute('noPage');
        }
        if (!$street) {
            return $this->redirectToRoute('noPage');
        }
       
         
    }


    public function noPage(): Response
    {
       
        return $this->render('404.html.twig',[
            'title'=>'404',
            'keywords'=>'404'.$this->keywords,
            'description'=>$this->description,
            'droit'=>$this->droit
            ]);
        
    }
}