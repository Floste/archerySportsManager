<?php

namespace Sf\ArcherySportsManagerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Archer
 *
 * @ORM\Table(name="archer")
 * @ORM\Entity(repositoryClass="Sf\ArcherySportsManagerBundle\Repository\ArcherRepository")
 */
class Archer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numLicence", type="string", length=10, unique=true)
     */
    private $numLicence;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=1)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=5)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="arme", type="string", length=5)
     */
    private $arme;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Depart", mappedBy="archer")
     *
     */
    private $departs;

    /**
     * Archer constructor.
     */
    public function __construct()
    {
        $this->departs = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getDeparts()
    {
        return $this->departs;
    }

    /**
     * @param ArrayCollection $departs
     * @return Archer
     */
    public function setDeparts($departs)
    {
        $this->departs = $departs;
        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numLicence.
     *
     * @param string $numLicence
     *
     * @return Archer
     */
    public function setNumLicence($numLicence)
    {
        $this->numLicence = $numLicence;

        return $this;
    }

    /**
     * Get numLicence.
     *
     * @return string
     */
    public function getNumLicence()
    {
        return $this->numLicence;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Archer
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return Archer
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set sexe.
     *
     * @param string $sexe
     *
     * @return Archer
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe.
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set categorie.
     *
     * @param string $categorie
     *
     * @return Archer
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie.
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set arme.
     *
     * @param string $arme
     *
     * @return Archer
     */
    public function setArme($arme)
    {
        $this->arme = $arme;

        return $this;
    }

    /**
     * Get arme.
     *
     * @return string
     */
    public function getArme()
    {
        return $this->arme;
    }
}
