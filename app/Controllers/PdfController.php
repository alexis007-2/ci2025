<?php

namespace App\Controllers;

use Spipu\Html2Pdf\Html2Pdf;


class PdfController extends BaseController
{
    private $client;

    public function __construct()
    {
        helper('url');
        $this->client = service('curlrequest');
    }

    public function monpdf()
    {

        $html = '
        <style>
            body { font-family: Arial; }
            h1 { color: #007BFF; }
            .box { border: 1px solid #ccc; padding: 10px; margin-top: 20px; }
        </style>

        <h1>Bonjour PDF</h1>
        <div class="box">
            Ceci est un PDF généré avec <strong>html2pdf</strong> !
        </div>
    ';

        // Crée un nouvel objet PDF (P = Portrait, A4 = format, fr = langue)
        $pdf = new Html2Pdf('P', 'A4', 'fr');
        $pdf->writeHTML($html);
        $pdf->output('mon_document.pdf', 'D'); // Affiche dans le navigateur
        // $pdf->output('mon_document.pdf', 'D'); // Forcer le téléchargement
    }
}
