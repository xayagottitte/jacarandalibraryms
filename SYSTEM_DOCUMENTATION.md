# Jacaranda Library Management System - System Documentation

## Table of Contents
1. Introduction
2. Project Initiation Phase
3. Planning Phase
4. Design Phase
5. Implementation Phase
6. Testing Phase
7. Deployment Phase
8. Maintenance & Support Phase
9. Closure Phase
10. Appendices

---

## 1. Introduction
- **Project Name:** Jacaranda Library Management System
- **Purpose:** To automate and streamline library operations for multiple libraries, including book management, borrowing, reporting, and user management.
- **Stakeholders:** Administrators, Librarians, Students, IT Support, Developers
- **Scope:** Multi-library support, user roles, book inventory, borrow/return, reporting, security, and mobile/responsive access.

---

## 2. Project Initiation Phase
- **Business Case:**
  - Need for efficient library management across multiple branches
  - Reduce manual errors and improve tracking
  - Enable digital access for users and staff
- **Objectives:**
  - Centralize library operations
  - Provide secure, role-based access
  - Support reporting and analytics
- **Requirements Gathering:**
  - Interviews with stakeholders
  - Review of existing processes
  - Documentation of functional and non-functional requirements
- **Feasibility Study:**
  - Technical feasibility (PHP, MySQL, Apache)
  - Financial feasibility (cost analysis)
  - Operational feasibility (staff training)
- **Approval & Authorization:**
  - Project charter signed
  - Budget and resources allocated

---

## 3. Planning Phase
- **Project Plan:**
  - Timeline and milestones
  - Resource allocation
  - Risk management plan
- **System Architecture:**
  - MVC pattern (Model-View-Controller)
  - MySQL database design
  - Responsive web interface
- **Technology Stack:**
  - PHP 7+, MySQL, Apache, Bootstrap 5, PHPMailer
- **Roles & Responsibilities:**
  - Admin: System configuration, user management, reporting
  - Librarian: Book and student management, borrow/return
  - Student: Search, borrow, manage profile
- **Documentation Plan:**
  - User manual
  - Developer documentation
  - API documentation (future enhancement)

---

## 4. Design Phase
- **System Design:**
  - Database schema (see Appendices)
  - UI/UX wireframes
  - Security design (.htaccess, input validation)
- **Module Design:**
  - Authentication & Login
  - User Management
  - Book Management
  - Borrow/Return System
  - Reporting & Analytics
  - Email Notifications
- **Integration Design:**
  - Google OAuth
  - PHPMailer for SMTP
- **Testing Design:**
  - Unit tests for models
  - Integration tests for controllers
  - UI tests for views

---

## 5. Implementation Phase
- **Development Process:**
  - Version control (Git)
  - Feature branches and code reviews
  - Coding standards (PSR-12)
- **Key Features Implemented:**
  - Secure login and registration
  - Role-based access control
  - Book CRUD operations
  - Borrow/return workflow
  - Lost book tracking
  - Category and library management
  - Student management
  - Email notifications (password reset, borrow alerts)
  - Reporting (borrows, inventory, fines)
  - Activity logging and dashboard insights
- **Security Measures:**
  - CSRF protection
  - Password hashing
  - Account lockout
  - Directory and file access restrictions
- **Documentation:**
  - Inline code comments
  - README.md for setup
  - SYSTEM_DOCUMENTATION.md for full lifecycle

---

## 6. Testing Phase
- **Test Plan:**
  - Functional testing (all features)
  - Security testing (OWASP ZAP, manual review)
  - Usability testing (mobile and desktop)
  - Performance testing (load, stress)
- **Test Cases:**
  - Login/logout
  - User registration/approval/rejection
  - Book add/edit/delete/restore
  - Borrow/return/lost book
  - Email notifications
  - Report generation/export
- **Bug Tracking:**
  - Issues tracked in GitHub
  - Debug logs and error logs
- **Acceptance Criteria:**
  - All critical bugs resolved
  - Stakeholder sign-off

---

## 7. Deployment Phase
- **Deployment Checklist:**
  - Final code review and merge to main branch
  - Database migration and backup
  - Server configuration (Apache, PHP, MySQL)
  - .htaccess security setup
  - SSL certificate for HTTPS
  - Environment variable configuration
- **Go-Live:**
  - Announce launch to stakeholders
  - Monitor system for issues
  - Provide user training

---

## 8. Maintenance & Support Phase
- **Ongoing Support:**
  - Bug fixes and updates
  - User support and helpdesk
  - Regular backups
  - Security patching
- **Enhancements:**
  - Mobile app API
  - SMS notifications
  - Advanced search
  - Integration with external systems
- **Documentation Updates:**
  - Update SYSTEM_DOCUMENTATION.md for major changes
  - Maintain change log

---

## 9. Closure Phase
- **Project Closure Activities:**
  - Final stakeholder review
  - Archive project documentation
  - Final backup and data export
  - Lessons learned session
  - Release resources
- **Post-Implementation Review:**
  - Evaluate project success
  - Collect user feedback
  - Document best practices

---

## 10. Appendices
- **A. Database Schema:**
  - See databasesql/schema.sql for full schema
- **B. User Roles & Permissions:**
  - Admin: All access
  - Librarian: Library-scoped management
  - Student: Personal access
- **C. Security Policies:**
  - Password policy
  - Data retention
  - Access control
- **D. Troubleshooting Guide:**
  - Common issues and solutions
- **E. Change Log:**
  - Major releases and updates

---

**For further details, see README.md, inline code documentation, and contact the project owner.**
