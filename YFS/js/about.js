 // Mobile menu functionality
    document.querySelector('.menu-toggle').addEventListener('click', function () {
        document.getElementById('main-nav').classList.toggle('active');
    this.classList.toggle('active');
        });

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
            const nav = document.getElementById('main-nav');
    const menuToggle = document.querySelector('.menu-toggle');

    if (!nav.contains(event.target) && event.target !== menuToggle && !menuToggle.contains(event.target)) {
        nav.classList.remove('active');
    menuToggle.classList.remove('active');
            }
        });

    // Chatbot functionality
    function toggleChatbot() {
            const chatbot = document.getElementById('chatbot');
    const isOpening = chatbot.style.display !== 'flex';
    chatbot.style.display = isOpening ? 'flex' : 'none';

    // If opening the chatbot, show greeting and options
    if (isOpening) {
        // Clear any existing messages
        document.getElementById('chatbotMessages').innerHTML = '';

    // Show greeting after a short delay
    setTimeout(showGreeting, 300);
    document.getElementById('chatbotInput').focus();
            }
        }

    // FAQ responses
    const faq = {
        "what is yfs": "Youth For Survival (YFS) is a registered Not-For-Profit Organization founded in 2004, officially registered in 2007. We focus on addressing GBV, social inequality, and economic hardship.",
    "where are you located": "Our head office is in Pretoria, Gauteng, with branches in Moloto North (Mpumalanga), Maboloka (North West), Nongoma (KwaZulu-Natal), and Port St. Johns (Eastern Cape).",
    "what do you do": "We provide safe houses for GBV survivors, run soup kitchens, offer skills training for unemployed youth, and support community development.",
    "how can i donate": "You can donate securely by clicking the 'Donate Now' button at the top of the page.",
    "when were you founded": "YFS was founded in 2004 and officially registered in 2007.",
    "contact number": "Our phone number is 012 304 0001.",
    "who do you help": "We focus on helping women, children, and youth from disadvantaged communities."
        };

    // Quick selection options
    const quickOptions = [
    "What is YFS?",
    "Where are you located?",
    "What do you do?",
    "How can I donate?",
    "When were you founded?",
    "Contact number",
    "Who do you help?"
    ];

    // Show greeting message with quick options
    function showGreeting() {
            const messages = document.getElementById('chatbotMessages');

    // Add greeting message
    const greetingDiv = document.createElement('div');
    greetingDiv.classList.add('message', 'bot-message');
    greetingDiv.innerHTML = "<strong>Welcome to YFS Support!</strong> How can I help you today?";
    messages.appendChild(greetingDiv);

    // Show typing indicator
    showTypingIndicator();

            // After a delay, show the quick options
            setTimeout(() => {
        removeTypingIndicator();
    showQuickOptions();
    messages.scrollTop = messages.scrollHeight;
            }, 1000);
        }

    // Show typing indicator
    function showTypingIndicator() {
            const messages = document.getElementById('chatbotMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.classList.add('typing-indicator');
    typingDiv.innerHTML = '<span></span><span></span><span></span>';
    messages.appendChild(typingDiv);
    messages.scrollTop = messages.scrollHeight;
        }

    // Remove typing indicator
    function removeTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
            }
        }

    // Show quick options
    function showQuickOptions() {
            const messages = document.getElementById('chatbotMessages');

    // Add prompt for quick options
    const promptDiv = document.createElement('div');
    promptDiv.classList.add('message', 'bot-message');
    promptDiv.textContent = "Please select from the following questions I can answer:";
    messages.appendChild(promptDiv);

    // Add quick options
    const optionsDiv = document.createElement('div');
    optionsDiv.classList.add('quick-options');

            quickOptions.forEach(option => {
                const button = document.createElement('button');
    button.classList.add('quick-option-btn');
    button.textContent = option;
                button.addEventListener('click', () => {
        // Simulate user clicking on the option
        selectQuickOption(option);
                });
    optionsDiv.appendChild(button);
            });

    messages.appendChild(optionsDiv);
    messages.scrollTop = messages.scrollHeight;
        }

    // Handle quick option selection
    function selectQuickOption(option) {
            const input = document.getElementById('chatbotInput');
    input.value = option;

    // Remove quick options
    const quickOptionsDiv = document.querySelector('.quick-options');
    if (quickOptionsDiv) {
        quickOptionsDiv.remove();
            }

    sendMessage();
        }

    // Send message function
    function sendMessage() {
            const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');
    const userMessage = input.value.trim();

    if (!userMessage) return;

    // Show user message
    const userDiv = document.createElement('div');
    userDiv.classList.add('message', 'user-message');
    userDiv.textContent = userMessage;
    messages.appendChild(userDiv);

    // Remove quick options after user sends a message
    const quickOptions = document.querySelector('.quick-options');
    if (quickOptions) {
        quickOptions.remove();
            }

    // Find answer
    const key = userMessage.toLowerCase();
    let answer = "I'm sorry, I can only answer questions about YFS. Please select from the available options.";
    let found = false;

    for (let q in faq) {
                if (key.includes(q)) {
        answer = faq[q];
    found = true;
    break;
                }
            }

    // Show typing indicator before bot response
    showTypingIndicator();

            // Show bot reply after a short delay
            setTimeout(() => {
        removeTypingIndicator();

    const botDiv = document.createElement('div');
    botDiv.classList.add('message', 'bot-message');
    botDiv.textContent = answer;
    messages.appendChild(botDiv);

    // Show options again after response
    showTypingIndicator();
                setTimeout(() => {
        removeTypingIndicator();
    showQuickOptions();
    messages.scrollTop = messages.scrollHeight;
                }, 800);

    // Scroll to bottom
    messages.scrollTop = messages.scrollHeight;
            }, 1200);

    input.value = "";
    messages.scrollTop = messages.scrollHeight;
        }

    // Add event listener for Enter key in chatbot input
    document.getElementById('chatbotInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
        sendMessage();
            }
        });

    // Initialize chatbot with greeting when page loads (if chatbot is open by default)
    document.addEventListener('DOMContentLoaded', function () {
            const chatbot = document.getElementById('chatbot');
    if (chatbot && window.getComputedStyle(chatbot).display === 'flex') {
        showGreeting();
            }

    // Add CSS for typing indicator if not already present
    if (!document.querySelector('style#chatbot-styles')) {
                const styles = document.createElement('style');
    styles.id = 'chatbot-styles';
    styles.textContent = `
    .typing-indicator {
        background: #e6e6e6;
    padding: 10px 15px;
    border-radius: 18px;
    display: inline-block;
    margin-bottom: 10px;
    max-width: 70%;
                    }

    .typing-indicator span {
        height: 8px;
    width: 8px;
    float: left;
    margin: 0 1px;
    background-color: #9E9EA1;
    display: block;
    border-radius: 50%;
    opacity: 0.4;
                    }

    .typing-indicator span:nth-of-type(1) {
        animation: typing 1s infinite;
                    }

    .typing-indicator span:nth-of-type(2) {
        animation: typing 1s 0.33s infinite;
                    }

    .typing-indicator span:nth-of-type(3) {
        animation: typing 1s 0.66s infinite;
                    }

    @keyframes typing {
        0 %, 100 % {
            transform: translateY(0);
            opacity: 0.4;
        }
                        50% {
        transform: translateY(-5px);
    opacity: 1;
                        }
                    }

    .quick-options {
        display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 10px 0;
                    }

    .quick-option-btn {
        background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 16px;
    padding: 8px 12px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
                    }

    .quick-option-btn:hover {
        background: #e0e0e0;
    border-color: #ccc;
                    }
    `;
    document.head.appendChild(styles);
            }
        });
