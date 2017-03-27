<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class AccessDeniedHandler implements AccessDeniedHandlerInterface {

    use \UserBundle\Helper\ControllerHelper;

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        // ...

        $response_array = array("error" =>  ["code" => 403, "message" => "Non hai i permessi per accedere a questa sezione."]);
        $response = new Response(json_encode($response_array), 403);
        return $this->setBaseHeaders($response);

/*        $response_array = array("error" =>  ["code" => 403, "message" => "eeeeeee"]);
        $response = new Response(json_encode($response_array), 403);
        return $this->setBaseHeaders($response);*/

    }
}
