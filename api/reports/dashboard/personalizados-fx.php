<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['fechai']) && isset($_GET['fechaf'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/pedidosPersonalizados.php');
    // Se instancia el módelo pedidos personalizado para procesar los datos.
    $pedido = new PedidosPersonalizados;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pedido->setFecha($_GET['fechai']) && $pedido->setFecha($_GET['fechaf'])) {
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Pedidos personalizados dentros de un rango de fechas','l');
        // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
        if ($dataPedido = $pedido->reportPedidosFX($_GET['fechai'],$_GET['fechaf'])) {
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255,107,107,1);//Color de relleno
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(25, 10, utf8_decode('Codigo Pedido'), 1, 0, 'C', 1);
            $pdf->cell(75, 10, utf8_decode('Descripción'), 1, 0, 'C', 1);
            $pdf->cell(40, 10, utf8_decode('Imagen Ejemplo'), 1, 0, 'C', 1);
            $pdf->cell(50, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
            $pdf->cell(25, 10, utf8_decode('Tamaño'), 1, 0, 'C', 1);
            $pdf->cell(25, 10, utf8_decode('Estado'), 1, 0, 'C', 1);
            $pdf->cell(30, 10, utf8_decode('Fecha del pedido'), 1, 1, 'C', 1);
            $pdf->ln(5);
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255,190,190,1);//Color de relleno
            $cont = 0;
            $y = 35;
            $x = 5;
            /*$pdf->SetXY($x,$y);
            $pdf->MultiCell(25, 30, utf8_decode('4'), 1, 'C', 1);
            $pdf->SetXY($x+25,$y);
            $pdf->MultiCell(75, 15, utf8_decode('Quiero un amigurumi parecido a mi rostro si se puede'), 1, 'J', 1);
            $pdf->SetXY($x+100,$y);
            $pdf->MultiCell(30, 30, $pdf->Image("../../images/pedidosper/harrypotter.png", $pdf->GetX(), $pdf->GetY(),30,30),1, 'J', 0);
            $pdf->SetXY($x+130,$y);
            $pdf->MultiCell(40, 15, utf8_decode('Jesús Gerardo Esquivel Ramírez'),1, 'J', 1);
            $pdf->SetXY($x+170,$y);
            $pdf->MultiCell(25, 15, utf8_decode('Pequeño:12cm o menos'),1, 'J', 1);
            $pdf->SetXY($x+195,$y);
            $pdf->MultiCell(25, 30, utf8_decode('Aceptado'),1, 'J', 1);
            $pdf->SetXY($x+220,$y);
            $pdf->MultiCell(30, 30, utf8_decode('Aceptado'),1, 'J', 1);

            //Prueba 2
            $y = $y+2;
            $pdf->SetXY($x,$y);
            $pdf->MultiCell(25, 30, utf8_decode('4'), 1, 'C', 1);
            $pdf->SetXY($x+25,$y);
            $pdf->MultiCell(75, 15, utf8_decode('Quiero un amigurumi parecido a mi rostro si se puede'), 1, 'J', 1);
            $pdf->SetXY($x+100,$y);
            $pdf->MultiCell(30, 30, $pdf->Image("../../images/pedidosper/harrypotter.png", $pdf->GetX(), $pdf->GetY(),30,30),1, 'J', 0);
            $pdf->SetXY($x+130,$y);
            $pdf->MultiCell(40, 15, utf8_decode('Jesús Gerardo Esquivel Ramírez'),1, 'J', 1);
            $pdf->SetXY($x+170,$y);
            $pdf->MultiCell(25, 15, utf8_decode('Pequeño:12cm o menos'),1, 'J', 1);
            $pdf->SetXY($x+195,$y);
            $pdf->MultiCell(25, 30, utf8_decode('Aceptado'),1, 'J', 1);
            $pdf->SetXY($x+220,$y);
            $pdf->MultiCell(30, 30, utf8_decode('Aceptado'),1, 'J', 1);*/
            // Se establece la fuente para los datos de los productos.
            $pdf->setFont('Times', '', 11);
            // Se recorren los registros ($dataPedido) fila por fila ($rowPedido).
            foreach ($dataPedido as $rowPedido) {
                //Aumentamos el contador
                $cont++;
                //Evaluamos si el contador esta en 4 que es él número de columnas para cambio  de página
                if($cont > 4){
                    //Si lo es colocamos de vuelta el header
                    $pdf->setFillColor(255,107,107,1);//Color de relleno
                    // Se establece la fuente para los encabezados.
                    $pdf->setFont('Times', 'B', 11);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(25, 10, utf8_decode('Codigo Pedido'), 1, 0, 'C', 1);
                    $pdf->cell(75, 10, utf8_decode('Descripción'), 1, 0, 'C', 1);
                    $pdf->cell(40, 10, utf8_decode('Imagen Ejemplo'), 1, 0, 'C', 1);
                    $pdf->cell(50, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
                    $pdf->cell(25, 10, utf8_decode('Tamaño'), 1, 0, 'C', 1);
                    $pdf->cell(25, 10, utf8_decode('Estado'), 1, 0, 'C', 1);
                    $pdf->cell(30, 10, utf8_decode('Fecha del pedido'), 1, 1, 'C', 1);
                    $pdf->ln(5);
                    // Se establece un color de relleno para los encabezados.
                    $pdf->setFillColor(255,190,190,1);//Color de relleno
                    $cont = 0;
                    $y = 35;
                    // Se establece la fuente para el contenido.
                    $pdf->setFont('Times', '', 11);
                }
                $y = $y+30;
                // Se imprimen las celdas con los datos de los productos.
                $pdf->SetXY($x, $y);
                $pdf->MultiCell(25, 30, utf8_decode($rowPedido['id_pedidos_personalizado']), 1, 'C', 1);
                $pdf->SetXY($x + 25, $y);
                $pdf->MultiCell(75, 15, utf8_decode($rowPedido['descripcion_pedidopersonal']), 1, 'J', 1);
                $pdf->SetXY($x + 100, $y);
                $pdf->MultiCell(40, 30, $pdf->Image("../../images/pedidosper/".$rowPedido['imagenejemplo_pedidopersonal'], $pdf->GetX(), $pdf->GetY(), 40, 30), 1, 'J', 0);
                $pdf->SetXY($x + 140, $y);
                $pdf->MultiCell(50, 15, utf8_decode($rowPedido['nombre_cliente'].' '.$rowPedido['apellido_cliente']), 1, 'J', 1);
                $pdf->SetXY($x + 190, $y);
                $pdf->MultiCell(25, 15, utf8_decode($rowPedido['tamano']), 1, 'J', 1);
                $pdf->SetXY($x + 215, $y);
                $pdf->MultiCell(25, 30, utf8_decode($rowPedido['estado']), 1, 'J', 1);
                $pdf->SetXY($x + 240, $y);
                $pdf->MultiCell(30, 30, utf8_decode($rowPedido['fecha_pedidopersonal']), 1, 'J', 1);
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('No hay productos para esta categoría'), 1, 1);
        }
        // Se envía el documento al navegador y se llama al método footer()
        $pdf->output('I', 'pedidospersonalizados.pdf');
    } else {
        header('location: ../../../views/dashboard/pedidosper.html');
    }
} else {
    header('location: ../../../views/dashboard/pedidosper.html');
}
