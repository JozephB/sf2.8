<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm(ContactType::class, new Contact(), array('em' => $em));


        $form->handleRequest($request);

        if ( $request->isXmlHttpRequest() ) {

            if (!$form->isValid()) {
                $errors =  array(
                        'result'  => 0,
                        'message' => 'Invalid form',
                        'data'    => $this->getErrorMessages($form)
                      );
                return new JsonResponse(array($errors), 400 );
            }

            $data = $form->getData();
            //var_dump($data->getFirstName());
            $data = $em->getRepository('AppBundle:Contact')->findAll();

            foreach ($data as $contact){
                /* $response['data'][] = array(
                     'first_name' => $contact->getFirstName(),
                     'last_name'  => $contact->getLastName(),
                     'email'      => $contact->getEmail(),
                     'subject'    => $contact->getSubject(),
                 );*/
                $response[] = array(
                    $contact->getFirstName(),
                    $contact->getLastName(),
                    $contact->getEmail(),
                    $contact->getSubject(),
                );
            }

            return new Response(json_encode($response), 200, array('Content-Type' => 'application/json'));


        }

        $data = $em->getRepository('AppBundle:Contact')->findAll();

        foreach ($data as $contact){
           /* $response['data'][] = array(
                'first_name' => $contact->getFirstName(),
                'last_name'  => $contact->getLastName(),
                'email'      => $contact->getEmail(),
                'subject'    => $contact->getSubject(),
            );*/
            $response[] = array(
                $contact->getFirstName(),
                $contact->getLastName(),
                $contact->getEmail(),
                $contact->getSubject(),
            );
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
            'data' => json_encode($response)
        ));

    }

    /**
     * @Route("/intM", name="intM")
     */
    public function indexIntM()
    {

        $em   = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:Contact')->findAll();

        foreach ($data as $contact){
            $response['data'][] = array(
                'first_name' => $contact->getFirstName(),
                'last_name'  => $contact->getLastName(),
                'email'      => $contact->getEmail(),
                'subject'    => $contact->getSubject(),
            );
        }
        return $this->render('default/index.html.twig', array(
            'data' => json_encode($response),
        ));
    }

    // Generate an array contains a key -> value with the errors where the key is the name of the form field
    protected function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

         foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

}
