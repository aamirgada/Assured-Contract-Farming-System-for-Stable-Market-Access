document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
            if (scrollTop > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.background = 'transparent';
                navbar.style.boxShadow = 'none';
            }
        }
        lastScrollTop = scrollTop;
    });

    // Animate elements on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.feature-card, .step, .template-card, .method-card, .security-item');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;
            
            if (elementTop < window.innerHeight && elementBottom > 0) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Set initial state for animated elements
    const elementsToAnimate = document.querySelectorAll('.feature-card, .step, .template-card, .method-card, .security-item');
    elementsToAnimate.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease-out';
    });

    // Call animation function on scroll
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Call once on load

    // Template preview functionality
    const templateButtons = document.querySelectorAll('.view-template');
    templateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const templateName = this.parentElement.querySelector('h3').textContent;
            showTemplatePreview(templateName);
        });
    });

    function showTemplatePreview(templateName) {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'template-modal';
        
        const modalContent = document.createElement('div');
        modalContent.className = 'modal-content';
        
        const closeButton = document.createElement('span');
        closeButton.className = 'close-modal';
        closeButton.innerHTML = '&times;';
        
        const title = document.createElement('h2');
        title.textContent = templateName;
        
        const content = document.createElement('div');
        content.className = 'template-preview';
        content.innerHTML = getTemplateContent(templateName);
        
        modalContent.appendChild(closeButton);
        modalContent.appendChild(title);
        modalContent.appendChild(content);
        modal.appendChild(modalContent);
        document.body.appendChild(modal);
        
        // Add modal styles
        const style = document.createElement('style');
        style.textContent = `
            .template-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .modal-content {
                background: white;
                padding: 2rem;
                border-radius: 10px;
                max-width: 800px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                position: relative;
                transform: translateY(20px);
                transition: transform 0.3s ease;
            }
            
            .close-modal {
                position: absolute;
                top: 1rem;
                right: 1rem;
                font-size: 1.5rem;
                cursor: pointer;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #f0f0f0;
                transition: all 0.3s ease;
            }
            
            .close-modal:hover {
                background: #e0e0e0;
                transform: rotate(90deg);
            }
            
            .template-preview {
                margin-top: 1rem;
            }
        `;
        document.head.appendChild(style);
        
        // Animate modal in
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'translateY(0)';
        }, 10);
        
        // Close modal functionality
        closeButton.addEventListener('click', () => {
            modal.style.opacity = '0';
            modalContent.style.transform = 'translateY(20px)';
            setTimeout(() => {
                document.body.removeChild(modal);
            }, 300);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeButton.click();
            }
        });
    }

    function getTemplateContent(templateName) {
        const templates = {
            'Crop Purchase Agreement': `
                <div class="template-section">
                    <h3>1. Parties</h3>
                    <p>This agreement is made between [Farmer Name] ("Seller") and [Buyer Name] ("Buyer").</p>
                    
                    <h3>2. Product Details</h3>
                    <ul>
                        <li>Crop Type: [Specify crop]</li>
                        <li>Quantity: [Amount in kg/tons]</li>
                        <li>Quality Standards: [Specify standards]</li>
                        <li>Delivery Date: [Date]</li>
                    </ul>
                    
                    <h3>3. Price and Payment</h3>
                    <ul>
                        <li>Price per unit: [Amount]</li>
                        <li>Total contract value: [Amount]</li>
                        <li>Payment terms: [Specify terms]</li>
                    </ul>
                    
                    <button class="use-template">Use This Template</button>
                </div>
            `,
            'Storage Agreement': `
                <div class="template-section">
                    <h3>1. Storage Facility</h3>
                    <p>Details of the storage facility and conditions...</p>
                    
                    <h3>2. Duration</h3>
                    <p>Storage period and terms...</p>
                    
                    <button class="use-template">Use This Template</button>
                </div>
            `,
            'Transportation Contract': `
                <div class="template-section">
                    <h3>1. Transport Details</h3>
                    <p>Vehicle type, capacity, and route information...</p>
                    
                    <h3>2. Schedule</h3>
                    <p>Pickup and delivery timeline...</p>
                    
                    <button class="use-template">Use This Template</button>
                </div>
            `,
            'Partnership Agreement': `
                <div class="template-section">
                    <h3>1. Partnership Terms</h3>
                    <p>Scope and duration of partnership...</p>
                    
                    <h3>2. Responsibilities</h3>
                    <p>Roles and obligations of each party...</p>
                    
                    <button class="use-template">Use This Template</button>
                </div>
            `
        };
        
        return templates[templateName] || '<p>Template preview not available.</p>';
    }

    // Handle template usage
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('use-template')) {
            if (!isLoggedIn()) {
                window.location.href = '../login.html';
            } else {
                // Redirect to contract creation page
                window.location.href = '../dashboard/create-contract.html';
            }
        }
    });

    // Check login status (placeholder function)
    function isLoggedIn() {
        // This should be replaced with actual login check
        return false;
    }
}); 