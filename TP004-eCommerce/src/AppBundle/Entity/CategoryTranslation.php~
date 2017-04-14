<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * CategoryTranslation
 *
 * @ORM\Table(name="category_translation")
 * @ORM\Entity()
 */
class CategoryTranslation
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
     * @return CategoryTranslation
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
