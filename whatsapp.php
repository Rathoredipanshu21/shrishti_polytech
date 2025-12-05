<style>
    /* 1. THE DOCK CONTAINER 
       Glassmorphism effect for a professional look 
    */
    .srishti-contact-dock {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px; /* Space between buttons */
        padding: 8px 12px;
        background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
        backdrop-filter: blur(8px); /* Blur effect behind */
        -webkit-backdrop-filter: blur(8px);
        border-radius: 50px; /* Pill shape */
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: transform 0.3s ease;
    }

    .srishti-contact-dock:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    /* 2. SHARED BUTTON STYLES 
       Small, compact, circular
    */
    .dock-btn {
        width: 42px;  /* Small size as requested */
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 18px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Bouncy transition */
        position: relative;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .dock-btn:hover {
        transform: scale(1.15); /* Grow slightly on hover */
    }

    /* 3. COLORS 
    */
    /* Call Button: Brand Teal (Professional) */
    .btn-call {
        background: linear-gradient(135deg, #1e90b8, #156f8f); 
    }
    
    /* WhatsApp Button: Standard Green (Recognizable) */
    .btn-whatsapp {
        background: linear-gradient(135deg, #25D366, #128C7E);
    }

    /* 4. TOOLTIP TEXT (Appears on Hover)
       Adds a professional touch explaining what the button does
    */
    .dock-tooltip {
        position: absolute;
        bottom: 110%; /* Above the button */
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        background: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        pointer-events: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }
    
    /* Arrow for tooltip */
    .dock-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -4px;
        border-width: 4px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .dock-btn:hover .dock-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    /* 5. ANIMATIONS 
    */
    /* Subtle wiggle for the phone icon */
    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }
    .btn-call:hover i {
        animation: wiggle 0.4s ease-in-out;
    }

    /* Mobile Adjustments */
    @media (max-width: 768px) {
        .srishti-contact-dock {
            bottom: 20px;
            right: 20px;
            padding: 6px 10px;
        }
        .dock-btn {
            width: 38px;
            height: 38px;
            font-size: 16px;
        }
    }
</style>

<div class="srishti-contact-dock">
    
    <!-- LEFT: CALL BUTTON -->
    <a href="tel:7004471859" class="dock-btn btn-call" aria-label="Call Us">
        <i class="fas fa-phone"></i>
        <span class="dock-tooltip">Call Now</span>
    </a>

    <!-- SEPARATOR LINE -->
    <div style="width: 1px; height: 20px; background: #e5e7eb;"></div>

    <!-- RIGHT: WHATSAPP BUTTON -->
    <a href="https://wa.me/917004471859?text=Hi%20Srishti%20Polytech%2C%20I%20am%20interested%20in%20your%20services." 
       class="dock-btn btn-whatsapp" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="dock-tooltip">Chat on WhatsApp</span>
    </a>

</div>