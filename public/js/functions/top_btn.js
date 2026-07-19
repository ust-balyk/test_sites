// topButton.js
export function initTopButton({ buttonId = "top_btn", showAfter = 1000, mobileMax = 768 } = {}) {
  const btn = document.getElementById(buttonId);
  if (!btn) return;

  const mq = window.matchMedia(`(max-width: ${mobileMax}px)`);
  let visible = false;

  const isMobile = () => mq.matches;

  const updateVisibility = () => {
    if (isMobile()) {
      if (visible) { btn.style.display = "none"; visible = false; }
      return;
    }
    const scrolled = document.documentElement.scrollTop || document.body.scrollTop;
    const shouldShow = scrolled > showAfter;
    if (shouldShow !== visible) {
      btn.style.display = shouldShow ? "block" : "none";
      visible = shouldShow;
    }
  };

  const onScroll = () => updateVisibility();
  const onClick = () => window.scrollTo({ top: 0, behavior: "smooth" });
  const onMQChange = () => updateVisibility();

  btn.style.display = "none";
  visible = false;

  window.addEventListener("scroll", onScroll, { passive: true });
  btn.addEventListener("click", onClick);

  if (typeof mq.addEventListener === "function") {
    mq.addEventListener("change", onMQChange);
  } else {
    window.addEventListener("resize", onMQChange);
  }

  updateVisibility();
}



