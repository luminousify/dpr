    <!-- Mainly scripts -->
    <script>
    // Only load jQuery if it's not already loaded
    if (typeof window.DPR_JQUERY_LOADED === 'undefined') {
        document.write('<script src="<?= base_url(); ?>template/js/jquery-3.1.1.min.js"><\/script>');
    }
    </script>
    <script src="<?= base_url(); ?>template/js/select2.min.js"></script>
    <script src="<?= base_url(); ?>template/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>template/js/bootstrap.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="<?= base_url(); ?>template/js/plugins/flot/jquery.flot.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="<?= base_url(); ?>template/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?= base_url(); ?>template/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?= base_url(); ?>template/js/inspinia.js"></script>
    <script src="<?= base_url(); ?>template/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?= base_url(); ?>template/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="<?= base_url(); ?>template/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="<?= base_url(); ?>template/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="<?= base_url(); ?>template/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="<?= base_url(); ?>template/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="<?= base_url(); ?>template/js/plugins/toastr/toastr.min.js"></script>


    <script>
        $(document).ready(function() {
            // Auto-dismiss success alerts after 5 seconds
            setTimeout(function() {
                $('.alert-success').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Prevent double form submissions with loading state
            $('form').on('submit', function(e) {
                var $form = $(this);
                var $submitBtn = $form.find('button[type="submit"], input[type="submit"]');
                
                // Don't prevent if already submitted
                if ($form.data('submitted') === true) {
                    e.preventDefault();
                    return false;
                }
                
                // Mark as submitted
                $form.data('submitted', true);
                
                // Add loading class to button
                $submitBtn.addClass('btn-loading').prop('disabled', true);
                
                // Store original text
                var originalText = $submitBtn.text() || $submitBtn.val();
                $submitBtn.data('original-text', originalText);
                
                // Change button text
                if ($submitBtn.is('button')) {
                    $submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                } else {
                    $submitBtn.val('Processing...');
                }
                
                // Re-enable after 3 seconds as fallback
                setTimeout(function() {
                    $form.data('submitted', false);
                    $submitBtn.removeClass('btn-loading').prop('disabled', false);
                    if ($submitBtn.is('button')) {
                        $submitBtn.html(originalText);
                    } else {
                        $submitBtn.val(originalText);
                    }
                }, 3000);
            });
            
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('PT Ciptajaya Kreasindo Utama', 'Welcome To DPR Website');

            }, 1300);


            var data1 = [
                [0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,30],[11,10],[12,13],[13,4],[14,3],[15,3],[16,6]
            ];
            var data2 = [
                [0,1],[1,0],[2,2],[3,0],[4,1],[5,3],[6,1],[7,5],[8,2],[9,3],[10,2],[11,1],[12,0],[13,2],[14,8],[15,0],[16,0]
            ];
            $("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
                data1, data2
            ],
                    {
                        series: {
                            lines: {
                                show: false,
                                fill: true
                            },
                            splines: {
                                show: true,
                                tension: 0.4,
                                lineWidth: 1,
                                fill: 0.4
                            },
                            points: {
                                radius: 0,
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#d5d5d5",
                            borderWidth: 1,
                            color: '#d5d5d5'
                        },
                        colors: ["#1ab394", "#1C84C6"],
                        xaxis:{
                        },
                        yaxis: {
                            ticks: 4
                        },
                        tooltip: false
                    }
            );

            var doughnutData = {
                labels: ["App","Software","Laptop" ],
                datasets: [{
                    data: [300,50,100],
                    backgroundColor: ["#a3e1d4","#dedede","#9CC3DA"]
                }]
            } ;


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var el1 = document.getElementById("doughnutChart");
            if (el1 && el1.getContext) {
                var ctx1 = el1.getContext("2d");
                new Chart(ctx1, {type: 'doughnut', data: doughnutData, options:doughnutOptions});
            }

            var doughnutData = {
                labels: ["App","Software","Laptop" ],
                datasets: [{
                    data: [70,27,85],
                    backgroundColor: ["#a3e1d4","#dedede","#9CC3DA"]
                }]
            } ;


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var el2 = document.getElementById("doughnutChart2");
            if (el2 && el2.getContext) {
                var ctx2 = el2.getContext("2d");
                new Chart(ctx2, {type: 'doughnut', data: doughnutData, options:doughnutOptions});
            }

        });
    </script>