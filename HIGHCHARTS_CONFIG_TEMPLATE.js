/**
 * Highcharts Standard Configuration Template for DPR Application
 * 
 * This template provides the standard configuration for all Highcharts
 * in the DPR application with proper offline PDF export support.
 * 
 * Requirements:
 * - highcharts.js
 * - exporting.js
 * - jspdf.umd.min.js
 * - offline-exporting.js
 */

// Standard Exporting Configuration
// Use this configuration for all charts to ensure consistent PDF export
const standardExportConfig = {
    fallbackToExportServer: false,
    sourceWidth: 1200,
    sourceHeight: 600,
    scale: 2,
    buttons: {
        contextButton: {
            menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
        }
    },
    menuItemDefinitions: {
        downloadPDF: {
            text: 'Download PDF document',
            onclick: function() {
                this.exportChart({
                    type: 'application/pdf',
                    sourceWidth: 1200,
                    sourceHeight: 600
                });
            }
        }
    }
};

// Example Chart Configuration
const chartOptions = {
    chart: {
        type: 'column',  // or 'line', 'pie', 'bar', etc.
        renderTo: 'container' // Your container ID
    },
    title: {
        text: 'Your Chart Title'
    },
    xAxis: {
        categories: [] // Your categories
    },
    yAxis: {
        title: {
            text: 'Y-Axis Title'
        }
    },
    series: [], // Your data series
    plotOptions: {
        column: {
            dataLabels: {
                enabled: true
            }
        }
    },
    // Use the standard export configuration
    exporting: standardExportConfig
};

// Alternative: For charts with different aspect ratios
const wideChartExportConfig = {
    ...standardExportConfig,
    sourceWidth: 1600,
    sourceHeight: 400,
    menuItemDefinitions: {
        downloadPDF: {
            text: 'Download PDF document',
            onclick: function() {
                this.exportChart({
                    type: 'application/pdf',
                    sourceWidth: 1600,
                    sourceHeight: 400
                });
            }
        }
    }
};

const tallChartExportConfig = {
    ...standardExportConfig,
    sourceWidth: 800,
    sourceHeight: 1200,
    menuItemDefinitions: {
        downloadPDF: {
            text: 'Download PDF document',
            onclick: function() {
                this.exportChart({
                    type: 'application/pdf',
                    sourceWidth: 800,
                    sourceHeight: 1200
                });
            }
        }
    }
};

// PHP Implementation Example
/*
In your PHP views, use this structure:

<script src="<?= base_url(); ?>template/js/grafik/highcharts.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/data.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/offline-exporting.js"></script>
<script src="<?= base_url(); ?>template/js/grafik/accessibility.js"></script>

<script>
$(document).ready(function() {
    var options = {
        chart: {
            renderTo: 'container',
            type: 'column'
        },
        title: {
            text: 'Your Chart Title'
        },
        // ... other chart options ...
        exporting: {
            fallbackToExportServer: false,
            sourceWidth: 1200,
            sourceHeight: 600,
            scale: 2,
            buttons: {
                contextButton: {
                    menuItems: ['viewFullscreen', 'separator', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                }
            },
            menuItemDefinitions: {
                downloadPDF: {
                    text: 'Download PDF document',
                    onclick: function() {
                        this.exportChart({
                            type: 'application/pdf',
                            sourceWidth: 1200,
                            sourceHeight: 600
                        });
                    }
                }
            }
        }
    };
    
    var chart = new Highcharts.Chart(options);
});
</script>
*/

// For Highcharts.visualize function (used in many DPR pages)
/*
Highcharts.visualize = function(table, options) {
    // ... existing visualization code ...
    
    // Add the standard export config
    options.exporting = standardExportConfig;
    
    var chart = new Highcharts.Chart(options);
}
*/

// Notes for Developers:
// 1. Always include jsPDF before offline-exporting.js
// 2. Set fallbackToExportServer: false for offline functionality
// 3. Adjust sourceWidth/sourceHeight based on your chart's aspect ratio
// 4. Scale: 2 provides good quality, increase for higher quality (but larger file size)
// 5. For best results, test PDF export with actual data to ensure proper sizing

// Common Issues and Solutions:
// - Chart cut off in PDF: Increase sourceWidth/sourceHeight
// - Text too small in PDF: Increase scale value
// - Export fails: Ensure jsPDF is loaded before offline-exporting.js
// - Online export used: Check fallbackToExportServer is set to false