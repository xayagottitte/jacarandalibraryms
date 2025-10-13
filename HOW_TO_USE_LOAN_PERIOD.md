# How to Configure Loan Period - Step-by-Step Guide

## 🎯 Overview
This guide shows you how to configure the loan period for your libraries.

---

## 📋 Prerequisites
- You must be logged in as **Super Admin**
- Database migration must be completed (see SETUP_LOAN_PERIOD.md)

---

## 🏢 Scenario 1: Configure Loan Period for Existing Library

### Step 1: Navigate to Libraries
1. Login as Super Admin
2. Click **"Libraries"** in the left sidebar
3. You'll see the libraries list with a **"Loan Period"** column

```
┌─────────────────────────────────────────────────────────────────┐
│ Libraries                                       [ + Add Library] │
├─────────────────────────────────────────────────────────────────┤
│ Name              Type      Loan Period  Actions                │
├─────────────────────────────────────────────────────────────────┤
│ Primary Library   Primary   5 days       [Edit] [Delete]        │
│ Secondary Library Secondary 5 days       [Edit] [Delete]        │
└─────────────────────────────────────────────────────────────────┘
```

### Step 2: Edit Library
1. Click the **[Edit]** button next to the library you want to configure
2. You'll be taken to the Edit Library page

### Step 3: Set Loan Period
1. Find the **"Loan Period (Days)"** field
2. Enter your desired number of days (1-90)
3. Examples:
   - Primary School: 5 days (shorter, younger students)
   - Secondary School: 7 days (longer, older students)
   - Research Library: 14 days (extended research time)

```
┌─────────────────────────────────────────────────────────┐
│ Edit Library                    [Back to Libraries]     │
├─────────────────────────────────────────────────────────┤
│ Library Name:     [Primary School Library        ]     │
│ Library Type:     [Primary School ▼]                   │
│ Address:         [123 Main Street                ]     │
│                                                         │
│ Loan Period (Days): [7]  ← Enter number here          │
│ ℹ Number of days students can borrow books            │
│   from this library (Default: 5 days)                 │
│                                                         │
│ ℹ Note: Changing the loan period will only affect     │
│   new book borrowings. Existing borrowed books        │
│   will keep their original due dates.                 │
│                                                         │
│                           [Cancel] [Update Library]    │
└─────────────────────────────────────────────────────────┘
```

### Step 4: Save Changes
1. Click **"Update Library"** button
2. You'll see a success message: "Library updated successfully!"
3. The loan period is now active for new borrowings

---

## 🆕 Scenario 2: Create New Library with Custom Loan Period

### Step 1: Start Creating Library
1. Go to Libraries page
2. Click **"+ Add Library"** button

### Step 2: Fill in Library Details
```
┌─────────────────────────────────────────────────────────┐
│ Create New Library              [Back to Libraries]     │
├─────────────────────────────────────────────────────────┤
│ Library Name:     [Community Library            ]      │
│ Library Type:     [Secondary School ▼]                 │
│ Address:         [789 Community Center          ]      │
│                                                         │
│ Loan Period (Days): [10]  ← Set custom period         │
│ ℹ Number of days students can borrow books            │
│   from this library (Default: 5 days)                 │
│                                                         │
│                           [Cancel] [Create Library]    │
└─────────────────────────────────────────────────────────┘
```

