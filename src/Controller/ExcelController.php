<?php

namespace App\Controller;

use App\Repository\TrotinetteRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelController
{
    private $trotinetteRepository;

    public function __construct(TrotinetteRepository $trotinetteRepository)
    {
        $this->trotinetteRepository = $trotinetteRepository;
    }

    public function exportTrotinettesExcel(): string
    {
        // Créer une nouvelle instance de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter les en-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Vitesse');
        $sheet->setCellValue('C1', 'Charge');
        $sheet->setCellValue('D1', 'Couleur');
        $sheet->setCellValue('E1', 'Dispo');
        $sheet->setCellValue('F1', 'ID de la Station');

        // Récupérer les données de trotinettes depuis le référentiel
        $trotinettes = $this->trotinetteRepository->findAll();

        // Remplir les données
        $row = 2;
        foreach ($trotinettes as $trotinette) {
            $sheet->setCellValue('A' . $row, $trotinette->getIdTrotinette());
            $sheet->setCellValue('B' . $row, $trotinette->getVitesse());
            $sheet->setCellValue('C' . $row, $trotinette->getCharge());
            $sheet->setCellValue('D' . $row, $trotinette->getCouleur());
            $sheet->setCellValue('E' . $row, $trotinette->getDispo());
            $sheet->setCellValue('F' . $row, $trotinette->getIdStation()->getIdStation());

            // Ajouter d'autres colonnes au besoin
            $row++;
        }

        // Enregistrer le fichier Excel
        $filename = 'trotinettes_' . date('Ymd_His') . '.xlsx';
        $excelDirectory = __DIR__ . '/../../public/excel';
        if (!is_dir($excelDirectory)) {
            mkdir($excelDirectory, 0777, true);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelDirectory . '/' . $filename);

        return $filename;
    }
}
