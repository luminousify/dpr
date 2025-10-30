/**
 * DPR Online - Input DPR Page JavaScript
 * Optimized for performance with debouncing and efficient DOM manipulation
 */

// Debounce utility function to reduce AJAX calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Global variables for dynamic table rows
var save = -1;
var sv = 0;
var saveLT = -1;
var svLT = 0;

$(document).ready(function() {
    // Set current date as default
    document.getElementById("tanggal").valueAsDate = new Date();
    
    // Set date restrictions
    var today = new Date();
    var maxDate = today.toISOString().split('T')[0];
    
    // Calculate 2 days before
    var minDate = new Date();
    minDate.setDate(minDate.getDate() - 2);
    var minDateString = minDate.toISOString().split('T')[0];
    
    // Apply restrictions to date input
    document.getElementById("tanggal").setAttribute('max', maxDate);
    document.getElementById("tanggal").setAttribute('min', minDateString);

    // BOM Autocomplete with debouncing
    $('.autocompleteBom').autocomplete({
        source: function(request, response) {
            // Debounced AJAX call
            debounce(function() {
                $.ajax({
                    url: BASE_URL + "c_operator/get_autocomplete",
                    data: { term: request.term },
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    }
                });
            }, 300)();
        },
        minLength: 2,
        select: function(event, ui) {
            $('#kp').html(ui.item.kp_pr);
            $('#id_bomS').val(ui.item.id_bom);
            $('#ct_mc_aktual').html(ui.item.cyt_mc_bom + ' <br/> ' + ui.item.cyt_mp_bom);
            $('#ct_mc').val(ui.item.cyt_mc_bom);
            $('#ct_mp').val(ui.item.cyt_mp_bom);
            $('#customer').val(ui.item.customer);
            $('#proses').val(ui.item.kode_proses);
            $('#id_pr').val(ui.item.id_pr);
            
            if(ui.item.cavity_product == null) {
                $('#cavity').val(1);
                $('#cavity2').val(1);
            } else {
                $('#cavity').val(ui.item.cavity_product);
                $('#cavity2').val(ui.item.cavity_product);
            }

            var nwt = $('#nwt_mp').val();
            var ot = $('#ot_mp').val();
            var target = (((parseFloat(nwt) + parseInt(ot)) * 3600) / (parseInt(ui.item.cyt_mc_bom) + parseInt(ui.item.cyt_mp_bom)));
            $('#Target').val(parseInt(target));

            var id_bom = ui.item.id_bom;
            $('#tes').val(ui.item.hasil);

            // Load BOM Mesin data
            $.ajax({
                type: "POST",
                url: BASE_URL + "c_operator/getdatabomMesinDPR",
                data: "id_bom=" + id_bom,
                success: function(data) {
                    $("#mesin").html(data);
                }
            });

            // Load Release data
            $.ajax({
                type: "POST",
                url: BASE_URL + "c_operator/getdataRelease",
                data: "id_bom=" + id_bom,
                success: function(data) {
                    var url = BASE_URL + "c_operator/showRelease/" + id_bom;
                    $('#release').load(url, 'refresh');
                }
            });
        }
    });

    // Defect Autocomplete with debouncing
    $('.autocompletedefect').autocomplete({
        source: function(request, response) {
            debounce(function() {
                $.ajax({
                    url: BASE_URL + "c_operator/get_autocompleteDefect",
                    data: { term: request.term },
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    }
                });
            }, 300)();
        },
        minLength: 0,
        select: function(event, ui) {
            $('#kategoriNG').val(ui.item.kategori);
            $('#satuanNG').val(ui.item.satuan);
            $('#typeNG').val(ui.item.type);
            $('#namaNGOk').val(ui.item.label);
            $('#namaNG').val(ui.item.label);
            return false;
        }
    }).on('focus', function() {
        $(this).autocomplete('search', '');
    }).on('input', function(e) {
        e.preventDefault();
        return false;
    }).attr('readonly', 'readonly')
      .css('background-color', '#FFFFFF');

    // Losstime Autocomplete with debouncing
    $('.autocompletelosstime').autocomplete({
        source: function(request, response) {
            debounce(function() {
                $.ajax({
                    url: BASE_URL + "c_operator/get_autocompleteLosstime",
                    data: { term: request.term },
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    }
                });
            }, 300)();
        },
        minLength: 0,
        select: function(event, ui) {
            $('#kategoriLT').val(ui.item.kategori);
            $('#satuanLT').val(ui.item.satuan);
            $('#typeLT').val(ui.item.type);
            $('#namaLT').val(ui.item.label);
            return false;
        }
    }).on('focus', function() {
        $(this).autocomplete('search', '');
    }).on('input', function(e) {
        e.preventDefault();
        return false;
    }).attr('readonly', 'readonly')
      .css('background-color', '#FFFFFF');

    // Cutting Tools Autocomplete
    $(".autocompletecuttingtools").autocomplete({
        minLength: 1,
        source: function(request, response) {
            debounce(function() {
                $.ajax({
                    url: SITE_URL + "c_dpr/get_autocomplete_cutting_tools",
                    dataType: "json",
                    data: { term: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return { label: item.label, value: item.value, id: item.id };
                        }));
                    }
                });
            }, 300)();
        },
        select: function(event, ui) {
            $('#cutting_tools_code').val(ui.item.label);
            $('#cutting_tools_id').val(ui.item.id);
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.label + "</div>")
            .appendTo(ul);
    };

    // Add cutting tool button handler
    $('#addCuttingToolBtn').click(function() {
        var code = $('#cutting_tools_code').val();
        var id = $('#cutting_tools_id').val();
        if (!id || !code) return;
        
        // Prevent duplicate
        if ($('input[name="cutting_tools_ids[]"][value="' + id + '"]').length) {
            $('#cutting_tools_code').val('');
            $('#cutting_tools_id').val('');
            return;
        }
        
        var rowCount = $('#cuttingToolsTable tbody tr').length + 1;
        var row = '<tr data-id="' + id + '">' +
            '<td>' + rowCount + '</td>' +
            '<td>' + code + '</td>' +
            '<td><button type="button" class="btn btn-danger btn-sm remove-tool">Hapus</button>' +
            '<input type="hidden" name="cutting_tools_ids[]" value="' + id + '"></td>' +
            '</tr>';
        $('#cuttingToolsTable tbody').append(row);
        $('#cutting_tools_code').val('');
        $('#cutting_tools_id').val('');
    });

    // Remove cutting tool handler
    $('#cuttingToolsTable').on('click', '.remove-tool', function() {
        $(this).closest('tr').remove();
        // Re-number rows
        $('#cuttingToolsTable tbody tr').each(function(idx) {
            $(this).find('td:first').text(idx + 1);
        });
    });

    // Initialize totals
    total();
    totalLT();

    // Table row removal handlers
    $('#tableNG').on('click', 'input[type="button"]', function(e) {
        $(this).closest('tr').remove();
        total();
    });

    $('#tableLT').on('click', 'input[type="button"]', function(e) {
        $(this).closest('tr').remove();
        totalLT();
    });

    // Form submission handler
    $("#my-form").submit(function(e) {
        $("#submit").attr("disabled", true);
        $('#loading').html("Loading, please wait...");
        document.getElementById("loading").style.color = "red";
        return true;
    });
});

