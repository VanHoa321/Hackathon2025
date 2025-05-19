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
            <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">Xin chào, bạn có câu hỏi gì về hệ thống!</div>
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
        position: fixed;
        background-color: white;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #modalContainer2.scale-effect {
        transform: translate(-50%, -50%) scale(0.95);
    }

    #modalContainer2.fullscreen {
        width: 100% !important;
        height: 100% !important;
        top: 0;
        left: 0;
        transform: none;
        border-radius: 0 !important;
    }

    .custom-no-focus:focus {
        box-shadow: none !important;
        outline: none !important;
    }
</style>
@endsection

<script>
    // Toggle modal visibility
    document.getElementById('toggleModal2').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer2');
        const commentIcon = this.querySelector('.fa-comments');
        const closeIcon = this.querySelector('.fa-times');
        if (modal.style.visibility === 'hidden') {
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
            commentIcon.style.display = 'none';
            closeIcon.style.display = 'inline';
        } else {
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.95)';
            commentIcon.style.display = 'inline';
            closeIcon.style.display = 'none';
        }
    });

    // Zoom modal to fullscreen
    document.getElementById('zoomModal2').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer2');
        modal.classList.toggle('fullscreen');
    });

    // Minimize modal
    document.getElementById('minimizeModal2').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer2');
        const commentIcon = document.getElementById('toggleModal2').querySelector('.fa-comments');
        const closeIcon = document.getElementById('toggleModal2').querySelector('.fa-times');
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        commentIcon.style.display = 'inline';
        closeIcon.style.display = 'none';
    });
</script>

@section('scriptchatbot')
<script>
    $(document).ready(function() {

        $("#sendMessageChatbotDocument2").click(function() {
            sendChatbotMessage();
        });

        $("#chatBotDocumentInput2").keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                sendChatbotMessage();
            }
        });

        function sendChatbotMessage() {
            let userMessage = $("#chatBotDocumentInput2").val();
            if (userMessage === "") {
                toastr.error("Vui lòng nhập câu hỏi");
                return;
            }
            $("#chatbotIntro2").remove();
            let userBubble =
                `<div class="d-flex justify-content-end mb-1">
                        <div class="bg-primary text-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${userMessage}</div>
                    </div>`;
            $("#chatbotDocumentContent2").append(userBubble);
            $("#chatbotDocumentContent2").animate({
                scrollTop: $('#chatbotDocumentContent2')[0].scrollHeight
            }, 500);

            $("#chatBotDocumentInput2").val("");
            $.ajax({
                url: "/api/ask-ai",
                type: "POST",
                data: {
                    question: userMessage
                },
                beforeSend: function() {
                    let loadingBubble = `
                            <div id="loadingMessage" class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%"><i class="fas fa-spinner fa-spin"></i></div>
                            </div>`;
                    $("#chatbotDocumentContent2").append(loadingBubble);
                    $("#chatbotDocumentContent2").animate({
                        scrollTop: $('#chatbotDocumentContent2')[0].scrollHeight
                    }, 500);
                },
                success: function(response) {
                    $("#loadingMessage").remove();
                    let botMessage = response.answer;
                    botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    let botBubble =
                        `<div class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${botMessage}</div>
                            </div>`;
                    $("#chatbotDocumentContent2").append(botBubble);
                },
                error: function() {
                    $("#loadingMessage").remove();
                    toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                }
            });
        }
    });
</script>

<script>
    $('#chatbotDocumentContent2').on('dragover', function(event) {
            event.preventDefault(); 
            $(this).css('border', '2px dashed #00ff00'); 
        });

        $('#chatbotDocumentContent2').on('dragleave', function(event) {
            event.preventDefault();
            $(this).css('border', 'none');
        });

        $('#chatbotDocumentContent2').on('drop', function(event) {
            event.preventDefault();
            $(this).css('border', 'none');

            let files = event.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                let imageFile = files[0];
                $("#chatbotIntro2").remove();
                sendImage(imageFile);
            }
        });

    function sendImage(imageFile) {
            let formData = new FormData();
            formData.append('image', imageFile);
            let imageUrl = URL.createObjectURL(imageFile);
            let userImageBubble = 
            `<div class="d-flex justify-content-end mb-1">
                <div class="bg-light p-2 rounded width-auto text-end">
                    <img src="${imageUrl}" alt="User Image" class="img-fluid rounded" style="max-width: 100px; height: 100px">
                </div>
                <div class="ms-2">
                    <img src="/storage/files/1/Avatar/12225935.png" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                </div>
            </div>`;
            $("#chatbotDocumentContent2").append(userImageBubble);
            $("#chatbotDocumentContent2").animate({ scrollTop: $('#chatbotDocumentContent2')[0].scrollHeight }, 500);
            $.ajax({
                url: "/api/question",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    let loadingBubble = `
                            <div id="loadingMessage" class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%"><i class="fas fa-spinner fa-spin"></i></div>
                            </div>`;
                    $("#chatbotDocumentContent2").append(loadingBubble);
                    $("#chatbotDocumentContent2").animate({
                        scrollTop: $('#chatbotDocumentContent2')[0].scrollHeight
                    }, 500);
                },
                success: function(response) {
                    $("#loadingMessage").remove();
                    let botMessage = response.response_text;
                    botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    let botBubble =
                        `<div class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${botMessage}</div>
                            </div>`;
                    $("#chatbotDocumentContent2").append(botBubble);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    toastr.error("Có lỗi xảy ra, đã ghi log trong console");
                }
            });
        }
</script>
@endsection