### Step 3: Create Library
1. Enter all required fields
2. Set the loan period (default is 5 if you don't change it)
3. Click **"Create Library"**
4. Success! Library created with your custom loan period

---

## 📚 Scenario 3: Librarian Borrowing Books

### What Librarians See
When a librarian logs in and goes to borrow a book:

```
┌─────────────────────────────────────────────────────────┐
│ Borrow Book                                             │
├─────────────────────────────────────────────────────────┤
│ Select Student:   [John Smith ▼]                       │
│ Select Book:      [Harry Potter ▼]                     │
│                                                         │
│ ℹ Information:                                         │
│   • Loan period: 7 days          ← Automatic!         │
│   • Maximum books per student: 5                       │
│   • Fine for overdue books: $5 per day                │
│   • Books are due on: Oct 20, 2025  ← Auto-calculated │
│                                                         │
│                           [Cancel] [Borrow Book]       │
└─────────────────────────────────────────────────────────┘
```

**Key Points:**
- Loan period is shown automatically
- Due date is calculated automatically
- No manual date entry needed
- Consistent with library's configuration

---

## 🎓 Usage Examples

### Example 1: Different Libraries, Different Periods

```
Library Type        | Recommended Period | Reason
--------------------|-------------------|------------------------
Primary School      | 3-5 days          | Younger students
Secondary School    | 7-10 days         | Homework assignments
Research Library    | 14-21 days        | Extended research
Community Library   | 7-14 days         | Flexible for all ages
```

### Example 2: Adjusting for Special Cases

**Scenario**: Exam period coming up
- **Action**: Temporarily increase loan period from 5 to 10 days
- **Steps**:
  1. Edit library
  2. Change loan period to 10
  3. Save
  4. After exams, change back to 5

**Scenario**: Short semester break
- **Action**: Reduce loan period from 7 to 3 days
- **Steps**: Same as above

---

## ✅ Verification Checklist

After configuring loan period, verify:

- [ ] Library list shows correct loan period
- [ ] Edit form shows correct value
- [ ] Librarian borrow form shows correct period
- [ ] Due date calculates correctly
- [ ] Success message appears after saving

---

## 🔧 Common Configurations

### Small Primary School
```
Loan Period: 3 days
Reason: Quick turnaround, many students sharing few books
```

### Large Secondary School
```
Loan Period: 10 days
Reason: Longer assignments, more available books
```

### University Library
```
Loan Period: 21 days
Reason: Semester-long research projects
```

### Community Library
```
Loan Period: 14 days
Reason: Mixed audience, flexible timeline
```

---

## ⚠️ Important Notes

### What Happens When You Change the Loan Period?

✅ **Affects**:
- All NEW book borrowings
- The borrow form displays updated period
- Due date calculations for new borrows

❌ **Does NOT Affect**:
- Books already borrowed
- Existing due dates
- Past borrowing records

### Example Timeline
```
Day 0:  Library has 5-day loan period
Day 1:  Student borrows book → Due: Day 6
Day 2:  Admin changes loan period to 7 days
Day 3:  New student borrows book → Due: Day 10
        (Original student still has Day 6 due date)
```

---

## 📊 Best Practices

### 1. Start Conservative
- Begin with shorter periods (5 days)
- Increase if books are returned on time
- Decrease if overdue rates are high

### 2. Monitor Returns
- Check overdue statistics weekly
- Adjust loan periods based on data
- Different libraries can have different settings

### 3. Communicate Changes
- Inform librarians when changing periods
- Update library signage
- Consider student announcements

### 4. Seasonal Adjustments
- Longer periods during holidays
- Shorter periods near exam time
- Adjust based on library usage patterns

---

## 🆘 Troubleshooting

### Problem: Can't see "Loan Period" column
**Solution**: Run the database migration (SETUP_LOAN_PERIOD.md)

### Problem: Can't edit loan period
**Solution**: Make sure you're logged in as Super Admin

### Problem: Due date wrong on borrow form
**Solution**: 
1. Check library's loan period setting
2. Clear browser cache
3. Verify database has correct value

### Problem: Librarians confused about new dates
**Solution**: 
- Explain loan period is library-specific
- Show them the info box on borrow form
- Provide quick reference guide

---

## 📱 Quick Reference Card (Print This!)

```
╔═══════════════════════════════════════════════════╗
║     LOAN PERIOD CONFIGURATION QUICK GUIDE         ║
╠═══════════════════════════════════════════════════╣
║                                                   ║
║  TO VIEW:   Admin → Libraries → Check column     ║
║                                                   ║
║  TO EDIT:   Libraries → [Edit] → Change value    ║
║                                                   ║
║  RANGE:     1 to 90 days                         ║
║                                                   ║
║  DEFAULT:   5 days                               ║
║                                                   ║
║  APPLIES:   Only to NEW borrowings               ║
║                                                   ║
║  FORMULA:   Due Date = Today + Loan Period       ║
║                                                   ║
╚═══════════════════════════════════════════════════╝
```

---

## 🎯 Summary

1. **Admins** configure loan period in library settings
2. **System** automatically applies it when borrowing
3. **Librarians** see it on borrow forms
4. **Students** benefit from clear expectations

**It's that simple!** 🎉

---

For more details, see:
- **LOAN_PERIOD_CONFIGURATION.md** - Full documentation
- **SETUP_LOAN_PERIOD.md** - Installation guide
- **IMPLEMENTATION_SUMMARY.md** - Technical details
