# Changelog

All notable changes to the Estrellita Toolkit custom WordPress plugin are documented in this file.

## [2026-07-18]

### Fixed

- **Date Format Consistency for `_last_completed` Meta Field**: Fixed the issue where the `_last_completed` order meta field was being stored in inconsistent date formats (previously `Y-m-d` and `M j, Y`).
  - Changed format to `m/d/Y` (e.g., `07/17/2026`) across all code paths
  - **Files modified**:
    - `estrellita-shopmanager.php` - Updated `silibas_order_is_completed()` function
    - `woocommerce.php` - Updated `silibas_add_status_change_date_column_content()` function
  - **Reason**: Ensures consistency between stored database values and CSV exports; aligns format with QuickBooks Online requirements
  - **Impact**: CSV exports from WooCommerce Customer / Order / Coupon Export plugin now display consistent date formatting
  - **Testing**: Verified that new orders marked as completed now store `_last_completed` in the correct `m/d/Y` format

- **Preserve "National" in PD K1 CSV Product Exports**: Fixed CSV export formatting that was incorrectly removing the word "National" from the "PD K1 National" product title.
  - **Root cause**: Legacy code removed "National" from all variation products using `str_replace()`, causing "PD K1 National" to export as "PD K1"
  - **Solution**: Added targeted exception to preserve "National" only for "PD K1 National" products while maintaining legacy behavior for other products
  - **Files modified**: `woocommerce.php` - Function `sv_wc_csv_export_order_line_item_name()`
  - **Impact**: CSV exports now match WooCommerce product titles; ensures data consistency with QuickBooks Online and accounting systems
  - **Non-breaking**: Other products continue to have "National" removed as before; backward compatible

- **Product Object Validation in CSV Export Function**: Added type checking to prevent fatal errors in CSV export processing.
  - **Root cause**: Function assumed `$product` was always a valid WC_Product object without validation
  - **Solution**: Added `instanceof WC_Product` check before accessing product methods
  - **Files modified**: `woocommerce.php` - Function `sv_wc_csv_export_order_line_item_name()`
  - **Impact**: Improved code robustness and error handling during CSV export operations
  - **Non-breaking**: Only adds safety validation; existing functionality unchanged

### Technical Details

**Root Cause**: Multiple code paths were writing `_last_completed` in different formats:

- `woocommerce_order_status_completed` hook was using `Y-m-d` format
- Admin order list column display function was using `M j, Y` format (display format used to store data)

**Solution**: Standardized all `_last_completed` writes to use `d/m/Y` format for consistency and compatibility with external systems (CSV exports, QuickBooks Online).

---

## Version History

This is the first tracked version of the Estrellita Toolkit private maintenance repository. Previous development history is not included.
