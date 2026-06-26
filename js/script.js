const sidebar=document.getElementById('sidebar'),mainWrap=document.getElementById('mainWrap'),themeToggle=document.querySelector('.theme-toggle'),loginBtn=document.getElementById('loginBtn'),adminLink=document.getElementById('adminLink');
fetch('api/index.php?type=homepage').then(function(r){return r.json()}).then(function(d){
  if(!d)return;
  var n=document.querySelector('.profile-mini-name');if(n&&d.name)n.textContent=d.name;
  var b=document.querySelector('.profile-mini-bio');if(b&&d.bio)b.textContent=d.bio;
  var a=document.querySelector('.profile-mini-avatar img');if(a&&d.avatar)a.src=d.avatar;
  var s=document.querySelector('.profile-mini-status');if(s&&d.status)s.innerHTML='<span class="status-dot"></span> '+d.status;
  var h=document.querySelector('.hero-desc');if(h&&d.heroSub)h.textContent=d.heroSub;
}).catch(function(){});
fetch('api/index.php?type=homepage').then(function(r){return r.json()}).then(function(d){
  if(!d)return;
  var socialMap={QQ:'qq',Bilibili:'bilibili',抖音:'douyin',快手:'kuaishou',小红书:'xiaohongshu',微博:'weibo',爱发电:'aifadian',YouTube:'youtube',X:'x',Telegram:'telegram',Instagram:'ins',Facebook:'facebook',TikTok:'tiktok',WhatsApp:'whatsapp',Discord:'discord',Reddit:'reddit',Twitch:'twitch',LinkedIn:'linkedin',Fursuit:'fursuit',Steam:'steam',GitHub:'github',Email:'email'};
  Object.keys(socialMap).forEach(function(k){
    var v=d[socialMap[k]];var el=document.querySelector('.social-icon[title="'+k+'"]');
    if(!el)return;
    if(!v){el.style.display='none';return}
    el.href=k==='Email'?'mailto:'+v:v;el.style.display=''
  });
}).catch(function(){});
fetch('api/index.php?type=settings').then(function(r){return r.json()}).then(function(d){
  if(!d)return;
  if(d.favicon){var f=document.querySelector('link[rel="icon"]');if(f)f.href=d.favicon}
  //if(d.hideSocial){var r=document.querySelector('.social-row');if(r)r.style.display='none'}
  if(d.hideDynamic){var r=document.querySelector('.col-bottom');if(r)r.style.display='none'}
  var navMap={博客:'hideBlog',社区:'hideCommunity',OC:'hideOc',项目:'hideRepositories',朋友:'hideFriend',友链:'hideFurl',关于:'hideAbout'};
  var cardMap={博客:'hideBlog',社区:'hideCommunity',OC:'hideOc',项目:'hideRepositories',朋友:'hideFriend',友情链接:'hideFurl',关于:'hideAbout'};
  Object.keys(navMap).forEach(function(k){if(d[navMap[k]]){document.querySelectorAll('.nav-label').forEach(function(e){if(e.textContent.trim()===k){var p=e.closest('.nav-item');if(p)p.style.display='none'}})}});
  Object.keys(cardMap).forEach(function(k){if(d[cardMap[k]]){document.querySelectorAll('.card-title').forEach(function(e){if(e.textContent.trim()===k){var p=e.closest('.card');if(p)p.style.display='none'}})}});
  // 隐藏快速入口标题如果所有卡片都隐藏了
  setTimeout(function(){var q=document.querySelector('.col-right');if(!q)return;var c=q.querySelectorAll('.card'),v=Array.from(c).some(function(e){return e.style.display!=='none'});if(!v){var t=q.querySelector('.section-title');if(t)t.style.display='none'}},100);
}).catch(function(){});
function showAdmin(){if(loginBtn)loginBtn.style.display='none';if(adminLink)adminLink.style.display='flex'}
if(sessionStorage.getItem('apwd'))showAdmin();
function toggleTheme(){var e=document.documentElement.getAttribute('data-theme')==='light'?'dark':'light';document.documentElement.setAttribute('data-theme',e);themeToggle.querySelector('.theme-icon').textContent=e==='light'?'☀️':'🌙';try{localStorage.setItem('theme',e)}catch{}}
try{var t=localStorage.getItem('theme');if(t){document.documentElement.setAttribute('data-theme',t);themeToggle.querySelector('.theme-icon').textContent=t==='light'?'☀️':'🌙'}}catch{}
function toggleSidebar(){const e=sidebar.classList.toggle('collapsed');mainWrap.classList.toggle('shifted',e);try{localStorage.setItem('sidebar_collapsed',e)}catch{}}
try{var c=localStorage.getItem('sidebar_collapsed');if(c==='true'){sidebar.classList.add('collapsed');mainWrap.classList.add('shifted')}else if(c==='false'){sidebar.classList.remove('collapsed');mainWrap.classList.remove('shifted')}else if(window.innerWidth<768){sidebar.classList.add('collapsed');mainWrap.classList.add('shifted')}else{sidebar.classList.remove('collapsed');mainWrap.classList.remove('shifted')}}catch{}
// Typewriter
(function(){var e=document.getElementById('typewriter');if(!e)return;
var start=function(t){e.textContent='👋 ';var n=0,i=function(){if(n<t.length){e.textContent+=t.charAt(n);n++;setTimeout(i,80)}else{e.innerHTML=e.innerHTML.replace('梦星STAR','<span class="highlight">梦星STAR</span>');e.nextElementSibling.style.display='inline'}};setTimeout(i,500)};
var x=new XMLHttpRequest;x.open('GET','api/index.php?type=homepage');x.onload=function(){if(x.status==200){var d=JSON.parse(x.responseText);start(d&&d.hero?d.hero:'Hi, I im 梦星STAR')}else start('Hi, I im 梦星STAR')};x.onerror=function(){start('Hi, I im 梦星STAR')};x.send()})();
const observer=new IntersectionObserver(e=>{e.forEach(n=>{n.isIntersecting&&n.target.classList.add('visible')})},{threshold:.1});
document.querySelectorAll('.fade-in').forEach(e=>observer.observe(e));
setTimeout(()=>{document.querySelectorAll('.fade-in').forEach(e=>{e.getBoundingClientRect().top<window.innerHeight&&e.classList.add('visible')})},100);
