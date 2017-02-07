<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Feature;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('stock')
            ->add('releasedAt', Type\DateType::class, [
                'years' => $this->getYears(),
            ])
            ->add('seo', SeoType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('c');
                    return $qb
                        ->andWhere($qb->expr()->eq('c.enabled', ':enabled'))
                        ->orderBy('c.title', 'ASC')
                        ->setParameter('enabled', true);
                },
            ])
            ->add('features', EntityType::class, [
                'class' => Feature::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('images', Type\CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    /**
     * Returns the releasedAt year choices.
     *
     * @return array
     */
    private function getYears()
    {
        $years = [];
        $start = date('Y');
        $end = $start - 30;

        for ($y = $start; $y > $end; $y--) {
            $years[] = $y;
        }

        return $years;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
