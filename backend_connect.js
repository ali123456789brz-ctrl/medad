
(function(){
  const API_BASE = '/medad_wamp_backend/api';
  const $ = id => document.getElementById(id);
  const alertBox = (m)=>{ try{ toast(m) }catch(e){ alert(m) } };

  function show(el){ if(el) el.classList.remove('hidden'); }
  function focusInside(el){ if(!el) return; const i = el.querySelector('input,textarea'); if(i) i.focus(); }

  function findButtons(){
    return Array.from(document.querySelectorAll('a,button,span,div')).filter(n=>{
      const t = (n.innerText||n.textContent||'').trim();
      return t.length>0 && t.length<40; 
    });
  }

  function openAIChat(){
    const modal = $('aiChatModal') || document.querySelector('.ai-chat-modal') || document.querySelector('[data-modal="ai"]');
    if(!modal){ console.warn('AI modal not found'); alertBox('پنجرهٔ چت هوشمند پیدا نشد.'); return; }
    show(modal); focusInside(modal);
  }
  function openClientChat(){
    const modal = $('ticketDetailsModal') || $('chatArea') || document.querySelector('.client-chat-modal') || document.querySelector('[data-modal="client"]');
    if(!modal){ console.warn('Client chat modal not found'); alertBox('پنجرهٔ چت کاربر پیدا نشد.'); return; }
    show(modal); focusInside(modal);
  }


  const aiKeywords = ['هوشمند','مشاور هوشمند','🤖','ai','هوش مصنوعی','مشاور هوش','مشاور ai'];
  const clientKeywords = ['رایگان','وکیل','مشاوره رایگان','با وکیل','مشاور حقوقی','تماس با وکیل','شروع چت','گفتگو','چت با','چت'];

  function matchesAny(text, list){
    const t = text.replace(/\s+/g,' ').toLowerCase();
    return list.some(k => t.includes(k));
  }

  function bindCorrectly(){
    const buttons = findButtons();
    buttons.forEach(btn=>{
      const txt = (btn.innerText||btn.textContent||'').trim();
      if(!txt) return;
      
      if(matchesAny(txt, aiKeywords)){
        if(!btn.__aiBound){ btn.addEventListener('click', (e)=>{ e.preventDefault(); openAIChat(); }); btn.__aiBound = true; }
      } else if(matchesAny(txt, clientKeywords)){
        if(!btn.__clientBound){ btn.addEventListener('click', (e)=>{ e.preventDefault(); openClientChat(); }); btn.__clientBound = true; }
      }
    });
  }


  function attachBySelectors(){
    const map = [
      {sel:'#aiChatBtn', fn: openAIChat},
      {sel:'#startClientChatBtn', fn: openClientChat},
      {sel:'.open-ai-chat', fn: openAIChat},
      {sel:'.open-client-chat', fn: openClientChat},
    ];
    map.forEach(m=>{
      const el = document.querySelector(m.sel);
      if(el && !el.__bound){ el.addEventListener('click',(e)=>{ e.preventDefault(); m.fn();}); el.__bound = true; }
    });
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    try{ bindCorrectly(); attachBySelectors(); }catch(e){ console.error(e); }

    window.__openAIChat = openAIChat;
    window.__openClientChat = openClientChat;
    window.__rebindChatButtons = ()=>{ bindCorrectly(); attachBySelectors(); };
    console.log('chat-binding applied');
  });
})();