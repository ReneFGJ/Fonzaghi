<?php
/**
 * @Author: Willian Fellipe Laynes <willianlaynes@hotmail.com>
 * Data de criação: 19/06/2013
 * PS035 - Gestão da capacitação Master
 * @Version: v0.13.24
 */

class grafico
{
    
    
        function grafico_dinamico($grs,$modelo,$titulo)
        {       
            $sx =   '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                    google.load(\'visualization\', \'1\', {packages: [\'charteditor\']});
                    </script>
                    <script type="text/javascript">
                    var wrapper;
            
                    function init() {
                    wrapper = new google.visualization.ChartWrapper({
                    chartType: \''.$modelo.'\',
                    dataTable: ['.$grs.'],
                    options: {\'title\': \''.$titulo.'\'},
                    containerId: \'vis_div\'
                    });
                    wrapper.draw();
                    }
    
                    function openEditor() {
                    // Handler for the "Open Editor" button.
                    var editor = new google.visualization.ChartEditor();
                    google.visualization.events.addListener(editor, \'ok\',
                    function() {
                    wrapper = editor.getChartWrapper();
                    wrapper.draw(document.getElementById(\'visualization\'));
                    });
                    editor.openDialog(wrapper);
                    }
    
    
                    google.setOnLoadCallback(init);
    
                    </script>
                    </head>
                    <body style="font-family: Arial;border: 0 none;">
                    <input type=\'button\' onclick=\'openEditor()\' value=\'Gerar Gráfico\'>
                    <center><div id=\'visualization\' style="width:95%;height:400px"></center>
                    </body> ';
            return ($sx);
            
        }
        }
?>            