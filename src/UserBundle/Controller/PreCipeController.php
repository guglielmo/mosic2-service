<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use UserBundle\Entity\Allegati;
use UserBundle\Entity\PreCipe;
use UserBundle\Entity\PreCipeOdg;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelAllegatiPreCipe;
use Doctrine\ORM\EntityNotFoundException;


class PreCipeController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/precipe", name="precipe")
     * @Method("GET")
     */
    public function precipeAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->listaPrecipe($limit, $offset, $sortBy, $sortType);

        $arrayPrecipe = array();

        foreach ($precipe as $item => $value) {
            $allegatiTLX = "";
            $allegatiAPG = "";
            $allegatiOSS = "";

            $allegati = $repository->getAllegatiByIdPreCipe($value->getId());
            $allegati = json_decode($this->serialize($allegati));
            foreach ($allegati as $i => $v) {
                switch ($v->tipologia){
                    case "TLX":
                        $allegatiTLX[] = $v;
                        break;
                    case "APG":
                        $allegatiAPG[] = $v;
                        break;
                    case "OSS":
                        $allegatiOSS[] = $v;
                        break;
                }
                //print_r($v->tipologia);
            }

            $arrayTemp = json_decode($this->serialize($value));
            $arrayTemp->allegati_TLX = $allegatiTLX;
            $arrayTemp->allegati_APG = $allegatiAPG;
            $arrayTemp->allegati_OSS = $allegatiOSS;
            $arrayPrecipe[] = $arrayTemp;
        }

        //$serialize = json_decode($this->serialize($precipe));

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipe),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $this->formatDateJsonArrayCustom($arrayPrecipe, array('data'))
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/precipe/{id}", name="precipe_item")
     * @Method("GET")
     */
    public function precipeItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $this->getDoctrine()->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findByIdPreCipe($id);

        $repositoryRelRegistriOdg = $this->getDoctrine()->getRepository('UserBundle:RelRegistriOdg');

        foreach ($ordini as $i => $v) {
            $registri_precipe = $repositoryRelRegistriOdg->findByIdOdg($v->getId());

            $registri_precipe = $this->mergeRegistriOdg($this->serialize($registri_precipe));

            //$registri_precipe = json_decode($this->serialize($registri_precipe));
            $arrayTemp = json_decode($this->serialize($v));
            $arrayTemp->id_registri = $registri_precipe;
            $arrayOrdini[] = $arrayTemp;
        }


        $allegati = $repository->getAllegatiByIdPreCipe($id);
        $allegati = json_decode($this->serialize($allegati));
        foreach ($allegati as $i => $v) {
            switch ($v->tipologia){
                case "TLX":
                    $allegatiTLX[] = $v;
                    break;
                case "APG":
                    $allegatiAPG[] = $v;
                    break;
                case "OSS":
                    $allegatiOSS[] = $v;
                    break;
            }
        }

        $precipeTemp = json_decode($this->serialize($precipe));
        $precipeTemp->precipe_odg = $arrayOrdini;
        $precipeTemp->allegati_TLX = $allegatiTLX;
        $precipeTemp->allegati_APG = $allegatiAPG;
        $precipeTemp->allegati_OSS = $allegatiOSS;

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipe),
            "limit" => 1,
            "offset" => 0,
            "data" => $this->formatDateJsonArrayCustom([$precipeTemp], array('data'))[0]
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
    /**
     * @Route("/precipe/{id}", name="precipe_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UFFICI')")
     */
    public function precipeItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($data->id);

        $precipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipe), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/precipe", name="precipe_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_UFFICI')")
     */
    public function precipeItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $precipe = new PreCipe();

        $precipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($precipe);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipe), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

    
    /**
     * @Route("/precipe/{id}", name="precipe_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UFFICI')")
     */
    public function precipeItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $em->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findOneByIdPreCipe($id);
        

        if ($ordini) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il precipe non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            //$em->remove($precipe); //delete
            //$em->flush(); //esegue l'update

            $response = new Response($this->serialize($precipe), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }



    /**
     * @Route("/precipe/{id}/{tipo}/upload", name="uploadPreCipe")
     * @Method("POST")
     */
    public function uploadPreCipeAction(Request $request, $id, $tipo) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);
        $dataPrecipe = $precipe->getData()->format('Y-m-d');


        $path_file = Costanti::URL_ALLEGATI_PRECIPE . "/" . $dataPrecipe . "/".$tipo."/";

        $file = $request->files->get('file');

        $nome_file = $file->getClientOriginalName();
        $nome_file = $this->sostituisciAccenti($nome_file);


        //memorizzo il file nel database
        $allegato = new Allegati();
        $allegato->setData(new \DateTime());
        $allegato->setFile($path_file . $nome_file);

        //$em->persist($allegato);
        //$em->flush(); //esegue query

        $id_allegato_creato = $allegato->getId();

        $allegatoRel = new RelAllegatiPreCipe();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdPrecipe($id);
        $allegatoRel->setTipo($tipo);



        $array = array(
            'id' => $id_allegato_creato,
            'id_precipe' => $id,
            'data' => filemtime($file) * 1000,
            'dimensione' => $file->getClientSize(),
            'nome' => $nome_file,
            'relURI' => $path_file . $nome_file,
            'tipo' => $this->getExtension($file->getMimeType()),
            'mime_tipe' => $file->getMimeType(),
        );

        //se il file è maggiore di 25 MB
        if ($file->getClientSize() > 26214400) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il file e' troppo grande. (max 25 MB)"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }
        //controllo su i tipi di file ammessi
        if(!in_array($file->getMimeType(), array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword'))) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Questo tipo di file non e' permesso."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }


        try {
            //$em->persist($allegatoRel);
            //$em->flush(); //esegue query

            //copio fisicamente il file
            //$file->move($path_file, $nome_file);

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");
    }





    /**
     * @Route("/precipe/{id}/{idallegato}/upload", name="uploadDeletePrecipe")
     * @Method("DELETE")
     */
    public function precipeAllegatiItemDeleteAction(Request $request, $id, $idallegato) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiPreCipe');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idPreCipe' => $id));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idallegato);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();

        if (!$relazione_allegato[0]) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Questo file non e' allegato a questo precipe."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

            $response = new Response($this->serialize($relazione_allegato[0]), Response::HTTP_OK);

            try {
                $em->remove($relazione_allegato[0]); //delete
                $em->flush(); //esegue l'update

                //elimino fisicamente il file
                unlink($file->getFile()); //il path
            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                echo "Exception Found - " . $ex->getMessage() . "<br/>";
            }


            return $this->setBaseHeaders($response);
        }
    }



        
        
        
        
        
    /**
     * @Route("/precipe", name="precipe_options")
     * @Method("OPTIONS")
     */
    public function precipeOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/precipe/{id2}", name="precipe_item_options")
     * @Method("OPTIONS")
     */
    public function precipeItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/precipe/{id}/{tipo}/upload", name="PreCipeUploadDelete_item_options")
     * @Method("OPTIONS")
     */
    public function precipeUploadDeleteItemOptions(Request $request, $id, $tipo) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
