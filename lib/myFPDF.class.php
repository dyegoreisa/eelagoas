<?php

require_once 'mc_table.class.php';

class myFPDF extends PDF_MC_Table
{
    private $userName;
    private $reportName;
    private $today;

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setReportName($reportName)
    {
        $this->reportName = $reportName;
    }

    public function getReportName()
    {
        return $this->reportName;
    }

    public function setToday($today)
    {
        $this->today = $today;
    }

    public function getToday()
    {
        return $this->today;
    }
    
    public function Header()
    {
        $numX = 225;
        $numWidth = 70; 

        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetFont('Arial', 'B', 14);
        
        $this->SetXY($numX, $this->GetY() - 5);
        $this->Cell($numWidth, 5, $this->getReportName(), 0, 1, 'R', 0);

        $this->SetFont('Arial', '', 10);
        $this->SetX($numX);
        $this->Cell($numWidth, 5, 'Emitido por: ' . $this->getUserName(), 0, 1, 'R', 0);
        $this->SetX($numX);
        $this->Cell($numWidth, 5, mb_convert_encoding('Emissão: ', 'ISO-8859-1', 'UTF-8') . $this->getToday(), 0, 1, 'R', 0);

        $this->Image(REP_LOGO_PDF, 5, 5, 45, 45);

        $this->SetXY(4, 35);
    }
    
    public function Footer()
    {
        $this->SetY(-5);
        $this->SetFont('Arial', 'I', 8);

        $this->SetFillColor(255);
        $this->SetTextColor(0);

        $this->Cell(275, 5, $this->PageNo() . '/{nb}', 0, 1, 'R', 0);
    }
}
