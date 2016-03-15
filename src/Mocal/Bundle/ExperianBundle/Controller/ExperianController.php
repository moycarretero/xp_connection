<?php
namespace Mocal\Bundle\ExperianBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NewsLetter controller.
 *
 */
class ExperianController extends Controller
{

    /**
     * @Route("/newsletter/create", name="createNewsletter")
     * @Method("GET")
     */
    public function createNewsletter()
    {
        $subject = "Envio Ciclico 6";
        $body = "<html><head><title></title></head><body>Esto es una alerta urgente 6</br></body></html>{[opt-out-tablaAPI|13502]}";

        $request = $this->getRequest();
        $experian = $this->get('mocal.experian.manager');

        //$res = $experian->createNewsletter(json_decode($request->getContent()));
        $res = $experian->createNewsletter(array('subject' => $subject, 'body' => $body));

        print_r($res);
        die;
    }

    /**
     * @Route("/newsletter/{newsletterId}/update", name="updateNewsletter")
     * @Method("GET")
     */
    public function updateNewsletter($newsletterId)
    {
        $subject = "Envio Ciclico 6";
        $body = "<html><head><title></title></head><body>Esto es una alerta urgente 6</br></body></html>{[opt-out-tablaAPI|13502]}";

        $request = $this->getRequest();
        $experian = $this->get('mocal.experian.manager');

        //$res = $experian->updateNewsletter(json_decode($request->getContent()));
        $res = $experian->createNewsletter(array('clientId' => $newsletterId, 'subject' => $subject, 'body' => $body));

        print_r($res);
        die;
    }

    /**
     * @Route("/newsletter/{newsletterId}", name="getNewsletter")
     * @Method("GET")
     */
    public function getNewsletter($newsletterId)
    {
        $experian = $this->get('mocal.experian.manager');

        //$res = $experian->getNewsletter("7897");
        $res = $experian->getNewsletter($newsletterId);

        print_r($res);
        die;
    }

    /**
     * @Route("/newsletter/{newsletterId}/audit", name="auditNewsletter")
     * @Method("GET")
     */
    public function auditNewsletter($newsletterId)
    {
        $experian = $this->get('mocal.experian.manager');

        $res = $experian->audit($newsletterId);

        print_r($res);
        die;
    }

    /**
     * @Route("/newsletter/{newsletterId}/proof", name="proofNewsletter")
     * @Method("GET")
     */
    public function proofNewsletter($newsletterId)
    {
        $experian = $this->get('mocal.experian.manager');

        $res = $experian->proof($newsletterId);

        print_r($res);
        die;
    }
}
