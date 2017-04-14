<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SubCategoryTranslation
 *
 * @ORM\Table(name="subcategory_translation")
 * @ORM\Entity()
 */
class SubCategoryTranslation
{

    use ORMBehaviors\Sluggable\Sluggable, ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SubCategoryTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * get sluggableFields
     *
     * @return array
     */
    public function getSluggableFields()
    {
        return [ 'name' ];
    }
}
