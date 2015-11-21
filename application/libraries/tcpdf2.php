<?php

define('K_TCPDF_EXTERNAL_CONFIG', true);
define ('K_PATH_MAIN', dirname(__FILE__).'/tcpdf/');
define ('K_PATH_URL', site_url());
define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');
define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');
define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');
define ('K_PATH_IMAGES', '');
define ('K_BLANK_IMAGE', K_PATH_IMAGES.'_blank.png');
define ('PDF_PAGE_FORMAT', 'A4');
define ('PDF_PAGE_ORIENTATION', 'P');
define ('PDF_CREATOR', '');
define ('PDF_AUTHOR', '');
define ('PDF_HEADER_TITLE', '');
define ('PDF_HEADER_STRING', '');
define ('PDF_HEADER_LOGO', '');
define ('PDF_HEADER_LOGO_WIDTH', 30);
define ('PDF_UNIT', 'mm');
define ('PDF_MARGIN_HEADER', 5);
define ('PDF_MARGIN_FOOTER', 10);
define ('PDF_MARGIN_TOP', 27);
define ('PDF_MARGIN_BOTTOM', 25);
define ('PDF_MARGIN_LEFT', 15);
define ('PDF_MARGIN_RIGHT', 15);
define ('PDF_FONT_NAME_MAIN', 'helvetica');
define ('PDF_FONT_SIZE_MAIN', 10);
define ('PDF_FONT_NAME_DATA', 'helvetica');
define ('PDF_FONT_SIZE_DATA', 8);
define ('PDF_FONT_MONOSPACED', 'courier');
define ('PDF_IMAGE_SCALE_RATIO', 1.25);
define('HEAD_MAGNIFICATION', 1.1);
define('K_CELL_HEIGHT_RATIO', 1.5);
define('K_TITLE_MAGNIFICATION', 1.3);
define('K_SMALL_RATIO', 2/3);
define('K_THAI_TOPCHARS', true);
define('K_TCPDF_CALLS_IN_HTML', true);

require_once APPPATH.'libraries/tcpdf/tcpdf.php';

class TCPDF2 extends TCPDF {

    protected $footer_string = '';
    protected $footer_align = 'L';
    protected $footer_num_align = 'R';
    protected $header_align = 'C';
    protected $header_num_align = 'R';
    protected $print_pagenum = TRUE;
    protected $watermark_sample = FALSE;

    public function startPage($orientation='', $format='') {
        parent::startPage($orientation, $format);
        $this->setPrintPagenum(TRUE);
    }

    public function setFooterData($ts, $align='L') {
        $this->footer_string = $ts;
        $this->footer_align = $align;
    }

    public function enableSampleMark() {
        $this->watermark_sample = TRUE;
    }

    public function Header() {
        $ormargins = $this->getOriginalMargins();
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();
        if (($headerdata['logo']) AND($headerdata['logo'] != K_BLANK_IMAGE)) {
            $this->Image($headerdata['logo'], $this->GetX(), $this->getHeaderMargin(), $headerdata['logo_width']);
            $imgy = $this->getImageRBY();
        }
        else {
            $imgy = $this->GetY();
        }

        # 页码
        if ($this->header_num_align !== NULL && $this->print_pagenum) {
            $this->SetTextColor(0, 0, 0);
            $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
            if (empty($this->pagegroups)) {
                $pagenumtxt = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            }
            else {
                $pagenumtxt = 'Page '.$this->getPageNumGroupAlias().' of '.$this->getPageGroupAlias();
            }
            $this->SetX($ormargins['left']);
            $this->Cell(0, 0, $pagenumtxt, 0, 0, $this->header_num_align);
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
        $this->SetX($ormargins['left']);
        $this->Cell(0, 0, $headerdata['title'], 0, 1, $this->header_align);
        $this->SetX($ormargins['left']);
        $this->Cell(0, 0, $headerdata['string'], 0, 1, $this->header_align);

        # 水印
        if ($this->watermark_sample) {
            $this->SetXY(0, 0);
            $this->StartTransform();
            $this->SetAlpha(0.05);
            $this->setTextRenderingMode(1, FALSE);
            $this->SetFontSize(128);
            $this->Rotate(10);
            $this->Write(0, 'DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT DRAFT');
            $this->StopTransform();
        }
    }

    public function Footer() {
        $cur_y = $this->GetY();
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        $line_width = 0.85 / $this->getScaleFactor();
        $barcode = $this->getBarcode();
        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        }
        else {
            $pagenumtxt = $this->l['w_page'].' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        }
        $this->SetY($cur_y);
        $this->Cell(0, 0, $this->footer_string, 0, 1, $this->footer_align);
        if ($this->footer_num_align !== NULL && $this->print_pagenum) {
            $this->SetY($cur_y);
            $this->Cell(0, 0, $pagenumtxt, 0, 0, $this->footer_num_align);
        }
    }


    public function setPrintPagenum($b) {
        $this->print_pagenum = $b;
    }

    public function setFooterAlign($align='C') {
        $this->footer_align = $align;
    }

    public function setFooterPageNumAlign($align='L') {
        $this->footer_num_align = $align;
    }

    public function setHeaderAlign($align='C') {
        $this->header_align = $align;
    }

    public function setHeaderPageNumAlign($align='L') {
        $this->header_num_align = $align;
    }

    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0,
                         $ignore_min_height=false, $calign='T', $valign='M') {
        $tx1 = $this->x;
        $ty1 = $this->y;
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
        $tx2 = $this->x;
        $ty2 = $this->y;

        if (in_array(strtolower($this->FontFamily), array('droidsansfallback', 'stsongstdlight')) &&
            strpos($this->FontStyle, 'B') !== FALSE) {
            $this->x = $tx1;
            $this->y = $ty1;
            parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
            $this->x = $tx2;
            $this->y = $ty2;
        }
    }

