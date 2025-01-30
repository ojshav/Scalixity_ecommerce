@php
    $channel = core()->getCurrentChannel();
@endphp

@push ('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

<x-shop::layouts>
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    
    <!-- Chat Button -->
    <div 
        id="chat-button"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 999; 
               background: #4CAF50; color: white; border: none; 
               padding: 15px; border-radius: 50%; cursor: pointer;
               width: 60px; height: 60px; display: flex;
               align-items: center; justify-content: center;
               box-shadow: 0 2px 10px rgba(0,0,0,0.1);"
        onclick="openChat()"
    >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </div>

    <!-- Chat UI Container (Hidden by default) -->
    <div id="chat-ui-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: none;">
        <div style="position: relative;">
            <button 
                id="close-chat" 
                style="position: absolute; top: 10px; right: 10px; z-index: 1001; background: #ff4444; color: white; border: none; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                onclick="closeChat()"
            >
                ×
            </button>
            <iframe 
                src="{{ url('http://localhost:3000/') }}" 
                style="width: 350px; height: 500px; border: none; border-radius: 8px;"
                title="Chatbot"
            ></iframe>
        </div>
    </div>

    <script>
        // Define functions in global scope
        function openChat() {
            console.log('Opening chat...'); // Debug log
            document.getElementById('chat-ui-container').style.display = 'block';
            document.getElementById('chat-button').style.display = 'none';
        }

        function closeChat() {
            console.log('Closing chat...'); // Debug log
            document.getElementById('chat-ui-container').style.display = 'none';
            document.getElementById('chat-button').style.display = 'flex';
        }

        // Add event listeners once DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing chat...'); // Debug log
            
            // Ensure elements exist
            const chatButton = document.getElementById('chat-button');
            const chatContainer = document.getElementById('chat-ui-container');
            const closeButton = document.getElementById('close-chat');
            
            if (chatButton) console.log('Chat button found');
            if (chatContainer) console.log('Chat container found');
            if (closeButton) console.log('Close button found');
        });
    </script>
    
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <x-shop::carousel :options="$data" aria-label="Image Carousel" />
                @break

            @case ($customization::STATIC_CONTENT)
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif
                @break

            @case ($customization::CATEGORY_CAROUSEL)
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="Categories Carousel"
                />
                @break

            @case ($customization::PRODUCT_CAROUSEL)
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="Product Carousel"
                />
                @break
        @endswitch
    @endforeach
</x-shop::layouts>