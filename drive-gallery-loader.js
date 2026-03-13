// Google Drive Gallery Loader - Enhanced with Zoom, Panning, and Filtering
class DriveGalleryLoader {
    constructor() {
        this.apiKey = "YOUR_GOOGLE_API_KEY"; 
        this.folderId = "";
        this.images = [];
        this.currentFilter = "all";
        this.zoomLevel = 1;
        this.isDragging = false;
        this.startX = 0;
        this.startY = 0;
        this.translateX = 0;
        this.translateY = 0;
        this.currentLightboxIndex = 0;
        this.currentLightboxImages = [];
    }

    extractFolderId(driveLink) {
        const match = driveLink.match(/\/folders\/([a-zA-Z0-9-_]+)/);
        return match ? match[1] : null;
    }

    async loadImagesFromDrive(driveLink) {
        this.folderId = this.extractFolderId(driveLink);
        this.originalLink = driveLink;
        
        if (this.apiKey === "YOUR_GOOGLE_API_KEY" || !this.apiKey) {
            console.log("No API key provided, using Demo Mode with effects");
            this.loadDemoMode();
            return;
        }

        try {
            if (!this.folderId) {
                console.error("Invalid Google Drive link");
                return;
            }

            const response = await fetch(`https://www.googleapis.com/drive/v3/files?q="${this.folderId}" in parents and mimeType contains "image/"&fields=files(id,name,webContentLink,thumbnailLink)&key=${this.apiKey}`);
            const data = await response.json();

            if (data.files && data.files.length > 0) {
                this.images = data.files;
                this.displayImages();
            } else {
                this.loadFallbackMethod(driveLink);
            }
        } catch (error) {
            console.error("Error loading images from Drive:", error);
            this.loadFallbackMethod(driveLink);
        }
    }

    loadDemoMode() {
        this.images = [
            { id: "1", name: "Church Feast Celebration", category: "Feast Day", url: "https://images.unsplash.com/photo-1548625361-195fe5772cd8?auto=format&fit=crop&w=800" },
            { id: "2", name: "Sunday Morning Mass", category: "Sunday Service", url: "https://images.unsplash.com/photo-1519491050282-ce00c729c8bf?auto=format&fit=crop&w=800" },
            { id: "3", name: "Easter Celebration 2024", category: "Church Events", url: "https://images.unsplash.com/photo-1523050853023-8c2d27443ef8?auto=format&fit=crop&w=800" },
            { id: "4", name: "Community Gathering", category: "Church Events", url: "https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800" },
            { id: "5", name: "Choir Practice", category: "Sunday Service", url: "https://images.unsplash.com/photo-1459749411177-042180ce673c?auto=format&fit=crop&w=800" },
            { id: "6", name: "St. Anthony Feast", category: "Feast Day", url: "https://images.unsplash.com/photo-1544427920-c49ccfb85579?auto=format&fit=crop&w=800" }
        ];
        
        this.displayImages();
        
        const galleryContainer = document.getElementById("dynamic-gallery");
        const notice = document.createElement("div");
        notice.className = "api-notice";
        notice.innerHTML = `
            <div style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; border: 1px solid #ffeeba;">
                <p><strong>Note:</strong> Showing demo images with full effects. To show your 100+ Google Drive images automatically, a Google API Key is required.</p>
                <a href="${this.originalLink}" target="_blank" class="btn-sm" style="color: #856404; text-decoration: underline;">View your real Drive folder here</a>
            </div>
        `;
        galleryContainer.prepend(notice);
    }

    loadFallbackMethod(driveLink) {
        const folderId = this.extractFolderId(driveLink);
        if (folderId) {
            const embedLink = `https://drive.google.com/embeddedfolderview?id=${folderId}#grid`;
            this.createIframeGallery(embedLink);
        }
    }

    displayImages() {
        const galleryContainer = document.getElementById("dynamic-gallery");
        if (!galleryContainer) return;

        galleryContainer.innerHTML = ""; 

        this.createFilterUI(galleryContainer);

        const grid = document.createElement("div");
        grid.className = "dynamic-gallery-grid";
        grid.id = "main-gallery-grid";
        
        this.renderFilteredImages(grid);
        galleryContainer.appendChild(grid);

        this.initializeLightbox();
    }

