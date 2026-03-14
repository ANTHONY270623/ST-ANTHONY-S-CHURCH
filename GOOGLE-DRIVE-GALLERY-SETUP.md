# 📸 GOOGLE DRIVE GALLERY SETUP GUIDE

## 🎯 **How to Connect Your 100+ Images from Google Drive**

I've created a dynamic gallery system that can load all your images from Google Drive automatically. Here's how to set it up:

### **📋 STEP 1: Prepare Your Google Drive Folder**

1. **Upload all your church images** to a single Google Drive folder
2. **Make the folder PUBLIC:**
   - Right-click on the folder → Share
   - Click "Change to anyone with the link"
   - Set permission to "Viewer"
   - Copy the folder link

### **📋 STEP 2: Get Your Drive Link**

Your Google Drive link should look like:
```
https://drive.google.com/drive/folders/1ABCdef123GHI456jkl789mnop-qrstuvwx
```

### **📋 STEP 3: Add Your Link to the Website**

1. **Open** `drive-gallery-loader.js`
2. **Find this line** (around line 185):
   ```javascript
   const driveLink = 'YOUR_GOOGLE_DRIVE_FOLDER_LINK_HERE';
   ```
3. **Replace it with your actual link:**
   ```javascript
   const driveLink = 'https://drive.google.com/drive/folders/YOUR_FOLDER_ID';
   ```

### **📋 STEP 4: Optional - Get Google API Key (For Better Performance)**

For advanced features, you can get a Google API key:

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable Google Drive API
4. Create credentials (API Key)
5. Add your API key to `drive-gallery-loader.js`:
   ```javascript
   this.apiKey = 'YOUR_GOOGLE_API_KEY_HERE';
   ```

## ✨ **FEATURES INCLUDED:**

### **🔄 Automatic Loading**
- Loads all 100+ images automatically
- Groups images by categories (Feast Day, Sunday Service, etc.)
- Responsive grid layout

### **🖼️ Image Organization**
- **Feast Day Photos** - Images with "feast" or "anthony" in filename
- **Sunday Service** - Images with "sunday" or "service" in filename  
- **Church Events** - Images with "event" or "celebration" in filename
- **Special Occasions** - Baptism, wedding, communion photos
- **Other Photos** - All remaining images

### **🔍 Lightbox Viewer**
- Click any image to view full-screen
- Navigate between images with arrow keys
- Smooth animations and transitions

### **📱 Mobile Responsive**
- Perfect on all device sizes
- Touch-friendly navigation
- Optimized loading

## 🚀 **EASY SETUP OPTIONS:**

### **Option 1: Simple Iframe Embed (Easiest)**
Just replace the link and it will embed your Drive folder directly.

### **Option 2: API Integration (Best)**
Get API key for individual image loading with better organization.

### **Option 3: Manual Upload**
Download all images and upload to your website folder for fastest loading.

## 🎯 **What You Get:**

✅ **All 100+ images displayed automatically**  
✅ **Organized into categories**  
✅ **Professional lightbox viewer**  
✅ **Mobile-friendly responsive design**  
✅ **Fast loading with lazy loading**  
✅ **Easy to update - just add to Drive folder**  

## 📞 **Next Steps:**

1. **Get your Google Drive folder link**
2. **Add it to the JavaScript file**
3. **Test the gallery**
4. **Enjoy your automatic gallery!**

Your church gallery will now automatically display all images from your Google Drive folder, organized beautifully and accessible on all devices! 🏆