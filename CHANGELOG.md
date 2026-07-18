# Changelog

All notable changes to the Estrellita Toolkit custom WordPress plugin are documented in this file.

## [2026-07-18]

### Fixed

- **Date Format Consistency for `_last_completed` Meta Field**: Fixed the issue where the `_last_completed` order meta field was being stored in inconsistent date formats (previously `Y-m-d` and `M j, Y`).
  - Changed format to `d/m/Y` (e.g., `17/07/2026`) across all code paths
  - **Files modified**:
    - `estrellita-shopmanager.php` - Updated `silibas_order_is_completed()` function
    - `woocommerce.php` - Updated `silibas_add_status_change_date_column_content()` function
  - **Reason**: Ensures consistency between stored database values and CSV exports; aligns format with QuickBooks Online requirements
  - **Impact**: CSV exports from WooCommerce Customer / Order / Coupon Export plugin now display consistent date formatting
  - **Testing**: Verified that new orders marked as completed now store `_last_completed` in the correct `d/m/Y` format

### Technical Details

**Root Cause**: Multiple code paths were writing `_last_completed` in different formats:

- `woocommerce_order_status_completed` hook was using `Y-m-d` format
- Admin order list column display function was using `M j, Y` format (display format used to store data)

**Solution**: Standardized all `_last_completed` writes to use `d/m/Y` format for consistency and compatibility with external systems (CSV exports, QuickBooks Online).

---

## Version History

This is the first tracked version of the Estrellita Toolkit private maintenance repository. Previous development history is not included.
