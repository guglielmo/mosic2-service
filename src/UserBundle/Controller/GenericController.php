<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class GenericController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/firmataritipo", name="firmataritipo")
     * @Method("GET")
     */
    public function firmataritipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:FirmatariTipo');
        $firmataritipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($firmataritipo),
            "data" => json_decode($this->serialize($firmataritipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/cipeesiti", name="cipeesiti")
     * @Method("GET")
     */
    public function cipeesitiAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeEsiti');
        $cipeesiti = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeesiti),
            "data" => json_decode($this->serialize($cipeesiti)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/cipeesititipo", name="cipeesititipo")
     * @Method("GET")
     */
    public function cipeesititipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeEsitiTipo');
        $cipeesititipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeesititipo),
            "data" => json_decode($this->serialize($cipeesititipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/cipeargomentitipo", name="cipeargomentitipo")
     * @Method("GET")
     */
    public function cipeargomentitipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeArgomentiTipo');
        $cipeargomentitipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeargomentitipo),
            "data" => json_decode($this->serialize($cipeargomentitipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/monitor", name="monitor")
     * @Method("GET")
     */
    public function monitorAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->findAll();

        $serialize = json_decode($this->serialize($delibere));
        //$serialize = $this->formatDateJsonArrayCustom($serialize,array("data"));

        $arrayDelibere = array();
        foreach ($serialize as $item) {
            if (isset($arrayDelibere[$item->data]['num'])) {
                $arrayDelibere[$item->data]['num'] = $arrayDelibere[$item->data]['num'] + 1;
            } else {
                $arrayDelibere[$item->data]['num'] = 1;
            }
        }



        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibere,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }










    /**
     * @Route("/firmataritipo", name="firmataritipoOptions")
     * @Method("OPTIONS")
     */
    public function firmataritipoOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeesiti", name="cipeesitiOptions")
     * @Method("OPTIONS")
     */
    public function cipeesitiOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeesititipo", name="cipeesititipoOptions")
     * @Method("OPTIONS")
     */
    public function cipeesititipoOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeargomentitipo", name="cipeargomentitipoOptions")
     * @Method("OPTIONS")
     */
    public function cipeargomentitipoOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

}
