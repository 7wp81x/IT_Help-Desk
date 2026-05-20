/**
 * Ticket Conversation Management System
 * Handles real-time message updates, deletion, and notifications
 */

const TicketConversation = {
    // Configuration
    config: {
        refreshInterval: 5000, // 5 seconds (can be adjusted between 5-10)
        scrollDuration: 300,
        deleteConfirmationMessage: 'Are you sure you want to delete this message?',
        deletedMessageText: 'This message was deleted',
    },

    // State management
    state: {
        ticketId: null,
        lastCheckedAt: null,
        isRefreshing: false,
        refreshTimer: null,
        userRole: null,
    },

    /**
     * Initialize the conversation system
     */
    init(ticketId, userRole, lastCheckedAt = null) {
        this.state.ticketId = ticketId;
        this.state.userRole = userRole;
        this.state.lastCheckedAt = lastCheckedAt;

        this.attachEventListeners();
        this.startRealTimeRefresh();
        this.updateUnreadCount();

        console.log('Ticket Conversation initialized for ticket:', ticketId);
    },

    /**
     * Attach event listeners to delete buttons
     */
    attachEventListeners() {
        document.addEventListener('click', (e) => {
            // Delete button
            if (e.target.closest('[data-action="delete-message"]')) {
                e.preventDefault();
                const commentId = e.target.closest('[data-action="delete-message"]').dataset.commentId;
                this.showDeleteConfirmation(commentId);
            }

            // Mark notification as read
            if (e.target.closest('[data-action="mark-read"]')) {
                const commentId = e.target.closest('[data-action="mark-read"]').dataset.commentId;
                this.markNotificationAsRead(commentId);
            }
        });
    },

    /**
     * Show delete confirmation modal
     */
    showDeleteConfirmation(commentId) {
        // Create a simple confirmation dialog
        if (confirm(this.config.deleteConfirmationMessage)) {
            this.deleteMessage(commentId);
        }
    },

    /**
     * Delete a message via AJAX
     */
    deleteMessage(commentId) {
        const endpoint = this.getDeleteEndpoint(commentId);
        
        fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.removeOrUpdateDeletedMessage(commentId);
                this.showNotification('Message deleted successfully', 'success');
            } else {
                this.showNotification(data.message || 'Failed to delete message', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            this.showNotification('An error occurred while deleting the message', 'error');
        });
    },

    /**
     * Update or remove deleted message from DOM
     */
    removeOrUpdateDeletedMessage(commentId) {
        const messageElement = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (messageElement) {
            // Mark as deleted
            messageElement.classList.add('deleted-message');
            
            // Update content to show deletion message
            const contentElement = messageElement.querySelector('[data-content]');
            if (contentElement) {
                contentElement.textContent = this.config.deletedMessageText;
                contentElement.classList.add('italic', 'text-gray-400');
            }

            // Hide delete button
            const deleteButton = messageElement.querySelector('[data-action="delete-message"]');
            if (deleteButton) {
                deleteButton.style.display = 'none';
            }
        }
    },

    /**
     * Start real-time refresh of comments
     */
    startRealTimeRefresh() {
        // Initial refresh
        this.refreshComments();

        // Set up interval
        this.state.refreshTimer = setInterval(() => {
            this.refreshComments();
        }, this.config.refreshInterval);
    },

    /**
     * Stop real-time refresh
     */
    stopRealTimeRefresh() {
        if (this.state.refreshTimer) {
            clearInterval(this.state.refreshTimer);
            this.state.refreshTimer = null;
        }
    },

    /**
     * Refresh comments from server
     */
    refreshComments() {
        if (this.state.isRefreshing) {
            return;
        }

        this.state.isRefreshing = true;
        const endpoint = this.getRefreshEndpoint();

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                last_checked_at: this.state.lastCheckedAt,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.comments && data.comments.length > 0) {
                this.addNewComments(data.comments);
                this.state.lastCheckedAt = data.timestamp;
                this.showNewMessageIndicator();
                this.autoScrollToLatest();
            }
        })
        .catch(error => {
            console.error('Refresh error:', error);
        })
        .finally(() => {
            this.state.isRefreshing = false;
        });
    },

    /**
     * Add new comments to the conversation
     */
    addNewComments(comments) {
        const commentContainer = document.querySelector('[data-comments-container]');
        if (!commentContainer) {
            return;
        }

        comments.forEach(comment => {
            // Check if comment already exists
            if (!document.querySelector(`[data-comment-id="${comment.id}"]`)) {
                const commentElement = this.createCommentElement(comment);
                commentContainer.appendChild(commentElement);
            }
        });
    },

    /**
     * Create a comment element
     */
    createCommentElement(comment) {
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3 p-4 rounded-lg border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900 dark:border-blue-700';
        div.dataset.commentId = comment.id;
        div.dataset.messageAnchor = comment.message_anchor;

        const timeAgo = this.getTimeAgo(comment.created_at);
        const content = comment.is_deleted ? this.config.deletedMessageText : comment.content;
        const contentClass = comment.is_deleted ? 'italic text-gray-400' : '';

        let attachmentsHtml = '';
        if (comment.attachments && comment.attachments.length > 0) {
            attachmentsHtml = '<div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-800">';
            comment.attachments.forEach(attachment => {
                attachmentsHtml += `
                    <div class="flex items-center justify-between text-sm">
                        <a href="${attachment.path}" class="text-blue-600 hover:text-blue-700" target="_blank">
                            📎 ${attachment.name}
                        </a>
                        <a href="${attachment.path}" class="text-blue-500 hover:text-blue-700" download>
                            ⬇️
                        </a>
                    </div>
                `;
            });
            attachmentsHtml += '</div>';
        }

        const actionButtons = !comment.is_deleted && this.canDeleteComment() ? `
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                <button data-action="delete-message" data-comment-id="${comment.id}" 
                        class="text-xs px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded"
                        title="Delete message">
                    Delete
                </button>
            </div>
        ` : '';

        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-blue-800 dark:text-blue-100">
                    ${comment.user_name.charAt(0).toUpperCase()}
                </span>
            </div>
            <div class="flex-1 group relative">
                <div class="flex justify-between items-start mb-1">
                    <span class="font-semibold text-gray-900 dark:text-white">${comment.user_name}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">${timeAgo}</span>
                </div>
                <p data-content class="${contentClass} text-gray-700 dark:text-gray-300 text-sm mb-2">
                    ${this.escapeHtml(content)}
                </p>
                ${attachmentsHtml}
                ${actionButtons}
            </div>
        `;

        return div;
    },

    /**
     * Show new message indicator
     */
    showNewMessageIndicator() {
        const indicator = document.querySelector('[data-new-message-indicator]');
        if (indicator) {
            indicator.style.display = 'flex';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 5000);
        }
    },

    /**
     * Auto scroll to latest message
     */
    autoScrollToLatest() {
        const container = document.querySelector('[data-comments-container]');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, this.config.scrollDuration);
        }
    },

    /**
     * Check if current user can delete comments
     */
    canDeleteComment() {
        // Admin can delete any comment
        if (this.state.userRole === 'admin') {
            return true;
        }
        // Agents and users can delete their own
        return this.state.userRole === 'agent' || this.state.userRole === 'user';
    },

    /**
     * Mark notification as read
     */
    markNotificationAsRead(commentId) {
        const endpoint = this.getMarkReadEndpoint(commentId);

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateNotificationBadge(data.unread_count);
            }
        })
        .catch(error => {
            console.error('Mark read error:', error);
        });
    },

    /**
     * Update notification badge count
     */
    updateNotificationBadge(count) {
        const badge = document.querySelector('[data-notification-badge]');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        }
    },

    /**
     * Update unread notification count
     */
    updateUnreadCount() {
        const endpoint = this.getUnreadCountEndpoint();

        fetch(endpoint, {
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            this.updateNotificationBadge(data.unread_count);
        })
        .catch(error => {
            console.error('Unread count error:', error);
        });
    },

    /**
     * Show notification toast
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 animate-fadeIn ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    },

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    /**
     * Get time ago string
     */
    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'just now';
        if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
        return `${Math.floor(seconds / 86400)}d ago`;
    },

    /**
     * Escape HTML special characters
     */
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    },

    /**
     * Get delete endpoint
     */
    getDeleteEndpoint(commentId) {
        // This should be implemented based on your routing
        // Examples: /user/tickets/comment/{id}, /agent/tickets/comment/{id}, /admin/tickets/comment/{id}
        const basePath = window.location.pathname.split('/')[1]; // Get 'user', 'agent', or 'admin'
        return `/${basePath}/tickets/comment/${commentId}`;
    },

    /**
     * Get refresh endpoint
     */
    getRefreshEndpoint() {
        const basePath = window.location.pathname.split('/')[1];
        const ticketId = this.state.ticketId;
        return `/${basePath}/tickets/${ticketId}/refresh-comments`;
    },

    /**
     * Get mark read endpoint
     */
    getMarkReadEndpoint(commentId) {
        const basePath = window.location.pathname.split('/')[1];
        return `/${basePath}/tickets/comment/${commentId}/mark-read`;
    },

    /**
     * Get unread count endpoint
     */
    getUnreadCountEndpoint() {
        const basePath = window.location.pathname.split('/')[1];
        return `/${basePath}/tickets/unread-count`;
    },

    /**
     * Scroll to message by anchor
     */
    scrollToMessage(anchor) {
        const element = document.querySelector(`[data-message-anchor="${anchor}"]`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('highlight');
            setTimeout(() => {
                element.classList.remove('highlight');
            }, 3000);
        }
    },
};

// Export for use in views
window.TicketConversation = TicketConversation;
