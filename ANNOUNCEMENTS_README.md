# Announcements Management System - Setup Guide

## 🎯 **NEW FEATURE: Admin Announcements Management**

You now have a complete announcements management system that allows administrators to add, edit, and delete announcements that appear on the homepage!

## 📋 **Files Created/Updated:**

### 🗄️ **Database Files:**
- `announcements_setup.php` - Creates announcements table and adds default data
- `announcements_api.php` - API to fetch announcements for frontend display

### 🖥️ **Admin Interface:**
- `announcements.php` - Complete admin interface for managing announcements
- Updated `admin-dashboard.php` - Added announcements management links
- Updated `admin-panel.html` - Added announcements management links

### 🌐 **Frontend Integration:**
- `announcements.js` - Dynamic loading of announcements on homepage
- Updated `index.html` - Includes announcements script

## 🚀 **Setup Instructions:**

### Step 1: Database Setup
1. If you have PHP installed locally, run:
   ```bash
   php announcements_setup.php
   ```

2. Or manually run this SQL in your database:
   ```sql
   CREATE TABLE IF NOT EXISTS announcements (
       id INT PRIMARY KEY AUTO_INCREMENT,
       title VARCHAR(255) NOT NULL,
       content TEXT NOT NULL,
       type ENUM('announcement', 'update', 'alert') DEFAULT 'announcement',
       status ENUM('active', 'inactive') DEFAULT 'active',
       display_order INT DEFAULT 0,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       created_by VARCHAR(255) DEFAULT 'admin'
   );
   ```

3. Insert default announcements:
   ```sql
   INSERT INTO announcements (title, content, type, display_order) VALUES 
   ('UPDATE 1', 'ON 27th JULY 2025 (SUNDAY) MASS WILL BE ON NEW CHAPEL.', 'update', 1),
   ('UPDATE 2', 'FR SEBASTAIN WIL BE NOT AVAILABLE FOR ONE WEEK', 'update', 2),
   ('UPDATE 3', 'HOUSE BLESSINGS ARE GOING ON PLEASE CONTACT COUNCIL MEMBERS.', 'update', 3);
   ```

### Step 2: Access the Feature
1. **Login to Admin Panel:**
   - Go to `admin-login.html`
   - Use credentials: `greenparkchurch25@gmail.com` / `7025727541`

2. **Manage Announcements:**
   - Click "Manage Announcements" from the dashboard
   - Add, edit, delete, or activate/deactivate announcements

## ✨ **Features:**

### 🎛️ **Admin Panel Features:**
- **➕ Add New Announcements:** Create announcements with title, content, type, and display order
- **✏️ Edit Existing:** Modify any announcement details
- **🗑️ Delete Announcements:** Remove unwanted announcements
- **⏸️ Activate/Deactivate:** Toggle announcements on/off without deleting
- **📊 Order Management:** Set display order for announcements
- **🏷️ Type Categories:** Announcement, Update, or Alert types
- **📅 Timestamps:** Track creation and modification dates

### 🌐 **Frontend Integration:**
- **🎢 Dynamic Ticker:** Announcements automatically appear in homepage ticker
- **📱 Mobile Ticker:** Updates mobile announcement ticker
- **📋 Updates Section:** Shows numbered announcements in updates section
- **⏱️ Auto-Refresh:** Frontend refreshes announcements every 5 minutes
- **🔄 Real-time Updates:** Changes in admin panel reflect on website

### 🎨 **Display Features:**
- **📱 Responsive Design:** Works on all devices
- **🌙 Dark Mode Support:** Integrated with existing dark mode
- **✨ Visual Indicators:** Status badges, type badges, order numbers
- **🎭 Beautiful UI:** Professional admin interface

## 🔧 **How to Use:**

### For Administrators:
1. **Login** to admin panel
2. **Navigate** to "Manage Announcements"
3. **Add** new announcements using the form
4. **Edit** existing announcements by clicking "Edit"
5. **Activate/Deactivate** using toggle buttons
6. **Delete** unwanted announcements
7. **Set Display Order** to control appearance sequence

### For Website Visitors:
- Announcements automatically appear in:
  - Homepage ticker (top and bottom)
  - Mobile ticker
  - Updates section
  - All content updates in real-time

## 🎯 **What Updates Automatically:**

When you add/edit announcements in the admin panel, these sections update:
- ✅ **Home Page Ticker:** `UPDATE 1: ... UPDATE 2: ... UPDATE 3: ...`
- ✅ **Mobile Ticker:** Shows same announcements on mobile
- ✅ **Updates Section:** Shows numbered list of announcements
- ✅ **Real-time:** Changes appear within 5 minutes (or refresh page)

## 🛡️ **Security:**
- ✅ **Admin Authentication Required:** Only logged-in admins can manage
- ✅ **SQL Injection Protection:** Prepared statements used
- ✅ **XSS Protection:** All output escaped
- ✅ **Session Management:** Secure admin sessions

## 📞 **Support:**
- All existing admin credentials work
- Existing announcement content preserved
- Backward compatible with current website
- No changes needed to existing functionality

---

**🎉 Your announcements management system is ready! Login to the admin panel and start managing your church announcements dynamically!**