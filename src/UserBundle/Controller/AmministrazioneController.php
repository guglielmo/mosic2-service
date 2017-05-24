<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Amministrazione;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelAmministrazioniRegistri;
use UserBundle\Entity\RelAmministrazioniFascicoli;



class AmministrazioneController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/amministrazioni", name="amministrazioni")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_AMMINISTRAZIONI')")
     */
    public function amministrazioneAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findAll();
        
        
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($amministrazione),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($amministrazione)),
        );

        //$response = new Response($this->serialize('example amministrazionei.'), Response::HTTP_OK);
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/amministrazioni/{id}", name="amministrazioni_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($amministrazione),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/amministrazioni", name="amministrazioni_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_AMMINISTRAZIONI')")
     */
    public function amministrazioneItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneByDenominazione($data->denominazione);
        //controllo se già esiste l'amministrazione
        if ($amministrazione) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L amministrazione ".$data->denominazione." è già utilizzata"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }


        $amministrazione_new = new Amministrazione();
        $amministrazione_new->setDenominazione($data->denominazione);
        $amministrazione_new->setCodice("xxx");//lo aggiornerò dopo con l'id dell'elemento creato

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($amministrazione_new);
        $em->flush(); //esegue l'update

        //aggiorno il codice come l'id
        $id_creato = $amministrazione_new->getId();
        $amministrazione_new->setCodice($id_creato);
        $em->persist($amministrazione_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/amministrazioni/{id}", name="amministrazioni_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($data->id);

        $amministrazione->setCodice($data->codice);
        $amministrazione->setDenominazione($data->denominazione);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/amministrazioni/{id}", name="amministrazioni_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($id);

        $repositoryRegistri = $em->getRepository('UserBundle:RelAmministrazioniRegistri');
        $registri = $repositoryRegistri->findOneByIdAmministrazioni($id);

        $repositoryFascicoli = $em->getRepository('UserBundle:RelAmministrazioniFascicoli');
        $fascicoli = $repositoryFascicoli->findOneByIdAmministrazioni($id);

        if ($registri || $fascicoli) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'Amministrazione non e' vuota, impossibile eliminarla."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($amministrazione); //delete

            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($amministrazione), Response::HTTP_OK);

            return $this->setBaseHeaders($response);
        }
    }









    /**
     * @Route("/amministrazioni", name="amministrazione_options")
     * @Method("OPTIONS")
     */
    public function amministrazioneOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/amministrazioni/{id2}", name="amministrazioni_item_options")
     * @Method("OPTIONS")
     */
    public function amministrazioniItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
