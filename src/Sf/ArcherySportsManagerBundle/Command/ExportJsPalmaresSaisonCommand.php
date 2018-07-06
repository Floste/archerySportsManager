<?php
/**
 * Created by PhpStorm.
 * User: floste
 * Date: 04/07/2018
 * Time: 21:50
 */

namespace Sf\ArcherySportsManagerBundle\Command;


use Sf\ArcherySportsManagerBundle\Service\ExporteurJsonPalmaresSaison;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportJsPalmaresSaisonCommand extends ContainerAwareCommand
{
    /**
     * @var ExportJsPalmaresSaisonCommand
     */
    private $exporteurJsonPalmaresSaison;

    /**
     * ExportJsonPalmaresSaisonCommand constructor.
     * @param ExporteurJsonPalmaresSaison $exporteurJsonPalmaresSaison
     */
    public function __construct(ExporteurJsonPalmaresSaison $exporteurJsonPalmaresSaison)
    {
        parent::__construct();
        $this->exporteurJsonPalmaresSaison = $exporteurJsonPalmaresSaison;
    }

    protected function configure()
    {
        $this->setName("archery:exportJs")
            ->setDescription("Export Palmares to Js")
            ->addArgument("saison",InputArgument::REQUIRED,"saison à exporter")
            ->addArgument("outputFolder",InputArgument::REQUIRED,"dossier ou écrire le fichier")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $export = $this->exporteurJsonPalmaresSaison->exportResultat(
            $input->getArgument("saison"),
            $input->getArgument("outputFolder")
        );
    }

}