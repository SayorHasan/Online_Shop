/**
 * Admin Dashboard JavaScript
 * Handles admin-specific functionality
 */

$(document).ready(function() {
    
    // Ensure admin dashboard is properly initialized
    console.log('Admin dashboard initialized');
    
    // Fix for menu toggle functionality
    $(".button-show-hide").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $layoutWrap = $('.layout-wrap');
        const currentState = $layoutWrap.hasClass('full-width');
        
        console.log('Button clicked. Current state:', currentState ? 'full-width' : 'normal');
        
        $layoutWrap.toggleClass('full-width');
        
        // Force layout update
        setTimeout(function() {
            updateLayout();
            console.log('Layout updated after toggle');
        }, 100);
    });
    
    // Ensure all admin content is visible
    $('.main-content-inner, .main-content-wrap').show();
    
    // Fix for image preview functionality
    if ($("#myFile").length > 0) {
        $("#myFile").on("change", function(e) {
            const file = this.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size should be less than 5MB');
                    return;
                }
                
                // Show preview
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
                $("#upload-file").hide();
            } else {
                $("#imgpreview").hide();
                $("#upload-file").show();
            }
        });
    }
    
    // Fix for gallery image preview
    if ($("#gFile").length > 0) {
        $("#gFile").on("change", function(e) {
            const files = this.files;
            if (files.length > 0) {
                // Clear previous previews
                $(".gitems").remove();
                
                $.each(files, function(key, file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select only image files');
                        return;
                    }
                    
                    // Validate file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size should be less than 5MB');
                        return;
                    }
                    
                    // Add preview with remove button
                    const previewHtml = `
                        <div class="item gitems">
                            <img src="${URL.createObjectURL(file)}" />
                            <button type="button" class="remove-gallery-item" onclick="removeGalleryItem(this)" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">×</button>
                        </div>
                    `;
                    $("#galUpload").prepend(previewHtml);
                });
            }
        });
    }
    
    // Auto-generate slugs from names
    $("input[name='name']").on("input", function() {
        const slugInput = $("input[name='slug']");
        if (slugInput.length > 0) {
            slugInput.val(StringToSlug($(this).val()));
        }
    });
    
    // Ensure proper table display
    $('.table-responsive').each(function() {
        if ($(this).find('table').width() > $(this).width()) {
            $(this).addClass('overflow-x-auto');
        }
    });
    
    // Fix for any hidden elements
    $('.hidden, [style*="display: none"]').each(function() {
        if ($(this).hasClass('main-content-inner') || $(this).hasClass('main-content-wrap')) {
            $(this).show();
        }
    });
    
    // Initialize layout
    updateLayout();
});

// Function to update layout based on current state
function updateLayout() {
    const isFullWidth = $('.layout-wrap').hasClass('full-width');
    
    if (isFullWidth) {
        // Full width layout - hide sidebar completely
        $('.section-menu-left').css({
            'left': '-280px',
            'transform': 'translateX(-100%)',
            'visibility': 'hidden',
            'opacity': '0',
            'pointer-events': 'none'
        });
        $('.box-logo').css({
            'left': '-280px',
            'transform': 'translateX(-100%)',
            'visibility': 'hidden',
            'opacity': '0'
        });
        $('.section-menu-left > .center').css({
            'left': '-280px',
            'transform': 'translateX(-100%)',
            'visibility': 'hidden',
            'opacity': '0'
        });
        
        // Hide logo elements
        $('#logo_header, #site-logo-inner').css({
            'visibility': 'hidden',
            'opacity': '0',
            'display': 'none'
        });
        
        $('.section-content-right').css({
            'margin-left': '0',
            'width': '100%',
            'left': '0'
        });
        $('.header-dashboard').css({
            'left': '0',
            'width': '100%'
        });
        $('.main-content').css({
            'padding-left': '0',
            'margin-left': '0'
        });
        
        // Update status indicator
        $('.sidebar-status').text('OFF').css('color', '#dc3545');
        
        console.log('Layout set to full width - sidebar hidden');
    } else {
        // Normal layout - show sidebar
        $('.section-menu-left').css({
            'left': '0',
            'transform': 'translateX(0)',
            'visibility': 'visible',
            'opacity': '1',
            'pointer-events': 'auto'
        });
        $('.box-logo').css({
            'left': '0',
            'transform': 'translateX(0)',
            'visibility': 'visible',
            'opacity': '1'
        });
        $('.section-menu-left > .center').css({
            'left': '0',
            'transform': 'translateX(0)',
            'visibility': 'visible',
            'opacity': '1'
        });
        
        // Show logo elements
        $('#logo_header, #site-logo-inner').css({
            'visibility': 'visible',
            'opacity': '1',
            'display': 'block'
        });
        
        $('.section-content-right').css({
            'margin-left': '280px',
            'width': 'calc(100% - 280px)',
            'left': 'auto'
        });
        $('.header-dashboard').css({
            'left': '280px',
            'width': 'auto'
        });
        $('.main-content').css({
            'padding-left': '280px',
            'margin-left': '0'
        });
        
        // Update status indicator
        $('.sidebar-status').text('ON').css('color', '#28a745');
        
        console.log('Layout set to normal - sidebar visible');
    }
}

// Utility function for creating slugs
function StringToSlug(text) {
    return text
        .toString()                
        .trim()                    
        .toLowerCase()             
        .replace(/\s+/g, '-')      
        .replace(/[^\w\-]+/g, '')  
        .replace(/\-\-+/g, '-')    
        .replace(/^-+/, '')        
        .replace(/-+$/, '');      
}

// Function to remove gallery items
function removeGalleryItem(button) {
    $(button).closest('.gitems').remove();
}

// Ensure admin dashboard is responsive
$(window).on('resize', function() {
    // Adjust layout on window resize
    if ($(window).width() < 768) {
        $('.layout-wrap').addClass('full-width');
        updateLayout();
    }
});

// Initialize admin dashboard on page load
$(window).on('load', function() {
    console.log('Admin dashboard fully loaded');
    
    // Ensure all content is visible
    $('.main-content-inner, .main-content-wrap').show();
    
    // Update layout
    updateLayout();
    
    // Add click event for menu toggle buttons
    $('.button-show-hide').off('click').on('click', function() {
        $('.layout-wrap').toggleClass('full-width');
        console.log('Menu toggled via load event');
        
        // Update layout after toggle
        setTimeout(function() {
            updateLayout();
        }, 100);
    });
});

// Add keyboard shortcut for sidebar toggle (Ctrl + B)
$(document).on('keydown', function(e) {
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        $('.layout-wrap').toggleClass('full-width');
        updateLayout();
        console.log('Sidebar toggled via keyboard shortcut');
    }
});
