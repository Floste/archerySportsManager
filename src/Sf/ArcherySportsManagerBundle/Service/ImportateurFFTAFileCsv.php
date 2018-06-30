<?php
/**
 * Created by PhpStorm.
 * User: floste
 * Date: 27/06/2018
 * Time: 21:12
 */

namespace Sf\ArcherySportsManagerBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sf\ArcherySportsManagerBundle\Entity\Archer;
use Sf\ArcherySportsManagerBundle\Entity\Concours;
use Sf\ArcherySportsManagerBundle\Entity\Depart;
use Sf\ArcherySportsManagerBundle\Entity\Resultat;
use Sf\ArcherySportsManagerBundle\Entity\Saison;
use Sf\ArcherySportsManagerBundle\Repository\ConcoursRepository;
use Sf\ArcherySportsManagerBundle\Repository\SaisonRepository;


class ImportateurFFTAFileCsv
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * ImportateurFFTAFileCsv constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function importCsvFFTAFile($pathFile){

        $fileContent = $this->getArrayFromCsvFile($pathFile);

        $keys = array_shift($fileContent);

        foreach ($fileContent as $item) {
            $row = array_combine($keys,$item);
            $saison = $this->getSaison($row);
            $concours = $this->getConcours($row,$saison);
            $archer = $this->getArcher($row);
            $depart = $this->getDepart($row,$archer,$concours);
            $resultat = $this->getResultat($row,$depart);
        }
    }

    /**
     * @param $row
     * @return null|Saison
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getSaison($row){
        /**
         * @var SaisonRepository $saisonRepository
         */
        $saisonRepository = $this->em->getRepository(Saison::class);
        $saison = $saisonRepository->findOneBy([
            'name' => $row["SAISON"]
        ]);
        if(is_null($saison)){
            $saison = new Saison();
            $saison->setName($row["SAISON"]);
            $this->em->persist($saison);
            $this->em->flush();
        }
        return $saison;
    }

    /**
     * @param $row
     * @param $saison
     * @return null|Concours
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getConcours($row,Saison $saison){
        $startDate = \DateTime::createFromFormat("d/m/Y",$row["D_DEBUT_CONCOURS"]);
        $startDate->setTime(0,0,0);
        $endDate = \DateTime::createFromFormat("d/m/Y",$row["D_FIN_CONCOURS"]);
        $endDate->setTime(0,0,0);
        $lieuConcours = $row["LIEU_CONCOURS"];
        /**
         * @var ConcoursRepository $concoursRepository
         */
        $concoursRepository = $this->em->getRepository(Concours::class);
        $concours = $concoursRepository->findOneBy([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'codeStructureOrganisatrice' => $row["CODE_STRUCTURE_ORGANISATRICE"]
        ]);
        if(is_null($concours)){
            $concours = new Concours();
            $concours->setCodeStructureOrganisatrice($row["CODE_STRUCTURE_ORGANISATRICE"])
                ->setDetailNiveauChampionnat($row["DETAIL_NIVEAU_CHPT"])
                ->setNiveauChampionnat($row["NIVEAU_CHPT"])
                ->setFormuleTir($row["FORMULE_TIR"])
                ->setLieu($lieuConcours)
                ->setSaison($saison)
                ->setNomStructureOrganisatrice($row["NOM_STRUCTURE_ORGANISATRICE"])
                ->setStartDate($startDate)
                ->setEndDate($endDate)
            ;

            $this->em->persist($concours);
            $this->em->flush();
        }
        return $concours;
    }

    /**
     * @param $row
     * @param Archer $archer
     * @param Concours $concours
     * @return null |Depart
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getDepart($row,Archer $archer,Concours $concours){
        /**
         * @var Depart $depart
         */
        $departRepository = $this->em->getRepository(Depart::class);
        $depart = $departRepository->findOneBy([
            'concours' => $concours,
            'archer' => $archer,
            'numDepart' => $row["NUM_DEPART"]
        ]);
        if(is_null($depart)){
            $depart = new Depart();
            $depart->setArcher($archer)
                ->setConcours($concours)
                ->setArme($row["ARME"])
                ->setCategorie($row["CAT"])
                ->setDiscipline($row["DISCIPLINE"])
                ->setNumDepart($row["NUM_DEPART"])
            ;
            $this->em->persist($depart);
            $this->em->flush();
        }
        return $depart;
    }

    /**
     * @param $row
     * @param Depart $depart
     * @return Resultat
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getResultat($row,Depart $depart){
        if(is_null($depart->getResultat())){
            $result = new Resultat();
            $result->setDepart($depart)
                ->setPlaceDefinitive($row["PLACE_DEF"])
                ->setDistance($row["DISTANCE"])
                ->setBlason($row["BLASON"])
                ->setScore($row["SCORE"])
                ->setPaille($row["PAILLE"])
                ->setDix($row["DIX"])
                ->setNeuf($row["NEUF"])
                ->setPlaceQualif($row["PLACE_QUALIF"])
                ->setScoreDistance1($row["SCORE_DIST1"])
                ->setScoreDistance2($row["SCORE_DIST2"])
                ->setScoreDistance3($row["SCORE_DIST3"])
                ->setScoreDistance4($row["SCORE_DIST4"])
                ->setScore32($row["SCORE_32"])
                ->setScore16($row["SCORE_16"])
                ->setScore8($row["SCORE_8"])
                ->setScore4($row["SCORE_QUART"])
                ->setScore2($row["SCORE_DEMI"])
                ->setScorePetiteFinal($row["SCORE_PETITE_FINAL"])
                ->setScoreFinal($row["SCORE_FINAL"])
            ;
            $this->em->persist($result);
            $depart->setResultat($result);
            $this->em->flush();
        }
        return $depart->getResultat();
    }

    /**
     * @param $row
     * @return null|Archer
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getArcher($row){
        $archerRepository = $this->em->getRepository(Archer::class);
        $archer = $archerRepository->findOneBy([
            'numLicence' => $row["NO_LICENCE"]
        ]);
        if(is_null($archer)){
            $archer = new Archer();
            $archer->setNumLicence($row["NO_LICENCE"])
                ->setCategorie($row["CAT"])
                ->setArme($row["ARME"])
                ->setNom($row["NOM_PERSONNE"])
                ->setPrenom($row["PRENOM_PERSONNE"])
                ->setSexe($row["SEXE_PERSONNE"])
            ;
            $this->em->persist($archer);
            $this->em->flush();
        }
        return $archer;
    }

    private function getArrayFromCsvFile($filePath){
        $detect = array("CP1252","ASCII","ISO-8859-1","ISO-8859-15","UTF-8");
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setInputEncoding(mb_detect_encoding(file_get_contents($filePath),$detect));
        $fftaFile = $reader->load($filePath);

        return $fftaFile->getActiveSheet()->toArray();
    }

}