// Check BOM selection
function cekNoNamaBOM() {
    var id_bom = $("#id_bomS").val();
    if(id_bom == '') {
        alert('Silahkan pilih No & Nama Bom terlebih dahulu!');
        $('#barcode_sparepart_code').val('');
    }
}

// Set target calculation
function setTarget(id) {
    var ct_aktual = $('#ct_mc_aktual').val();
    var cavity = $('#cavity').val();
    var nwt = $('#nwt').val();
    var ot = $('#ot_mp').val();
    let nwt_plus_ot = parseFloat(nwt) + parseInt(ot);

    var hasil = ((3600 / ct_aktual) * (cavity * nwt_plus_ot));
    $('#target_mc').val(hasil.toFixed(2));
    $('#target_mp').val(0);
}

// Lot generation function
function lot(id) {
    var tanggal = $("#tanggal").val();
    var ambil_tahun = tanggal.substr(2, 2);
    var ambil_bulan = tanggal.substr(8, 2);
    var ambil_tanggal = tanggal.substr(5, 2);
    var mesin = $("#mesin").val();
    var sh = $("#shift").val();
    var id_bomS = $("#id_bomS").val();
    
    var shift;
    if(sh == '1') { shift = 'A'; }
    else if(sh == '2') { shift = 'B'; }
    else if(sh == '3') { shift = 'C'; }
    
    var lotproduksi_save = ambil_tahun + ambil_tanggal + ambil_bulan + mesin + shift;
    $('#lotGlobalSave').val(lotproduksi_save);
    $('#lotGlobal').html('<b>' + lotproduksi_save + '</b>');
    
    var id_production = ambil_tahun + ambil_tanggal + ambil_bulan + $('#waktu').val() + id_bomS;
    $('#id_production').val(id_production);
    GrossNett();

    if($('#id_bomS').val() == '' && $('#mesin_save').val() == '') {
        $('#save').css("display", "none");
    } else {
        $('#save').css("display", "");
    }
    
    for(var i = 0; i < 10; i++) {
        let usageNya = $('.iniUsage' + i).val();
        $('.ubahQtyRelease' + i).val(usageNya * id);
        $('.ubahQtyReleaseText' + i).html('<b>' + usageNya * id + '</b>');
    }
}

