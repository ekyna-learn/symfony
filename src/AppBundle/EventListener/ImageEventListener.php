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
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
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

        // Only deals with uploaded file object
        $file = $entity->getUpload();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);

        $this->removeFile($entity);

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

        if (null !== $file = $this->uploader->loadFile($entity->getFile())) {
            $this->uploader->remove($file);
        }
    }
}
