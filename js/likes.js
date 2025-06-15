jQuery(document).ready(function($) {

    // Handle like clicks
    $('.like-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $link = $(this);
        const postId = $link.data('post-id');

        // Prevent multiple clicks
        if ($link.hasClass('processing')) {
            return false;
        }

        $link.addClass('processing');

        $.ajax({
            url: playneko_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'playneko_like',
                post_id: postId,
                nonce: playneko_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;

                    // Update count
                    $('.likes-count').text(data.likes);

                    // Update link state
                    $('.like-btn').toggleClass('active', data.user_liked);

                    // Add animation effect
                    if (data.action === 'added_like') {
                        $('.like-btn').addClass('animate-pulse');
                        setTimeout(() => $('.like-btn').removeClass('animate-pulse'), 600);
                    }
                }
            },
            error: function() {
                console.error('Error processing like');
            },
            complete: function() {
                $link.removeClass('processing');
            }
        });

        return false;
    });

    // Handle share button click
    $('.share-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $link = $(this);
        const postId = $link.data('post-id');
        const postTitle = $link.data('title');
        const postUrl = $link.data('url');

        // Check if Web Share API is supported
        if (navigator.share) {
            navigator.share({
                title: postTitle,
                url: postUrl
            }).then(() => {
                // Track share count
                trackShare(postId);

                // Add success animation
                $link.addClass('animate-pulse');
                setTimeout(() => $link.removeClass('animate-pulse'), 600);
            }).catch((error) => {
                console.log('Error sharing:', error);
                // Fallback to copy to clipboard
                fallbackShare(postUrl, $link);
            });
        } else {
            // Fallback for browsers without Web Share API
            fallbackShare(postUrl, $link);
        }

        return false;
    });

    // Prevent default link behavior on keypress
    $('.like-btn, .share-btn').on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
            return false;
        }
    });

    // Fallback share function
    function fallbackShare(url, $link) {
        // Copy to clipboard
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                showShareFeedback($link, 'Link copied to clipboard!');
                trackShare($link.data('post-id'));
            }).catch(() => {
                showShareFeedback($link, 'Unable to copy link');
            });
        } else {
            // Very old browser fallback
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showShareFeedback($link, 'Link copied to clipboard!');
                trackShare($link.data('post-id'));
            } catch (err) {
                showShareFeedback($link, 'Unable to copy link');
            }
            document.body.removeChild(textArea);
        }
    }

    // Show share feedback
    function showShareFeedback($link, message) {
        const $feedback = $('<span class="share-feedback">' + message + '</span>');
        $link.parent().append($feedback);

        $feedback.fadeIn(200).delay(2000).fadeOut(300, function() {
            $(this).remove();
        });

        $link.addClass('animate-pulse');
        setTimeout(() => $link.removeClass('animate-pulse'), 600);
    }

    // Track share count
    function trackShare(postId) {
        $.ajax({
            url: playneko_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'playneko_share',
                post_id: postId,
                nonce: playneko_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.shares-count').text(response.data.shares);
                }
            }
        });
    }

    // Add basic CSS animations and link styles
    const style = document.createElement('style');
    style.textContent = `
        
        .animate-pulse {
            animation: pulse 0.6s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .share-feedback {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            display: none;
        }
        
        .share-feedback::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #333;
        }
        
        .like-share {
            position: relative;
        }
        
        .processing {
            opacity: 0.6;
            pointer-events: none;
        }
    `;
    document.head.appendChild(style);
});