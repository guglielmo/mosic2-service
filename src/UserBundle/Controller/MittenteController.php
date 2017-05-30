<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Mittente;
use UserBundle\Entity\LastUpdates;



class MittenteController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/mittenti", name="mittenti")
     * @Method("GET")
     */
    public function mittenteAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Mittente');
        $mittente = $repository->findAll();
        
        //print_r($mittente);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($mittente)),
        );
				
				$response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/mittenti", name="mittenti_item_create")
     * @Method("POST")
     */
    public function mittentiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneByDenominazione($data->denominazione);
        //controllo se già esiste il mittente
        if ($mittente) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il mittente ".$data->denominazione." è già utilizzato"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $mittente_new = new Mittente();
        $mittente_new->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("mittenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($mittente_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/mittenti/{id}", name="mittenti_item")
     * @Method("GET")
     */
    public function mittentiItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($mittente),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/mittenti/{id}", name="mittenti_item_save")
     * @Method("PUT")
     */
    public function mittentiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($data->id);

        $mittente->setDenominazione($data->denominazione);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("mittenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/mittenti/{id}", name="mittenti_item_delete")
     * @Method("DELETE")
     */
    public function mittentiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($id);

        $repositoryRegistri = $em->getRepository('UserBundle:Registri');
        $registri = $repositoryRegistri->findOneByIdTitolari($id);

        $repositoryFascicoli = $em->getRepository('UserBundle:Fascicoli');
        $fascicoli = $repositoryFascicoli->findOneByIdTitolari($id);

        if ($registri || $fascicoli) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il titolario non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


           // $em->remove($titolario); //delete

            //$em->flush(); //esegue l'update

            $response = new Response($this->serialize($titolario), Response::HTTP_OK);

            return $this->setBaseHeaders($response);
        }
    }







		
    /**
     * @Route("/mittenti", name="mittenti_options")
     * @Method("OPTIONS")
     */
    public function mittenteOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/mittenti/{id2}", name="mittenti_item_options")
     * @Method("OPTIONS")
     */
    public function mittentiItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
