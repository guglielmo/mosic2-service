<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\CipeOdg;
use UserBundle\Entity\PreCipe;
use UserBundle\Entity\LastUpdates;


class CipeOdgController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	
    /**
     * @SWG\Tag(
     *   name="Cipe OdG",
     *   description="Cancellazione dell'Ordine del Giorno di un Cipe"
     * )
     */


    /**
     * @Route("/cipeodg", name="cipeodg")
     * @Method("GET")
     */
    public function cipeodgAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeOdg');
        $cipeodg = $repository->listaCipeOdg($limit, $offset, $sortBy, $sortType);
        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeodg),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($cipeodg)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/cipeodg/{id}", name="cipeodg_item")
     * @Method("GET")
     */
    public function cipeodgItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeOdg');
        $cipeodg = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeodg),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($cipeodg)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/cipeodg/{id}", name="cipeodg_item_save")
     * @Method("PUT")
     */
    public function cipeodgItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:CipeOdg');
        $cipeodg = $repository->findOneById($data->id);

        $cipeodg->setIdPrecipe($data->id_precipe);
        $cipeodg->setProgressivo($data->progressivo);
        $cipeodg->setIdTitolari($data->id_titolari);
        $cipeodg->setIdFascicoli($data->id_fascicoli);
        $cipeodg->setIdArgomenti($data->id_argomenti);
        $cipeodg->setIdUffici($data->id_uffici);
        $cipeodg->setNumeroOdg($data->numero_odg);
        $cipeodg->setDenominazione($data->denominazione);
        $cipeodg->setRisultanza($data->risultanza);
        $cipeodg->setAnnotazioni($data->annotazioni);
        $cipeodg->setStato($data->stato);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($cipeodg), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/cipeodg", name="cipeodg_item_create")
     * @Method("POST")
     */
    public function cipeodgItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $cipeodg = new CipeOdg();

        $cipeodg->setIdPrecipe($data->id_precipe);
        $cipeodg->setProgressivo($data->progressivo);
        $cipeodg->setIdTitolari($data->id_titolari);
        $cipeodg->setIdFascicoli($data->id_fascicoli);
        $cipeodg->setIdArgomenti($data->id_argomenti);
        $cipeodg->setIdUffici($data->id_uffici);
        $cipeodg->setNumeroOdg($data->numero_odg);
        $cipeodg->setDenominazione($data->denominazione);
        $cipeodg->setRisultanza($data->risultanza);
        $cipeodg->setAnnotazioni($data->annotazioni);
        $cipeodg->setStato($data->stato);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($cipeodg);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($cipeodg), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Delete(
     *     path="/api/cipeodg/{id}",
     *     summary="Eliminazione OdG di un Cipe",
     *     tags={"Cipe OdG"},
     *     operationId="idCipeOdg",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'OdG",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/cipeodg/{id}", name="cipeodg_item_delete")
     * @Method("DELETE")
     */
    public function cipeodgItemDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:CipeOdg');
        $cipeodg = $repository->findOneById($id);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->remove($cipeodg); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($cipeodg), Response::HTTP_OK);
        return $this->setBaseHeaders($response);

    }


    /**
     * @Route("/cipeodg", name="cipeodg_options")
     * @Method("OPTIONS")
     */
    public function cipeodgOptions(Request $request)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeodg/{id2}", name="cipeodg_item_options")
     * @Method("OPTIONS")
     */
    public function cipeodgItemOptions(Request $request, $id2)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
