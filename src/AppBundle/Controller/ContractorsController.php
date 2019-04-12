<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contractor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ContractorsController extends Controller
{
    /**
     * @Route("/contractors/", name="contractors")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contractors = $em->getRepository('AppBundle:Contractor')->findAll();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $contractors, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("contractors/contractors.html.twig", ['pagination' => $pagination]);
    }


    /**
     * @Route("/contractors/get/{id}", name="getContractor")
     * @Method("GET")
     */
    public function getContractor($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $contractor = $em->getRepository('AppBundle:Contractor')->find($id);

            if (!$contractor) {

                $data = ['No results found' => null];

            } else {

                $data = [
                    'id' => $contractor->getId(),
                    'name' => $contractor->getName(),
                    'nip' => $contractor->getNip(),
                    'address' => $contractor->getAddress()
                ];
            }

            return new JsonResponse([
                'success' => true,
                'data'    => $data
            ]);

        } catch (Exception $exception) {

            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/contractors/new", name="newContractor")
     * @Method("POST")
     */
    public function newContractor(Request $request)
    {
        $name = $request->get('name');
        $nip = $request->get('nip');
        $address = $request->get('address');

        $contractor = new Contractor();
        $contractor->setName($name);
        $contractor->setNip($nip);
        $contractor->setAddress($address);

        $em = $this->getDoctrine()->getManager();
        $em->persist($contractor);
        $em->flush();

        return new Response('It\'s probably been saved', 201);
    }
}
