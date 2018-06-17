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
        $lieuConcours = utf8_encode($row["LIEU_CONCOURS"]);
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

}