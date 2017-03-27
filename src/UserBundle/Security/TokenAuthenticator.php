<?php

namespace UserBundle\Security;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;

    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }

    public function getCredentials(Request $request)
    {
			
//				if(!$request->headers->has('Authorization')) {
//						echo "occhio la variabile Authorization non � presente ";
//            return;
//        }
				//echo $request->headers->get('Authorization');
       // $request->headers->set('Authorization', 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InRpemlvIiwiZXhwIjoiMTQ4MzYxNDY1NSIsImlhdCI6IjE0ODM2MTEwNTUifQ.si6pW_9Q3gfPqtdGZwLdnnaphbjcZ7HmPUkxSbK_rsjqNpYKKi6e11w4qaVyk5mk7MZa-Q3892xRB2JQhim65lSaG4SEOXrMJudwB81B-OXmei_NsxB2jTI7VqsUxUELTH1jz9K8CSpqxcgtpLQJCNBqpxITCBGkkjL4gY9McVn5NJ24NRvHyR1ayFrDPGZWpN2T78emIFoVSlDFfAzTqfrrsoDUTyQCx5CEG7Esx32csYgtlw1xikcvG5UJfoi0wcwvL9HEt9l9JhYqKNB1N-phP0zz_jietTLkhwtzGfZDuTpxuUsd6AukoXCEexxcOlVmEhmnzSpexWKxoUvP5jErfApahc5Jqwt07QD9iM9nOAOLnJbKspDLleMWPqZB3pUGt6zh183AW04GDE1r3gWLM1cHdZqaQE_6-9vm1etVKG47vd9OWHPQ5nyH5EYhHjmCRQbEagvw6taDY3ecwJ1Wy1OawofwZoKMZLF0QYHHa79xc8fQ9seQCRsZvuJv8GfGODfCRxN2KxVnUPewdf6r9XHKy-fv5ikKFr0GCalBb-DMb5cMeCL7SiJxrvJezc_W9FCwpMVBZKjQ5GrOVLG-_N-ib62P1LrfihUowo1rhMzxhyKZT9t4NS7igHOjwnXvxsy_KXNYAOBbyKBEpy57aPOYWt4oV4xpssmVrIU');
				$extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
						//echo "non ho trovato il token ";
            return;
        }
	//			echo $token;

        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)    {
			 
        $data = $this->jwtEncoder->decode($credentials);
																
        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
				//print_r( $data);


        $username = $data['username'];

        //print_r( $this->em
        //    ->getRepository('UserBundle:User')
        //    ->findOneBy(['username' => $username]));
        return $this->em
            ->getRepository('UserBundle:User')
            ->findOneBy(['username' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
						//echo "autenticazione fallitaaaaaa";

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
						//echo "autenticazione eseguitaaaa";

    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
			  
        //$response = new Response('Token is mancante222!', Response::HTTP_UNAUTHORIZED);
        //$response = new Response('Token is mancante!', Response::HTTP_OK);
        //
        
        $response_array = array("error" =>  ["code" => 401, "message" => "Non sei autorizzato ad accedere a questa pagina"]);
        $response = new Response(json_encode($response_array), 401);
        return $response;

        //return $response;
    }
}
