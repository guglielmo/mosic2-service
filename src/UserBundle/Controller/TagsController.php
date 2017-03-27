<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Tags;
use UserBundle\Entity\LastUpdates;



class TagsController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/tags", name="tags")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_TAGS')")
     */
    public function tagsAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Tags');
        $tags = $repository->findAll();
        
        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($tags),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($tags)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/tags/{id}", name="tags_item")
     * @Method("GET")
     */
    public function tagsItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Tags');
        $tag = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($tag),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($tag)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/tags", name="tags_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_TAGS')")
     */
    public function tagsItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Tags');
        $tag = $repository->findOneByDenominazione($data->denominazione);
        //controllo se già esiste il tag
        if ($tag) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il tag ".$data->denominazione." è già utilizzato"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $tag_new = new Tags();
        $tag_new->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($tag_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($tag_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/tags/{id}", name="tags_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_TAGS')")
     */
    public function tagsItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Tags');
        $tags = $repository->findOneById($data->id);

        $tags->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($tags), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/tags/{id}", name="tags_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_TAGS')")
     */
    public function tagsItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Tags');
        $tags = $repository->findOneById($id);

        $repository_rel_fascicoli = $em->getRepository('UserBundle:RelTagsFascicoli');
        $relTagsFascicoli_delete = $repository_rel_fascicoli->findByIdTags($id);//ricavo tutte le relazioni con l'id del registro

        $repository_rel_registri = $em->getRepository('UserBundle:RelTagsRegistri');
        $relTagsRegistri_delete = $repository_rel_registri->findByIdTags($id);//ricavo tutte le relazioni con l'id del registro


        if ($relTagsFascicoli_delete || $relTagsRegistri_delete) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il tags non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($tags); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($tags), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }
    
    
    
    
    


		
    /**
     * @Route("/tags", name="tags_options")
     * @Method("OPTIONS")
     */
    public function tagsOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/tags/{id2}", name="tags_item_options")
     * @Method("OPTIONS")
     */
    public function tagsItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
