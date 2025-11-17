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

if (!Number.isFinite) {
    Number.isFinite = function(value) {
        return typeof value === 'number' && isFinite(value);
    };
}

if (!Number.isNaN) {
    Number.isNaN = function(value) {
        return typeof value === 'number' && value !== value;
    };
}

function getNumericValue(selector) {
    var value = $(selector).val();
    if (value === undefined || value === null || value === '') {
        return 0;
    }

    var normalized = value.toString().replace(/,/g, '.');
    var cleaned = normalized.replace(/[^0-9.+-]/g, '').trim();

    if (cleaned === '' || /^nan$/i.test(cleaned) || cleaned === '-' || cleaned === '.' || cleaned === '+') {
        return 0;
    }

    var parsed = Number(cleaned);
    return Number.isFinite(parsed) ? parsed : 0;
}

function safeDivision(numerator, denominator) {
    var num = Number(numerator);
    var den = Number(denominator);
    if (!Number.isFinite(num) || !Number.isFinite(den) || den === 0) {
        return 0;
    }
    return num / den;
}

function writeNumeric(selector, value) {
    var numeric = Number(value);
    var formatted = Number.isFinite(numeric) ? numeric.toFixed(2) : '0.00';
    $(selector).val(formatted);
}

function canCalculateGrossNett() {
    return getNumericValue('#qty') > 0 &&
        getNumericValue('#ct_mc_aktual') > 0 &&
        (getNumericValue('#cavity') > 0 || getNumericValue('#cavity2') > 0) &&
        getNumericValue('#nwt') > 0;
}

function clearProductionOutputs() {
    $('#production_time').val('0.0');
    $('#cal_dt').val('0.0');
    writeNumeric('#nett_produksi', 0);
    writeNumeric('#gross_produksi', 0);
}

// Initialize all calculation fields to prevent NaN values on page load
function initializeCalculationFields() {
    // Ensure all numeric fields have valid initial values
    $('#amountNG').val('0');
    $('#amountLT').val('0.00');
    $('#qty_lt_minutes').val('0');
    $('#amountIdle').val('0');
    $('#cal_dt').val('0.0');
    writeNumeric('#nett_produksi', 0);
    writeNumeric('#gross_produksi', 0);
    $('#production_time').val('0.0');
    
    // Initialize totals for empty tables
    total();
    totalLT();
    clearProductionOutputs();
}

