<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Bacheca;
use UserBundle\Entity\LastUpdates;



class BachecaController extends Controller {
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Bacheca",
     *   description="Tutte le Api della Bacheca"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/bacheca",
     *     summary="Bacheca",
     *     tags={"Bacheca"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":147,"limit":"99999","offset":0,"data":{{dipe: 15,mef: 15,firme: 7,cc: 30,gu: 10,anno: "2022"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/bacheca", name="bacheca")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_BACHECA')")
     */
    public function bachecaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('UserBundle:Bacheca');
        $bacheca = $repository->findOneById(1); //sempre e solo id = 1

        if ($bacheca == null) { //se non esiste la creiamo (per la prima volta)
            $bacheca = new Bacheca();
            $bacheca->setDipe(15);
            $bacheca->setMef(15);
            $bacheca->setFirme(7);
            $bacheca->setCc(30);
            $bacheca->setGu(10);
            $bacheca->setAnno(2022);

            $em->persist($bacheca);
            $em->flush(); //esegue l'update

            //$response_array = array("error" =>  ["code" => 409, "message" => "Bacheca non inizializzata"]);
            //$response = new Response(json_encode($response_array), 409);
            //return $this->setBaseHeaders($response);
        }


        //print_r($bacheca);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "data" => json_decode($this->serialize($bacheca)),
        );
				
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/bacheca/1", name="bacheca_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_BACHECA')")
     */
    public function bachecaUpdateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["dipe", "mef", "firme", "cc", "gu", "anno"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Bacheca');
        $bacheca = $repository->findOneById(1); //sempre e solo id = 1
        if ($bacheca == null) { //se non esiste, lo creiamo (per la prima volta)
            $bacheca = new Bacheca();
            $bacheca->setDipe(15);
            $bacheca->setMef(15);
            $bacheca->setFirme(7);
            $bacheca->setCc(30);
            $bacheca->setGu(10);
            $bacheca->setAnno(2022);
        } else {
            $bacheca->setDipe($data->dipe);
            $bacheca->setMef($data->mef);
            $bacheca->setFirme($data->firme);
            $bacheca->setCc($data->cc);
            $bacheca->setGu($data->gu);
            $bacheca->setAnno($data->anno);
        }

        $em->persist($bacheca);
        $em->flush(); //esegue l'update

        $response_array = array(
            "response" => Response::HTTP_OK,
            "data" => json_decode($this->serialize($bacheca)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }




		
    /**
     * @Route("/bacheca", name="bacheca_options")
     * @Method("OPTIONS")
     */
    public function bachecaOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/bacheca/{id2}", name="bacheca_item_options")
     * @Method("OPTIONS")
     */
    public function bachecaItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
