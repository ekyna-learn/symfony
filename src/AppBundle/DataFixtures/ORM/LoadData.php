<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use AppBundle\Entity\Seo;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class LoadData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadData implements FixtureInterface
{
    /**
     * @var string
     */
    private $directory;

    public function load(ObjectManager $manager)
    {
        $this->directory = realpath(__DIR__ . '/../../Resources/config/fixtures');

        Fixtures::load([
            $this->directory.'/category.yml',
            $this->directory.'/feature.yml',
            $this->directory.'/apple.yml',
            $this->directory.'/samsung.yml',
        ], $manager, [
            'providers' => [$this]
        ]);
    }

    /**
     * Creates the seo entity.
     *
     * @param string $title
     * @param string $description
     *
     * @return Seo
     */
    public function createSeo($title, $description)
    {
        $seo = new Seo();

        $seo
            ->setTitle($title)
            ->setDescription($description);

        return $seo;
    }

    /**
     * Creates the image based on fixtures file name.
     *
     * @param string $file
     * @param string $alt
     *
     * @return Image
     */
    public function createImage($file, $alt)
    {
        $source = $this->directory . '/images/' . $file;
        if (!is_file($source)) {
            throw new \InvalidArgumentException(sprintf('Image file %s not found.', $file));
        }

        $target = sys_get_temp_dir() . '/' . $file;

        if (!copy($source, $target)) {
            throw new \RuntimeException(sprintf('Failed to copy %s image file.', $file));
        }

        $image = new Image();

        $image
            ->setFile(new UploadedFile($target, $file, null, null, null, true)) // Last arg fakes the upload test
            ->setAlt($alt);

        return $image;
    }
}