// Gross and Nett Production calculation
function GrossNett() {
    var qty = parseFloat($('#qty').val());
    var ct_aktual = $('#ct_mc_aktual').val();
    var cavity = $('#cavity').val();
    var cavity2 = $('#cavity2').val();
    var defect = parseFloat($('#amountNG').val());
    var kalkulasi = qty + defect;
    var hasil_time = (kalkulasi / cavity) * (ct_aktual / 3600);

    console.log("cavity1: " + cavity);
    console.log("cavity2: " + cavity2);

    var hasil = hasil_time.toFixed(1);
    $('#production_time').val(hasil);
    
    // LT input is in HOURS (user-friendly), stored as MINUTES in database
    var LT = parseFloat($('#amountLT').val()) || 0; // Loss Time in hours (user input)
    var LT_new = LT; // Already in hours for calculations
    var calcDT = $('#amountIdle').val() / 60;

    var nwt = $('#nwt').val();
    var ot = $('#ot_mp').val();
    var nwt_new = parseFloat(nwt) + parseInt(ot);

    var calDT_new = nwt_new - hasil;
    var calDT_new_lagi = calDT_new - LT_new; // LT_new is in hours
    
    var raw_nilaiGross = 3600 * (nwt_new - calDT_new_lagi) / qty * cavity;
    var raw_nilaiGross_2 = 3600 * nwt_new / qty * cavity2;
    var nilaiGross = Math.round(raw_nilaiGross);
    var nilaiGross_2 = Math.round(raw_nilaiGross_2);
    
    if(calDT_new_lagi < 0) {
        $('#cal_dt').val(0);
    } else {
        $('#cal_dt').val(calDT_new_lagi.toFixed(1));
    }

    if(nwt_new == 8) {
        if(hasil > 8) {
            $('#gross_produksi').val(nilaiGross_2.toFixed(2));
        } else {
            $('#gross_produksi').val(nilaiGross.toFixed(2));
        }
    } else {
        if(hasil > 5) {
            $('#gross_produksi').val(nilaiGross_2.toFixed(2));
        } else {
            $('#gross_produksi').val(nilaiGross.toFixed(2));
        }
    }

    // Nett calculation
    if(defect != 0) {
        var nilaiNett = nilaiGross;
    } else {
        var nilaiNett = Math.round((hasil * 3600 / kalkulasi) * cavity2);
    }
    
    $('#nett_produksi').val(nilaiNett.toFixed(2));
    
    if(LT == 0) { LT = 0; }
    // LT is already in hours (user input), use directly in calculations
    var WorkHour = (parseFloat(hasil) + parseFloat(LT)) - parseFloat(ot);
    var Overtime = ot;
    var TotStopTime = LT; // Loss Time in hours (same as user input)
    var OK = qty;
    var ProductCavity = cavity2;
    
    let grossProduction = 0;
    if((WorkHour + Overtime - TotStopTime) !== 0) {
        grossProduction = 3600 / ((parseFloat(OK) / parseFloat(ProductCavity)) / (parseFloat(WorkHour) + parseFloat(Overtime)));
    }

    let NProd = 0;
    if(WorkHour + Overtime - TotStopTime !== 0) {
        NProd = 3600 / ((parseFloat(OK) / ProductCavity) / (parseFloat(WorkHour) + parseFloat(Overtime) - parseFloat(TotStopTime)));
    }

    var nilaiGross = customRound(raw_nilaiGross);
    $('#nett_produksi').val(customRound(NProd).toFixed(2));
    $('#gross_produksi').val(customRound(grossProduction).toFixed(2));

    console.log("debug : " + WorkHour, Overtime, TotStopTime, OK, ProductCavity);
    console.log("debug WorkHour: " + WorkHour);
    console.log("debug hasil: " + hasil);
    console.log("debug hasil_time: " + hasil_time);
    console.log("debug LT: " + LT);
    console.log("debug Kalkulasi : " + kalkulasi);
    console.log("debug cavity : " + cavity);
    console.log("debug ct_aktual : " + ct_aktual);
    console.log("debug Overtime: " + Overtime);
    console.log("debug TotStopTime: " + TotStopTime);
    console.log("debug OK: " + OK);
    console.log("debug ProductCavity: " + ProductCavity);
    console.log("result gross: " + grossProduction);
    console.log("result nett: " + NProd);
}

// Custom rounding function
function customRound(value) {
    var remainder = value % 1;
    if(remainder <= 0.5) {
        return Math.floor(value);
    } else {
        return Math.ceil(value);
    }
}

