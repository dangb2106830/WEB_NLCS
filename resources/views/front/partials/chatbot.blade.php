<div
    id="hometech-chatbot"
    class="ht-chatbot"
    data-endpoint="{{ route('ai.chat') }}"
    data-csrf="{{ csrf_token() }}"
>
    <button
        type="button"
        class="ht-chatbot__launcher"
        aria-expanded="false"
        aria-controls="hometech-chatbot-panel"
        aria-label="Mở chatbot MinhDang"
    >
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 3C6.477 3 2 7.03 2 12c0 2.04.77 3.92 2.07 5.42L3 21l4.03-1.55A11.1 11.1 0 0 0 12 20c5.523 0 10-4.03 10-9s-4.477-8-10-8Zm-4 7h8a1 1 0 1 1 0 2H8a1 1 0 1 1 0-2Zm0 4h5a1 1 0 1 1 0 2H8a1 1 0 1 1 0-2Z" />
        </svg>
    </button>

    <section
        id="hometech-chatbot-panel"
        class="ht-chatbot__panel"
        hidden
        aria-hidden="true"
    >
        <header class="ht-chatbot__header">
            <div>
                <p class="ht-chatbot__eyebrow">Hỗ trợ thông minh</p>
                <h3>Chat với MINHDANG.VN</h3>
            </div>

            <button
                type="button"
                class="ht-chatbot__close"
                aria-label="Đóng chatbot"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.4 5 12 10.6 17.6 5 19 6.4 13.4 12 19 17.6 17.6 19 12 13.4 6.4 19 5 17.6 10.6 12 5 6.4 6.4 5Z" />
                </svg>
            </button>
        </header>

        <div class="ht-chatbot__body">
            <div class="ht-chatbot__messages" role="log" aria-live="polite" aria-label="Tin nhắn chatbot"></div>
        </div>

        <form class="ht-chatbot__composer">
            <input
                type="text"
                name="message"
                class="ht-chatbot__input"
                placeholder="Nhập câu hỏi về sản phẩm, bài viết, chính sách..."
                autocomplete="off"
                maxlength="1000"
            >
            <button type="submit" class="ht-chatbot__send">Gửi</button>
        </form>
    </section>
</div>

