<?php
require('../../helpers/database.php');
require('../../helpers/validator.php');
require('../../libraries/fpdf182/fpdf.php');

/**
 *   Clase para definir las plantillas de los reportes del sitio privado. Para más información http://www.fpdf.org/
 */
class Report extends FPDF
{
    // Propiedad para guardar el título del reporte.
    private $title = null; //Titulo
    private $format = null; //Formato

    /*
    *   Método para iniciar el reporte con el encabezado del documento.
    *
    *   Parámetros: $title (título del reporte).
    *
    *   Retorno: ninguno.
    */
    public function startReport($title, $format)
    {
        // Se establece la zona horaria a utilizar durante la ejecución del reporte.
        ini_set('date.timezone', 'America/El_Salvador');
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en los reportes.
        session_start();
        // Se verifica si un administrador ha iniciado sesión para generar el documento, de lo contrario se direcciona a main.php
        if (isset($_SESSION['id_usuario'])) {
            // Se asigna el título del documento a la propiedad de la clase.
            $this->title = $title;
            // Se establece el título del documento (true = utf-8).
            $this->setTitle('Lolinsv - Reporte', true);
            // Se establecen los margenes del documento (izquierdo, superior y derecho).
            $this->setMargins(5, 10, 5);
            #Establecemos el margen inferior:
            $this->SetAutoPageBreak(true, 25);
            // Se añade una nueva página al documento (orientación vertical y formato carta) y se llama al método header()
            $this->format = $format;
            $this->addPage($format, 'letter');
            // Se define un alias para el número total de páginas que se muestra en el pie del documento.
            $this->aliasNbPages();
        }elseif (isset($_SESSION['id_cliente'])) {
            // Se asigna el título del documento a la propiedad de la clase.
            $this->title = $title;
            // Se establece el título del documento (true = utf-8).
            $this->setTitle('Lolinsv - Reporte', true);
            // Se establecen los margenes del documento (izquierdo, superior y derecho).
            $this->setMargins(5, 10, 5);
            #Establecemos el margen inferior:
            $this->SetAutoPageBreak(true, 25);
            // Se añade una nueva página al documento (orientación vertical y formato carta) y se llama al método header()
            $this->format = $format;
            $this->addPage($format, 'letter');
            // Se define un alias para el número total de páginas que se muestra en el pie del documento.
            $this->aliasNbPages();
        } else {
            header('location: ../../../views/dashboard/index.html');
        }
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del encabezado de los reportes.
    *   Se llama automáticamente en el método addPage()
    */
    public function header()
    {
        if (isset($_SESSION['id_usuario'])) {
            $txtN = 'Nombre del empleado: ';
        }else{
            $txtN = 'Nombre del cliente: ';
        }
        //Validamos el estilo de la página si es vertical (p) u horizontal (l) para mostrar distintos valores
        if ($this->format == 'p') {
            // Se establece el logo.
            $this->image('../../images/Logo.png', 10, 15, 20);
            // Se ubica el título.
            $this->cell(20);
            $this->setFont('Arial', 'B', 15);
            $this->cell(166, 10, utf8_decode($this->title), 0, 1, 'C');
            //Se ubica los nombresd el usuario junto con el nombre correspondiente al empleado
            //Nombre usuario
            $this->cell(20);
            $this->setFont('Arial', 'B', 10);
            $this->cell(70, 10, 'Nombre del usuario : ', 0, 0, 'C');
            $this->setFont('Arial', '', 10);
            $this->cell(-20, 10, utf8_decode($_SESSION['usuario']), 0, 0, 'C');
            //Nombre empleado
            $this->setFont('Arial', 'B', 10);
            $this->cell(115, 10, $txtN, 0, 0, 'C');
            $this->setFont('Arial', '', 10);
            $this->cell(-50, 10, utf8_decode($_SESSION['nombreUsuario'] . ' ' . $_SESSION['apellidoUsuario']), 0, 1, 'C');
            // Se ubica la fecha y hora del servidor.
            $this->cell(20);
            $this->setFont('Arial', 'B', 10);
            $this->cell(140, 10, 'Fecha/Hora: ', 0, 0, 'C');
            //Fechas y hora
            $this->setFont('Arial', '', 10);
            $this->cell(-85, 10, date('d-m-Y H:i:s'), 0, 1, 'C');
            // Se agrega un salto de línea para mostrar el contenido principal del documento.
            $this->ln(10);
        } else {
            // Se establece el logo.
            $this->image('../../images/Logo.png', 60, 15, 20);
            // Se ubica el título.
            $this->cell(20);
            $this->setFont('Arial', 'B', 15);
            $this->cell(260, 10, utf8_decode($this->title), 0, 1, 'C');
            //Se ubica los nombresd el usuario junto con el nombre correspondiente al empleado
            //Nombre usuario
            $this->cell(20);
            $this->setFont('Arial', 'B', 10);
            $this->cell(180, 10, 'Nombre del usuario : ', 0, 0, 'C');
            $this->setFont('Arial', '', 10);
            $this->cell(-125, 10, utf8_decode($_SESSION['usuario']), 0, 0, 'C');
            //Nombre empleado
            $this->setFont('Arial', 'B', 10);
            $this->cell(200, 10, $txtN, 0, 0, 'C');
            $this->setFont('Arial', '', 10);
            $this->cell(-130, 10, utf8_decode($_SESSION['nombreUsuario'] . ' ' . $_SESSION['apellidoUsuario']), 0, 1, 'C');
            // Se ubica la fecha y hora del servidor.
            $this->cell(70);
            $this->setFont('Arial', 'B', 10);
            $this->cell(140, 10, 'Fecha/Hora: ', 0, 0, 'C');
            //Fechas y hora
            $this->setFont('Arial', '', 10);
            $this->cell(-85, 10, date('d-m-Y H:i:s'), 0, 1, 'C');
            // Se agrega un salto de línea para mostrar el contenido principal del documento.
            $this->ln(10);
        }
    }

    /*
    *   Se sobrescribe el método de la librería para establecer la plantilla del pie de los reportes.
    *   Se llama automáticamente en el método output()
    */
    public function footer()
    {
        // Se establece la posición para el número de página (a 15 milimetros del final).
        $this->setY(-15);
        // Se establece la fuente para el número de página.
        $this->setFont('Arial', 'I', 8);
        // Se imprime una celda con el número de página.
        $this->cell(0, 10, utf8_decode('Página ') . $this->pageNo() . '/{nb}', 0, 0, 'C');
    }


    //***** Aquí comienza código para ajustar texto *************
    //***********************************************************
    function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true)
    {
        //Get string width
        $str_width = $this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $ratio = ($w - $this->cMargin * 2) / $str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit) {
            if ($scale) {
                //Calculate horizontal scaling
                $horiz_scale = $ratio * 100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
            } else {
                //Calculate character spacing in points
                $char_space = ($w - $this->cMargin * 2 - $str_width) / max($this->MBGetStringLength($txt) - 1, 1) * $this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET', $char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align = '';
        }

        //Pass on to Cell method
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT ' . ($scale ? '100 Tz' : '0 Tc') . ' ET');
    }

    function CellFitSpace($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, false);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if ($this->CurrentFont['type'] == 'Type0') {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++) {
                if (ord($s[$i]) < 128)
                    $len++;
                else {
                    $len++;
                    $i++;
                }
            }
            return $len;
        } else
            return strlen($s);
    }
    //************** Fin del código para ajustar texto *****************
    //******************************************************************
    /* -------------- Método para ajustar celdas ---------------------*/
    var $widths;
    var $aligns;
    var $heights;

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetHeight($y)
    {
        //Set the array of column widths
        $this->heights = $y;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $this->heights * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, $this->heights, $data[$i], 1, $a, 1);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
    /*---------------------------FIN del método para ajustar celdas-----------*/
}
