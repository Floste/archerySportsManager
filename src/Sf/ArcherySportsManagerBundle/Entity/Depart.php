<?php

namespace Sf\ArcherySportsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Depart
 *
 * @ORM\Table(name="depart")
 * @ORM\Entity(repositoryClass="Sf\ArcherySportsManagerBundle\Repository\DepartRepository")
 */
class Depart
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
     * @var string
     *
     * @ORM\Column(name="discipline", type="string", length=5)
     */
    private $discipline;

    /**
     * @var string
     *
     * @ORM\Column(name="distance", type="string", length=255)
     */
    private $distance;

    /**
     * @var string
     *
     * @ORM\Column(name="blason", type="string", length=5)
     */
    private $blason;

    /**
     * @var int|null
     *
     * @ORM\Column(name="numDepart", type="integer", nullable=true)
     */
    private $numDepart;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var int|null
     *
     * @ORM\Column(name="paille", type="integer", nullable=true)
     */
    private $paille;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dix", type="integer", nullable=true)
     */
    private $dix;

    /**
     * @var int|null
     *
     * @ORM\Column(name="neuf", type="integer", nullable=true)
     */
    private $neuf;

    /**
     * @var int|null
     *
     * @ORM\Column(name="placeQualif", type="integer", nullable=true)
     */
    private $placeQualif;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scoreDistance1", type="integer", nullable=true)
     */
    private $scoreDistance1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scoreDistance2", type="integer", nullable=true)
     */
    private $scoreDistance2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scoreDistance3", type="integer", nullable=true)
     */
    private $scoreDistance3;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scoreDistance4", type="integer", nullable=true)
     */
    private $scoreDistance4;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score32", type="integer", nullable=true)
     */
    private $score32;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score16", type="integer", nullable=true)
     */
    private $score16;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score8", type="integer", nullable=true)
     */
    private $score8;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score4", type="integer", nullable=true)
     */
    private $score4;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score2", type="integer", nullable=true)
     */
    private $score2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scorePetiteFinal", type="integer", nullable=true)
     */
    private $scorePetiteFinal;

    /**
     * @var int|null
     *
     * @ORM\Column(name="scoreFinal", type="integer", nullable=true)
     */
    private $scoreFinal;

    /**
     * @var int|null
     *
     * @ORM\Column(name="placeDefinitive", type="integer", nullable=true)
     */
    private $placeDefinitive;


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
     * Set categorie.
     *
     * @param string $categorie
     *
     * @return Depart
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
     * @return Depart
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

    /**
     * Set discipline.
     *
     * @param string $discipline
     *
     * @return Depart
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline.
     *
     * @return string
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * Set distance.
     *
     * @param string $distance
     *
     * @return Depart
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance.
     *
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set blason.
     *
     * @param string $blason
     *
     * @return Depart
     */
    public function setBlason($blason)
    {
        $this->blason = $blason;

        return $this;
    }

    /**
     * Get blason.
     *
     * @return string
     */
    public function getBlason()
    {
        return $this->blason;
    }

    /**
     * Set numDepart.
     *
     * @param int|null $numDepart
     *
     * @return Depart
     */
    public function setNumDepart($numDepart = null)
    {
        $this->numDepart = $numDepart;

        return $this;
    }

    /**
     * Get numDepart.
     *
     * @return int|null
     */
    public function getNumDepart()
    {
        return $this->numDepart;
    }

    /**
     * Set score.
     *
     * @param int|null $score
     *
     * @return Depart
     */
    public function setScore($score = null)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score.
     *
     * @return int|null
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set paille.
     *
     * @param int|null $paille
     *
     * @return Depart
     */
    public function setPaille($paille = null)
    {
        $this->paille = $paille;

        return $this;
    }

    /**
     * Get paille.
     *
     * @return int|null
     */
    public function getPaille()
    {
        return $this->paille;
    }

    /**
     * Set dix.
     *
     * @param int|null $dix
     *
     * @return Depart
     */
    public function setDix($dix = null)
    {
        $this->dix = $dix;

        return $this;
    }

    /**
     * Get dix.
     *
     * @return int|null
     */
    public function getDix()
    {
        return $this->dix;
    }

    /**
     * Set neuf.
     *
     * @param int|null $neuf
     *
     * @return Depart
     */
    public function setNeuf($neuf = null)
    {
        $this->neuf = $neuf;

        return $this;
    }

    /**
     * Get neuf.
     *
     * @return int|null
     */
    public function getNeuf()
    {
        return $this->neuf;
    }

    /**
     * Set placeQualif.
     *
     * @param int|null $placeQualif
     *
     * @return Depart
     */
    public function setPlaceQualif($placeQualif = null)
    {
        $this->placeQualif = $placeQualif;

        return $this;
    }

    /**
     * Get placeQualif.
     *
     * @return int|null
     */
    public function getPlaceQualif()
    {
        return $this->placeQualif;
    }

    /**
     * Set scoreDistance1.
     *
     * @param int|null $scoreDistance1
     *
     * @return Depart
     */
    public function setScoreDistance1($scoreDistance1 = null)
    {
        $this->scoreDistance1 = $scoreDistance1;

        return $this;
    }

    /**
     * Get scoreDistance1.
     *
     * @return int|null
     */
    public function getScoreDistance1()
    {
        return $this->scoreDistance1;
    }

    /**
     * Set scoreDistance2.
     *
     * @param int|null $scoreDistance2
     *
     * @return Depart
     */
    public function setScoreDistance2($scoreDistance2 = null)
    {
        $this->scoreDistance2 = $scoreDistance2;

        return $this;
    }

    /**
     * Get scoreDistance2.
     *
     * @return int|null
     */
    public function getScoreDistance2()
    {
        return $this->scoreDistance2;
    }

    /**
     * Set scoreDistance3.
     *
     * @param int|null $scoreDistance3
     *
     * @return Depart
     */
    public function setScoreDistance3($scoreDistance3 = null)
    {
        $this->scoreDistance3 = $scoreDistance3;

        return $this;
    }

    /**
     * Get scoreDistance3.
     *
     * @return int|null
     */
    public function getScoreDistance3()
    {
        return $this->scoreDistance3;
    }

    /**
     * Set scoreDistance4.
     *
     * @param int|null $scoreDistance4
     *
     * @return Depart
     */
    public function setScoreDistance4($scoreDistance4 = null)
    {
        $this->scoreDistance4 = $scoreDistance4;

        return $this;
    }

    /**
     * Get scoreDistance4.
     *
     * @return int|null
     */
    public function getScoreDistance4()
    {
        return $this->scoreDistance4;
    }

    /**
     * Set score32.
     *
     * @param int|null $score32
     *
     * @return Depart
     */
    public function setScore32($score32 = null)
    {
        $this->score32 = $score32;

        return $this;
    }

    /**
     * Get score32.
     *
     * @return int|null
     */
    public function getScore32()
    {
        return $this->score32;
    }

    /**
     * Set score16.
     *
     * @param int|null $score16
     *
     * @return Depart
     */
    public function setScore16($score16 = null)
    {
        $this->score16 = $score16;

        return $this;
    }

    /**
     * Get score16.
     *
     * @return int|null
     */
    public function getScore16()
    {
        return $this->score16;
    }

    /**
     * Set score8.
     *
     * @param int|null $score8
     *
     * @return Depart
     */
    public function setScore8($score8 = null)
    {
        $this->score8 = $score8;

        return $this;
    }

    /**
     * Get score8.
     *
     * @return int|null
     */
    public function getScore8()
    {
        return $this->score8;
    }

    /**
     * Set score4.
     *
     * @param int|null $score4
     *
     * @return Depart
     */
    public function setScore4($score4 = null)
    {
        $this->score4 = $score4;

        return $this;
    }

    /**
     * Get score4.
     *
     * @return int|null
     */
    public function getScore4()
    {
        return $this->score4;
    }

    /**
     * Set score2.
     *
     * @param int|null $score2
     *
     * @return Depart
     */
    public function setScore2($score2 = null)
    {
        $this->score2 = $score2;

        return $this;
    }

    /**
     * Get score2.
     *
     * @return int|null
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Set scorePetiteFinal.
     *
     * @param int|null $scorePetiteFinal
     *
     * @return Depart
     */
    public function setScorePetiteFinal($scorePetiteFinal = null)
    {
        $this->scorePetiteFinal = $scorePetiteFinal;

        return $this;
    }

    /**
     * Get scorePetiteFinal.
     *
     * @return int|null
     */
    public function getScorePetiteFinal()
    {
        return $this->scorePetiteFinal;
    }

    /**
     * Set scoreFinal.
     *
     * @param int|null $scoreFinal
     *
     * @return Depart
     */
    public function setScoreFinal($scoreFinal = null)
    {
        $this->scoreFinal = $scoreFinal;

        return $this;
    }

    /**
     * Get scoreFinal.
     *
     * @return int|null
     */
    public function getScoreFinal()
    {
        return $this->scoreFinal;
    }

    /**
     * Set placeDefinitive.
     *
     * @param int|null $placeDefinitive
     *
     * @return Depart
     */
    public function setPlaceDefinitive($placeDefinitive = null)
    {
        $this->placeDefinitive = $placeDefinitive;

        return $this;
    }

    /**
     * Get placeDefinitive.
     *
     * @return int|null
     */
    public function getPlaceDefinitive()
    {
        return $this->placeDefinitive;
    }
}
