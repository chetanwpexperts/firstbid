@if(session('ok') || session('status'))
  <div class="flash-toast ok" id="flashToast">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 16px;">✅</span>
      <span>{{ session('ok') ?? session('status') }}</span>
    </div>
    <button type="button" onclick="document.getElementById('flashToast').remove()" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 16px;">✕</button>
  </div>
@endif

@if(session('err') || (isset($errors) && $errors->any()))
  <div class="flash-toast err" id="flashErrToast">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 16px;">⚠️</span>
      <span>{{ session('err') ?? $errors->first() }}</span>
    </div>
    <button type="button" onclick="document.getElementById('flashErrToast').remove()" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 16px;">✕</button>
  </div>
@endif
