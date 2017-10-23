<?php
/**
 * Created by PhpStorm.
 * User: Mirnamiq
 * Date: 10/17/2017
 * Time: 9:59 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ProductEntity;
use Doctrine\ORM\EntityManagerInterface;

class MskProductController extends Controller
{
    /**
     * @Route("/msk/product/", name="msk_product")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(ProductEntity::class)->findBy([
            'is_deleted' => 0
        ]);
        return $this->render('default/MskProduct.html.twig',[
            'products' => $products,
        ]);
    }

    /**
     * @Route("/msk/product/operation/", name="MskProductOperation")
     */
    public function operationAction(Request $req)
    {
        if($req->isXmlHttpRequest())
        {
            if($req->request->has('id') && is_numeric($req->request->get('id')) && (int)$req->request->get('id') >= 0 && $req->request->has('name') && is_string($req->request->get('name')) && trim( $req->request->get('name') ) != "")
            {
                $id = (int)$req->request->get('id');
                $name = trim($req->request->get('name'));
                $em = $this->getDoctrine()->getManager();
                if($id == 0)
                {
                    $product = new ProductEntity();
                    $product->setName($name);
                    $em->persist($product);
                }
                else
                {
                    $product = $em->getRepository(ProductEntity::class)->find($id);
                    $product->setName($name);
                }
                $em->flush();
                $id = $product->getId();
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
     * @Route("/msk/product/delete/", name="MskProductDelete")
     */
    public function deleteAction(Request $req)
    {
        if($req->isXmlHttpRequest())
        {
            if($req->request->has('id') && is_numeric($req->request->get('id')) && (int)$req->request->get('id'))
            {
                $id = (int)$req->request->get('id');
                $em = $this->getDoctrine()->getManager();
                $product = $em->getRepository(ProductEntity::class)->find($id);
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