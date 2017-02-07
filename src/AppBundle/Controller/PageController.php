<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

/**
 * Class PageController
 * @package AppBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Page:index.html.twig');
    }

    /**
     * Contact action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $data = [
            'email' => null,
            'subject' => null,
            'message' => null,
            'copy' => null,
            'service' => null
        ];

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_contact'))
            ->setMethod('POST')
            ->add('email', Type\EmailType::class, [
                'label' => 'Adresse email',
                //'label_format' => 'form.field.%name%', // or '%id%',
                'label_attr' => [
                    'class' => 'text-capitalize',
                ],
            ])
            ->add('subject', Type\TextType::class, [
                'disabled' => false, // False by default, pour désactiver la validation html 5
                'required' => false, // true par défaut
                'data' => 'Par défaut',
                //'empty_data' => 'Vide',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length([
                        'min' => 3,
                        'max' => 16
                    ]),
                ],
            ])
            ->add('message', Type\TextareaType::class)
            ->add('birthday', Type\DateType::class, [
                //'format' => 'dd/MM/yyyy'
            ])
            ->add('copy', Type\CheckboxType::class)
            ->add('service', Type\ChoiceType::class, [
                // Attention: Clé/Valeur inversées version < 2.7
                'choices' => [
                    'Service commercial' => 1,
                    'Service facturation' => 2,
                    'Service technique' => 3,
                ],
                // 'expanded' => true, // False by default, Select => Radio
                // 'multiple' => true, // False by default, Radio => Checkboxes
                'placeholder' => 'Choisissez un service',
            ])
            ->add('attachment', Type\FileType::class, [
                'constraints' => [
                    new Constraints\File([
                        'mimeTypes' => ["application/pdf"],
                        'mimeTypesMessage' => 'Please upload a valid PDF',
                    ]),
                ],
            ])
            ->add('send', Type\SubmitType::class)
            ->getForm();

        $form->handleRequest($request); // Before createView()

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /* Symfony\Component\HttpFoundation\File\UploadedFile */
            $file = $data['attachment'];

            // $fileName = $file->getClientOriginalName();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $dir = $this->getParameter('kernel.root_dir') . '/../var/data';

            // Move the file to the directory
            $file->move($dir, $fileName);
        }
        return $this->render('AppBundle:Page:contact.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }
}
