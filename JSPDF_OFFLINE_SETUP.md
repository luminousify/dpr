# jsPDF Offline Setup Instructions

## Overview
This document provides instructions for setting up jsPDF library locally for true offline PDF export functionality in the DPR application.

## Current Setup
Currently, the application uses jsPDF from CDN:
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
```

This requires an internet connection for the initial library download. For complete offline functionality, follow the steps below.

## Steps to Download jsPDF Locally

### Option 1: Manual Download
1. Download the jsPDF library file from:
   - https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js
   
2. Save the file as `jspdf.min.js` in:
   ```
   \\192.168.1.201\www\dpr\template\js\grafik\jspdf.min.js
   ```

3. Update all view files to use the local path instead of CDN:
   - Find: `<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>`
   - Replace with: `<script src="<?= base_url(); ?>template/js/grafik/jspdf.min.js"></script>`

### Option 2: Using Command Line (if you have internet access)
```bash
# Navigate to the grafik directory
cd \\192.168.1.201\www\dpr\template\js\grafik\

# Download using PowerShell (Windows)
Invoke-WebRequest -Uri "https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" -OutFile "jspdf.min.js"

# Or using curl (if available)
curl -o jspdf.min.js https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js
```

## Files That Need Updating
After downloading jsPDF locally, update the script source in all these files:

### High Priority Files:
- `application\views\home.php`
- `application\views\report\report.php`
- `application\views\report\1productivity.php`

### Global Reports:
- All files in `application\views\global\productivity\`
- All files in `application\views\global\defect\`
- All files in `application\views\global\losstime\`
- All files in `application\views\global\production_ppm\`
- All files in `application\views\global\efesiency\`
- All files in `application\views\global\qty_by_cust\`
- All files in `application\views\global\analis\`
- All files in `application\views\global\akunting\`

### Other Views:
- All files in `application\views\machine\`
- All files in `application\views\material_transaction\`

## Batch Update Script
To update all files at once after downloading jsPDF locally, you can use this PowerShell script:

```powershell
# PowerShell script to update all PHP files
$path = "\\192.168.1.201\www\dpr\application\views"
$files = Get-ChildItem -Path $path -Filter "*.php" -Recurse

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    if ($content -match "https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js") {
        $newContent = $content -replace 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', '<?= base_url(); ?>template/js/grafik/jspdf.min.js'
        Set-Content -Path $file.FullName -Value $newContent
        Write-Host "Updated: $($file.Name)"
    }
}
```

## Verification
After setup, verify that PDF export works offline by:
1. Disconnecting from the internet
2. Opening any report page with charts
3. Clicking on the export menu in any chart
4. Selecting "Download PDF document"
5. The PDF should download successfully without any errors

## Benefits of Local Setup
- Complete offline functionality
- Faster loading times (no external CDN requests)
- Better security (no external dependencies)
- Consistent availability regardless of CDN status

## Current Implementation Status
✅ All Highcharts pages have been configured with:
- offline-exporting.js for offline export capability
- fallbackToExportServer: false in chart configurations
- jsPDF library reference (currently from CDN)

Once jsPDF is downloaded locally and references are updated, the application will have complete offline PDF export functionality.