(function() {
    const API_URL = '/banners'; 
    const DEFAULT_CONTAINER_ID = 'banner-container';
    
    const scriptElement = document.currentScript;
    const containerId = scriptElement.getAttribute('data-container') || DEFAULT_CONTAINER_ID;
    
    const createStyles = () => {
        const style = document.createElement('style');
        style.textContent = `
            .banner-container {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                overflow: hidden;
            }
            .banner-item {
                display: block;
                width: 100%;
                margin-bottom: 1rem;
                text-decoration: none;
                overflow: hidden;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }
            .banner-item:hover {
                transform: translateY(-5px);
            }
            .banner-image {
                width: 100%;
                height: auto;
                display: block;
            }
        `;
        document.head.appendChild(style);
    };
    
    const fetchBanners = async () => {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) {
                throw new Error('Failed to fetch banners');
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching banners:', error);
            return [];
        }
    };
    
    const renderBanners = (banners, container) => {
        const activeBanners = banners.filter(banner => banner.is_active);
        
        if (activeBanners.length === 0) {
            container.innerHTML = '<p>No active banners available.</p>';
            return;
        }
        
        activeBanners.forEach(banner => {
            const bannerLink = document.createElement('a');
            bannerLink.href = banner.link_url;
            bannerLink.target = '_blank';
            bannerLink.className = 'banner-item';
            bannerLink.title = banner.title;
            
            const bannerImage = document.createElement('img');
            bannerImage.src = banner.image_url;
            bannerImage.alt = banner.title;
            bannerImage.className = 'banner-image';
            
            bannerLink.appendChild(bannerImage);
            container.appendChild(bannerLink);
        });
    };
    
    const initBannerDisplay = async () => {
        createStyles();
        
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            document.body.appendChild(container);
        }
        

        container.className = 'banner-container';
        

        container.innerHTML = '<p>Loading banners...</p>';
        
        const banners = await fetchBanners();
        renderBanners(banners, container);
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBannerDisplay);
    } else {
        initBannerDisplay();
    }
})();