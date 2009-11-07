<?php
require_once FPDF . 'fpdf.php';

class PDF_MC_Table extends FPDF
{
var $widths;
var $height;
var $aligns;
var $valigns;
var $fills;
var $innerBorderX;
var $innerBorderY;
var $doBorder = TRUE;
var $doFills = FALSE;
var $doCalculateHeight = TRUE;
var $zeilenhoehe = 4.5;
var $currentLine = 0;

function calculateHeight($b) {
	$this->doCalculateHeight = $b;
}

function border($b) {
	$this->doBorder = $b;
}

function SetWidths($w) {
	//Set the array of column widths
	$this->widths=$w;
}

function SetHeight($h) {
	//Set the array of column widths
	$this->height=$h;
}

function SetAligns($a) {
	//Set the array of column alignments
	$this->aligns=$a;
}

function SetvAligns($a) {
	//Set the array of column alignments
	$this->valigns=$a;
}

function SetFills($f) {
	//Set the array of Cell Fillings
	$this->fills=$f;
}

function SetInnerBorders($x, $y) {
	$this->innerBorderX = $x;
	$this->innerBorderY = $y;
}

function SetZeilenhoehe($h) {
	$this->zeilenhoehe = $h;
}

function Row($data) {
	//Calculate the height of the row
	if($this->doCalculateHeight) {
		$nb=0;
		foreach($data as $key => $val) {
		    if (!isset($this->widths[$key])) {
		       continue;
		    }
			$nb=max($nb,$this->NbLines($this->widths[$key],$val));
		}
		$h=$this->zeilenhoehe*$nb;
	} else {
		$h = $this->height;
	}
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	$i = 0;
	foreach ($this->widths as $key => $val)
	{
		$w = $val;
		$text_w=$w-$this->innerBorderX*2;
		$a=isset($this->aligns[$key]) ? $this->aligns[$key] : 'L';
		$f=$this->fills[$key];

		//TODO: Set font and colour

		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Check the vertical alignment
		switch($this->valigns[$key]) {
			case "T":
				//Vertikaler Rand addieren
				$this->SetXY($this->GetX(), $this->GetY()+$this->innerBorderY);
			break;
			case "C":
				$text_height = $this->zeilenhoehe*$this->NbLines($text_w, $data[$key]);
				$offset = ($h-$text_height)/2;
				$this->SetXY($this->GetX(), ($this->GetY()+$offset));
			break;
			case "B":
				$text_height = $this->zeilenhoehe*$this->NbLines($text_w, $data[$key]);
				$offset = $h-$text_height;
				$this->SetXY($this->GetX(), ($this->GetY()+$offset));
				//Vertikaler Rand subtrahieren
				$this->SetXY($this->GetX(), $this->GetY()-$this->innerBorderY);
			break;
		}
		//Horizontaler Rand
		switch($a) {
			case "L":
				$this->SetXY($this->GetX()+$this->innerBorderX, $this->GetY());
			break;
			case "C":
			break;
			case "R":
				$this->SetXY($this->GetX()+$this->innerBorderX, $this->GetY());
			break;
		}
        //Draw the fills
        if($this->doFills) $this->Rect($x,$y,$w,$h,'F');
		//Print the text
		$this->MultiCell($text_w,$this->zeilenhoehe,$data[$key],0,$a,$f);
		//Draw the border
		if($this->doBorder) $this->Rect($x,$y,$w,$h);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
		$i++;
	}
	//Go to the next line
	$this->Ln($h);
	return true;
}


function CheckPageBreak($h) {
    //ko: Return if no auto page breaking
	if(!$this->AutoPageBreak) return FALSE;
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger) {
		$this->AddPage($this->CurOrientation);
		$this->Titles();
        $this->currentLine = 1;   
	}
}

function Titles() {
    // Implements titles here...
}

function NbLines($w,$txt) {
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
