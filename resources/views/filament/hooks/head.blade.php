<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Custom Chat Styles with Dark Mode Support -->
<style>
    /* Light Mode */
    .chat-item:hover {
        background-color: rgb(248 250 252);
        transition: background-color 0.2s ease;
    }
    
    .chat-item.selected {
        background-color: rgb(239 246 255);
        border-left: 4px solid rgb(59 130 246);
    }
    
    /* Dark Mode */
    .dark .chat-item:hover {
        background-color: rgb(31 41 55);
    }
    
    .dark .chat-item.selected {
        background-color: rgb(30 58 138);
        border-left: 4px solid rgb(96 165 250);
    }
    
    /* Scrollbar Styles */
    #messages-container {
        scrollbar-width: thin;
        scrollbar-color: rgb(203 213 225) rgb(241 245 249);
    }
    
    .dark #messages-container {
        scrollbar-color: rgb(75 85 99) rgb(31 41 55);
    }
    
    #messages-container::-webkit-scrollbar {
        width: 6px;
    }
    
    #messages-container::-webkit-scrollbar-track {
        background: rgb(241 245 249);
        border-radius: 10px;
    }
    
    .dark #messages-container::-webkit-scrollbar-track {
        background: rgb(31 41 55);
    }
    
    #messages-container::-webkit-scrollbar-thumb {
        background: rgb(203 213 225);
        border-radius: 10px;
    }
    
    .dark #messages-container::-webkit-scrollbar-thumb {
        background: rgb(75 85 99);
    }
    
    #messages-container::-webkit-scrollbar-thumb:hover {
        background: rgb(148 163 184);
    }
    
    .dark #messages-container::-webkit-scrollbar-thumb:hover {
        background: rgb(107 114 128);
    }
      /* Message Animations */
    .message-bubble {
        animation: fadeInUp 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Admin Message Bubble Styles */
    .admin-bubble {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1), 0 2px 4px -1px rgba(16, 185, 129, 0.06);
    }
    
    .user-bubble {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    }
    
    .received-bubble {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .dark .received-bubble {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
    }
    
    /* Input Focus Styles */
    .chat-input {
        transition: all 0.2s ease;
    }
    
    .chat-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .dark .chat-input:focus {
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
    }
    
    /* Button Animations */
    .send-button {
        transition: all 0.2s ease;
    }
    
    .send-button:hover:not(:disabled) {
        transform: scale(1.05);
    }
    
    /* Online Indicator */
    .online-indicator {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    /* Chat Container Dark Mode */
    .dark .chat-container {
        background-color: rgb(17 24 39);
        border-color: rgb(55 65 81);
    }
    
    .dark .chat-header {
        background: linear-gradient(to right, rgb(17 24 39), rgb(31 41 55));
        border-color: rgb(55 65 81);
    }
    
    .dark .chat-input-area {
        background-color: rgb(31 41 55);
        border-color: rgb(55 65 81);
    }
    
    .dark .message-input {
        background-color: rgb(55 65 81);
        border-color: rgb(75 85 99);
        color: rgb(229 231 235);
    }
    
    .dark .message-input::placeholder {
        color: rgb(156 163 175);
    }
    
    .dark .file-upload-button {
        background-color: rgb(55 65 81);
    }
    
    .dark .file-upload-button:hover {
        background-color: rgb(75 85 99);
    }
</style>
