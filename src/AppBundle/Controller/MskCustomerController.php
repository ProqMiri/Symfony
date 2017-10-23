<?php
/**
 * Created by PhpStorm.
 * User: Mirnamiq
 * Date: 10/20/2017
 * Time: 11:08 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Customer;

class MskCustomerController extends Controller
{
    /**
     * @Route("msk/customer/", name="msk_customer")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Customers = $em->getRepository(Customer::class)->findBy(['is_deleted'=>0]);
        return $this->render('default/MskCustomer.html.twig',[
            'Customers'=>$Customers,
        ]);
    }

    /**
     * @Route("/msk/Customer/operation/", name="MskCustomerOperation")
     */
    public function operationAction(Request $req)
    {
        if($req->isXmlHttpRequest())
        {
            if($req->request->has('id') && is_numeric($req->request->get('id')) && (int)$req->request->get('id') >= 0 &&
                $req->request->has('name') && is_string($req->request->get('name')) && trim( $req->request->get('name') ) != "" &&
                $req->request->has('surname') && is_string($req->request->get('surname')) && trim( $req->request->get('surname') ) != "" &&
                $req->request->has('phone') && is_string($req->request->get('phone')) && trim( $req->request->get('phone') ) != ""
            )
            {
                $id = (int)$req->request->get('id');
                $name = trim($req->request->get('name'));
                $surname = trim($req->request->get('surname'));
                $phone = trim($req->request->get('phone'));
                $em = $this->getDoctrine()->getManager();
                if($id == 0)
                {
                    $customer = new Customer();
                    $customer->setName($name);
                    $customer->setSurname($surname);
                    $customer->setPhone($phone);
                    $em->persist($customer);
                }
                else
                {
                    $customer = $em->getRepository(Customer::class)->find($id);
                    $customer->setName($name);
                    $customer->setSurname($surname);
                    $customer->setPhone($phone);
                }
                $em->flush();
                $id = $customer->getId();
                return new JsonResponse(array('status'=>'success', 'id'=>$id));
            }
            else
            {
                return new JsonResponse(array('status'=>'error', 'errorMsg'=>'Parametrlərdə xəta var!'));
            }
        }
        else
        {
            return new JsonResponse(array('status'=>'error', 'errorMsg'=>'Xəta baş verdi.'));
        }
    }

    /**
     * @Route("/msk/Customer/delete/", name="MskCustomerDelete")
     */
    public function deleteAction(Request $req)
    {
        if($req->isXmlHttpRequest())
        {
            if($req->request->has('id') && is_numeric($req->request->get('id')) && (int)$req->request->get('id'))
            {
                $id = (int)$req->request->get('id');
                $em = $this->getDoctrine()->getManager();
                $product = $em->getRepository(Customer::class)->find($id);
                $product->setIsDeleted(1);
                $em->flush();
                return new JsonResponse(array('status'=>'success'));
            }
            else
            {
                return new JsonResponse(array('status'=>'error', 'errorMsg'=>'Parametrlərdə xəta var!'));
            }
        }
        else
        {
            return new JsonResponse(array('status'=>'error', 'errorMsg'=>'Xəta baş verdi.'));
        }
    }
}