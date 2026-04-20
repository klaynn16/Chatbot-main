// utilityfunction
export function uuid() {
  return crypto.randomUUID();
}

export function renderMarkdown(text) {
  return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
}

export function getBotReply(msg) {
  return null;
}

export function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

export function formatMonth(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short' }).toUpperCase();
}

export function formatDay(dateStr) {
  return new Date(dateStr).getDate().toString().padStart(2, '0');
}

export function highlightText(text, keyword) {
  if (!keyword) return text;
  const regex = new RegExp(`(${keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
  return text.replace(regex, '<mark class="bg-yellow-200 text-foreground rounded px-0.5">$1</mark>');
}

export function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}
