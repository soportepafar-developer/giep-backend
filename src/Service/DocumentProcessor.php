<?php

namespace App\Service;

use App\Entity\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

class DocumentProcessor
{
    private DeepSeekAnalyzer $deepSeekAnalyzer;
    private string $uploadsDirectory;

    public function __construct(DeepSeekAnalyzer $deepSeekAnalyzer, string $uploadsDirectory)
    {
        $this->deepSeekAnalyzer = $deepSeekAnalyzer;
        $this->uploadsDirectory = $uploadsDirectory;
    }

    public function processUploadedFile(UploadedFile $file, string $promptUser): Document
    {
        $document = new Document();
        $document->setOriginalName($file->getClientOriginalName());
        $document->setFileType($file->getClientMimeType());
        $document->setFileSize($file->getSize());

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $document->setFilename($filename);

        $file->move($this->uploadsDirectory, $filename);
        $filePath = $this->uploadsDirectory . '/' . $filename;

        $content = $this->extractContent($filePath, $file->getClientMimeType());
        $document->setContent($content);

        $analysis = $this->deepSeekAnalyzer->analyzeDocument($content, $promptUser);
        $document->setSummary($analysis['summary']);
        $document->setAnalysis($analysis['analysis']);

        return $document;
    }

    private function extractContent(string $filePath, string $mimeType): string
    {
        try {
            switch ($mimeType) {
                case 'text/plain':
                case 'text/csv':
                    return file_get_contents($filePath);
                case 'application/pdf':
                    return $this->extractFromPdf($filePath);
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'application/msword':
                    return $this->extractFromDocx($filePath);
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                case 'application/vnd.ms-excel':
                    return $this->extractFromXlsx($filePath);
                default:
                    throw new \Exception('Tipo de archivo no soportado: ' . $mimeType);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al extraer contenido: ' . $e->getMessage());
        }
    }

    private function extractFromPdf(string $filePath): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        if (trim($text) === '') {
            throw new \Exception('El PDF no contiene texto extraíble.');
        }

        return trim($text);
    }

    private function extractFromDocx(string $filePath): string
    {
        $phpWord = WordIOFactory::load($filePath);
        $content = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $text) {
                        if ($text instanceof \PhpOffice\PhpWord\Element\Text) {
                            $content .= $text->getText() . ' ';
                        }
                    }
                }
            }
        }

        return trim($content);
    }

    private function extractFromXlsx(string $filePath): string
    {
        $spreadsheet = SpreadsheetIOFactory::load($filePath);
        $content = '';

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                foreach ($cellIterator as $cell) {
                    $value = $cell->getCalculatedValue();
                    if ($value !== null) {
                        $content .= $value . ' ';
                    }
                }

                $content .= "\n";
            }
        }

        return trim($content);
    }
}