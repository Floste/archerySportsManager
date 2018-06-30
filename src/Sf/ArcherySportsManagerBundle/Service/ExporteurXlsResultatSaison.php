<?php
/**
 * Created by PhpStorm.
 * User: floste
 * Date: 29/06/2018
 * Time: 21:56
 */

namespace Sf\ArcherySportsManagerBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sf\ArcherySportsManagerBundle\Entity\Archer;
use Sf\ArcherySportsManagerBundle\Entity\Depart;
use Sf\ArcherySportsManagerBundle\Entity\Saison;

class ExporteurXlsResultatSaison
{
    const CLM_ARCHER_NOM = 'A';
    const CLM_ARCHER_PRENOM = 'B';
    const CLM_ARCHER_CATEGORIE = 'C';
    const CLM_ARCHER_SEXE = 'D';
    const CLM_ARCHER_ARME = 'E';
    const CLM_ARCHER_DISCIPLINE = 'F';
    const CLM_ARCHER_DISTANCE = 'G';
    const CLM_CONCOURS_DATE = 'H';
    const CLM_CONCOURS_LIEU = 'I';
    const CLM_DEPART_NUM = 'J';
    const CLM_RESULTAT_SERIE1 = 'K';
    const CLM_RESULTAT_SERIE2 = 'L';
    const CLM_RESULTAT_SCORE = 'M';
    const CLM_RESULTAT_PLACE = 'N';
    const CLM_RESULTAT_MOYENNE = 'O';
    const CLM_RESULTAT_MOYENNE_SAISON = 'P';
    const CLM_RESULTAT_MOYENNE_SAISON_PREC = 'Q';
    const CLM_RESULTAT_MOYENNE_SAISON_PREC2 = 'R';

    CONST START_LINE = 5;
    CONST MAX_LINE = 500;

    /**
     * @var EntityManager $em
     */
    private $em;

    /** @var Color */
    private $goldColor;
    /** @var Color */
    private $silverColor;
    /** @var Color */
    private $bronzeColor;
    /** @var Color */
    private $blueColor;
    /** @var Color */
    private $redColor;
    /** @var Color */
    private $yellowColor;

    /**
     * ImportateurFFTAFileCsv constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->goldColor = new Color();
        $this->goldColor->setRGB('FFD966');
        $this->silverColor = new Color();
        $this->silverColor->setRGB('BFBFBF');
        $this->bronzeColor = new Color();
        $this->bronzeColor->setRGB('F4B084');

        $this->blueColor = new Color();
        $this->blueColor->setRGB('0070C0');
        $this->redColor = new Color();
        $this->redColor->setRGB('FF0000');
        $this->yellowColor = new Color();
        $this->yellowColor->setRGB('BF8F00');
    }

    /**
     * @param $saison
     * @param $outputFolder
     */
    public function exportResultat($saison, $outputFolder)
    {
        @ini_set("memory_limit",'512M');
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

        $saisonRepo = $this->em->getRepository(Saison::class);
        $objSaison = $saisonRepo->findOneBy([
            'name'=>$saison
        ]);
        if(is_null($objSaison)){
            throw new \Exception("Saison non trouvé");
        }
        // Fin de controles des paramètres

        //Création du fhcihier Excel
        $outFile = new Spreadsheet();
        $this->prepareWorkBook($outFile);

        $sheet = $outFile->getActiveSheet();
        $this->exportResultArchers($sheet,self::START_LINE,$objSaison);

        $exportFileName = "Recap Score - " . (new \DateTime())->format("Y-m-d") . ".xlsx";
        $exportFileName = $outputFolder . $exportFileName;
        $writer = new Xlsx($outFile);
        $writer->save($exportFileName);
    }

