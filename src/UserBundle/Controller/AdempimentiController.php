<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Adempimenti;
use UserBundle\Entity\AdempimentiAmbiti;
use UserBundle\Entity\AdempimentiTipologie;
use UserBundle\Entity\AdempimentiAzioni;
use UserBundle\Entity\AdempimentiAmministrazione;
use UserBundle\Entity\RelAmministrazioniAdempimenti;
use UserBundle\Entity\RelScadenzeAdempimenti;
use UserBundle\Entity\LastUpdates;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;


class AdempimentiController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


    /**
     * @SWG\Tag(
     *   name="Adempimenti",
     *   description="Tutte le Api degli adempimenti"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/adempimenti",
     *     summary="Lista adempimenti",
     *     tags={"Adempimenti"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":280,"limit":"99999","offset":0,"data":{{"id":281,"codice":null,"progressivo":null,"codice_scheda":null,"id_delibere":418,"descrizione":"test date","codice_descrizione":null,"codice_fonte":null,"codice_esito":null,"data_scadenza":1495749600000,"giorni_scadenza":null,"mesi_scadenza":null,"anni_scadenza":null,"vincolo":null,"note":"","utente":null,"data_modifica":1495922400000},{"id":258,"codice":1,"progressivo":1,"codice_scheda":616,"id_delibere":2346,"descrizione":" ","codice_descrizione":0,"codice_fonte":0,"codice_esito":0,"data_scadenza":1495317600000,"giorni_scadenza":1,"mesi_scadenza":2,"anni_scadenza":3,"vincolo":1,"note":"","utente":90138,"data_modifica":1412632800000}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/adempimenti", name="adempimenti")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI')")
     */
    public function adempimentiAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:Adempimenti');
        $adempimenti = $repository->listaAdempimenti($limit, $offset, $sortBy, $sortType);

        //converte i risultati in json
        $serialize = $this->serialize($adempimenti);
        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustom(json_decode($serialize), array('seduta', 'data_scadenza'));

        foreach ($serialize as $item) {
            //AMMINISTRAZIONI
            $repositoryAmministrazioni = $this->getDoctrine()->getRepository('UserBundle:RelAmministrazioniAdempimenti');
            $adempimentiAmministrazioni = $repositoryAmministrazioni->findBy(array("idAdempimenti" => $item->id));

            $adempimentiAmministrazioni = json_decode($this->serialize($adempimentiAmministrazioni));
            $arrayAmministrazioni = array();
            foreach ($adempimentiAmministrazioni as $k) {
                $arrayAmministrazioni[] = $k->id_amministrazioni;
            }
            $item->id_soggetti = $arrayAmministrazioni;

            //SCADENZE
            $repositoryScadenze = $this->getDoctrine()->getRepository('UserBundle:RelScadenzeAdempimenti');
            $adempimentiScadenze = $repositoryScadenze->findBy(array("idAdempimenti" => $item->id));

            $adempimentiScadenze = json_decode($this->serialize($adempimentiScadenze));
            $arrayScadenze = array();
            foreach ($adempimentiScadenze as $k) {
                $arrayScadenze[] = $k->id_scadenze;
            }
            $item->id_scadenze = $arrayScadenze;


            //calcolo scadenze superate
            $currentDate = time();
            if (($item->data_scadenza / 1000) < $currentDate) {
                $numeroDateScadute = 1;
            } else {
                $numeroDateScadute = 0;
            }
            $rangeMesi = 12 / $item->periodicita;
            $totScadenze = $item->periodicita * $item->pluriennalita;
            for ($i = 1; $i <= $totScadenze; $i++) {
                $dataTemp = strtotime('+'.$rangeMesi * $i.' months', ($item->data_scadenza / 1000));
                if ($currentDate > $dataTemp) {
                    $numeroDateScadute = $numeroDateScadute + 1;
                }
            }

            $item->ottemperanza = count($arrayScadenze);
            $item->scadenze_superate = $numeroDateScadute;
            $item->tot_scadenze = $totScadenze;
        }

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($adempimenti),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }




    /**
     * @SWG\Get(
     *     path="/api/adempimenti/{id}",
     *     summary="Singolo adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'adempimento",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":280,"limit":"99999","offset":0,"data":{{"id":281,"codice":null,"progressivo":null,"codice_scheda":null,"id_delibere":418,"descrizione":"test date","codice_descrizione":null,"codice_fonte":null,"codice_esito":null,"data_scadenza":1495749600000,"giorni_scadenza":null,"mesi_scadenza":null,"anni_scadenza":null,"vincolo":null,"note":"","utente":null,"data_modifica":1495922400000},{"id":258,"codice":1,"progressivo":1,"codice_scheda":616,"id_delibere":2346,"descrizione":" ","codice_descrizione":0,"codice_fonte":0,"codice_esito":0,"data_scadenza":1495317600000,"giorni_scadenza":1,"mesi_scadenza":2,"anni_scadenza":3,"vincolo":1,"note":"","utente":90138,"data_modifica":1412632800000}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI')")
     */
    public function adempimentiItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneById($id);

        //AMMINISTRAZIONI
        $repositoryAmministrazioni = $this->getDoctrine()->getRepository('UserBundle:RelAmministrazioniAdempimenti');
        $adempimentiAmministrazioni = $repositoryAmministrazioni->findBy(array("idAdempimenti" => $id));
        $adempimentiAmministrazioni = json_decode($this->serialize($adempimentiAmministrazioni));
        $arrayAmministrazioni = array();
        foreach ($adempimentiAmministrazioni as $item) {
            $arrayAmministrazioni[] = $item->id_amministrazioni;
        }

        //SCADENZE
        $repositoryScadenze = $this->getDoctrine()->getRepository('UserBundle:RelScadenzeAdempimenti');
        $adempimentiScadenze = $repositoryScadenze->findBy(array("idAdempimenti" => $id));
        $adempimentiScadenze = json_decode($this->serialize($adempimentiScadenze));
        $arrayScadenze = array();
        foreach ($adempimentiScadenze as $item) {
            $arrayScadenze[] = $item->id_scadenze;
        }

        //converte i risultati in json
        $serialize = json_decode($this->serialize($adempimento));
        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustom([$serialize], array('seduta', 'data_scadenza'));

        $serialize[0]->id_soggetti = $arrayAmministrazioni;
        $serialize[0]->id_scadenze = $arrayScadenze;

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($adempimento),
            "limit" => 1,
            "offset" => 0,
            "data" => $serialize[0],
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Put(
     *     path="/api/adempimenti/{id}",
     *     summary="Salvataggio adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id dell'adempimento",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Parameter(
     *         name="adempimenti",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
     *         @SWG\Schema(
     *                type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
     *                 	@SWG\Property(property="id_delibere", type="integer"),
     *					@SWG\Property(property="descrizione", type="string"),
     *					@SWG\Property(property="codice_descrizione", type="integer"),
     *					@SWG\Property(property="codice_fonte", type="integer"),
     *					@SWG\Property(property="codice_esito", type="integer"),
     *					@SWG\Property(property="fonti", type="string"),
     *					@SWG\Property(property="data_scadenza", type="string"),
     *					@SWG\Property(property="vincolo", type="integer"),
     *					@SWG\Property(property="note", type="string"),
     *					@SWG\Property(property="codice", type="integer"),
     *					@SWG\Property(property="progressivo", type="integer"),
     *					@SWG\Property(property="codice_scheda", type="integer"),
     *					@SWG\Property(property="giorni_scadenza", type="integer"),
     *					@SWG\Property(property="mesi_scadenza", type="integer"),
     *					@SWG\Property(property="anni_scadenza", type="integer"),
     *					@SWG\Property(property="utente", type="integer"),
     *					@SWG\Property(property="data_modifica", type="string"),
     *					@SWG\Property(property="id_cipe", type="integer")
     *             )
     *            ),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI')")
     */
    public function adempimentiItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["id_delibere", "descrizione", "data_scadenza", "periodicita", "pluriennalita"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneBy(array("id" => $data->id));



        $adempimento->setIstruttore($data->istruttore);
        $adempimento->setNumeroDelibera($data->numero_delibera);
        $adempimento->setAnno($data->anno);
        //$adempimento->setSeduta(new \DateTime($this->zulu_to_rome($data->seduta)));
        $adempimento->setMateria($data->materia);
        $adempimento->setArgomento($data->argomento);
        $adempimento->setFondoNorma($data->fondo_norma);
        $adempimento->setAmbito($data->ambito);
        $adempimento->setLocalizzazione($data->localizzazione);
        $adempimento->setCup($data->cup);
        $adempimento->setRiferimento($data->riferimento);
        $adempimento->setDescrizione($data->descrizione);
        $adempimento->setTipologia($data->tipologia);
        $adempimento->setAzione($data->azione);
        $adempimento->setMancatoAssolvimento($data->mancato_assolvimento);
        $adempimento->setNormeDelibere($data->norme_delibere);
        $adempimento->setDataScadenza(new \DateTime($this->zulu_to_rome($data->data_scadenza)));
        $adempimento->setDestinatario($data->destinatario);
        $adempimento->setStruttura($data->struttura);
        $adempimento->setAdempiuto($data->adempiuto);
        $adempimento->setPeriodicita($data->periodicita);
        $adempimento->setPluriennalita($data->pluriennalita);
        $adempimento->setNote($data->note);
        if (isset($data->superato)) { $adempimento->setSuperato($data->superato); } else {$adempimento->setSuperato(0);}

        // AMMINISTRAZIONI ADEMPIMENTI
        $repository_relAmmAdempimenti = $em->getRepository('UserBundle:RelAmministrazioniAdempimenti');
        $relAmmAdempimenti_delete = $repository_relAmmAdempimenti->findBy(array("idAdempimenti" => $data->id));
        foreach ($relAmmAdempimenti_delete as $relAmmAdempimenti_delete) {
            $em->remove($relAmmAdempimenti_delete);
        }
        foreach ($data->id_soggetti as $item) {
            $relAmmAdempimenti = new RelAmministrazioniAdempimenti();
            $relAmmAdempimenti->setIdAdempimenti($data->id);
            $relAmmAdempimenti->setIdAmministrazioni((int)$item);

            $em->persist($relAmmAdempimenti); //create
        }

        //SCADENZE ADEMPIMENTI
        $repository_relScadenzeAdempimenti = $em->getRepository('UserBundle:RelScadenzeAdempimenti');
        $relScadenzeAdempimenti_delete = $repository_relScadenzeAdempimenti->findBy(array("idAdempimenti" => $data->id));
        foreach ($relScadenzeAdempimenti_delete as $relScadenzeAdempimenti_delete) {
            $em->remove($relScadenzeAdempimenti_delete);
        }
        foreach ($data->id_soggetti as $item) {
            $relScadenzeAdempimenti = new RelScadenzeAdempimenti();
            $relScadenzeAdempimenti->setIdAdempimenti($data->id);
            $relScadenzeAdempimenti->setIdScadenze((int)$item);

            $em->persist($relScadenzeAdempimenti); //create
        }
        

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Post(
     *     path="/api/adempimenti",
     *     summary="Creazione adempimento",
     *     tags={"Adempimenti"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


    /**
     * @Route("/adempimenti", name="adempimenti_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI')")
     */
    public function adempimentiItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["id_cipe","id_delibere", "descrizione", "data_scadenza", "periodicita", "pluriennalita"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new Adempimenti();

        $adempimento->setIstruttore($data->istruttore);
        $adempimento->setNumeroDelibera($data->numero_delibera);
        $adempimento->setAnno($data->anno);
        $adempimento->setSeduta(new \DateTime($this->zulu_to_rome($data->seduta)));
        $adempimento->setMateria($data->materia);
        $adempimento->setArgomento($data->argomento);
        $adempimento->setFondoNorma($data->fondo_norma);
        $adempimento->setAmbito($data->ambito);
        $adempimento->setLocalizzazione($data->localizzazione);
        $adempimento->setCup($data->cup);
        $adempimento->setRiferimento($data->riferimento);
        $adempimento->setDescrizione($data->descrizione);
        $adempimento->setTipologia($data->tipologia);
        $adempimento->setAzione($data->azione);
        $adempimento->setMancatoAssolvimento($data->mancato_assolvimento);
        $adempimento->setNormeDelibere($data->norme_delibere);
        $adempimento->setDataScadenza(new \DateTime($this->zulu_to_rome($data->data_scadenza)));
        $adempimento->setDestinatario($data->destinatario);
        $adempimento->setStruttura($data->struttura);
        $adempimento->setAdempiuto($data->adempiuto);
        $adempimento->setPeriodicita($data->periodicita);
        $adempimento->setPluriennalita($data->pluriennalita);
        $adempimento->setNote($data->note);
        $adempimento->setIdDelibere($data->id_delibere);

        if (isset($data->superato)) { $adempimento->setSuperato($data->superato); } else {$adempimento->setSuperato(0);}

        $em->persist($adempimento);
        $em->flush(); //esegue query

        $id_creato = $adempimento->getId();

        // AMMINISTRAZIONI ADEMPIMENTI
        foreach ($data->id_amministrazioni as $item) {
            $relAmmAdempimenti = new RelAmministrazioniAdempimenti();
            $relAmmAdempimenti->setIdAdempimenti($id_creato);
            $relAmmAdempimenti->setIdAmministrazioni((int)$item);

            $em->persist($relAmmAdempimenti); //create
        }

        // SCADENZE ADEMPIMENTI
        foreach ($data->id_scadenze as $item) {
            $relScadenzeAdempimenti = new RelScadenzeAdempimenti();
            $relScadenzeAdempimenti->setIdAdempimenti($id_creato);
            $relScadenzeAdempimenti->setIdScadenze((int)$item);

            $em->persist($relScadenzeAdempimenti); //create
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update 

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Delete(
     *     path="/api/adempimentu/{id}",
     *     summary="Eliminazione adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'adempimento",
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
     * @Route("/adempimenti/{id}", name="adempimenti_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI')")
     */
    public function adempimentiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }





    /* ######################################## adempimentiAmministrazioni ####################################### */
    /* ########################################################################################################### */

    /**
     * @Route("/adempimenti_soggetti", name="adempimentiAmministrazioni")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_SOGGETTI')")
     */
    public function adempimentiAmministrazioniAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAmministrazione');
        $amministrazioni = $repository->findAll();

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($amministrazioni)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti/{id}", name="adempimentiAmministrazioni_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_SOGGETTI')")
     */
    public function adempimentiAmministrazioniItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAmministrazione');
        $amministrazioni = $repository->findOneById($id);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($amministrazioni),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazioni)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti/{id}", name="adempimentiAmministrazioni_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI_SOGGETTI')")
     */
    public function adempimentiAmministrazioniItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:AdempimentiAmministrazione');
        $adempimento = $repository->findOneBy(array("id" => $data->id));

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_soggetti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti", name="adempimentiAmministrazioni_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI_SOGGETTI')")
     */
    public function adempimentiAmministrazioniItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new AdempimentiAmministrazione();

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_soggetti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti/{id}", name="adempimentiAmministrazioni_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI_SOGGETTI')")
     */
    public function adempimentiAmministrazioniItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:AdempimentiAmministrazione');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_soggetti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /* ############################################ adempimentiAmbiti ############################################ */
    /* ########################################################################################################### */

    /**
     * @Route("/adempimenti_ambiti", name="adempimentiAmbiti")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_AMBITI')")
     */
    public function adempimentiAmbitiAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAmbiti');
        $ambiti = $repository->findAll();

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($ambiti)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti/{id}", name="adempimentiAmbiti_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_AMBITI')")
     */
    public function adempimentiAmbitiItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAmbiti');
        $ambiti = $repository->findOneById($id);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($ambiti),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($ambiti)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti/{id}", name="adempimentiAmbiti_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI_AMBITI')")
     */
    public function adempimentiAmbitiItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:AdempimentiAmbiti');
        $adempimento = $repository->findOneBy(array("id" => $data->id));

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_ambiti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti", name="adempimentiAmbiti_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI_AMBITI')")
     */
    public function adempimentiAmbitiItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new AdempimentiAmbiti();

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_ambiti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti/{id}", name="adempimentiAmbiti_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI_AMBITI')")
     */
    public function adempimentiAmbitiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:AdempimentiAmbiti');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_ambiti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /* ############################################ adempimentiTipologie ######################################### */
    /* ########################################################################################################### */

    /**
     * @Route("/adempimenti_tipologie", name="adempimentiTipologie")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_TIPOLOGIE')")
     */
    public function adempimentiTipologieAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiTipologie');
        $tipologie = $repository->findAll();

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($tipologie)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie/{id}", name="adempimentiTipologie_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_TIPOLOGIE')")
     */
    public function adempimentiTipologieItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiTipologie');
        $tipologie = $repository->findOneById($id);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($tipologie),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($tipologie)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie/{id}", name="adempimentiTipologie_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI_TIPOLOGIE')")
     */
    public function adempimentiTipologieItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:AdempimentiTipologie');
        $adempimento = $repository->findOneBy(array("id" => $data->id));

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_tipologie");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie", name="adempimentiTipologie_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI_TIPOLOGIE')")
     */
    public function adempimentiTipologieItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new AdempimentiTipologie();

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_tipologie");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie/{id}", name="adempimentiTipologie_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI_TIPOLOGIE')")
     */
    public function adempimentiTipologieItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:AdempimentiTipologie');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_tipologie");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /* ############################################ adempimentiAzioni ######################################### */
    /* ######################################################################################################## */

    /**
     * @Route("/adempimenti_azioni", name="adempimentiAzioni")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_AZIONI')")
     */
    public function adempimentiAzioniAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAzioni');
        $azioni = $repository->findAll();

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($azioni)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni/{id}", name="adempimentiAzioni_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_AZIONI')")
     */
    public function adempimentiAzioniItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiAzioni');
        $azioni = $repository->findOneById($id);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($azioni),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($azioni)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni/{id}", name="adempimentiAzioni_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI_AZIONI')")
     */
    public function adempimentiAzioniItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:AdempimentiAzioni');
        $adempimento = $repository->findOneBy(array("id" => $data->id));

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_azioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni", name="adempimentiAzioni_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI_AZIONI')")
     */
    public function adempimentiAzioniItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new AdempimentiAzioni();

        $adempimento->setDenominazione($data->denominazione);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_azioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni/{id}", name="adempimentiAzioni_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI_AZIONI')")
     */
    public function adempimentiAzioniItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:AdempimentiAzioni');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_azioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /* ############################################ adempimentiScadenze######################################## */
    /* ######################################################################################################## */

    /**
     * @Route("/adempimenti_scadenze", name="adempimentiScadenze")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_SCADENZE')")
     */
    public function adempimentiScadenzeAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiScadenze');
        $scadenze = $repository->findAll();

        $scadenze = json_decode($this->serialize($scadenze));
        //formatto le date
        foreach ($scadenze as $item) {
            $item->data = strtotime($this->zulu_to_rome($item->data)) * 1000;
        }

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => $scadenze,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze/{id}", name="adempimentiScadenze_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI_SCADENZE')")
     */
    public function adempimentiScadenzeItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:AdempimentiScadenze');
        $scadenze = $repository->findOneById($id);

        $scadenze = json_decode($this->serialize($scadenze));
        //formatto le date
        $scadenze->data = strtotime($this->zulu_to_rome($scadenze->data)) * 1000;

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($scadenze),
            "limit" => 1,
            "offset" => 0,
            "data" => $scadenze,
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze/{id}", name="adempimentiScadenze_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI_SCADENZE')")
     */
    public function adempimentiScadenzeItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["data"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:AdempimentiScadenze');
        $adempimento = $repository->findOneBy(array("id" => $data->id));

        $adempimento->setData(new \DateTime($this->zulu_to_rome($data->data)));
        $adempimento->setStato($data->stato);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_scadenze");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze", name="adempimentiScadenze_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI_SCADENZE')")
     */
    public function adempimentiScadenzeItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()), ["data"]);
        if ($check != "ok") {
            $response_array = array("error" => ["code" => 409, "message" => "Il campo " . $check . " e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $adempimento = new AdempimentiScadenze();

        $adempimento->setData(new \DateTime($this->zulu_to_rome($data->data)));
        $adempimento->setStato($data->stato);
        $adempimento->setNote($data->note);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_scadenze");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($adempimento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze/{id}", name="adempimentiScadenze_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI_SCADENZE')")
     */
    public function adempimentiScadenzeItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:AdempimentiScadenze');
        $adempimento = $repository->findOneById($id);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti_scadenze");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->remove($adempimento); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    

    /* ##################################################### */
    /* ###################### OPTIONS ###################### */
    /* ##################################################### */


    /**
     * @Route("/adempimenti", name="adempimenti_options")
     * @Method("OPTIONS")
     */
    public function adempimentiOptions(Request $request) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti", name="adempimentiAmministrazioni_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAmministrazioniOptions(Request $request){
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_soggetti/{id}", name="adempimentiAmministrazioni_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAmministrazioniItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti", name="adempimentiAmbiti_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAmbitiOptions(Request $request){
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_ambiti/{id}", name="adempimentiAmbiti_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAmbitiItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie", name="adempimentiTipologie_options")
     * @Method("OPTIONS")
     */
    public function adempimentiTipologieOptions(Request $request){
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_tipologie/{id}", name="adempimentiTipologie_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiTipologieItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni", name="adempimentiAzioni_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAzioniOptions(Request $request){
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_azioni/{id}", name="adempimentiAzioni_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiAzioniItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze", name="adempimentiScadenze_options")
     * @Method("OPTIONS")
     */
    public function adempimentiScadenzeOptions(Request $request){
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/adempimenti_scadenze/{id}", name="adempimentiScadenze_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiScadenzeItemOptions(Request $request, $id) {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


}
