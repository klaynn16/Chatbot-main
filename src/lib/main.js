import { state, WELCOME_MSG } from './state.js';
import { uuid, renderMarkdown, getBotReply } from './utils.js';

const BOT_AVATAR = '/ai-avatar.png';

let _renderedMsgIds = new Set();
let _renderedConvoId = null;

export function createConversation(firstMsg) {
  const id = uuid();
  const msgs = [
    {
      id: 'welcome-' + id,
      role: 'assistant',
      content: WELCOME_MSG,
      timestamp: new Date()
    }
  ];

  if (firstMsg) {
    msgs.push({
      id: uuid(),
      role: 'user',
      content: firstMsg,
      timestamp: new Date()
    });
  }

  return {
    id,
    title: firstMsg || 'New Chat',
    messages: msgs,
    createdAt: new Date()
  };
}

export function getActiveConvo() {
  return state.conversations.find(c => c.id === state.activeId) || state.conversations[0];
}

function buildMessageHTML(msg) {
  const isUser = msg.role === 'user';
  const time = msg.timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

  const avatar = !isUser
    ? `<img src="${BOT_AVATAR}" class="h-8 w-8 rounded-full mt-1" />`
    : '';

  const bubbleCls = isUser
    ? 'bg-chat-user text-primary-foreground rounded-br-md'
    : 'bg-chat-bot text-card-foreground rounded-bl-md';

  const timeCls = isUser
    ? 'text-primary-foreground/60'
    : 'text-muted-foreground';

  return `
    <div class="flex gap-3 ${isUser ? 'justify-end' : 'justify-start'}" data-msg-id="${msg.id}">
      ${avatar}
      <div class="max-w-[70%] rounded-2xl px-4 py-3 text-sm ${bubbleCls}">
        <div class="prose prose-sm max-w-none">${renderMarkdown(msg.content)}</div>
        <p class="text-[10px] mt-1 ${timeCls}">${time}</p>
      </div>
    </div>
  `;
}

export function renderChat() {
  const chatMessages = document.getElementById('chat-messages');
  const suggestedDiv = document.getElementById('suggested-questions');
  const convo = getActiveConvo();
  if (!convo) return;

  if (_renderedConvoId !== convo.id) {
    _renderedMsgIds.clear();
    _renderedConvoId = convo.id;
    chatMessages.innerHTML = '';
  }

  const fragment = document.createDocumentFragment();
  let hasNew = false;

  convo.messages.forEach(msg => {
    if (!_renderedMsgIds.has(msg.id)) {
      _renderedMsgIds.add(msg.id);

      const wrapper = document.createElement('div');
      wrapper.innerHTML = buildMessageHTML(msg);
      fragment.appendChild(wrapper.firstElementChild);

      hasNew = true;
    }
  });

  if (hasNew) {
    chatMessages.appendChild(fragment);

    requestAnimationFrame(() => {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    });
  }

  const hasUserMsg = convo.messages.some(m => m.role === 'user');
  if (suggestedDiv) suggestedDiv.style.display = hasUserMsg ? 'none' : 'block';
}

export function sendMessage(content) {
  const chatInput = document.getElementById('chat-input');
  const chatSendBtn = document.getElementById('chat-send-btn');

  const convo = getActiveConvo();
  if (!convo || !content.trim()) return;

  const userMessage = content.trim();

  const userMsg = {
    id: uuid(),
    role: 'user',
    content: userMessage,
    timestamp: new Date()
  };

  convo.messages.push(userMsg);
  renderChat();

  const botReply = getBotReply(userMessage);

  const botMsg = {
    id: uuid(),
    role: 'assistant',
    content: botReply,
    timestamp: new Date()
  };

  convo.messages.push(botMsg);
  renderChat();

  fetch("http://localhost/admin_db/api/chatlogs/add.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      student_number: state.currentUser?.studentNumber || "unknown",
      question: userMessage,
      response: botReply
    })
  }).catch(err => console.error("Log save error:", err));

  chatInput.value = '';
  chatSendBtn.disabled = false;
}

export function newChat() {
  const c = createConversation();
  state.conversations.unshift(c);
  state.activeId = c.id;

  _renderedMsgIds.clear();
  _renderedConvoId = null;
}