    /**
     * Preparation général du fichier
     *  - style
     *  - alignement
     *  - taille des colonnes
     * @param Spreadsheet $spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function prepareWorkBook(Spreadsheet &$spreadsheet){
        //Global style
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getDefaultStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $worksheet = $spreadsheet->getActiveSheet();
        //Colonne nom et prénom
        $worksheet->getColumnDimension(self::CLM_ARCHER_NOM)->setWidth(15);
        $worksheet->getColumnDimension(self::CLM_ARCHER_PRENOM)->setWidth(12);
        $worksheet->getStyle(self::CLM_ARCHER_PRENOM . ":" . self::CLM_ARCHER_PRENOM)->getFont()->setBold(true);

        //Colonne catégories
        $worksheet->getStyle(self::CLM_ARCHER_CATEGORIE.self::START_LINE.":".self::CLM_ARCHER_CATEGORIE.self::MAX_LINE)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED);
        $worksheet->getColumnDimension(self::CLM_ARCHER_CATEGORIE)->setWidth(2.57);
        $worksheet->getColumnDimension(self::CLM_ARCHER_SEXE)->setWidth(2.57);
        $worksheet->getColumnDimension(self::CLM_ARCHER_ARME)->setWidth(2.57);
        $worksheet->getStyle(self::CLM_ARCHER_ARME.self::START_LINE.":".self::CLM_ARCHER_ARME.self::MAX_LINE)->getBorders()->getRight()->setBorderStyle(Border::BORDER_DASHED);

        //Colonne disciplines
        $worksheet->getColumnDimension(self::CLM_ARCHER_DISCIPLINE)->setWidth(9.14);
        $worksheet->getColumnDimension(self::CLM_ARCHER_DISTANCE)->setWidth(7.27);

        //Colonne concours
        $worksheet->getColumnDimension(self::CLM_CONCOURS_DATE)->setWidth(10.14);
        $worksheet->getColumnDimension(self::CLM_CONCOURS_LIEU)->setWidth(16);

        //Colonne depart
        $worksheet->getColumnDimension(self::CLM_DEPART_NUM)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_SERIE1)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_SERIE2)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_SCORE)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_PLACE)->setWidth(5);

        //Colonne moyenne
        $worksheet->getColumnDimension(self::CLM_RESULTAT_MOYENNE)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_MOYENNE_SAISON)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_MOYENNE_SAISON_PREC)->setWidth(7);
        $worksheet->getColumnDimension(self::CLM_RESULTAT_MOYENNE_SAISON_PREC2)->setWidth(7);
        $worksheet->getStyle(self::CLM_RESULTAT_MOYENNE.self::START_LINE.":".self::CLM_RESULTAT_MOYENNE.self::MAX_LINE)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
        $worksheet->getStyle(self::CLM_RESULTAT_MOYENNE_SAISON_PREC.self::START_LINE.":".self::CLM_RESULTAT_MOYENNE_SAISON_PREC.self::MAX_LINE)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED);
    }

    private function exportResultArchers(Worksheet &$worksheet, $startIndice, Saison $objSaison){
        $archers = $this->em->getRepository(Archer::class)->getArchersFromSaison($objSaison);
        $startLoopIndice = $startIndice;

        /** @var Archer $archer */
        foreach ($archers as $archer) {
            $curIndice = $startLoopIndice;

            //todo restreindre à la saison demandée
            $departs = $archer->getDeparts();
            $categorieArcherSaison = $departs[0]->getCategorie();

            $worksheet->setCellValue(self::CLM_ARCHER_NOM . $curIndice,$archer->getNom());
            $worksheet->setCellValue(self::CLM_ARCHER_PRENOM . $curIndice,ucwords(strtolower($archer->getPreNom())));
            $worksheet->setCellValue(self::CLM_ARCHER_CATEGORIE . $curIndice,$categorieArcherSaison);
            $worksheet->setCellValue(self::CLM_ARCHER_SEXE . $curIndice,$archer->getSexe());

            //Fusion des cellules globales de chaque archer
            $fromIndice = $curIndice;
            $nbDeparts = count($departs);
            $toIndice = $fromIndice + $nbDeparts - 1;
            $worksheet->mergeCells(self::CLM_ARCHER_NOM.$fromIndice . ":" . self::CLM_ARCHER_NOM.$toIndice);
            $worksheet->mergeCells(self::CLM_ARCHER_PRENOM.$fromIndice . ":" . self::CLM_ARCHER_PRENOM.$toIndice);
            $worksheet->mergeCells(self::CLM_ARCHER_SEXE.$fromIndice . ":" . self::CLM_ARCHER_SEXE.$toIndice);
            $worksheet->mergeCells(self::CLM_ARCHER_CATEGORIE.$fromIndice . ":" . self::CLM_ARCHER_CATEGORIE.$toIndice);
            $worksheet->getStyle(self::CLM_ARCHER_NOM.$toIndice.":".self::CLM_RESULTAT_MOYENNE_SAISON_PREC2.$toIndice)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

            $curIndice = $startLoopIndice;
            /** @var Depart $depart */
            foreach ($archer->getDeparts() as $depart) {
                $worksheet->setCellValue(self::CLM_CONCOURS_DATE.$curIndice, $depart->getConcours()->getStartDate()->format("d/m/Y"));
                $worksheet->setCellValue(self::CLM_CONCOURS_LIEU.$curIndice, $depart->getConcours()->getLieu());
                $worksheet->setCellValue(self::CLM_DEPART_NUM.$curIndice, $depart->getNumDepart());
                $worksheet->setCellValue(self::CLM_RESULTAT_SERIE1.$curIndice, $depart->getResultat()->getScoreDistance1());
                $worksheet->setCellValue(self::CLM_RESULTAT_SERIE2.$curIndice, $depart->getResultat()->getScoreDistance2());
                $worksheet->setCellValue(self::CLM_RESULTAT_SCORE.$curIndice, $depart->getResultat()->getScore());
                $worksheet->setCellValue(self::CLM_RESULTAT_PLACE.$curIndice, $depart->getResultat()->getPlaceDefinitive());
                $worksheet->setCellValue(self::CLM_RESULTAT_MOYENNE.$curIndice, $depart->getResultat()->getMoyenne());
                if($depart->getResultat()->getPlaceDefinitive() < 4){
                    $bgColor = $this->bronzeColor;
                    if (2 == $depart->getResultat()->getPlaceDefinitive()){
                        $bgColor = $this->silverColor;
                    }elseif(1 == $depart->getResultat()->getPlaceDefinitive()){
                        $bgColor = $this->goldColor;
                    }
                    $worksheet->getStyle(self::CLM_CONCOURS_DATE.$curIndice.":".self::CLM_RESULTAT_PLACE.$curIndice)->getFill()
                        ->setStartColor($bgColor)
                        ->setEndColor($bgColor)
                        ->setFillType(Fill::FILL_SOLID)
                        ;
                }
                $colorMoyenne = null;
                if(9 <= $depart->getResultat()->getMoyenne()){
                    $colorMoyenne = $this->yellowColor;
                }elseif(7 <= $depart->getResultat()->getMoyenne()) {
                    $colorMoyenne = $this->redColor;
                }elseif(5 <= $depart->getResultat()->getMoyenne()) {
                    $colorMoyenne = $this->blueColor;
                }
                if($colorMoyenne){
                    $worksheet->getStyle(self::CLM_RESULTAT_MOYENNE.$curIndice)->getFont()->setColor($colorMoyenne);
                }
                $curIndice += 1;
            }
            $startLoopIndice = $startLoopIndice + $nbDeparts;
        }
    }
}