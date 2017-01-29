<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Image;
use AppBundle\Upload\Uploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageEventListener
 * @package AppBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ImageEventListener
{
    /**
     * @var Uploader
     */
    private $uploader;


    /**
     * Constructor.
     *
     * @param Uploader $uploader
     */
    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Pre persist event handler.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update event handler.
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Post remove event handler.
     *
     * @param PreUpdateEventArgs $args
     */
    public function postRemove(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    /**
     * Post load event handler.
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Check if the entity is an instance of Image, and abort if not
        if (!$entity instanceof Image) {
            return;
        }

        $fileName = $entity->getFile();

        // Transforms the file name into a File object
        $entity->setFile($this->uploader->loadFile($fileName));
    }

    /**
     * Uploads the entity's file.
     *
     * @param mixed $entity
     */
    private function uploadFile($entity)
    {
        // Check if the entity is an instance of Image, and abort if not
        if (!$entity instanceof Image) {
            return;
        }

        // Only deals with ploaded file object
        $file = $entity->getFile();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);

        $entity->setFile($fileName);
    }

    /**
     * Removes the entity's file.
     *
     * @param mixed $entity
     */
    private function removeFile($entity)
    {
        // Check if the entity is an instance of Image, and abort if not
        if (!$entity instanceof Image) {
            return;
        }

        $file = $entity->getFile();
        if (!$file instanceof File) {
            return;
        }

        $this->uploader->remove($file);
    }
}
