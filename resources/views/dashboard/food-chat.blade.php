@extends('dashboard.layout')

@section('title', 'Ask AI — Vitality')
@section('eyebrow', 'Feature 05')
@section('heading', 'Ask what to eat')
@section('sub', 'Chat with the AI about meals, recipes, or what fits your goals — it knows what is in the food database.')

@section('content')
<div class="stall">
  <div id="chatWindow" style="display:flex;flex-direction:column;gap:12px;min-height:200px;max-height:440px;overflow-y:auto;margin-bottom:16px;padding-right:4px;">
    <div style="background:var(--bg-panel-2);padding:12px 14px;border-radius:12px;font-size:14px;max-width:85%;">
      Hi! Ask me things like "what should I eat for a high-protein lunch" or "give me a low-cost dinner idea."
    </div>
  </div>
  <div style="display:flex;gap:10px;">
    <input class="field" id="chatInput" type="text" placeholder="What should I eat today?" style="flex:1;width:auto;margin:0;">
    <button class="action" onclick="sendChat()">Send</button>
  </div>
  <div class="status" id="chatStatus"></div>
</div>
@endsection

@section('scripts')
<script>
let chatHistory = [];

function appendBubble(text, fromUser){
  const window_ = document.getElementById('chatWindow');
  const bubble = document.createElement('div');
  bubble.style.cssText = `
    background:${fromUser ? 'var(--mint)' : 'var(--bg-panel-2)'};
    color:${fromUser ? '#06281C' : 'var(--ink)'};
    padding:12px 14px;
    border-radius:12px;
    font-size:14px;
    max-width:85%;
    align-self:${fromUser ? 'flex-end' : 'flex-start'};
    white-space:pre-wrap;
  `;
  bubble.textContent = text;
  window_.appendChild(bubble);
  window_.scrollTop = window_.scrollHeight;
}

async function sendChat(){
  const token = getToken();
  if(!token){ setStatus('chatStatus', 'Log in first', false); return; }

  const input = document.getElementById('chatInput');
  const message = input.value.trim();
  if(!message) return;

  appendBubble(message, true);
  input.value = '';
  setStatus('chatStatus', 'Thinking…', true);

  try{
    const res = await fetch(BASE + '/api/food-chat', {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({ message, history: chatHistory })
    });
    const data = await res.json();
    if(data.reply){
      appendBubble(data.reply, false);
      chatHistory.push({ role: 'user', content: message });
      chatHistory.push({ role: 'assistant', content: data.reply });
      if(chatHistory.length > 10) chatHistory = chatHistory.slice(-10);
      setStatus('chatStatus', '', true);
    } else {
      setStatus('chatStatus', data.message || 'Could not get a reply', false);
    }
  }catch(e){
    setStatus('chatStatus', 'Error: ' + e.message, false);
  }
}

document.getElementById('chatInput').addEventListener('keydown', function(e){
  if(e.key === 'Enter') sendChat();
});
</script>
@endsection
