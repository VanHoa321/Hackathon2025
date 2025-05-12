<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="bootstrap 5, premium, multipurpose, sass, agency, seo, marketing, business, digital, rtl" />
    <meta name="description" content="HTML5 Template" />
    <meta name="author" content="www.themeht.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hackathon 2025</title>
    <link href="{{ asset("web-assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/bootstrap-icons.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/animate.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/magnific-popup.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/swiper-bundle.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/odometer.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/spacing.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/seoland-icon.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/base.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/shortcodes.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/style.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("web-assets/css/responsive.css") }}" rel="stylesheet" type="text/css" />
    <link href="#" data-style="styles" rel="stylesheet">
    <link href="{{ asset("web-assets/css/color-customize/color-customizer.css") }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{asset("assets/plugins/toastr/toastr.css")}}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>

<body>

    <div class="page-wrapper">
        <div id="particles-js"></div>
        @include('layout.partial.chatbot')
        @include('layout.partial.menu')

        @yield('content')

        @include('layout.partial.footer')

    </div>

    <div class="scroll-top position-fixed" style="bottom: 100px; right: 16px; z-index: 99998;">
        <svg class="scroll-circle svg-content" width="60" height="60" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <script src="{{ asset("web-assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/jquery-appear.js") }}"></script>
    <script src="{{ asset("web-assets/js/jquery.magnific-popup.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/isotope.pkgd.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/odometer.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/jquery.countdown.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/gsap.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/scrolltrigger.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/gsap-animation.js") }}"></script>
    <script src="{{ asset("web-assets/js/particles.js") }}"></script>
    <script src="{{ asset("web-assets/js/swiper-bundle.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/seoland-swiper-init.js") }}"></script>
    <script src="{{ asset("web-assets/js/sticksy.min.js") }}"></script>
    <script src="{{ asset("web-assets/js/color-customize/color-customizer.js") }}"></script>
    <script src="{{ asset("web-assets/js/theme-script.js") }}"></script>
    <script src="{{asset("assets/plugins/toastr/toastr.min.js")}}"></script>
    @yield('scripts')

    <script>
        $(document).ready(function () {

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);

            $("#sendMessageChatbot").click(function () {
                sendChatbotMessage();
            });

            $("#chatBotInput").keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    sendChatbotMessage();
                }
            });

            function sendChatbotMessage(){
                let userMessage = $("#chatBotInput").val();
                if (userMessage === ""){
                    toastr.error("Vui lòng nhập câu hỏi");
                    return;
                }
                $("#chatbotIntro").remove(); 
                let userBubble = 
                `<div class="d-flex justify-content-end mb-1">                   
                    <div class="bg-light p-2 rounded w-75 text-end">${userMessage}</div>
                    <div class="ms-2">
                        <img src="/storage/files/1/Avatar/12225935.png" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                </div>`;
                $("#chatbotContent").append(userBubble);
                $("#chatBody").animate({ scrollTop: $('#chatBody')[0].scrollHeight }, 500);
                $("#chatBotInput").val("");

                $.ajax({
                    url: "/api/ask-ai",
                    type: "POST",
                    data: { 
                        question: userMessage 
                    },
                    beforeSend: function() {
                        let loadingBubble = `
                        <div id="loadingMessage" class="d-flex align-items-start mb-1">
                            <div class="me-2">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div class="bg-light p-2 rounded w-25 text-center"><i class="fa-solid fa-ellipsis"></i></div>
                        </div>`;
                        $("#chatbotContent").append(loadingBubble);
                        $("#chatBody").animate({ scrollTop: $('#chatBody')[0].scrollHeight }, 500);
                    },
                    success: function (response) {
                        $("#loadingMessage").remove(); 
                        let botMessage = response.answer;
                        botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        let botBubble = 
                        `<div class="d-flex align-items-start mb-1">
                            <div class="me-2">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div class="bg-light p-2 rounded w-75">${botMessage}</div>
                        </div>`;
                        $("#chatbotContent").append(botBubble);
                    },
                    error: function () {
                        $("#loadingMessage").remove(); 
                        toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                    }
                });
            }
        });
    </script>

    <script>
        const chatButton = document.getElementById("chatButton");
        const chatBox = document.getElementById("chatBox");

        chatButton.addEventListener("click", function () {
            chatBox.classList.toggle("d-none");
            chatButton.innerHTML = chatBox.classList.contains("d-none") ? "<i class='bi bi-chat-dots fs-4'></i>" : "<i class='bi bi-x-lg fs-4'></i>";
        });

        document.getElementById("closeChat").addEventListener("click", function () {
            chatBox.classList.add("d-none");
            chatButton.innerHTML = "<i class='bi bi-chat-dots fs-4'></i>";
        });

        document.addEventListener("click", function (event) {
            if (!chatBox.contains(event.target) && !chatButton.contains(event.target)) {
                chatBox.classList.add("d-none");
                chatButton.innerHTML = "<i class='bi bi-chat-dots fs-4'></i>";
            }
        }, true);
    </script>
</body>

</html>