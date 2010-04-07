<?
include "./findconfig.php";
include $DOCROOT . "/" . $ROOTPATH . "/class_inc.php";

require_once( $_SERVER['DOCUMENT_ROOT'] . "/" . $ROOTPATH . "/" . $tcpdfPath . '/config/lang/eng.php');
require_once( $_SERVER['DOCUMENT_ROOT'] . "/" . $ROOTPATH . "/" . $tcpdfPath . '/tcpdf.php');





class MYPDF extends TCPDF {
	public function LoadData()
	{
		$DB = new conn();
		$DB->connect();
		$sql = "SELECT * FROM finance_options";
		$result = $DB->query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$financeDetails[] = $row;
		}

		$DB->close();
		return $financeDetails;
	}


    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(135, 192, 196);
        $this->SetTextColor(255);
        $this->SetDrawColor(191, 191, 191);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(15, 55, 55, 45); // Cell Widths
        for($i = 0; $i < count($header); $i++)
        $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L', 1);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row["id"], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row["CompanyName"], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row["Address"], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row["Email"], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

//Column titles
$header = array('ID', 'Company Name', 'Area (sq km)', 'Pop. (thousands)');

//Data loading
$data = $pdf->LoadData();

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>>