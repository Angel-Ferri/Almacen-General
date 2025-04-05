<?php

?>

<script type="text/javascript" src="jspdf.min.js"></script>
    <script type="text/javascript">
        function genPDF(mes, anio) {
            var doc = new jsPDF();

            // Generar contenido del PDF
            let mensaje = 'Productos del mes: ' + mes + ' de ' + anio;
            doc.text(20, 20, mensaje);
            doc.addPage();
            doc.text(20, 20, 'Mi trabajo!!');

            // Guardar el archivo con un nombre personalizado
            doc.save('productos_' + mes + '_' + anio + '.pdf');
        }

</script>

