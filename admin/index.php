<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理后台 · 梦星的互联网世界</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root{--bg:#080a0f;--bg2:#0e1117;--bg3:#141a26;--border:#1a2030;--border2:#2a3346;--text:#fff;--text2:#94a3b8;--text3:#64748b;--blue:#5B89D2;--red:#ec4141;--green:#4ade80;--radius:10px}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;font-size:14px}
.container{max-width:960px;margin:0 auto;padding:24px 20px}
h1{font-size:22px;font-weight:600;margin-bottom:24px}
h2{font-size:16px;font-weight:500;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:20px}
.tabs{display:flex;gap:4px;margin-bottom:20px;flex-wrap:wrap}
.tab{padding:8px 16px;border-radius:var(--radius);border:1px solid var(--border);background:transparent;color:var(--text2);cursor:pointer;font-size:13px;transition:all .2s}
.tab:hover{border-color:var(--border2);color:var(--text)}
.tab.active{background:var(--blue);border-color:var(--blue);color:#fff}
.panel{display:none}
.panel.active{display:block}
.item{display:flex;align-items:center;gap:12px;padding:12px 14px;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:8px;transition:all .2s}
.item:hover{border-color:var(--border2);background:var(--bg3)}
.item-info{flex:1;min-width:0}
.item-title{font-size:14px;font-weight:500}
.item-desc{font-size:12px;color:var(--text3);margin-top:2px}
.item-actions{display:flex;gap:6px}
.btn{padding:6px 14px;border-radius:var(--radius);border:1px solid var(--border);background:transparent;color:var(--text2);cursor:pointer;font-size:12px;transition:all .2s}
.btn:hover{border-color:var(--border2);color:var(--text)}
.btn-red{border-color:var(--red);color:var(--red)}
.btn-red:hover{background:var(--red);color:#fff}
.btn-blue{border-color:var(--blue);color:var(--blue)}
.btn-blue:hover{background:var(--blue);color:#fff}
.btn-green{border-color:var(--green);color:var(--green)}
.btn-green:hover{background:var(--green);color:#000}
.empty{text-align:center;padding:40px;color:var(--text3);font-size:13px}
form{display:flex;flex-direction:column;gap:10px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border)}
input,textarea{padding:10px 12px;border-radius:var(--radius);border:1px solid var(--border);background:var(--bg3);color:var(--text);font-size:13px;font-family:inherit;outline:none;transition:border-color .2s}
input:focus,textarea:focus{border-color:var(--blue)}
textarea{resize:vertical;min-height:60px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.login-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:9999}
.login-box{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px;width:320px;max-width:90vw}
.login-box h2{margin-bottom:16px;text-align:center}
.login-box form{gap:12px;border:none;padding:0;margin:0}
.login-msg{font-size:12px;color:var(--red);text-align:center;margin-top:8px;display:none}
#toast{position:fixed;bottom:24px;right:24px;padding:10px 20px;border-radius:var(--radius);font-size:13px;z-index:999;display:none;animation:fadeIn .3s ease}
#toast.ok{background:var(--green);color:#000;display:block}
#toast.err{background:var(--red);color:#fff;display:block}
@keyframes fadeIn{0%{opacity:0;transform:translateY(10px)}100%{opacity:1;transform:translateY(0)}}
</style>
</head>
<body>

<div class="login-overlay" id="loginOverlay">
  <div class="login-box">
    <h2>🔐 管理后台</h2>
    <form onsubmit="login();return false">
      <input type="password" id="pwdInput" placeholder="输入密码" autofocus>
      <button class="btn btn-blue" type="submit" style="padding:10px;font-size:14px">登录</button>
      <div class="login-msg" id="loginMsg">密码错误</div>
    </form>
  </div>
</div>

<div id="toast"></div>

<div class="container" id="app" style="display:none">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <h1>📋 管理后台</h1>
    <button class="btn" onclick="logout()">退出</button>
  </div>

  <div class="tabs">
    <div class="tab active" onclick="switchTab(this,'friends')">👥 朋友</div>
    <div class="tab" onclick="switchTab(this,'furls')">🔗 友链</div>
    <div class="tab" onclick="switchTab(this,'oc')">🎨 OC</div>
    <div class="tab" onclick="switchTab(this,'projects')">⚙ 项目</div>
  </div>

  <div id="tab-friends" class="panel active"></div>
  <div id="tab-furls" class="panel"></div>
  <div id="tab-oc" class="panel"></div>
  <div id="tab-projects" class="panel"></div>
</div>

<script>
var PWD = '', TOAST_TIMER = null;

function toast(msg, ok) {
  var t = document.getElementById('toast');
  t.textContent = msg; t.className = ok ? 'ok' : 'err';
  clearTimeout(TOAST_TIMER); TOAST_TIMER = setTimeout(function(){ t.className = '' }, 2500);
}

function login() {
  PWD = document.getElementById('pwdInput').value;
  if (!PWD) return;
  var x = new XMLHttpRequest();
  x.open('GET', '../api/index.php?type=friends');
  x.onload = function() {
    if (x.status === 200) {
      document.getElementById('loginOverlay').style.display = 'none';
      document.getElementById('app').style.display = 'block';
      loadAll();
    } else { document.getElementById('loginMsg').style.display = 'block' }
  };
  x.onerror = function() { document.getElementById('loginMsg').style.display = 'block' };
  x.send();
}

function logout() {
  PWD = ''; document.getElementById('loginOverlay').style.display = 'flex';
  document.getElementById('app').style.display = 'none'; document.getElementById('pwdInput').value = '';
}

function loadAll() { loadTab('friends'); loadTab('furls'); loadTab('oc'); loadTab('projects'); }

var FIELDS = {
  friends: [
    { key: 'n', label: '昵称', type: 'text' },
    { key: 'd', label: '简介', type: 'text' },
    { key: 'u', label: '链接', type: 'text' },
    { key: 'a', label: '头像URL', type: 'text' }
  ],
  furls: [
    { key: 'n', label: '站点名', type: 'text' },
    { key: 'u', label: '链接', type: 'text' },
    { key: 'd', label: '描述', type: 'text' },
    { key: 'a', label: '图标URL', type: 'text' }
  ],
  oc: [
    { key: 'n', label: '名称', type: 'text' },
    { key: 'd', label: '描述', type: 'textarea' },
    { key: 'a', label: '图片URL', type: 'text' }
  ],
  projects: [
    { key: 'n', label: '项目名', type: 'text' },
    { key: 'd', label: '描述', type: 'textarea' },
    { key: 'u', label: '链接', type: 'text' },
    { key: 'a', label: '图标URL', type: 'text' }
  ]
};

function loadTab(type) {
  var x = new XMLHttpRequest();
  x.open('GET', '../api/index.php?type=' + type);
  x.onload = function() {
    if (x.status === 200) renderTab(type, JSON.parse(x.responseText));
  };
  x.send();
}

function renderTab(type, data) {
  var el = document.getElementById('tab-' + type);
  if (!data) data = [];
  var h = '<div class="card"><h2>' + getIcon(type) + ' ' + getTitle(type) + '</h2><div id="list-' + type + '">';
  if (data.length === 0) h += '<div class="empty">暂无数据，点击下方添加</div>';
  else data.forEach(function(item, i) {
    h += '<div class="item"><div class="item-info"><div class="item-title">' + esc(item.n || '未命名') + '</div><div class="item-desc">' + esc((item.d || '').substring(0, 50)) + '</div></div><div class="item-actions"><button class="btn btn-blue" onclick="editItem(\'' + type + '\',' + i + ')">编辑</button><button class="btn btn-red" onclick="deleteItem(\'' + type + '\',' + i + ')">删除</button></div></div>';
  });
  h += '</div><button class="btn btn-green" onclick="addItem(\'' + type + '\')" style="margin-top:8px">+ 添加</button></div>';
  el.innerHTML = h;
}

function addItem(type) { showForm(type, null, -1) }

function editItem(type, idx) {
  var x = new XMLHttpRequest();
  x.open('GET', '../api/index.php?type=' + type);
  x.onload = function() {
    if (x.status === 200) {
      var data = JSON.parse(x.responseText);
      showForm(type, data[idx] || {}, idx, data);
    }
  };
  x.send();
}

function deleteItem(type, idx) {
  if (!confirm('确定删除？')) return;
  var x = new XMLHttpRequest();
  x.open('GET', '../api/index.php?type=' + type);
  x.onload = function() {
    if (x.status !== 200) return;
    var data = JSON.parse(x.responseText);
    data.splice(idx, 1);
    saveData(type, data);
  };
  x.send();
}

function showForm(type, item, idx, allData) {
  var fields = FIELDS[type];
  var overlay = document.createElement('div');
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9998';
  var box = document.createElement('div');
  box.style.cssText = 'background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;width:480px;max-width:90vw;max-height:90vh;overflow-y:auto';
  var h = '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px"><h2 style="font-size:16px">' + (idx === -1 ? '添加' : '编辑') + '</h2><button onclick="this.closest(\'div\').parentElement.parentElement.remove()" style="background:none;border:none;color:var(--text2);cursor:pointer;font-size:18px">✕</button></div>';
  h += '<form id="editForm">';
  fields.forEach(function(f) {
    var val = item && item[f.key] ? item[f.key] : '';
    if (f.type === 'textarea') h += '<textarea placeholder="' + f.label + '" name="' + f.key + '">' + esc(val) + '</textarea>';
    else h += '<input type="text" placeholder="' + f.label + '" name="' + f.key + '" value="' + esc(val) + '">';
  });
  h += '<div class="form-row"><button type="submit" class="btn btn-blue">保存</button><button type="button" class="btn" onclick="this.closest(\'form\').closest(\'div\').parentElement.remove()">取消</button></div></form>';
  box.innerHTML = h; overlay.appendChild(box); document.body.appendChild(overlay);

  box.querySelector('#editForm').onsubmit = function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    var obj = {};
    fields.forEach(function(f) { obj[f.key] = fd.get(f.key) || '' });
    if (idx === -1) { allData ? allData.push(obj) : (allData = [obj]) }
    else { allData[idx] = obj }
    saveData(type, allData);
    overlay.remove();
  };
}

function saveData(type, data) {
  var x = new XMLHttpRequest();
  x.open('POST', '../api/index.php?type=' + type);
  x.setRequestHeader('Content-Type', 'application/json');
  x.onload = function() {
    if (x.status === 200) { toast('保存成功', true); loadTab(type) }
    else toast('保存失败', false);
  };
  x.send(JSON.stringify({ password: PWD, data: data }));
}

function switchTab(el, type) {
  document.querySelectorAll('.tab').forEach(function(t) { t.classList.remove('active') });
  el.classList.add('active');
  document.querySelectorAll('.panel').forEach(function(p) { p.classList.remove('active') });
  document.getElementById('tab-' + type).classList.add('active');
}

function getIcon(t) { return { friends: '👥', furls: '🔗', oc: '🎨', projects: '⚙' }[t] || '📄' }
function getTitle(t) { return { friends: '朋友列表', furls: '友链列表', oc: 'OC 列表', projects: '项目列表' }[t] || '' }
function esc(s) { if (!s) return ''; return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') }

// Enter key login
document.addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && document.getElementById('loginOverlay').style.display !== 'none') login();
});
</script>
</body>
</html>
