<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @Assert\UniqueEntity("email")
 * @Assert\UniqueEntity("username")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class User extends BaseUser implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection")
     */
    protected $tasks;

    /**
     * @var string The username of the author.
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     */
    protected $username;

    /**
     * @var string The email of the user.
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     */
    protected $email;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tasks = new ArrayCollection();
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
     * @return Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }


    /**
     * @param Task $task
     * @return bool
     */
    public function hasTask(Task $task)
    {
        return $this->tasks->contains($task);
    }

    /**
     * @param Task $task
     *
     * @return $this
     */
    public function addTask(Task $task)
    {
        if(!$this->hasTask($task)) {
            $task->setUser($this);
            $this->tasks->add($task);
        }

        return $this;
    }

    /**
     * @param Task $task
     * @return User
     */
    public function removeTask(Task $task)
    {
        if ($this->hasTask($task)) {
            $this->tasks->removeElement($task);
        }
        return $this;
    }

    /**
     * @return string
     */
    function jsonSerialize()
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'tasks' => $this->tasks,
        ];
    }
}

