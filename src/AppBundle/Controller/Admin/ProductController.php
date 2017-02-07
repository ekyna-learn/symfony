<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('AppBundle:Admin/Product:index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Creates a new product entity.
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this
            ->createForm(ProductType::class, $product)
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-sm btn-primary',
                ],
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('app_admin_product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('AppBundle:Admin/Product:new.html.twig', [
            'product' => $product,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a product entity.
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('AppBundle:Admin/Product:show.html.twig', [
            'product'     => $product,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing product entity.
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this
            ->createForm('AppBundle\Form\ProductType', $product)
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-sm btn-primary',
                ],
            ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('app_admin_product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('AppBundle:Admin/Product:edit.html.twig', [
            'product'     => $product,
            'form'        => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a product entity.
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('app_admin_product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_product_delete', ['id' => $product->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}