// Convert minutes to hours (for display purposes if needed)
function convertJam() {
    var LT_menit = $("#qtyLT").val();
    var nilaiLTJam = LT_menit / 60; // Correct conversion: minutes to hours
    $('#qtyLT_jam').val(nilaiLTJam.toFixed(2));
}

// Add NG (Defect) row
function addNG(id) {
    save++;
    sv++;
    var nama = $("#namaNG").val();
    var kategori = $('#kategoriNG').val();
    var type = $('#typeNG').val();
    var satuan = $('#satuanNG').val();
    var qty = $('#qtyNG').val();
    
    var markup = "<tr><td><input type='button' value='X'></td><td>" + sv + "</td>" +
        "<td><input type='hidden' name='detail[" + save + "][nama]' value='" + nama + "'/>" + nama + "</td>" +
        "<td><input type='hidden' name='detail[" + save + "][kategori]' value='" + kategori + "''>" + kategori + "</td>" +
        "<td><input type='hidden' name='detail[" + save + "][qty]' value=" + qty + " class='nilai'>" + qty + "</td>" +
        "<td><input type='hidden' name='detail[" + save + "][satuan]' value=" + satuan + ">" + satuan + 
        "<input type='hidden' name='detail[" + save + "][type]' value=" + type + "></td>" +
        "</tr>";
    
    if(kategori == '') {
        alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
    } else {
        $("#tableNG").append(markup);
    }
    
    total();
    GrossNett();
    $('.formNG').val('');
}

// Add LT (Loss Time) row
function addLT(id) {
    saveLT++;
    svLT++;
    var nama = $('#namaLT').val();
    var kategori = $('#kategoriLT').val();
    var type = $('#typeLT').val();
    var satuan = $('#satuanLT').val();
    var qty_hours = parseFloat($('#qtyLT').val()); // User inputs in hours
    
    // Validate: Loss Time should not exceed 8 hours per shift
    if(qty_hours > 8) {
        alert('Loss Time tidak boleh lebih dari 8 jam per shift!');
        $('#qtyLT').val('');
        return false;
    }
    
    if(qty_hours <= 0 || isNaN(qty_hours)) {
        alert('Masukkan nilai Loss Time yang valid (dalam jam)!');
        $('#qtyLT').val('');
        return false;
    }
    
    var qty_minutes = qty_hours * 60; // Convert to minutes for storage
    
    var markup = "<tr><td><input type='button' value='X'></td><td>" + svLT + "</td>" +
        "<td><input type='hidden' name='detailLT[" + saveLT + "][nama]' value='" + nama + "''>" + nama + "</td>" +
        "<td><input type='hidden' name='detailLT[" + saveLT + "][kategori]' value='" + kategori + "'>" + kategori + "</td>" +
        "<td><input type='hidden' name='detailLT[" + saveLT + "][qty]' value=" + qty_minutes + " class='nilai'>" + qty_hours + " Jam</td>" +
        "<td><input type='hidden' name='detailLT[" + saveLT + "][satuan]' value=" + satuan + ">" + satuan + 
        "<input type='hidden' name='detailLT[" + saveLT + "][type]' value=" + type + "></td>" +
        "</tr>";
    
    if(kategori == '') {
        alert('Silahkan pilih nama defect/losstime terlebih dahulu!');
    } else {
        $("#tableLT").append(markup);
    }
    
    totalLT();
    
    if(kategori == 'START/STOP') {
        totalStartStop(qty_minutes); // Pass minutes for consistency
    }
    
    GrossNett();
    $('.formLT').val('');
}

// Calculate total NG
function total() {
    var sum = 0;
    $('#tableNG > tr').each(function() {
        var price = parseFloat($(this).find('.nilai').val());
        sum += price;
        $('#amountNG').val(parseFloat(sum));
    });
}

// Calculate total LT (sum minutes, display as hours)
function totalLT() {
    var sum_minutes = 0;
    $('#tableLT > tr').each(function() {
        var minutes = parseFloat($(this).find('.nilai').val()); // Hidden field stores minutes
        sum_minutes += minutes;
    });
    var sum_hours = (sum_minutes / 60).toFixed(2); // Convert to hours for display
    $('#amountLT').val(sum_hours); // Display in hours
    // Store minutes in hidden field for form submission
    if($('input[name="user[0][qty_lt]"]').length) {
        $('input[name="user[0][qty_lt]"]').val(sum_minutes);
    }
}

// Calculate total Start/Stop time
function totalStartStop(qty) {
    var sum2 = parseFloat($('#amountIdle').val());
    sum2 += parseFloat(qty);
    $('#amountIdle').val(sum2);
}

