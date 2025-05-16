
<button id="toggleModal2" class="btn rounded-circle position-fixed bottom-0 end-0 m-4" style="width: 60px; height: 60px; z-index: 9999; background-color:#11B76B; color:#FFFFFF">
    <i class="fas fa-comments"></i>
    <i class="fas fa-times" style="display: none"></i>
</button>

<div id="modalContainer2" class="position-fixed bg-white rounded-3 shadow d-flex flex-column overflow-hidden" style="visibility: hidden; opacity: 0; transform: scale(0.95); transition: all 0.4s ease;">
    <header class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
        <button id="zoomModal2" aria-label="Zoom" class="btn btn-link p-0 text-dark">
            <i class="fa fa-expand"></i>
        </button>
        <div class="d-flex align-items-center bg-white rounded-pill px-2 py-1 shadow-sm">
            <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="ChatBot logo" class="rounded-circle" width="32" height="32" />
            <span class="fw-semibold ms-2">WHK18</span>
        </div>
        <button id="minimizeModal2" aria-label="Minimize" class="btn btn-link p-0 text-dark">
            <i class="fas fa-minus"></i>
        </button>
    </header>

    <main id="chatbotDocumentContent2" class="flex-grow-1 overflow-auto chat-body px-3 py-4">
        <div id="chatbotIntro2" class="d-flex align-items-start mb-3">
            <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
            <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">Xin chào, bạn cần hỏi gì trong tài liệu này!</div>
        </div>
    </main>

    <div class="d-flex align-items-center border border-primary rounded-pill mx-3 my-3 px-3 py-2">
        <input id="chatBotDocumentInput2" type="text" class="form-control border-0 me-2 custom-no-focus" placeholder="Nhập câu hỏi với tài liệu..." aria-label="Write a message" />
        <button id="sendMessageChatbotDocument2" type="button" class="btn btn-link text-secondary p-0">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>

    <footer class="d-flex justify-content-center gap-2 text-center text-muted small pb-3">
        Được phát triển bởi
        <span class="d-inline-flex align-items-center">
            <span class="fw-semibold">WHK18</span>
        </span>
    </footer>
</div>

@section('styles')
    <style>
        .chat-body::-webkit-scrollbar {
            width: 6px;
        }

        .chat-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }

        #modalContainer2 {
            width: 450px;
            height: 600px;
            z-index: 9999;
            bottom: 90px;
            right: 1.5rem;
            border-radius: 12px;
            visibility: hidden;
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.4s ease;
        }

        #modalContainer2.fullscreen {
            width: 100% !important;
            height: 100% !important;
            top: 0;
            left: 0;
            border-radius: 0 !important;
        }

        .custom-no-focus:focus {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
@endsection