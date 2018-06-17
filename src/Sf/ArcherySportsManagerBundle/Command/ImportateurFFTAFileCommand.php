<?php
/**
 * Created by PhpStorm.
 * User: floste
 * Date: 17/06/2018
 * Time: 15:20
 */

namespace Sf\ArcherySportsManagerBundle\Command;


use Doctrine\ORM\EntityManager;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Sf\ArcherySportsManagerBundle\Entity\Archer;
use Sf\ArcherySportsManagerBundle\Entity\Concours;
use Sf\ArcherySportsManagerBundle\Entity\Saison;
use Sf\ArcherySportsManagerBundle\Repository\ConcoursRepository;
use Sf\ArcherySportsManagerBundle\Repository\SaisonRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportateurFFTAFileCommand extends ContainerAwareCommand
{

    /**
     * @var EntityManager $em
     */
    private $em;

    protected function configure()
    {
        $this->setName("archery:importFFTAFile")
            ->setDescription("Import FFTA File")
            ->addArgument("fftaFile",InputArgument::REQUIRED,"FFTA File path");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $fftaFile = IOFactory::load($input->getArgument("fftaFile"));
        $fileContent = $fftaFile->getActiveSheet()->toArray();

        $keys = array_shift($fileContent);

        foreach ($fileContent as $item) {
            $row = array_combine($keys,$item);
            $saison = $this->getSaison($row);
            $concours = $this->getConcours($row,$saison);
            $archer = $this->getArcher($row);
        }
    }

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

    private function getConcours($row,$saison){
        $startDate = \DateTime::createFromFormat("d/m/Y",$row["D_DEBUT_CONCOURS"]);
        $endDate = \DateTime::createFromFormat("d/m/Y",$row["D_FIN_CONCOURS"]);
        $lieuConcours = $this->ConvertToUTF8($row["LIEU_CONCOURS"]);
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

    }

    private function ConvertToUTF8($text){

        $encoding = mb_detect_encoding($text, mb_detect_order(), false);

        if($encoding == "UTF-8")
        {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }


        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);


        return $out;
    }
}