    createFilterUI(container) {
        const filterDiv = document.createElement("div");
        filterDiv.className = "gallery-filters";
        
        const categories = ["all", "Church Events", "Feast Day", "Sunday Service", "Special Occasions"];
        
        categories.forEach(cat => {
            const btn = document.createElement("button");
            btn.className = `filter-btn ${this.currentFilter === cat ? "active" : ""}`;
            btn.textContent = cat === "all" ? "All Photos" : cat;
            btn.onclick = () => this.filterGallery(cat);
            filterDiv.appendChild(btn);
        });
        
        container.appendChild(filterDiv);
    }

    filterGallery(category) {
        this.currentFilter = category;
        
        document.querySelectorAll(".filter-btn").forEach(btn => {
            const btnText = btn.textContent.toLowerCase();
            const catText = category.toLowerCase();
            btn.classList.toggle("active", btnText === catText || (category === "all" && btnText === "all photos"));
        });

        const grid = document.getElementById("main-gallery-grid");
        if (grid) {
            grid.style.opacity = "0";
            setTimeout(() => {
                this.renderFilteredImages(grid);
                grid.style.opacity = "1";
            }, 300);
        }
    }

    renderFilteredImages(grid) {
        grid.innerHTML = "";
        
        const filtered = this.currentFilter === "all" 
            ? this.images 
            : this.images.filter(img => img.category === this.currentFilter || this.getCategoryFromName(img.name) === this.currentFilter);

        filtered.forEach((image, index) => {
            const item = document.createElement("div");
            item.className = "gallery-item";
            const url = image.url || this.getImageUrl(image);
            
            item.innerHTML = `
                <img src="${url}" alt="${image.name}" loading="lazy">
                <div class="image-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            `;
            item.onclick = () => this.openLightbox(url, image.name, filtered, index);
            grid.appendChild(item);
        });
    }

    getCategoryFromName(name) {
        name = name.toLowerCase();
        if (name.includes("feast")) return "Feast Day";
        if (name.includes("sunday") || name.includes("service")) return "Sunday Service";
        if (name.includes("event")) return "Church Events";
        if (name.includes("wedding") || name.includes("baptism")) return "Special Occasions";
        return "Church Events";
    }

    getImageUrl(image) {
        return `https://drive.google.com/uc?id=${image.id}&export=view`;
    }

    initializeLightbox() {
        if (document.getElementById("gallery-lightbox")) return;

        const lightbox = document.createElement("div");
        lightbox.id = "gallery-lightbox";
        lightbox.className = "lightbox";
        lightbox.innerHTML = `
            <div class="lightbox-zoom-info" id="zoom-info">Zoom: 100%</div>
            <div class="lightbox-controls">
                <button class="lightbox-btn" onclick="galleryLoader.zoomIn()" title="Zoom In"><i class="fas fa-search-plus"></i></button>
                <button class="lightbox-btn" onclick="galleryLoader.zoomOut()" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                <button class="lightbox-btn" onclick="galleryLoader.resetZoom()" title="Reset"><i class="fas fa-sync-alt"></i></button>
                <button class="lightbox-btn lightbox-close" onclick="galleryLoader.closeLightbox()"><i class="fas fa-times"></i></button>
            </div>
            <div class="lightbox-content">
                <div class="lightbox-image-container" id="lightbox-container">
                    <img id="lightbox-image" src="" alt="">
                </div>
                <div class="lightbox-caption" id="lightbox-caption"></div>
            </div>
            <button class="lightbox-prev" onclick="galleryLoader.prevImage()"><i class="fas fa-chevron-left"></i></button>
            <button class="lightbox-next" onclick="galleryLoader.nextImage()"><i class="fas fa-chevron-right"></i></button>
        `;
        document.body.appendChild(lightbox);

        const container = document.getElementById("lightbox-container");
        container.addEventListener("mousedown", (e) => this.startDragging(e));
        window.addEventListener("mousemove", (e) => this.drag(e));
        window.addEventListener("mouseup", () => this.stopDragging());
        
        container.addEventListener("wheel", (e) => {
            e.preventDefault();
            if (e.deltaY < 0) this.zoomIn();
            else this.zoomOut();
        });
    }

