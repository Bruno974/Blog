<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 03/05/2017
 * Time: 22:05
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  /*  /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
  /*  private $auteur;*/

    /**
     * @var string
     * @ORM\Column(name="texteCommentaire", type="text")
     */
    private $texteCommentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="signaler", type="boolean")
     */
    private $signaler;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="parent",  orphanRemoval=true)
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Commentaire", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Episode")
     * @ORM\JoinColumn(nullable=false)
     */
    private $episode;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    public $recupIdCommentaire;

    public function __construct()
    {
        $this->date = new \DateTime();

        //On hydrate signaler à false
        $this->setSignaler(false);
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

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
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Commentaire
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set texteCommentaire
     *
     * @param string $texteCommentaire
     *
     * @return Commentaire
     */
    public function setTexteCommentaire($texteCommentaire)
    {
        $this->texteCommentaire = $texteCommentaire;

        return $this;
    }

    /**
     * Get texteCommentaire
     *
     * @return string
     */
    public function getTexteCommentaire()
    {
        return $this->texteCommentaire;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commentaire
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set episode
     *
     * @param \AppBundle\Entity\Episode $episode
     *
     * @return Commentaire
     */
    public function setEpisode(\AppBundle\Entity\Episode $episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return \AppBundle\Entity\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set signaler
     *
     * @param boolean $signaler
     *
     * @return Commentaire
     */
    public function setSignaler($signaler)
    {
        $this->signaler = $signaler;

        return $this;
    }

    /**
     * Get signaler
     *
     * @return boolean
     */
    public function getSignaler()
    {
        return $this->signaler;
    }
    

    public function setChildren($commentaire)
    {
        $this->children = $commentaire;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Commentaire $child
     *
     * @return Commentaire
     */
    public function addChild(\AppBundle\Entity\Commentaire $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Commentaire $child
     */
    public function removeChild(\AppBundle\Entity\Commentaire $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Commentaire $parent
     *
     * @return Commentaire
     */
    public function setParent(\AppBundle\Entity\Commentaire $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Commentaire
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Commentaire
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
