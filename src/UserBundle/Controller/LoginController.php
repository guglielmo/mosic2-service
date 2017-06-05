<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;

class LoginController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/import-utenti", name="import_utenti")
     * @Method("GET")

    public function importUtentiAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:User');

        $utenti = $repository->findAll();

        //converte i risultati in json
        $serialize = $this->serialize($utenti);

        foreach (json_decode($serialize, true) as $item => $value) {
            $rep = $em->getRepository('UserBundle:User');
            $u = $rep->findOneById($value['id']);
            $u->setEnabled(true);

            //mi ricavo l'id per id_ruoli_cipe (DA ESEGUIRE UNA SOLA VOLTA!! poi commentare)
            //$id_ruoli_cipe = $this->convertiIdRuoliCipe($value['id_ruoli_cipe']);
            //$u->setIdRuoliCipe($id_ruoli_cipe);

            //// GESTIONE CONVERSIONE PASSWORD (va eseguito a spezzoni altrimenti non ce la fà..)
            // if ($value['id'] > 100  && $value['id'] <= 13043) {
            // if ($value['id'] > 13043  && $value['id'] <= 90159) {
            //if ($value['id'] > 90159  && $value['id'] <= 90204) {
            if ($value['id'] > 90204) {
                $plainPassword = $value['password'];
                $salt = uniqid(mt_rand());
                $u->setSalt($salt); // Unique salt for user
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($u, $plainPassword); //password criptata


                //echo $plainPassword . " " .  $encoded . "<br>";

                $u->setPassword($encoded);
                print_r($this->serialize($u));


            }

            $em->persist($u);
        }
        $em->flush(); //esegue query

        //creiamo la risposta in json
        //$response = new Response($this->serialize($utenti), Response::HTTP_OK);
        $response = new Response($serialize, Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }*/


	/**
     * @SWG\Tag(
     *   name="Autenticazione",
     *   description="Api per autenticazione"
     * )
     */


    /**
     * @SWG\Post(
     *     path="/api/authenticate",
     *     summary="Autenticazione utente",
     *     tags={"Autenticazione"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="username e password dell'utente",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="email", type="string"),
	 *					@SWG\Property(property="password", type="string")
	 *             )
	 *			),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"total_results":1,"data":{"id":1,"username":"string","firstName":"string","lastName":"string","id_group":"[int]","capabilities":"[string]","token":"string"}}
     *       }
     *     ),
     *     @SWG\Response(response=403, description="Nome utente e/o password inseriti non sono corretti."))
     */


    /**
     * @Route("/authenticate", name="user_login")
     * @Method("POST")
     */
    public function loginAction(Request $request) {
        
        
        $data = json_decode($request->getContent(), true); //converto il json in array

        $userEmail = $data['email'];
        $password = $data['password'];
        
        
        //cerchiamo l'utente con username = $userName
        $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->findOneBy(['email' => $userEmail]);

        if (!$user) {
            $response_array = array("error" =>  ["code" => 403, "message" => "Il nome utente inserito non è corretto."]);
            $response = new Response(json_encode($response_array), 403);
            return $this->setBaseHeaders($response);
            //throw $this->createNotFoundException();
        }
				
//$user = new User();
//$plainPassword = '12345678';
//$salt = uniqid(mt_rand());
//$user->setSalt($salt); // Unique salt for user
//$encoder = $this->container->get('security.password_encoder');
//$encoded = $encoder->encodePassword($user, $plainPassword);
//
//echo 		$salt;
//echo " ||| ";
//echo $encoded;
//exit;
        
        //verifichiamo se la password è corretta per l'utente
        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            $response_array = array("error" =>  ["code" => 403, "message" => "La password inserita non è corretta."]);
            $response = new Response(json_encode($response_array), 403);
            return $this->setBaseHeaders($response);
            throw new BadCredentialsException();
        }

        //genera il token
        $token = $this->getToken($user);
        
        
        // aggiorniamo il token nel db
        $user->setConfirmationToken($token);
        $this->get('fos_user.user_manager')->updateUser($user, false);
        // make more modifications to the database
        $this->getDoctrine()->getManager()->flush();

        //prendo i gruppi a cui appartiene l'utente
        $gruppiAppartenentiUtente = $user->getGroups();
        foreach ($gruppiAppartenentiUtente as $item) {
            $idGruppi[] = $item->getId();
        }

        $response_array = array(
            "total_results" => count($user),
            "data" =>  ['id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'id_group' => $idGruppi,
            'capabilities' => $user->getRoles(),
            'token' => $token]
        );
				
        //creiamo la risposta in json
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
		
		
		
		
		

    /**
     * Returns token for user.
     *
     * @param User $user
     *
     * @return array
     */
    public function getToken(User $user)
    {
        return $this->container->get('lexik_jwt_authentication.encoder')
                ->encode([
                    'username' => $user->getUsername(),
                    'exp' => $this->getTokenExpiryDateTime(),
                ]);
    }

    /**
     * Returns token expiration datetime.
     *
     * @return string Unixtmestamp
     */
    private function getTokenExpiryDateTime()
    {
        $tokenTtl = $this->container->getParameter('lexik_jwt_authentication.token_ttl');
        $now = new \DateTime();
        $now->add(new \DateInterval('PT'.$tokenTtl.'S'));

        return $now->format('U');
    }
}
