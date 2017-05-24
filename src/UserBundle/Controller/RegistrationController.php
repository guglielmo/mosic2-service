<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\Form\FormInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\LastUpdates;

class RegistrationController extends BaseController
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/users", name="user_register")
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
				
        $user = $userManager->createUser();
        
        $data = json_decode($request->getContent(), true); //converto il json in array
                    
        $user->setPassword($data['password']);
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $data['password']);

        $user->setEmail($data['eMail']);
        $user->setUsername($data['userName']);
        $user->setEnabled(0);
        $user->setPassword($encoded);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setIdUffici($data['id_uffici']);
        $user->setCessatoServizio($data->cessatoServizio);
        $user->setIp($data['ip']);
        $user->setStazione($data['stazione']);
        $user->setIdRuoliCipe($data['id_ruoli_cipe']);


        //cerco l'utente dall'email
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:User');
        $utente = $repository->findOneByEmail($data['eMail']);
        //controllo se esiste un utente con questa mail
        if ($utente) {
                $response_array = array("error" =>  ["code" => 409, "message" => "L'email ".$data['eMail']." è già utilizzata"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);
        }
				
		//cerco l'utente dalla username
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('UserBundle:User');
        $utente = $repository->findOneByUsername($data['userName']);
        //controllo se esiste un utente con questa username
        if ($utente) {
                $response_array = array("error" =>  ["code" => 409, "message" => "La username ".$data['userName']." è già utilizzata"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);
        }

        //associo l'utente al gruppo Uffici (di default)
        $repository_group = $em->getRepository('UserBundle:Group');
        $gruppo_default = $repository_group->findOneByName('Base');
        $user->addGroup($gruppo_default);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("users");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        //$response = new Response($this->serialize($user), Response::HTTP_CREATED);
        //return $this->setBaseHeaders($response);


        $userManager->updateUser($user);
        
        $this->getDoctrine()->getManager()->flush();
        
        $response = new Response($this->serialize($user), Response::HTTP_CREATED);
        return $this->setBaseHeaders($response);
    }
		
		
    /**
     * @Route("/users", name="users")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UTENTI')")
     */
    public function usersListAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository('UserBundle:User');
        $users = $repository->findAll();
    
        //converte i risultati in json
        $serialize = $this->serialize($users);

        //funzione per formattare le date del json
        $serialize = $this->formatDateTimeJsonCustom($serialize, array('registrationDate'));

        foreach ($serialize as $item) {
            $gruppi = "";
            foreach ($item->groups as $k) {
                $gruppi[] = $k->id;
            }

            $utenti[] = array(
                "id" => $item->id,
                "userName" => $item->username,
                "firstName" => $item->first_name,
                "lastName" => $item->last_name,
                "eMail" => $item->email,
                "id_uffici" => $item->id_uffici,
                "cessatoServizio" => $item->cessato_servizio,
                "ip" => $item->ip,
                "stazione" => $item->stazione,
                "id_ruoli_cipe" => $item->id_ruoli_cipe,
                "registrationDate" => $item->created,
                "id_groups" => $gruppi, //togliere [0] se vogliamo gestire più gruppi
            );
        }
        
				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($users),
            "limit" => count($users),
            "offset" => 0,
            "data" => $utenti,
        );
				
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    
    /**
     * @Route("/users/{id}", name="users_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UTENTI')")
     */
    public function usersItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:User');
        //$user = $repository->schedaUtente($id);
        $user = $repository->findOneBy(["id" => $id]);

        $serialize = json_decode($this->serialize($user));

        foreach ($serialize->groups as $item) {
            $gruppi[] = $item->id;
        }

        $utente = array(
            "id" => $serialize->id,
            "userName" => $serialize->username,
            "firstName" => $serialize->first_name,
            "lastName" => $serialize->last_name,
            "eMail" => $serialize->email,
            "id_uffici" => $serialize->id_uffici,
            "cessatoServizio" => $serialize->cessato_servizio,
            "ip" => $serialize->ip,
            "stazione" => $serialize->stazione,
            "id_ruoli_cipe" => $serialize->id_ruoli_cipe,
            "registrationDate" => $serialize->created,
            "id_groups" => $gruppi[0], //togliere [0] se vogliamo gestire più gruppi
        );

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($user),
            "limit" => 1,
            "offset" => 0,
            "data" => $utente,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    
    
    
    /**
     * @Route("/users/{id}", name="users_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UTENTI')")
     */
    public function usersItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
    
        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:User');
        $user = $repository->findOneBy(["id" => $data->id]);
        
        $user->setUsername($data->userName);
        $user->setFirstName($data->firstName);
        $user->setLastName($data->lastName);
        $user->setEmail($data->eMail);
        $user->setIdUffici($data->id_uffici);
        $user->setCessatoServizio($data->cessatoServizio);
        $user->setIp($data->ip);
        $user->setStazione($data->stazione);
        $user->setIdRuoliCipe($data->id_ruoli_cipe);

        //$user->removeGroup();

        $gruppi = $user->getGroups();
        foreach ($gruppi as $item) {
            $user->removeGroup($item);
        }
        $repositoryGroup = $em->getRepository('UserBundle:Group');
        $gruppo = $repositoryGroup->findOneBy(["id" => (int) $data->id_groups]);
        $user->addGroup($gruppo);



        //cerco l'utente dall'email
        $utente = $repository->findOneByEmail($data->eMail);
        //controllo se esiste un utente con questa mail
        if ($utente && $id != $utente->getId()) {
                $response_array = array("error" =>  ["code" => 409, "message" => "L'email ".$data->eMail." è già utilizzata"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);
        }
		//cerco l'utente dalla username
        $utente = $repository->findOneByUsername($data->userName);
        //controllo se esiste un utente con questa username
        if ($utente && $id != $utente->getId()) {
                $response_array = array("error" =>  ["code" => 409, "message" => "La username ".$data->userName." è già utilizzata"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);
        }

        if ($data->password != "") {
            if ($data->password != $data->repeatPassword) {
                $response_array = array("error" =>  ["code" => 409, "message" => "Le password non coincidono"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);            
            } else {
                $user->setPassword($data->password);
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $data->password);
                $user->setPassword($encoded);   
            }
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("users");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update 
    
        $response = new Response($this->serialize($user), Response::HTTP_OK);
    
        return $this->setBaseHeaders($response);
    }
    
    
    
    /**
     * @Route("/users/{id}", name="users_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UTENTI')")
     */
    public function usersItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:User');
        $user = $repository->findOneById($id);
           
        $em->remove($user); //delete



        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("users");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update 

        $response = new Response($this->serialize($user), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
        
        
        
        
        
		
    /**
     * @Route("/users", name="users_options")
     * @Method("OPTIONS")
     */
    public function usersOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/users/{id2}", name="users_item_options")
     * @Method("OPTIONS")
     */
    public function usersItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }	
		
		
        
        
        
        
        
        
        
        
        
        
        
        
        
		
		
		

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException();
        }

        $form->submit($data);
    }

    /**
     * Returns response in case of invalid request.
     *
     * @param FormInterface $form
     *
     * @return ApiProblemException
     * @todo Make custom response for invalid request
     */
    private function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        throw new BadRequestHttpException($this->serialize($errors));
    }

    /**
     * Returns form errors.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }
            $errors[$key] = $template;
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
