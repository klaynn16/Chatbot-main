
import { state, SUGGESTED_QUESTIONS } from './lib/state.js';
import { createConversation, renderChat, sendMessage, newChat } from './lib/main.js';
import { renderSidebars } from './lib/sidebar.js';
import { showPage, openMobile, closeMobile } from './lib/router.js';
import { initLogin } from './lib/login.js';

window.__chatModule = { createConversation };


function showMain(user) {
  document.getElementById('login-page').style.display = 'none';
  document.getElementById('main-app').style.display = 'flex';

  if (user.role === 'student') {
    if (state.conversations.length === 0) {
      const c = createConversation();
      state.conversations.push(c);
      state.activeId = c.id;
    }
    renderSidebars();
    renderChat();
    showPage('chat');
  } else if (user.role === 'admin') {
    renderSidebars();
    showPage('admin-dashboard');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  lucide.createIcons();

  document.getElementById('mobile-menu-btn').addEventListener('click', openMobile);
  document.getElementById('mobile-close-btn').addEventListener('click', closeMobile);
  document.getElementById('mobile-overlay-bg').addEventListener('click', closeMobile);

  const chatInput = document.getElementById('chat-input');
  const chatSendBtn = document.getElementById('chat-send-btn');
  chatInput.addEventListener('input', () => { chatSendBtn.disabled = !chatInput.value.trim(); });
  chatInput.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) sendMessage(chatInput.value); });
  chatSendBtn.addEventListener('click', () => sendMessage(chatInput.value));

  const suggestedList = document.getElementById('suggested-list');
  SUGGESTED_QUESTIONS.forEach(q => {
    const btn = document.createElement('button');
    btn.textContent = q;
    btn.className = 'px-3 py-1.5 text-xs rounded-full border border-suggested-border bg-suggested text-secondary-foreground hover:bg-primary hover:text-primary-foreground hover:border-primary transition-colors';
    btn.addEventListener('click', () => sendMessage(q));
    suggestedList.appendChild(btn);
  });

  initLogin(showMain);
});
