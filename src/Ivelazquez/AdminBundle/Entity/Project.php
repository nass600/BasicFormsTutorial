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
   	 * @ORM\Column(name="finish_date", type="datetime", nullable=true)
   	 * @var \DateTime
   	 */
    protected $finishDate;

    /**
   	 * @ORM\Column(name="status", type="string", length=32, nullable=true)
   	 * @var string
   	 */
    protected $status;

    /**
   	 * @ORM\Column(name="country", type="string", length=2, nullable=true)
   	 * @var string
   	 */
    protected $country;

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

    /**
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * @param \DateTime $finishDate
     *
     * @return Project
     */
    public function setFinishDate($finishDate)
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Project
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Project
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}
