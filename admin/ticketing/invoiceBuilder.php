<?php

require_once AUTOLOADER;

use PhpOffice\PhpWord\TemplateProcessor;

class Invoice {
    private string $templatePath;
    private string $outputFolder;
    private string $filename;
    private TemplateProcessor $template;

    public function __construct(string $templateName = 'invoice.docx') {
        $this->templatePath  = PDFPATH . $templateName;
        $this->outputFolder  = PDFPATH;
        $this->filename      = 'invoice_' . date('YmdHis') . '_' . rand(100, 999);

        if (!file_exists($this->templatePath)) {
            throw new \RuntimeException("Template nicht gefunden: " . $this->templatePath);
        }

        $this->template = new TemplateProcessor($this->templatePath);
    }

    public function setData(array $data): self {
        foreach ($data as $key => $value) {
            $this->template->setValue($key, htmlspecialchars($value));
        }
        return $this;
    }

    public function generate(): string {
        $docxPath = $this->outputFolder . $this->filename . '.docx';
        $pdfPath  = $this->outputFolder . $this->filename . '.pdf';

        $this->template->saveAs($docxPath);

        // LibreOffice Konvertierung zu PDF
        $cmd = "/usr/bin/libreoffice --headless --convert-to pdf $docxPath --outdir " . $this->outputFolder . " 2>&1";
        exec($cmd, $output);

        // Optional: docx löschen
        if (file_exists($docxPath)) {
            unlink($docxPath);
        }

        return file_exists($pdfPath) ? $pdfPath : '';
    }
}