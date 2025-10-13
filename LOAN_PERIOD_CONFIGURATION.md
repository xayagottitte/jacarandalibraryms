# Configurable Loan Period Feature

## Summary
The loan period for book borrowing has been changed from a fixed 14 days to a configurable setting that defaults to 5 days. Each library can now have its own loan period configuration.

## Changes Made

### 1. Database Changes
- **Added Column**: `loan_period_days` to the `libraries` table (default: 5 days)
- **Updated System Setting**: Changed default `loan_period_days` from 14 to 5 in `system_settings` table
- **Migration File**: Created `databasesql/add_library_loan_period.sql`
- **Updated Schema**: Modified `databasesql/schema.sql` to include the new column

### 2. Model Updates

#### Library Model (`app/models/Library.php`)
- Added `getLoanPeriod($libraryId)` method to retrieve library-specific loan period
- Updated `updateLibrary()` method to handle `loan_period_days` field

#### Borrow Model (`app/models/Borrow.php`)
- Modified `borrowBook()` method to automatically fetch loan period from library settings
- Changed default from 14 days to use library-specific setting (fallback: 5 days)

### 3. Controller Updates

#### AdminController (`app/controllers/AdminController.php`)
- Updated `createLibrary()` to handle `loan_period_days` field
- Updated `editLibrary()` to handle `loan_period_days` field

#### LibrarianController (`app/controllers/LibrarianController.php`)
- Updated `borrowBook()` to pass loan period to the view

### 4. View Updates

#### Admin Views
- **Created**: `app/views/admin/edit-library.php` with loan period configuration
- **Updated**: `app/views/admin/create-library.php` to include loan period field
- **Updated**: `app/views/admin/libraries.php` to display loan period column in table

#### Librarian Views
- **Updated**: `app/views/librarian/borrow-book.php` to dynamically show library-specific loan period
- **Updated**: `app/views/librarian/borrows.php` - fixed BASE_PATH issues
- **Updated**: `app/views/librarian/quick-borrow.php` - fixed BASE_PATH issues

## How to Use

### For Administrators

1. **View Loan Period**: Navigate to Admin → Libraries to see each library's loan period
2. **Edit Loan Period**: 
   - Click the "Edit" button on any library
   - Change the "Loan Period (Days)" field (1-90 days)
   - Click "Update Library"
3. **Create New Library**: When creating a new library, set the loan period (default: 5 days)

### For Librarians

The loan period is automatically applied when borrowing books:
- The borrow form shows the current library's loan period
- Due dates are calculated automatically based on the library's setting
- No manual intervention needed

## Installation Instructions

### For Existing Database

Run the migration SQL file:

```sql
-- Option 1: Using MySQL command line
mysql -u root -p multi_library_system < databasesql/add_library_loan_period.sql

-- Option 2: Using phpMyAdmin
-- 1. Open phpMyAdmin
-- 2. Select "multi_library_system" database
-- 3. Go to "SQL" tab
-- 4. Copy and paste contents of databasesql/add_library_loan_period.sql
-- 5. Click "Go"
```

### For New Installation

The changes are already included in `databasesql/schema.sql`. Simply import the schema as usual.

## Technical Details

### Database Schema
```sql
ALTER TABLE libraries 
ADD COLUMN loan_period_days INT DEFAULT 5 
COMMENT 'Default loan period in days for this library';
```

### API Changes

**Library Model - New Method:**
```php
public function getLoanPeriod($libraryId)
```
Returns the loan period for a specific library (integer, days).

**Borrow Model - Updated Signature:**
```php
public function borrowBook($bookId, $studentId, $librarianId, $dueDays = null)
```
If `$dueDays` is null, it automatically fetches from library settings.

## Configuration Options

- **Minimum**: 1 day
- **Maximum**: 90 days
- **Default**: 5 days
- **Scope**: Per-library (each library can have different settings)

## Important Notes

1. **Existing Borrows**: Changing the loan period only affects NEW borrowings. Existing borrowed books keep their original due dates.
2. **System-wide Default**: The `system_settings` table still has `loan_period_days` for backwards compatibility, but library-specific settings take precedence.
3. **Fallback**: If a library doesn't have a loan period set, it defaults to 5 days.

## Testing

### Test Cases

1. ✅ Create a new library with custom loan period
2. ✅ Edit existing library's loan period
3. ✅ Borrow a book and verify due date calculation
4. ✅ Different libraries with different loan periods
5. ✅ View library list showing loan periods

### Verification Steps

1. Go to Admin → Libraries
2. Click "Edit" on a library
3. Change loan period to 7 days
4. Save changes
5. Login as librarian for that library
6. Go to Borrow Book
7. Verify info box shows "Loan period: 7 days"
8. Verify due date is 7 days from today

## Future Enhancements

Possible improvements:
- Email notifications based on loan period
- Different loan periods for different book categories
- Student-specific loan period overrides
- Automated extension/renewal based on loan period
- Analytics dashboard showing loan period effectiveness

## Troubleshooting

### Issue: Loan period not showing in library list
**Solution**: Run the migration SQL to add the column to existing libraries.

### Issue: Borrow form still shows 14 days
**Solution**: 
1. Check library has `loan_period_days` set in database
2. Clear browser cache
3. Verify LibrarianController passes `loan_period` to view

### Issue: "Column 'loan_period_days' doesn't exist"
**Solution**: Run the migration SQL file provided.

## Version History

- **v1.1.0** (October 13, 2025)
  - Added configurable loan period per library
  - Changed default from 14 to 5 days
  - Added edit-library.php view
  - Fixed BASE_PATH issues in librarian views

## Contact

For issues or questions, please contact the development team.

---
**Last Updated**: October 13, 2025
**Author**: Development Team
**Status**: Production Ready ✅