<style>
    .ht-chatbot {
        --ht-chatbot-bottom: 88px;
        --ht-chatbot-panel-offset: 84px;
        position: fixed;
        right: 24px;
        bottom: var(--ht-chatbot-bottom);
        z-index: 10050;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .ht-chatbot__launcher {
        width: 64px;
        height: 64px;
        border: 0;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f7a800 0%, #ff6a00 100%);
        color: #fff;
        box-shadow: 0 18px 40px rgba(226, 111, 0, 0.28);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .ht-chatbot__launcher:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 48px rgba(226, 111, 0, 0.34);
    }

    .ht-chatbot__launcher svg,
    .ht-chatbot__close svg {
        width: 28px;
        height: 28px;
        fill: currentColor;
    }

    .ht-chatbot__panel {
        width: min(380px, calc(100vw - 48px));
        height: min(620px, calc(100vh - var(--ht-chatbot-bottom) - var(--ht-chatbot-panel-offset) - 12px));
        position: absolute;
        right: 0;
        bottom: var(--ht-chatbot-panel-offset);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid rgba(201, 118, 0, 0.16);
        background: linear-gradient(180deg, #fffaf2 0%, #ffffff 18%, #ffffff 100%);
        box-shadow: 0 28px 80px rgba(35, 26, 11, 0.22);
    }

    .ht-chatbot__header {
        padding: 18px 20px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #fff;
        background:
            radial-gradient(circle at top left, rgba(255, 227, 173, 0.55), transparent 46%),
            linear-gradient(135deg, #1f2937 0%, #27354a 58%, #101826 100%);
    }

    .ht-chatbot__header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
    }

    .ht-chatbot__eyebrow {
        margin: 0 0 4px;
        font-size: 11px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255, 229, 171, 0.92);
    }

    .ht-chatbot__close {
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 999px;
        flex: 0 0 auto;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .ht-chatbot__close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .ht-chatbot__body {
        flex: 1 1 auto;
        min-height: 0;
        padding: 16px;
        background:
            radial-gradient(circle at top right, rgba(255, 193, 93, 0.08), transparent 38%),
            linear-gradient(180deg, #fffefc 0%, #fff7eb 100%);
    }

    .ht-chatbot__messages {
        height: 100%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-right: 6px;
    }

    .ht-chatbot__messages::-webkit-scrollbar {
        width: 6px;
    }

    .ht-chatbot__messages::-webkit-scrollbar-thumb {
        background: rgba(167, 116, 24, 0.28);
        border-radius: 999px;
    }

    .ht-chatbot__message {
        max-width: 85%;
        padding: 12px 14px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
        box-shadow: 0 10px 18px rgba(36, 26, 16, 0.05);
    }

    .ht-chatbot__message--bot {
        align-self: flex-start;
        border-top-left-radius: 8px;
        color: #2f2417;
        background: #fff;
    }

    .ht-chatbot__message--user {
        align-self: flex-end;
        border-top-right-radius: 8px;
        color: #fff;
        background: linear-gradient(135deg, #ff8f1f 0%, #f0561f 100%);
    }

    .ht-chatbot__message a {
        color: #c45b00;
        font-weight: 700;
        text-decoration: none;
    }

    .ht-chatbot__message--user a {
        color: #fff8d8;
    }

    .ht-chatbot__message--loading::after {
        content: "...";
        display: inline-block;
        width: 16px;
        animation: ht-chatbot-dots 1s steps(4, end) infinite;
    }

    @keyframes ht-chatbot-dots {
        0% { content: ""; }
        25% { content: "."; }
        50% { content: ".."; }
        75% { content: "..."; }
        100% { content: ""; }
    }

    .ht-chatbot__composer {
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-top: 1px solid rgba(196, 137, 31, 0.14);
        background: #fff;
    }

    .ht-chatbot__input {
        flex: 1 1 auto;
        min-width: 0;
        height: 48px;
        border: 1px solid #e8d8b9;
        border-radius: 14px;
        padding: 0 14px;
        font-size: 14px;
        color: #302213;
        background: #fffaf2;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .ht-chatbot__input:focus {
        border-color: #ff9a2f;
        box-shadow: 0 0 0 4px rgba(255, 166, 54, 0.15);
    }

    .ht-chatbot__send {
        height: 48px;
        border: 0;
        border-radius: 14px;
        padding: 0 18px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #1f2937 0%, #314258 100%);
        cursor: pointer;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .ht-chatbot__send:hover {
        transform: translateY(-1px);
    }

    .ht-chatbot__send:disabled,
    .ht-chatbot__input:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    @media (max-width: 767.98px) {
        .ht-chatbot {
            --ht-chatbot-bottom: 82px;
            --ht-chatbot-panel-offset: 76px;
            right: 16px;
        }

        .ht-chatbot__launcher {
            width: 58px;
            height: 58px;
        }

        .ht-chatbot__panel {
            width: min(380px, calc(100vw - 20px));
            height: min(72vh, calc(100vh - var(--ht-chatbot-bottom) - var(--ht-chatbot-panel-offset) - 18px));
            right: -4px;
            border-radius: 20px;
        }
    }
</style>

<script>
    (function () {
        const root = document.getElementById('hometech-chatbot');

        if (!root || root.dataset.initialized === 'true') {
            return;
        }

        root.dataset.initialized = 'true';

        const launcher = root.querySelector('.ht-chatbot__launcher');
        const panel = root.querySelector('.ht-chatbot__panel');
        const closeButton = root.querySelector('.ht-chatbot__close');
        const messages = root.querySelector('.ht-chatbot__messages');
        const form = root.querySelector('.ht-chatbot__composer');
        const input = root.querySelector('.ht-chatbot__input');
        const submitButton = root.querySelector('.ht-chatbot__send');
        const endpoint = root.dataset.endpoint;
        const csrfToken = root.dataset.csrf;

        let isSending = false;

        appendMessage('bot', 'Xin chào, tôi là trợ lý MinhDang. Bạn có thể hỏi về sản phẩm, bài viết hoặc chính sách.');

        launcher.addEventListener('click', function () {
            togglePanel();
        });

        closeButton.addEventListener('click', function () {
            togglePanel(false);
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            sendMessage();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !panel.hidden) {
                togglePanel(false);
            }
        });

        function togglePanel(forceState) {
            const willOpen = typeof forceState === 'boolean' ? forceState : panel.hidden;

            panel.hidden = !willOpen;
            panel.setAttribute('aria-hidden', String(!willOpen));
            launcher.setAttribute('aria-expanded', String(willOpen));

            if (willOpen) {
                window.setTimeout(function () {
                    input.focus();
                    scrollToBottom();
                }, 60);
            }
        }

        function appendMessage(role, text, options) {
            const config = options || {};
            const bubble = document.createElement('div');

            bubble.className = 'ht-chatbot__message ht-chatbot__message--' + role;

            if (config.loading) {
                bubble.classList.add('ht-chatbot__message--loading');
            }

            if (config.markdown) {
                bubble.innerHTML = formatMarkdown(text);
            } else {
                bubble.textContent = text;
            }

            messages.appendChild(bubble);
            scrollToBottom();

            return bubble;
        }

        function scrollToBottom() {
            messages.scrollTop = messages.scrollHeight;
        }

        async function sendMessage() {
            const message = input.value.trim();

            if (!message || isSending) {
                return;
            }

            togglePanel(true);
            appendMessage('user', message);

            input.value = '';
            input.focus();
            setSendingState(true);

            const loadingBubble = appendMessage('bot', 'MinhDang đang tìm thông tin', { loading: true });

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: message })
                });

                const payload = await response.json().catch(function () {
                    return {};
                });

                loadingBubble.remove();

                if (!response.ok) {
                    appendMessage('bot', payload.message || 'Xin lỗi, tôi chưa thể trả lời lúc này.');
                    return;
                }

                appendMessage('bot', payload.answer || 'Xin lỗi, tôi chưa có câu trả lời phù hợp.', { markdown: true });
            } catch (error) {
                loadingBubble.remove();
                appendMessage('bot', 'Không thể kết nối chatbot lúc này. Vui lòng thử lại sau.');
            } finally {
                setSendingState(false);
            }
        }

        function setSendingState(state) {
            isSending = state;
            input.disabled = state;
            submitButton.disabled = state;
        }

        function formatMarkdown(text) {
            const escaped = escapeHtml(text);

            return escaped
                .replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>')
                .replace(/\n/g, '<br>');
        }

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    })();
</script>