    protected function addHTMLVertSpace($hbz=0, $hb=0, $cell=false, $firsttag=false, $lasttag=false) {
        return parent::addHTMLVertSpace($hbz, 0, $cell, $firsttag, $lasttag);
    }

    protected function openHTMLTagHandler($dom, $key, $cell) {
        # 换页
        if ($dom[$key]['value'] == 'hr' && isset($dom[$key]['attribute']['pagebreakafter']) && $dom[$key]['attribute']['pagebreakafter']) {
            $dom[$key]['value'] = 'ignore';
        }

        # 图片路径
        if ($dom[$key]['value'] == 'img') {
            $CI =& get_instance();
            if (method_exists($CI, 'GetImagePath')) {
                $src = $CI->GetImagePath($dom[$key]['attribute']['src']);
                if ($src && file_exists($src)) $dom[$key]['attribute']['src'] = $CI->GetImagePath($dom[$key]['attribute']['src']);
            }
        }
        return parent::openHTMLTagHandler($dom, $key, $cell);
    }

    public function grid($fontname, $fontsize, $linewidth, $data) {
        foreach ($data as $row) {
            foreach ($row as $item) {
                $fill = FALSE;
                $ln = 0;
                $x = '';
                $y = '';
                $reseth = TRUE;
                $stretch = 0;
                $ishtml = FALSE;
                $autopadding = FALSE;
                $valign = 'M';
                $maxh = 0;
                $fitcell = TRUE;
                $this->SetFont($fontname, '', $fontsize);
                $this->SetLineStyle(array('width'=>$linewidth, 'color'=>array(255, 255, 255, 0)));
                if (isset($item[0])) $w = $item[0];
                if (isset($item[1])) $h = $item[1];
                if (isset($item[2])) $border = $item[2];
                if (isset($item[3])) $align = $item[3];
                if (isset($item[4])) $txt = $item[4];
                if (isset($item[5]) && isset($item[6]) && isset($item[7])) $this->SetFont($item[5], $item[6], $item[7]);
                if (isset($item[8])) $this->SetLineStyle(array('width'=>$item[8], 'color'=>array(255, 255, 255, 0)));;
                if (isset($item[9])) $ishtml = $item[9];
                if ($h > 0) $maxh = $h;
                elseif ($h < 0) $h = $this->getLastH();
                $this->checkPageBreak($h);
                $this->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh,
                                 $valign, $fitcell);
                if (isset($item[5]) && isset($item[6]) && isset($item[7])) $this->SetFont($fontname, '', $fontsize);
                if (isset($item[8])) $this->SetLineStyle(array('width'=>$linewidth, 'color'=>array(255, 255, 255, 0)));
            }
            $this->ln();
        }
    }

    public function gridhtml($fontname, $fontsize, $border=0, $data) {
    	$html = '<table '.(($border == 1)?'border="1"':'').'>';
        foreach ($data as $row) {
        	$html .= '<tr>';
            foreach ($row as $item) {
            	if (isset($item[0])) $w = $item[0];
                if (isset($item[1])) $h = $item[1];
                if (isset($item[2])) $border = $item[2];
                if (isset($item[3])) $align = ($item[3] == 'L')?'left':(($item[3] == 'C')?'center':(($item[3] == 'R')?'right':''));
                if (isset($item[4])) $txt = $item[4];
                if (isset($item[5]) && isset($item[6]) && isset($item[7])) $this->SetFont($item[5], $item[6], $item[7]);
            	$html .= '<td align="'.$align.'" width="'.$w.'" hight="'.$h.'"> '.$txt.'</td>';
            }
			$html .= '</tr>';
        }
        $html .= '</table>';
        $this->writeHTML($html, true, false, true, false, '');
        $this->SetFont($fontname, '', $fontsize);
    }

    public function hr($width=0.5, $color=array(255,255,255,0), $defaultwidth=0.2) {
        $this->Line($this->GetX(), $this->GetY(), $this->getPageWidth()-$this->GetX(), $this->GetY(), array('width'=>$width, 'color'=>$color));
        $this->SetLineStyle(array('width'=>$defaultwidth, 'color'=>array(255, 255, 255, 0)));
    }

    public function space2bottom($height) {
        $margins = $this->getMargins();
        $this->ln($this->getPageHeight()-$this->getY()-$margins['bottom']-$height);
    }
}
