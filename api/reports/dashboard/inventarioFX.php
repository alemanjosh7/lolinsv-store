<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['fechai']) && isset($_GET['fechaf'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/inventario.php');
    // Se instancia el módelo productos para procesar los datos.
    $inventario = new Inventario;
    // Se instancia la clase para crear el reporte.
    if ($inventario->setFecha($_GET['fechai']) && $inventario->setFecha($_GET['fechaf'])) {
        // Se instancia la clase para crear el reporte.
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        $pdf->startReport('Reporte de existencias añadidas a inventario', 'l');
        $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
        // Se establece la fuente para los encabezados.
        //ESPACIO ENTRE CELDA
        $pdf->cell(70, 10, ' ', 0, 0, 'C');
        $pdf->setFont('Times', 'B', 11);
        $pdf->cell(35, 10, utf8_decode('Fecha inicial: '), 1, 0, 'C', 1);
        $pdf->setFillColor(252, 190, 190, 1);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Times', '', 11);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(40, 10, utf8_decode($_GET['fechai']), 1, 0, 'C', 1);
        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
        //ESPACIO ENTRE CELDA
        $pdf->cell(3, 10, ' ', 0, 0, 'C');
        $pdf->cell(35, 10, utf8_decode('Fecha final:'), 1, 0, 'C', 1);
        $pdf->setFillColor(252, 190, 190, 1);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Times', '', 11);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(40, 10, utf8_decode($_GET['fechaf']), 1, 1, 'C', 1);
        $pdf->Ln(5);
        $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(50, 10, 'Nombre del administrador', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(50, 10, 'Producto modificado', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(40, 10, 'Cantidad previo ingreso', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(35, 10, 'Cantidad ingresada', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(35, 10, 'Fecha de registro', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(25, 10, 'Modificado', 1, 0, 'C', 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->cell(35, 10, 'Cantidad total', 1, 1, 'C', 1);
        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(255, 190, 190, 1);
        // Se establece la fuente para los datos de los productos.
        $pdf->setFont('Times', '', 11);
        $pdf->LN(5);
        $cont = 0;
        // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
        if ($rowinventario = $inventario->reporteIFX($_GET['fechai'], $_GET['fechaf'])) {
            foreach ($rowinventario as $rowinventario) {
                $cont++;
                if ($cont > 7) {
                    $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
                    // Se establece la fuente para los encabezados.
                    //ESPACIO ENTRE CELDA
                    $pdf->cell(70, 10, ' ', 0, 0, 'C');
                    $pdf->setFont('Times', 'B', 11);
                    $pdf->cell(35, 10, utf8_decode('Fecha inicial: '), 1, 0, 'C', 1);
                    $pdf->setFillColor(252, 190, 190, 1);
                    // Se establece la fuente para los encabezados.
                    $pdf->setFont('Times', '', 11);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(40, 10, utf8_decode($_GET['fechai']), 1, 0, 'C', 1);
                    // Se establece un color de relleno para los encabezados.
                    $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
                    //ESPACIO ENTRE CELDA
                    $pdf->cell(3, 10, ' ', 0, 0, 'C');
                    $pdf->cell(35, 10, utf8_decode('Fecha final:'), 1, 0, 'C', 1);
                    $pdf->setFillColor(252, 190, 190, 1);
                    // Se establece la fuente para los encabezados.
                    $pdf->setFont('Times', '', 11);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(40, 10, utf8_decode($_GET['fechaf']), 1, 1, 'C', 1);
                    $pdf->Ln(5);
                    $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(50, 10, 'Nombre del administrador', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(50, 10, 'Producto modificado', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(40, 10, 'Cantidad previo ingreso', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(35, 10, 'Cantidad ingresada', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(35, 10, 'Fecha de registro', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(25, 10, 'Modificado', 1, 0, 'C', 1);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(35, 10, 'Cantidad total', 1, 1, 'C', 1);
                    // Se establece un color de relleno para los encabezados.
                    $pdf->setFillColor(255, 190, 190, 1);
                    // Se establece la fuente para los datos de los productos.
                    $pdf->setFont('Times', '', 11);
                    $pdf->LN(5);
                    $cont = 0;
                }
                $pdf->SetWidths(array(50, 50, 40, 35, 35, 25, 35)); //Seteamos el ancho de las celdas
                $pdf->setHeight(10);
                $pdf->Row(array(utf8_decode($rowinventario['nombre_admin'] . ' ' . $rowinventario['apellido_admin']), utf8_decode($rowinventario['nombre_producto']), $rowinventario['cantidada'], $rowinventario['cantidadn'], $rowinventario['fecha'], comprobarMod($rowinventario['modificado']), $rowinventario['cantidadn'] + $rowinventario['cantidada']));
                $pdf->Ln(5); //Espacio vertical  
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('No hay registros entre estas fechas'), 1, 1);
        }
        // Se envía el documento al navegador y se llama al método footer()
        $pdf->output('I', 'pedidospersonalizados.pdf');
    } else {
        header('location: ../../../views/dashboard/inventario.html');
    }
} else {
    header('location: ../../../views/dashboard/inventario.html');
}

function comprobarMod($boolean)
{
    if ($boolean) {
        return 'Si';
    } else {
        return 'No';
    }
}
