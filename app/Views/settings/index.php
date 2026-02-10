<h1>Settings & Deep Customization</h1>
<div class="adaptive-grid">
  <section class="card depth-focus">
    <h5>Visual Modes</h5>
    <p class="text-muted">Light / Dark / Glass, high contrast, reduced motion, focus mode, seasonal/brand-ready accent.</p>
    <form id="uiControls" class="smart-spacing">
      <label>Accent Color <input type="color" name="accent" class="form-control form-control-color" value="#0d6efd"></label>
      <label>Font Size % <input type="range" min="85" max="125" name="fontSize" class="form-range"></label>
      <label>Line Height <input type="range" min="1.2" max="2" step="0.05" name="lineHeight" class="form-range"></label>
      <label>Border Radius <input type="range" min="2" max="12" step="1" name="radius" class="form-range"></label>
      <label>Shadow Intensity <input type="range" min="0.4" max="2" step="0.1" name="shadow" class="form-range"></label>
      <label>Animation Speed <input type="range" min="0.4" max="1.8" step="0.1" name="anim" class="form-range"></label>
      <label>Card Density <input type="range" min="0.75" max="1.5" step="0.05" name="density" class="form-range"></label>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="contrast" id="contrast"><label class="form-check-label" for="contrast">High contrast mode</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="reducedMotion" id="reducedMotion"><label class="form-check-label" for="reducedMotion">Reduced motion mode</label></div>
      <div class="form-check"><input class="form-check-input" type="checkbox" name="focusMode" id="focusMode"><label class="form-check-label" for="focusMode">Focus mode</label></div>
    </form>
  </section>

  <section class="card">
    <h5>Smart UI System</h5>
    <ul>
      <li>Role-aware density (Admin dense / Client simplified)</li>
      <li>User-aware menu ordering and rare feature auto-hide</li>
      <li>Morning/evening context tone + low-data lightweight mode</li>
      <li>Jarvis status whispers + notification center</li>
      <li>Scroll progress, adaptive spacing, fluid typography, elevation scale</li>
    </ul>
  </section>

  <section class="card distraction">
    <h5>UI Motion & Transitions</h5>
    <p>Fade/slide transitions, blur-to-focus load, ripple clicks, magnetic buttons, inline validation pulses, KPI breathing, skeleton shimmer, and smart toasts are globally enabled.</p>
    <button class="btn btn-primary">Test Interaction</button>
  </section>
</div>
