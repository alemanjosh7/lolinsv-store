<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['fechai']) && isset($_GET['fechaf'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/pedidosEstablecidos.php');
    // Se instancia el módelo pedidos personalizado para procesar los datos.
    $pedido = new PedidosEstablecidos;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pedido->setFecha($_GET['fechai']) && $pedido->setFecha($_GET['fechaf'])) {
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Número de pedidos establecidos dentros de un rango de fechas', 'p');
        // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
        if ($dataPedido = $pedido->reportPEMxMiPrFX($_GET['fechai'], $_GET['fechaf'])) {
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
            // Se establece la fuente para los encabezados.
            //ESPACIO ENTRE CELDA
            $pdf->cell(25, 10, ' ', 0, 0, 'C');
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
            $pdf->cell(35, 10, utf8_decode('Venta máxima ($):'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(40, 10, utf8_decode('$' . $dataPedido[0]['maximo']), 1, 0, 'C', 1);
            //ESPACIO ENTRE CELDA
            $pdf->cell(3, 10, ' ', 0, 0, 'C');
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(35, 10, utf8_decode('Venta minima ($):'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(25, 10, utf8_decode('$' . $dataPedido[0]['minimo']), 1, 0, 'C', 1);
            //ESPACIO ENTRE CELDA
            $pdf->cell(3, 10, ' ', 0, 0, 'C');
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(30, 10, utf8_decode('Promedio ($):'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(35, 10, utf8_decode('$' . $dataPedido[0]['promedio']), 1, 1, 'C', 1);
            $pdf->ln(5);

            //PEDIDOS
            $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 20);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(206, 10, utf8_decode('Fechas y número de pedidos'), 1, 1, 'C', 1);
            $pdf->ln(10);
            //Obtenemos los pedidos durante esas fechas y calculamos el número de pedidos que se realizaron
            //Creamos las variables a usar
            $fecha = ['1999-01-01', ''];
            $max = ['0', ''];
            $min = ['0', ''];
            $cont = 0;

            //Obtenemos los pedidos entre esas fechas
            if ($rowPedido = $pedido->reportPEFX($_GET['fechai'], $_GET['fechaf'])) {
                foreach ($rowPedido as $rowPedido) {
                    //Colocamos la fecha que se obtiene
                    $fecha[1] = $rowPedido['fecha_pedidoesta'];
                    //Colocamos el monto actual como máximo
                    $max[1] = $rowPedido['montototal_pedidoesta'];
                    //Colocamis el monto actual como minimo
                    $min[1] = $rowPedido['montototal_pedidoesta'];

                    //Colocamos los textos de información respecto a esa fecha
                    if ($fecha[0] != $fecha[1]) {
                        $fecha[0] = $rowPedido['fecha_pedidoesta'];
                        //Se coloca el titulo de la fecha
                         $pdf->setFillColor(255, 107, 107, 1); //Color de relleno
                        // Se establece la fuente para los encabezados.
                        $pdf->setFont('Times', 'B', 20);
                        // Se imprimen las celdas con los encabezados.
                        $pdf->cell(206, 10, utf8_decode('Fecha: ' . $rowPedido['fecha_pedidoesta']), 1, 1, 'C', 1);
                        $pdf->ln(5);
                        //Colocamos el número de pedidos realizados
                        if ($contPedido = $pedido->numeroPedidos($fecha[0])) {
                            //ESPACIO ENTRE CELDA
                            $pdf->cell(55, 10, ' ', 0, 0, 'C');
                            //Se coloca el titulo
                            $pdf->setFillColor(252, 190, 190, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', 'B', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(50, 10, utf8_decode('Número de pedidos: '), 1, 0, 'C', 1);
                            $pdf->setFillColor(255, 226, 205, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', '', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(50, 10, utf8_decode($contPedido[0]['numero']), 1, 1, 'C', 1);
                            $pdf->ln(5);
                        }
                        //Colocamos el número de pedidos realizados
                        if ($infoPedido = $pedido->reportPEMxMiPrFX($fecha[0], $fecha[0])) {
                            $pdf->setFillColor(252, 190, 190, 1);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(35, 10, utf8_decode('Venta máxima ($):'), 1, 0, 'C', 1);
                            $pdf->setFillColor(255, 226, 205, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', '', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(40, 10, utf8_decode('$' . $infoPedido[0]['maximo']), 1, 0, 'C', 1);
                            //ESPACIO ENTRE CELDA
                            $pdf->cell(3, 10, ' ', 0, 0, 'C');
                            // Se establece un color de relleno para los encabezados.
                            $pdf->setFillColor(252, 190, 190, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', 'B', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(35, 10, utf8_decode('Venta minima ($):'), 1, 0, 'C', 1);
                            $pdf->setFillColor(255, 226, 205, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', '', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(25, 10, utf8_decode('$' . $infoPedido[0]['minimo']), 1, 0, 'C', 1);
                            //ESPACIO ENTRE CELDA
                            $pdf->cell(3, 10, ' ', 0, 0, 'C');
                            // Se establece un color de relleno para los encabezados.
                            $pdf->setFillColor(252, 190, 190, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', 'B', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(35, 10, utf8_decode('Total en ventas ($):'), 1, 0, 'C', 1);
                            $pdf->setFillColor(255, 226, 205, 1);
                            // Se establece la fuente para los encabezados.
                            $pdf->setFont('Times', '', 11);
                            // Se imprimen las celdas con los encabezados.
                            $pdf->cell(30, 10, utf8_decode('$' . $infoPedido[0]['suma']), 1, 1, 'C', 1);
                        }
                    }
                    $pdf->ln(5);
                }
            } else {
                $pdf->cell(0, 10, utf8_decode('No hay pedidos establecidos entre estas fechas'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('No hay pedidos establecidos entre estas fechas'), 1, 1);
        }
        // Se envía el documento al navegador y se llama al método footer()
        $pdf->output('I', 'Número de pedidos establecidos entre fechas.pdf');
    } else {
        header('location: ../../../views/dashboard/pedidosami.html');
    }
} else {
    header('location: ../../../views/dashboard/pedidosami.html');
}