$(document).ready(function() {
    // Initialize all calculation fields to prevent NaN values
    initializeCalculationFields();
    
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
            $('#ct_mc_aktual').val(ui.item.cyt_mc_bom);
            $('#ct_mp_aktual').val(ui.item.cyt_mp_bom);
            $('#ct_mc').val(ui.item.cyt_mc_bom);
            $('#ct_mp').val(ui.item.cyt_mp_bom);
            // Store cycle time quote for target calculation
            $('#ct_quo').val(ui.item.cyt_quo || ui.item.cyt_mc_bom); // Fallback to cyt_mc_bom if cyt_quo is not available
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

            // Calculate target_mc using cycle time quote when BOM is selected
            setTarget();

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

            setTarget();
            GrossNett();
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
        GrossNett();
    });

    $('#tableLT').on('click', 'input[type="button"]', function(e) {
        $(this).closest('tr').remove();
        totalLT();
        GrossNett();
    });

    // Form submission handler
    $("#my-form").submit(function(e) {
        GrossNett();

        if (!Number.isFinite(Number($('#nett_produksi').val()))) {
            writeNumeric('#nett_produksi', getNumericValue('#nett_produksi'));
        }

        if (!Number.isFinite(Number($('#gross_produksi').val()))) {
            writeNumeric('#gross_produksi', getNumericValue('#gross_produksi'));
        }

        $("#submit").attr("disabled", true);
        $('#loading').html("Loading, please wait...");
        document.getElementById("loading").style.color = "red";
        return true;
    });
    
    // Recalculate target_mc when NWT or OT changes
    $('#nwt, #ot_mp').on('input change', function() {
        setTarget();
    });
    
    // Add event listeners to recalculate on keyup/change for input fields
    $('#qty, #amountNG, #ct_mc_aktual, #cavity, #cavity2').on('input change', function() {
        setTimeout(function() {
            total();
            totalLT();
            GrossNett();
        }, 100); // Small delay to ensure values are updated
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

// Set target calculation using cycle time quote
function setTarget() {
    var ct_quo = getNumericValue('#ct_quo'); // Use cycle time quote instead of actual
    var cavity = getNumericValue('#cavity');
    if (cavity <= 0) {
        cavity = 1;
    }
    var totalHours = getNumericValue('#nwt') + getNumericValue('#ot_mp');

    if (ct_quo > 0 && totalHours > 0) {
        var hasil = (3600 / ct_quo) * (cavity * totalHours);
        $('#target_mc').val(hasil.toFixed(2));
    } else {
        $('#target_mc').val('');
    }
    $('#target_mp').val(0);
    GrossNett();
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
    if(!canCalculateGrossNett()) {
        clearProductionOutputs();
        return;
    }

    var qty = getNumericValue('#qty');
    var defect = getNumericValue('#amountNG');
    var totalShots = qty + defect;
    var cavity = getNumericValue('#cavity');
    var cavity2 = getNumericValue('#cavity2');
    var ctAktual = getNumericValue('#ct_mc_aktual');

    // Set minimum values for cavities to prevent division by zero
    if (cavity <= 0) {
        cavity = 1;
    }
    if (cavity2 <= 0) {
        cavity2 = cavity;
    }

    // Validate required inputs for calculation
    if (qty <= 0 || totalShots <= 0 || ctAktual <= 0 || cavity <= 0 || cavity2 <= 0) {
        clearProductionOutputs();
        return;
    }

    // Calculate production time (equivalent to 'hasil_time' in admin pages)
    var productionTime = safeDivision(totalShots, cavity) * safeDivision(ctAktual, 3600);
    if (!Number.isFinite(productionTime) || productionTime < 0) {
        productionTime = 0;
    }
    // Round to 1 decimal place (equivalent to 'hasil' in admin pages)
    var hasil = productionTime.toFixed(1);
    $('#production_time').val(hasil);

    // Handle loss time - zero loss time is valid
    var lossTimeField = $('#amountLT');
    var lossTimeHours = getNumericValue(lossTimeField);
    if (!Number.isFinite(lossTimeHours) || lossTimeHours < 0) {
        lossTimeHours = 0;
    }

    lossTimeField.val(lossTimeHours.toFixed(2));

    var nwtHours = getNumericValue('#nwt');
    var otHours = getNumericValue('#ot_mp');
    var totalShiftHours = nwtHours + otHours;
    var calDtHours = totalShiftHours - parseFloat(hasil) - lossTimeHours;
    if (!Number.isFinite(calDtHours) || calDtHours < 0) {
        calDtHours = 0;
    }
    $('#cal_dt').val(calDtHours.toFixed(1));

    // Calculate work hours using the exact same formula as admin pages
    // Match admin: WorkHour = (parseFloat(hasil) + parseFloat(LT)) - parseFloat(ot)
    if (lossTimeHours == 0) { lossTimeHours = 0; }
    var WorkHour = (parseFloat(hasil) + parseFloat(lossTimeHours)) - parseFloat(otHours);
    var Overtime = otHours;
    var TotStopTime = lossTimeHours;
    var OK = qty;
    var ProductCavity = cavity2;

    // Calculate gross and nett production using exact admin page formula
    let grossProduction = 0;
    if ((WorkHour + Overtime - TotStopTime) !== 0) {
        grossProduction = 3600 / ((parseFloat(OK) / parseFloat(ProductCavity)) / (parseFloat(WorkHour) + parseFloat(Overtime)));
    }

    let nettProduction = 0;
    if (WorkHour + Overtime - TotStopTime !== 0) {
        nettProduction = 3600 / ((parseFloat(OK) / ProductCavity) / (parseFloat(WorkHour) + parseFloat(Overtime) - parseFloat(TotStopTime)));
    }

    if (!Number.isFinite(grossProduction) || grossProduction < 0) {
        grossProduction = 0;
    }
    if (!Number.isFinite(nettProduction) || nettProduction < 0) {
        nettProduction = 0;
    }

    var roundedGross = customRound(grossProduction);
    var roundedNett = customRound(nettProduction);

    if (!Number.isFinite(roundedGross)) {
        console.warn('Gross Production calculation resulted in NaN/Infinity', {
            qty: qty,
            defect: defect,
            cavity: cavity,
            cavity2: cavity2,
            ctAktual: ctAktual,
            availableHours: availableHours,
            denominator: denominator,
            rawGross: grossProduction
        });
        roundedGross = 0;
    }

    if (!Number.isFinite(roundedNett)) {
        console.warn('Nett Production calculation resulted in NaN/Infinity', {
            qty: qty,
            defect: defect,
            cavity: cavity,
            cavity2: cavity2,
            ctAktual: ctAktual,
            effectiveHours: effectiveHours,
            denominator: denominator,
            rawNett: nettProduction
        });
        roundedNett = 0;
    }

    writeNumeric('#gross_produksi', roundedGross);
    writeNumeric('#nett_produksi', roundedNett);
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
        if(!Number.isNaN(price)) {
            sum += price;
        }
    });
    $('#amountNG').val(Number.isFinite(sum) ? sum : 0);
}

// Calculate total LT (sum minutes, display as hours)
function totalLT() {
    var sum_minutes = 0;
    $('#tableLT > tr .nilai').each(function() {
        var minutes = Number($(this).val());
        if (Number.isFinite(minutes) && minutes > 0) {
            sum_minutes += minutes;
        }
    });

    var sum_hours = sum_minutes / 60;
    $('#amountLT').val(Number.isFinite(sum_hours) ? sum_hours.toFixed(2) : '0.00');

    var hiddenLtField = $('#qty_lt_minutes');
    if (hiddenLtField.length === 0) {
        hiddenLtField = $('<input>', {
            type: 'hidden',
            id: 'qty_lt_minutes',
            name: 'user[0][qty_lt]'
        }).appendTo('form');
    }
    hiddenLtField.val(sum_minutes);
}

// Calculate total Start/Stop time
function totalStartStop(qty) {
    var sum2 = parseFloat($('#amountIdle').val());
    if(Number.isNaN(sum2)) {
        sum2 = 0;
    }
    var addition = parseFloat(qty);
    if(Number.isNaN(addition)) {
        addition = 0;
    }
    sum2 += addition;
    $('#amountIdle').val(sum2);
}

