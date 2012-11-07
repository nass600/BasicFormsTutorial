<?php

namespace Ivelazquez\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project")
 */
class Project
{
    /**
   	 * @ORM\Id
   	 * @ORM\Column(type="integer")
   	 * @ORM\GeneratedValue(strategy="AUTO")
   	 * @var integer
   	 */
    protected $id;

    /**
   	 * @ORM\Column(name="title", type="string", length=126)
   	 * @var string
   	 */
    protected $title;

    /**
   	 * @ORM\Column(name="url", type="string", length=255, nullable=true)
   	 * @var string
   	 */
    protected $url;

    /**
   	 * @ORM\Column(name="description", type="text", nullable=true)
   	 * @var string
   	 */
    protected $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Project
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