    openLightbox(src, caption, images, index) {
        this.currentLightboxImages = images;
        this.currentLightboxIndex = index;
        
        const lightbox = document.getElementById("gallery-lightbox");
        const img = document.getElementById("lightbox-image");
        const cap = document.getElementById("lightbox-caption");
        
        img.src = src;
        cap.textContent = caption;
        lightbox.style.display = "flex";
        document.body.style.overflow = "hidden";
        
        this.resetZoom();
    }

    closeLightbox() {
        document.getElementById("gallery-lightbox").style.display = "none";
        document.body.style.overflow = "auto";
    }

    nextImage() {
        this.currentLightboxIndex = (this.currentLightboxIndex + 1) % this.currentLightboxImages.length;
        this.updateLightboxContent();
    }

    prevImage() {
        this.currentLightboxIndex = (this.currentLightboxIndex - 1 + this.currentLightboxImages.length) % this.currentLightboxImages.length;
        this.updateLightboxContent();
    }

    updateLightboxContent() {
        const img = this.currentLightboxImages[this.currentLightboxIndex];
        const url = img.url || this.getImageUrl(img);
        document.getElementById("lightbox-image").src = url;
        document.getElementById("lightbox-caption").textContent = img.name;
        this.resetZoom();
    }

    zoomIn() {
        this.updateZoom(this.zoomLevel + 0.2);
    }

    zoomOut() {
        this.updateZoom(this.zoomLevel - 0.2);
    }

    resetZoom() {
        this.translateX = 0;
        this.translateY = 0;
        this.updateZoom(1);
    }

    updateZoom(newZoom) {
        this.zoomLevel = Math.max(0.5, Math.min(4, newZoom));
        const img = document.getElementById("lightbox-image");
        const zoomInfo = document.getElementById("zoom-info");
        
        img.style.transform = `translate(${this.translateX}px, ${this.translateY}px) scale(${this.zoomLevel})`;
        
        zoomInfo.textContent = `Zoom: ${Math.round(this.zoomLevel * 100)}%`;
        zoomInfo.classList.add("show");
        clearTimeout(this.zoomTimeout);
        this.zoomTimeout = setTimeout(() => zoomInfo.classList.remove("show"), 2000);
    }

    startDragging(e) {
        if (this.zoomLevel <= 1) return;
        this.isDragging = true;
        this.startX = e.clientX - this.translateX;
        this.startY = e.clientY - this.translateY;
    }

    drag(e) {
        if (!this.isDragging) return;
        e.preventDefault();
        this.translateX = e.clientX - this.startX;
        this.translateY = e.clientY - this.startY;
        this.updateZoom(this.zoomLevel);
    }

    stopDragging() {
        this.isDragging = false;
    }

    createIframeGallery(embedLink) {
        const galleryContainer = document.getElementById("dynamic-gallery");
        galleryContainer.innerHTML = `
            <div class="gallery-section">
                <h3 class="gallery-header">CHURCH GALLERY</h3>
                <div class="drive-embed-container">
                    <iframe src="${embedLink}" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
                    <p class="drive-link">
                        <a href="${this.originalLink}" target="_blank" class="btn-primary">
                            <i class="fas fa-external-link-alt"></i> Open Full Gallery
                        </a>
                    </p>
                </div>
            </div>
        `;
    }
}

const galleryLoader = new DriveGalleryLoader();

function loadGalleryFromDrive(driveLink) {
    galleryLoader.loadImagesFromDrive(driveLink);
}

document.addEventListener("DOMContentLoaded", function() {
    const driveLink = "https://drive.google.com/drive/folders/1bRsKwlkY6VO-w8n9wAGElP3fVRro77am?usp=sharing";
    if (driveLink) {
        loadGalleryFromDrive(driveLink);
    }
});
