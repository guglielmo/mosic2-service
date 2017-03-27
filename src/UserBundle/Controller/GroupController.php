<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Group;
use UserBundle\Entity\LastUpdates;



class GroupController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/groups", name="groups")
     * @Method("GET")
     */
    public function groupsAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Group');
        $groups = $repository->findAll();
        
        //print_r($mittente);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($groups),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($groups)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/groups/{id}", name="groups_item")
     * @Method("GET")
     */
    public function groupsItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Group');
        $group = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($group),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($group)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/groups/{id}", name="groups_item_save")
     * @Method("PUT")
     */
    public function groupsItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Group');
        $group = $repository->findOneById($id);


        $group->setCodice($data->codice);
        $group->setName($data->name);
        $group->setRoles([]);
        foreach ($data->roles as $item) {
            $group->addRole($item);
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("groups");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($group), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/groups", name="groups_item_create")
     * @Method("POST")
     */
    public function groupsItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $group = new Group();

        $group->setCodice($data->codice);
        $group->setName($data->name);
        $group->setRoles([]);
        foreach ($data->roles as $item) {
            $group->addRole($item);
        }
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("groups");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($group);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($group), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/groups/{id}", name="groups_item_delete")
     * @Method("DELETE")
     */
    public function groupsItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Group');
        $group = $repository->findOneById($id);

        $repositoryUtenti = $em->getRepository('UserBundle:User');
        $utenti = $repositoryUtenti->findAll();

        $no_delete = 0;
        $pippo = "";
        foreach ($utenti as $item) {
            //$id_group_user = $this->serialize($item->getGroups()[0]->getId());
            $id_group_user = $this->serialize($item->getGroups()[0]);
            $id_group_user = json_decode($id_group_user);

            if ($id_group_user->id == $id) {
                $no_delete = 1;
                continue;
            }
            //$pippo = $pippo . $id_group_user->id . " -- ";
        }


        if ($no_delete) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il gruppo non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("groups");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($group); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($group), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }
    




		
    /**
     * @Route("/groups", name="groups_options")
     * @Method("OPTIONS")
     */
    public function groupsOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/groups/{id2}", name="groups_item_options")
     * @Method("OPTIONS")
     */
    public function groupsItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}