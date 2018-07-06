<?php
/**
 * Created by PhpStorm.
 * User: floste
 * Date: 04/07/2018
 * Time: 21:53
 */

namespace Sf\ArcherySportsManagerBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Sf\ArcherySportsManagerBundle\Entity\Resultat;
use Sf\ArcherySportsManagerBundle\Entity\Saison;
use Sf\ArcherySportsManagerBundle\Repository\ResultatRepository;
use Sf\ArcherySportsManagerBundle\Repository\SaisonRepository;

class ExporteurJsonPalmaresSaison
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /** @var ResultatRepository */
    private $resultatRepo;
    /** @var SaisonRepository */
    private $saisonRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->resultatRepo = $this->em->getRepository(Resultat::class);
        $this->saisonRepo = $this->em->getRepository(Saison::class);
    }

    /**
     * @param $saison
     * @param $outputFolder
     */
    public function exportResultat($saison, $outputFolder)
    {
        // Controles des paramètres
        if($saison == "" || !is_numeric($saison)){
            throw new \Exception("saison doit etre une année");
        }

        if(substr($outputFolder,-1)!=DIRECTORY_SEPARATOR){
            $outputFolder .= DIRECTORY_SEPARATOR;
        }
        if(!is_dir($outputFolder)){
            throw new \Exception("output folder non trouvé");
        }

        $objSaison = $this->saisonRepo->findOneBy([
            'name'=>$saison
        ]);
        if(is_null($objSaison)){
            throw new \Exception("Saison non trouvé");
        }
        // Fin de controles des paramètres

        $fileNameExport = "palmares-data-";
        $fileNameExport .= $objSaison->getName();
        $fileNameExport .= ".js";
        file_put_contents($outputFolder . DIRECTORY_SEPARATOR . $fileNameExport, $this->getStrPalmaresIndividuel($objSaison));
    }

    private function getStrPalmaresIndividuel(Saison $objSaison){
        $resultats = $this->resultatRepo->getPodiumsIndividuelsForSaison($objSaison);

        $ret = [];
        foreach ($resultats as $resultat) {
            $ret[] = $this->getArrayResultForPalmares($resultat);
        }
        $str = json_encode($ret);
        $str = str_replace("Pr\u00e9nom","Prénom",$str);
        return "var palmaresIndividuel = " . $str . "; ";
    }

    private function getArrayResultForPalmares(Resultat $result){
        return [
            "Nom" => $result->getDepart()->getArcher()->getNom(),
            "Prénom" => $result->getDepart()->getArcher()->getPrenom(),
            "Categorie" => $result->getDepart()->getCategorie(),
            "Sexe" => $result->getDepart()->getArcher()->getSexe(),
            "Arme" => $result->getDepart()->getArme(),
            "Discipline" => $result->getDepart()->getDiscipline(),
            "Blason" => $result->getDepart()->getResultat()->getBlason(),
            "Date" => $result->getDepart()->getConcours()->getStartDate()->format("d/m/Y"),
            "Organisateur" => $result->getDepart()->getConcours()->getNomStructureOrganisatrice(),
            "Series" => $result->getDepart()->getConcours()->getFormuleTir(),
            "Score" => $result->getScore(),
            "Place" => $result->getPlaceDefinitive()
        ];
    }

}