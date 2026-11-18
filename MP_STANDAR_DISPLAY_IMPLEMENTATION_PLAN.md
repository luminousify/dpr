# MP_STANDAR Column Display Implementation Plan

## Overview

This document outlines the implementation plan for adding `mp_standar` column display functionality across the master product pages at:
- http://server64/dpr/c_new/search_master_product
- http://server64/dpr/c_new/master_productAct
- http://server64/dpr/c_new/edit_master_product/766

## Current State Analysis

Based on the examination of the view files, I can see that:

1. **`master_product.php`** - Already has a "Usage" column header (line 76) but no corresponding data column
2. **`master_productAct.php`** - Has a form field for "Usage" (line 89) but no corresponding data column
3. **`edit_master_product.php`** - Has a form field for "Usage" (line 89) but no corresponding data column

## Implementation Tasks

### 1. Update Table Headers in `master_product.php`

**File:** `application/views/master/master_product.php`
**Location:** Lines 67-88 (table header section)

**Changes Required:**
- Add "MP Standar" column header after "Usage" column
- Update footer section to include the new column

**Before:**
```html
<th style="text-align: center;" class="align-middle"><b>Usage</b></th>
```

**After:**
```html
<th style="text-align: center;" class="align-middle"><b>Usage</b></th>
<th style="text-align: center;" class="align-middle"><b>MP Standar</b></th>
```

### 2. Update Table Data Display in `master_product.php`

**File:** `application/views/master/master_product.php`
**Location:** Lines 91-156 (table body section)

**Changes Required:**
- Add MP Standar data column after Usage column
- Update footer section to include the new column

**Before:**
```html
<td>
    <?php echo $data->usage   ; ?>
</td>
```

**After:**
```html
<td>
    <?php echo $data->usage   ; ?>
</td>
<td>
    <?php echo $data->mp_standar; ?>
</td>
```

### 3. Update Table Headers in Footer Section

**File:** `application/views/master/master_product.php`
**Location:** Lines 159-180 (footer section)

**Changes Required:**
- Add "MP Standar" column header in footer table

**Before:**
```html
<th><b>Usage</b></th>
```

**After:**
```html
<th><b>Usage</b></th>
<th><b>MP Standar</b></th>
```

### 4. Update Form Fields in `master_productAct.php`

**File:** `application/views/master/master_productAct.php`
**Location:** Line 89 (Usage field)

**Changes Required:**
- Add MP Standar field after Usage field
- Update JavaScript to handle the new field

**Before:**
```html
<td><input type="text" name="user['+save+'][usage]" value="0" class="form-control"   /></td>
```

**After:**
```html
<td><input type="text" name="user['+save+'][usage]" value="0" class="form-control"   /></td>
<td><input type="text" name="user['+save+'][mp_standar]" value="0" class="form-control"   /></td>
```

### 5. Update Form Fields in `edit_master_product.php`

**File:** `application/views/master/edit_master_product.php`
**Location:** Line 89 (Usage field)

**Changes Required:**
- Add MP Standar field after Usage field
- Update form to display current value

**Before:**
```html
<div class="form-group col-md-3">
    <label for="usage">Usage</label>
    <input type="text" class="form-control" name="usage" value="<?php echo $dp->usage ?>">
</div>
```

**After:**
```html
<div class="form-group col-md-3">
    <label for="usage">Usage</label>
    <input type="text" class="form-control" name="usage" value="<?php echo $dp->usage ?>">
</div>
<div class="form-group col-md-3">
    <label for="mp_standar">MP Standar</label>
    <input type="text" class="form-control" name="mp_standar" value="<?php echo $dp->mp_standar ?>">
</div>
```

### 6. Update Controller Methods

**File:** `application/controllers/c_new.php`
**Methods to Update:**
- `update_master_product()` - Already updated to handle `mp_standar` field
- `master_productAct()` - Add handling for `mp_standar` field in form submission

**Changes Required:**
- Ensure `mp_standar` field is properly processed in both add and edit operations

### 7. Update Model Methods

**File:** `application/models/m_new.php`
**Methods to Update:**
- All methods already updated to include `mp_standar` in SELECT queries
- Verify all queries properly escape the `usage` keyword

**Changes Required:**
- No additional changes needed (already fixed)

## Implementation Sequence

1. **Update Table Headers** (master_product.php)
2. **Update Table Data Display** (master_product.php)
3. **Update Footer Headers** (master_product.php)
4. **Update Add Form** (master_productAct.php)
5. **Update Edit Form** (edit_master_product.php)
6. **Test Functionality** (all pages)

## Testing Checklist

After implementation, verify:

- [ ] MP Standar column appears in product listing table
- [ ] MP Standar values are displayed correctly (including NULL values)
- [ ] Add form includes MP Standar field
- [ ] Edit form includes MP Standar field with current value
- [ ] Form submission saves MP Standar value correctly
- [ ] Search functionality includes MP Standar in results
- [ ] No JavaScript errors in browser console
- [ ] No PHP errors in application logs

## Notes

1. The `mp_standar` field should be displayed as a numeric value with appropriate formatting
2. NULL values should be displayed as empty or "N/A"
3. The field should be properly validated (numeric input)
4. All changes should maintain backward compatibility with existing data

## Files to Modify

1. `application/views/master/master_product.php`
2. `application/views/master/master_productAct.php`
3. `application/views/master/edit_master_product.php`
4. `application/controllers/c_new.php` (if needed)

## Estimated Time

- **Implementation:** 2-3 hours
- **Testing:** 1 hour
- **Total:** 3-4 hours