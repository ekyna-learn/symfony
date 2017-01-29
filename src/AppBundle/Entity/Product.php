<?php

namespace AppBundle\Entity;

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Product
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Seo
     */
    private $seo;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var ArrayCollection
     */
    private $images;

    /**
     * @var ArrayCollection
     */
    private $features;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $slug;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->seo = new Seo();
        $this->images = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    /**
     * Get the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set seo
     *
     * @param Seo $seo
     *
     * @return Product
     */
    public function setSeo(Seo $seo)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * Get seo
     *
     * @return Seo
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * Set category
     *
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add image
     *
     * @param Image $image
     *
     * @return Product
     */
    public function addImage(Image $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    /**
     * Remove image
     *
     * @param Image $image
     *
     * @return Product
     */
    public function removeImage(Image $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            $image->setProduct(null);
        }

        return $this;
    }

    /**
     * Get images
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add feature
     *
     * @param Feature $feature
     *
     * @return Product
     */
    public function addFeature(Feature $feature)
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
            $feature->addProduct($this);
        }

        return $this;
    }

    /**
     * Remove feature
     *
     * @param Feature $feature
     *
     * @return Product
     */
    public function removeFeature(Feature $feature)
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            $feature->removeProduct($this);
        }

        return $this;
    }

    /**
     * Get features
     *
     * @return ArrayCollection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Pre persist event handler.
     */
    public function onPrePersist()
    {
        $this->updateSlug();
    }

    /**
     * Pre update event handler.
     *
     * @param PreUpdateEventArgs $event
     */
    public function onPreUpdate(PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('title')) {
            $this->updateSlug();
        }
    }

    /**
     * Updates the slug from the title.
     */
    private function updateSlug()
    {
        // Turns 'This is a great product' into 'this-is-a-great-product'
        $this->setSlug(str_replace('_', '-', Transliterator::urlize($this->getTitle())));
    }
}

